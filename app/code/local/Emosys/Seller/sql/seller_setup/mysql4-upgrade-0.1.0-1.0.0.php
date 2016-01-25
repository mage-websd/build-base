<?php
/**
 * @author 		Vladimir Popov
 * @copyright  	Copyright (c) 2014 Vladimir Popov
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('sales/order_item')}` ADD `customer_id` int(11) NOT NULL AFTER `store_id`;

");

$installer->endSetup();