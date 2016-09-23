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

   <h2>Currency List</h2> 
    <table class="table table-bordered">
        <thead>
        <tr>
        <th>Currency Name</th>
        <th>Currency Code</th>
        <th>Currency Rate</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($currencyList as $currency): ?>
        <tr>
        <td><?php echo $currency->name;?></td>
        <td><?php echo $currency->code;?></td>
        <td><?php echo $currency->rate;?></td>
        </tr>
        <?php endforeach;?>   
        </tbody>
    </table>
   