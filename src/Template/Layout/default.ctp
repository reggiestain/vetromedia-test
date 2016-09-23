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

?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset();?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    <?php echo $title;?>
    </title>
    <?php echo $this->Html->meta('icon');?>   
    <?php echo $this->Html->css('bootstrap.css');?>
    <?php echo $this->Html->css('cake.css');?>
    <?php echo $this->Html->css('style.css');?>
    <?php echo $this->Html->script('jquery.min');?>
</head>
<body>
<?php echo $this->fetch('content') ?>
</body>
</html>
