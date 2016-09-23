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
 <?php echo $this->element('header');?>
    
    <h2>Currency Converter</h2>
    <div>
    <?php
    echo $this->Flash->render();
    echo $this->Flash->render('auth');     
    ?>    
    </div>
    <br>
    <form class="form-inline" style="margin-top: 50px">
        <div class="row" style="margin-left: 20px">
        <div class="form-group"> 
            <label>From: </label>
            <?php 
            echo $this->Form->select('from_country',$currencies, ['empty' =>'--Select Currency--','class'=>'form-control','id'=>'from-value']);
            ?>
        </div>
        <div class="form-group">            
            <input type="number" name="from_amount" class="form-control" min="1" id="from">
        </div>
        </div>
        <br>
        <div class="row" style="margin-left: 38px">
        <div class="form-group"> 
            <label>To: </label>
            <?php 
            echo $this->Form->select('to_country', $currencies, ['empty' =>'--Select Currency--','class'=>'form-control','id'=>'to-value']);
            ?>
        </div>
        <div class="form-group" id="rate">            
            <input type="text" name="to_amount" class="form-control" id="to">
        </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function(){
    
    var from = $('#from');
    
     from.on('keyup', function(){         
         if(!$('#from-value').val() == '' && !$('#to-value').val() == ''){
            $('#to').val('converting.....');
            var fromvalue = $('#from-value').val();
            var tovalue = $('#to-value').val();
            var amount = $('#from').val();
            if(amount == ''){
               amount = 0; 
            }
            $.ajax({
                type:"POST",
                url:"<?php echo \Cake\Routing\Router::Url('/users/get_rates/');?>"+fromvalue+"/"+tovalue+"/"+amount,
                dataType: 'text',
                async:false,
                success: function(data){
                    $('#rate').html(data);
                },
                error: function (xhr,textStatus,error) {
                    alert('An error occure please try again.');
                }
            });
          }else{
          alert('Please select currency');
          }
    });   
});
</script>
