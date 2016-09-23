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
    <?php echo $this->element('header')?>


    <div>
    <?php
    echo $this->Flash->render();
    echo $this->Flash->render('auth');     
    ?>    
    </div>
    <br>
    <div class="col-lg-6">
        <h2>Manage Currency</h2>    
    <?php echo $this->Form->create($currency);?> 
        <div class="form-group">
            <label>Currency Name</label>
            <?php echo $this->Form->input('name',['type'=>'text','label' =>false,'class'=>'form-control','id'=>'name','style'=>'width:350px']);?>
        </div>
        <div class="form-group">
            <label>Currency Code</label>
            <?php echo $this->Form->input('code',['label' =>false,'class'=>'form-control','id'=>'code','style'=>'width:300px']);?>
            <a href="#" style="color:#0088cc" id="convert">Click to display conversion rate to the US dollar below:</a>
        </div>
        <div class="form-group" id="rate-value">
            <label>Currency Conversion Rate</label>
            <?php echo $this->Form->input('rate',['label' =>false,'type'=>'text','class'=>'form-control','id'=>'rate','style'=>'width:300px']);?>
        </div>
        <div class="form-group" style="margin-bottom: 100px">
            <button type="submit" class="btn btn-primary">Add</button>
            <a class="btn btn-default" id="reset-button">Reset</a>
        <?php echo $this->Form->end()?>
        </div>
    </div>

    <div class="col-lg-6" id="reset"> 

    </div>
    <script>
        $(document).ready(function () {
            
            $(window).load(function () {
                $('#reset').html('refreshing.....');
                $.ajax({
                    url: "<?php echo \Cake\Routing\Router::Url('/users/reset_list/');?>",
                    async: true
                }).done(function (result) {
                    $("#reset").html(result);
                });
            });
            
            $('#reset-button').click(function () {
            $('#reset').html('refreshing.....');
            $.ajax({
                    url: "<?php echo \Cake\Routing\Router::Url('/users/reset_list/');?>",
                    async: true
                }).done(function (result) {
                    $("#reset").html(result);
                });
            });

            $('#convert').click(function () {
                if (!$('#name').val() == '' && !$('#code').val() == '') {
                    $('#rate').val('converting.....');
                    var code = $('#code').val();
                    var usd = 'USD';
                    $.ajax({
                        type: "POST",
                        url: "<?php echo \Cake\Routing\Router::Url('/users/conversion_rate/');?>" + code + "/" + usd,
                        dataType: 'text',
                        async: false,
                        success: function (data) {
                            $('#rate-value').html(data);
                        },
                        error: function (xhr, textStatus, error) {
                            alert(error + ', please try again.');
                        }
                    });
                } else {
                    alert('Please enter currency name and code.');
                }
            });
        });
    </script>
