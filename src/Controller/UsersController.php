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
    public $AuditLogsTable;
    public $UsersTable;
    public $CurrencyTable;
    public $OrdersTable;

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login','register']);
        $this->loadComponent('RequestHandler');
        $this->AuditLogsTable = TableRegistry::get('audit_logs');
        $this->UsersTable = TableRegistry::get('users');
        $this->CurrencyTable = TableRegistry::get('currency');
        $this->OrdersTable = TableRegistry::get('orders');
        
        $this->set('userEmail',$this->Auth->user('email'));
    }

    public function login() {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->UsersTable->patchEntity($user, $this->request->data);
            $AuthUser = $this->Auth->identify();
            if ($AuthUser) {
                $this->Auth->setUser($AuthUser);
                $auditTable = $this->AuditLogsTable->newEntity();
                $Log = ['user_id' => $this->Auth->user('id'), 'event' => 'Sign In'];
                $Audit = $this->AuditLogsTable->patchEntity($auditTable, $Log);
                $this->AuditLogsTable->save($Audit);
                $this->Flash->success(__('Welcome ' . $this->Auth->user('email')));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('email / password combination is not valid.'));
        }
        $this->set('user', $user);
        $this->set('title', 'Login');
    }
    
    public function register() {
        if ($this->request->is('ajax')) {
            $user = $this->UsersTable->newEntity();
            if ($this->request->is('post')) {
                $user = $this->UsersTable->patchEntity($user, $this->request->data);
                if (empty($user->errors())) {
                    $this->UsersTable->save($user);
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
            $this->set('_serialize', ['status', 'message']);

            $this->viewBuilder()->layout(false);
        }
    }

    public function index() {
        $users = $this->UsersTable->find();
        $currencies = $this->CurrencyTable->find('list', ['keyField' => 'code', 'valueField' => 'name']);
        $this->set('users', $users);
        $this->set('currencies', $currencies);
        $this->set('title', 'Currency Conversion');
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
            $currs = $this->CurrencyTable->find()->select(['surcharge', 'code', 'rate']);
            $rate = $this->fetchRate($fromCurrency, $toCurrency);
            $value = (double) $rate * (double) $amount;
            foreach ($currs as $curr) {
                if ($curr->code === $fromCurrency) {
                    $surPerc = $curr->surcharge;
                    $surCharge = ($surPerc / 100) * $value;
                    $rate = $curr->rate;
                    $amountTopay = $value + $surPerc;
                }
            }

            $this->set(['surPerc' => $surPerc, 'currency' => $fromCurrency, 'surCharge' => $surCharge, 'amountTopay' => $amountTopay, 'ZarAmountForeign' => $value, 'rate' => $rate, 'surCharge' => $surCharge, 'Foreignamount' => $amount, '_serialize' =>
                ['currency', 'AmountForeign', 'rate', 'surPerc', 'amountTopay', 'Foreignamount', 'surCharge', 'ZarAmountForeign']]);
            $this->viewBuilder()->layout(false);
        }
    }

    public function sendmail($id) {
            $order = $this->OrdersTable->get($id);
            $DefaultEmail = new Email();
            $DefaultEmail->viewVars(['id' => $id, 'topay' => $order->amount_to_pay, 'currency' => $order->foreign_currency_purchased, 'amount' => $order->amount_of_foreign_currency]);
            $DefaultEmail->transport('default');
            $DefaultEmail->template('orderdetails', 'orderdetails')
                        ->emailFormat('html')
                        ->from(['info@judahtips.org' => 'judahtips.org'])
                        ->to($this->Auth->user('email'))
                        ->subject('Order Details')
                        ->send();
    }

    private function discount($id) {
        $order = $this->OrdersTable->get($id);
        $discount = 2;
        $newamount = $order->amount_to_pay - ($order->amount_to_pay * ($discount/100));
        $order->amount_to_pay = $newamount;
        $this->OrdersTable->save($order);
    }

    public function purchase() {
        if ($this->request->is('ajax')) {
            $order = $this->OrdersTable->newEntity();
            if ($this->request->is('post')) {
                $currency = $this->request->data('foreign_currency_purchased');
                $order->user_id = $this->Auth->user('id');
                $order = $this->OrdersTable->patchEntity($order, $this->request->data);
                if ($this->OrdersTable->save($order)) {
                    
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

    public function logout() {
        $auditTable = $this->AuditLogsTable->newEntity();
        $Log = ['user_id' => $this->Auth->user('id'), 'event' => 'Logout'];
        $Audit = $this->AuditLogsTable->patchEntity($auditTable, $Log);
        $this->AuditLogsTable->save($Audit);
        return $this->redirect($this->Auth->logout());
    }

}
