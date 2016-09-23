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
    public $UsersTable;
    public $CurrencyTable;
    public $AuditLogsTable;

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);
        $this->UsersTable = TableRegistry::get('users');
        $this->CurrencyTable = TableRegistry::get('currency');
        $this->AuditLogsTable = TableRegistry::get('audit_logs');
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

    public function index() {
        $users = $this->UsersTable->find();
        $currencies = $this->CurrencyTable->find('list', ['keyField' => 'code', 'valueField' => 'name']);
        $this->set('users', $users);
        $this->set('currencies', $currencies);
        $this->set('title', 'Currency Conversion');
    }

    public function manageCurrency() {
        $currency = $this->CurrencyTable->newEntity();
        if ($this->request->is('post')) {
            $currencyData = $this->CurrencyTable->patchEntity($currency, $this->request->data);
            if ($this->CurrencyTable->save($currencyData)) {

                $auditTable = $this->AuditLogsTable->newEntity();
                $Log = ['user_id' => $this->Auth->user('id'), 'event' => 'Added currency'];
                $Audit = $this->AuditLogsTable->patchEntity($auditTable, $Log);
                $this->AuditLogsTable->save($Audit);

                $this->Flash->success(__('Currency has been added successfully.'));
                return $this->redirect(['action' => 'manageCurrency']);
            }
        }
        $this->set('currency', $currency);
        $this->set('title', 'Management Currency');
    }

    public function resetList() {
        if ($this->request->is('ajax')) {
            $currencyList = $this->CurrencyTable->find('all');
            $this->set('currencyList', $currencyList);
        }
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
            $rate = $this->fetchRate($fromCurrency, $toCurrency, $amount);
            $value = (double) $rate * (double) $amount;

            $this->set([
                'value' => $value,
                '_serialize' => ['value']
            ]);
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
