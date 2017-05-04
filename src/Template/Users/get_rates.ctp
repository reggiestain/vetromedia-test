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
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
?>

<div class="row">
    <div class="form-group col-md-12"> 
        <div class="input-group">
            <span class="input-group-addon">ZAR</span>
            <input type="text" name="amount_to_pay" value="<?php echo number_format((double)$ZarAmountForeign, 2, '.','');?>" class="form-control" readonly>           
        </div>
        <input type="hidden" name="exchange_rate" value="<?php echo $rate;?>">
        <input type="hidden" name="amount_of_surcharge" value="<?php echo number_format((double)$surCharge, 2, '.','');?>">
        <input type="hidden" name="surcharge_percentage" value="<?php echo $surPerc;?>">
        <input type="hidden" name="amount_of_foreign_currency" value="<?php echo number_format((double)$Foreignamount, 2, '.','');?>">
        <input type="hidden" name="foreign_currency_purchased" value="<?php echo $currency;?>">
    </div>
</div>                        
<?php echo $this->Form->end();?>