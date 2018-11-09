<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Email\Email;
use Cake\View\Exception\MissingTemplateException;
use Cake\Http\Client;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'register']);
        $this->loadComponent('RequestHandler');
        $this->set('userEmail', $this->Auth->user('email'));
    }

    /**
     * Displays login view
     */
    public function index() {
        $users = $this->Users->find();
        $currencies = $this->Currencies->find('list', ['keyField' => 'code', 'valueField' => 'name']);
        $order = $this->Orders->newEntity();
        $this->set('users', $users);
        $this->set('currencies', $currencies);
        $this->set('title', 'Currency Conversion');
        $this->set('order', $order);
    }

    /**
     * 
     * @return type
     */
    public function login() {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $AuthUser = $this->Auth->identify();
            if ($AuthUser) {
                $this->Auth->setUser($AuthUser);
                $auditTable = $this->AuditLogs->newEntity();
                $Log = ['user_id' => $this->Auth->user('id'), 'event' => 'Sign In'];
                $Audit = $this->AuditLogs->patchEntity($auditTable, $Log);
                $this->AuditLogs->save($Audit);
                $this->Flash->success(__('Welcome ' . $this->Auth->user('email')));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Incorrect email or password'));
        }
        $this->set('user', $user);
        $this->set('title', 'Login');
    }

    /**
     * Register method
     */
    public function register() {
        if ($this->request->is('ajax')) {
            $user = $this->Users->newEntity();
            if ($this->request->is('post')) {
                $user = $this->Users->patchEntity($user, $this->request->data);
                if (empty($user->errors())) {
                    $this->Users->save($user);
                    $status = '200';
                    $message = '';
                } else {
                    $error_msg = [];
                    foreach ($user->errors() as $errors) {
                        if (is_array($errors)) {
                            foreach ($errors as $error) {
                                $error_msg[] = $error;
                            }
                        } else {
                            $error_msg[] = $errors;
                        }
                    }
                    $status = 'error';
                    $message = $error_msg;
                }
            }
            $this->set("status", $status);
            $this->set("message", $message);

            $this->viewBuilder()->layout(false);
        }
    }

    public function updaterates() {
        $currs = $this->Currencies->find('all');
        $arr = json_decode($this->fetchRate()->body(),true);
        
        foreach ($arr['rates'] as $key=>$curr) {
            //$curr->rate = $this->fetchRate();
            //$this->Currencies->save($curr);
            if($key == 'USD'){
                echo $curr;
            }
        }
        exit();
        $this->Flash->success(__('Currency rate has been updated successfully.'));
        return $this->redirect(['action' => 'index']);
    }

    public function conversionRate($fromCurrency, $toCurrency) {
        if ($this->request->is('ajax')) {
            $rate = $this->fetchRate($fromCurrency, $toCurrency);
            $this->set([
                'value' => $rate,
                '_serialize' => ['value']
            ]);
        }
    }

    public function getRates($fromCurrency, $toCurrency, $amount) {
        if ($this->request->is('ajax')) {
            $currs = $this->Currencies->find()->where(['code' => $fromCurrency])->select(['surcharge', 'code', 'rate', 'name'])->first();
            if ($currs->has('rate')) {

                $value = (double) $amount / $currs->rate;
                $surCharge = ($currs->surcharge / 100) * $value;
                $rate = $currs->rate;
                $amountTopay = $value + $surCharge;
                $this->set(['surPerc' => $currs->surcharge, 'currency' => $fromCurrency, 'surCharge' => $surCharge,
                    'amountTopay' => $amountTopay, 'ZarAmountForeign' => $value, 'rate' => $rate, 'surCharge' => $surCharge, 'Foreignamount' => $amount]);
            } else {
                $this->render('unmatched');
            }
            $this->viewBuilder()->layout(false);
        }
    }

    /**
     * 
     * @param type $id
     */
    private function discount($id) {
        $order = $this->Orders->get($id);
        $discount = 2;
        $newamount = $order->amount_to_pay - ($order->amount_to_pay * ($discount / 100));
        $order->amount_to_pay = $newamount;
        $this->Orders->save($order);
    }

    /**
     * 
     */
    public function purchase() {
        if ($this->request->is('ajax')) {
            $order = $this->Orders->newEntity();
            if ($this->request->is('post')) {
                $currency = $this->request->data('foreign_currency_purchased');
                $order->user_id = $this->Auth->user('id');
                $order = $this->Orders->patchEntity($order, $this->request->data);
                if ($this->Orders->save($order)) {
                    switch ($currency) {
                        case "USD":
                            break;
                        case "GBP":
                            $this->sendmail($order->id);
                            break;
                        case "EUR":
                            $this->discount($order->id);
                            break;
                        case "KES":
                            break;
                    }

                    $this->set(['status' => '200']);
                } else {
                    $this->set(['status' => 'error']);
                }
            }
            $this->viewBuilder()->layout(false);
        }
    }

    /**
     * 
     * @return type
     */
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
