<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
 ?>
<div class="container">
  
  <div class="row" id="pwd-container">
    <div class="col-md-4"></div>
    
    <div class="col-md-4">
        <div class="row" style="margin-top: 50px">    
      <?php
      echo $this->Flash->render();
      echo $this->Flash->render('auth');     
      ?>
     </div>     
      <section class="login-form">        
        <?php echo $this->Form->create($user,['role'=>'login']);?> 
        <?php echo $this->Form->input('email',['type'=>'email','label'=>false,'class'=>'form-control input-lg','placeholder'=>'Email']) ?>
        <?php echo $this->Form->input('password',['type'=>'password','label'=>false,'class'=>'form-control input-lg','placeholder'=>'Password']) ?>                         
        <div class="pwstrength_viewport_progress"></div>          
        <button type="submit" class="btn btn-lg btn-success btn-block">Sign In</button>
        <div>
        <a href="https://php.net" target="_blank" class="btn btn-lg btn-primary btn-block">Sign Up</a>              
        </div> 
        <?php echo $this->Form->end()?>   
      </section>  
      </div>      
      <div class="col-md-4"></div>      
  </div>  
</div>