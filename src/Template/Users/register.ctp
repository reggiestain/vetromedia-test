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


if($status === '300'){
   echo "<div class='alert alert-warning'><strong>warning! </strong>".$message."</div>"; 
}else if($status === '200'){  
  echo '200';  
} else if($status === 'error'){
    foreach( $message as $messages){
      echo "<div class='alert-div alert alert-danger fade in alert-dismissable lead'>"
          . "<a class='close' href='#' data-dismiss='alert' aria-label='close' title='close'>×</a>"
          . "<strong>Error! </strong>". $messages."</div>";  
    }      
}
