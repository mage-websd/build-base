<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 23/09/2015
 * Time: 22:48
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$tableLikeProduct = $installer->getTable('likeg/product');

$installer->run("

CREATE TABLE IF NOT EXISTS `{$tableLikeProduct}`(
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`like_id`),
  UNIQUE KEY `UNIQUE_CODE` (`product_id`,`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

");

$installer->endSetup();