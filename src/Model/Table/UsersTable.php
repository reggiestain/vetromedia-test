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
// src/Model/Table/UsersTable.php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use Cake\ORM\Rule\IsUnique;

class UsersTable extends Table {

    public function initialize(array $config) {

        $this->table('users');
        $this->addBehavior('Timestamp');
        
        $this->hasMany('AuditLogs', [
            'className' => 'AuditLogs',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->notEmpty('firstname', 'First name is required.')
                ->notEmpty('surname', 'Surname is required.')                
                ->notEmpty('mobile', 'Mobile number is required.')                
                ->add('email', 'validFormat', [
                    'rule' => 'email',
                    'message' => 'E-mail must be a valid email address.'
                ])
                ->add('email', ['unique' => [
                        'rule' => 'validateUnique',
                        'provider' => 'table',
                        'message' => 'This email already exist.']
                        ]
                )
                ->add('mobile', 'notEmpty', [
                    'rule' => ['custom', '/^([0-9]{1}[0-9]{9})$/'],
                    'message' => __('Invalid mobile number! mobile number format: eg 0755434434')
                ])
                ->add('mobile', ['unique' => [
                        'rule' => 'validateUnique',
                        'provider' => 'table',
                        'message' => 'This mobile number already exist.']
                        ]
                )->add('password', [
                    'minLength' => [
                        'rule' => ['minLength', 6],
                        'message' => 'Password must contain at least 6 character'
                    ],]
                );
        
        $validator
            ->requirePresence('confirm_password', 'create', 'Password must be required!')
            ->notEmpty('confirm_password', 'Confirm password must be required!')
            ->add(
                'confirm_password',
                'custom',
                [
                    'rule' => function ($value, $context) {
                            if (isset($context['data']['password']) && $value == $context['data']['password']) {
                                return true;
                            }
                            return false;
                        },
                    'message' => 'Sorry, password and confirm password does not matched'
                ]
            );   
       
        return $validator;
    }

}
