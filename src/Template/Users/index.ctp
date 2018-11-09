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
    <?php
    echo $this->Flash->render();
    echo $this->Flash->render('auth');     
    ?>   
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Currency Converter</h4></div>
                <div class="panel-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-6"> 
                           <?php 
                           echo $this->Form->select('from_country',$currencies, ['empty' =>'--Select Currency--','class'=>'form-control','id'=>'currency']);
                           ?>
                            </div>
                            <div class="form-group col-md-4">            
                                <input type="text" name="foreign_exchange_amount" class="form-control" placeholder="Enter amount" id="f-value">
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" class="btn btn-success btn-lg convert">Convert</button>   
                            </div>
                        </div>
                    </form>     
                </div>
            </div>
        </div>
    </div> 
    <!---Modal-->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
        <?php echo $this->Form->create($order,['id'=>'p-form','url'=>['controller' => 'users', 'action' => 'purchase']]);?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="modal-body">
                    <div> 
                        <p class="alert-info"></p>    
                        <input type="hidden" name="from_currency" value="" class="form-control">  
                        <input type="hidden" name="foreign_exchange_amount" value="" class="form-control">
                    </div>
                    <div class="conversion">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="bottom-text" class="btn btn-success">Purchase</button>     
                </div>
            </div>
        <?php echo $this->Form->end();?>
        </div> 
    </div>
</div>

<script>
    $(document).ready(function () {
        
        $(document).on('click', '.convert', function (event) {
            event.preventDefault();
            var tocurrency = 'ZAR';
            var fromcurrency = $('#currency').val();
            var randvalue = parseFloat($('#f-value').val()).toFixed(2);
            if (!fromcurrency == '' && !tocurrency == '') {
                $('input[name=from_currency]').val(fromcurrency);
                $('input[name=foreign_exchange_amount]').val(randvalue);
                $('#myModal').modal();
                $.ajax({
                    url: "<?php echo \Cake\Routing\Router::Url('/users/get_rates/');?>" + fromcurrency + "/" + tocurrency + "/" + randvalue,
                    type: "POST",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                        $('.modal-title').html('');
                        $('.conversion').html("<button class='btn btn-lg btn-default'><span class='glyphicon glyphicon-refresh spinning'></span> Converting...</button>");
                    },
                    success: function (data) {
                        $('.modal-title').html('Amount to be paid in South African Rands');
                        $('.conversion').html(data);
                    },
                    error: function (xhr, textStatus, error) {
                        alert('An error occure please try again.');
                    }
                });
            } else {
                alert('Please select currency');
            }
        });

        $('#f-value').keypress(function (e) {
             var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
            if (verified) {e.preventDefault();}
        });

        $(document).on('submit', '#p-form', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var url = $(this).attr("action");
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                beforeSend: function (xhr) {
                       xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },        
                success: function (data, textStatus, jqXHR)
                {
                    $('.alert-info').html(data);
                    setTimeout(function () {
                       window.location.reload();
                    }, 5000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    //location.reload();
                }
            });
        });
    });
</script>
