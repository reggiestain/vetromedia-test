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
        <?php echo $this->Form->input('email',['type'=>'email','label'=>false,'id'=>'l-email','class'=>'form-control input-lg','placeholder'=>'Email','required' => false, 'error' => true]) ?>
        <?php echo $this->Form->input('password',['type'=>'password','label'=>false,'id'=>'l-pass','class'=>'form-control input-lg','placeholder'=>'Password']) ?>                         
        <div class="pwstrength_viewport_progress"></div>          
        <button type="submit" class="btn btn-lg btn-success btn-block">Sign In</button>
        <div>
        <a href="#" class="btn btn-lg btn-primary btn-block register">Register</a>              
        </div> 
        <?php echo $this->Form->end()?>   
      </section>  
      </div>      
      <div class="col-md-4"></div>      
  </div> 
    <!-- Register Modal -->
<div id="regModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->               
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h1 class="modal-title">Register</h1>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(null,['id'=>'reg-form','url'=>['controller' => 'users', 'action' => 'register']]);?>
                <p class="reg-alert"></p>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="firstname" class="form-label">First Name</label>
                                <?php echo $this->Form->input('first_name',['templates' => ['inputContainer' => '{{content}}'],'type' => 'text','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>                               
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="surname" class="form-label">Surname</label>
                                <?php echo $this->Form->input('surname',['templates' => ['inputContainer' => '{{content}}'],'type' => 'text','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>                   
                    </div>    
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="email" class="form-label">Email Address</label>
                                <?php echo $this->Form->input('email',['templates' => ['inputContainer' => '{{content}}'],'type' => 'text','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>                             
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="phone" class="form-label">Mobile</label>
                                <?php echo $this->Form->input('mobile',['templates' => ['inputContainer' => '{{content}}'],'type' => 'text','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>
                    </div>                            
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="pwd" class="form-label">Password</label>
                                <?php echo $this->Form->input('password',['templates' => ['inputContainer' => '{{content}}'],'type' => 'password','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>                     
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <label for="pwd-confirm" class="form-label">Confirm Password</label>
                                <?php echo $this->Form->input('confirm_password',['templates' => ['inputContainer' => '{{content}}'],'type' => 'password','label' => false,'class'=>'form-control','required'=>false, 'error' => true]);?>
                    </div>    
                </div>
                <div class="modal-footer">
                    <button type="submit" id="bottom-text-1" class="btn btn-success">Submit</button>  
                    <button type="button" id="bottom-text-2" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
                    <?php echo $this->Form->end();?>
        </div>

    </div>
</div> 
</div>

<script>
 $(document).ready(function () {
     
   $('.register').click(function(){
      $('#regModal').modal(); 
  });  
  
  $('#reg-form').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var url = $(this).attr("action");
            $.ajax({
                url: url,
                type: "POST",
                asyn: false,
                data: formData,
                beforeSend: function (xhr) {
                      xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function (data, textStatus, jqXHR)
                {
                   if (data === '200') {
                        $(".reg-alert").html("<div class='alert alert-success'>\n\
                                   <a class='close' href='#' data-dismiss='alert' aria-label='close' title='close'>×</a>\n\
                                   <strong>Success!</strong> Registration was successful, please login with your email and password.</div>");
                        setTimeout(function () {
                       window.location.reload(1);
                    }, 5000);
                    } else {
                        $(".reg-alert").html(data);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    location.reload();
                }
            });
        });
 });   
</script>