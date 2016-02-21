<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

//DROP TABLE IF EXISTS `{$this->getTable('e_promotion/item')}`;

$installer->run("
    
CREATE TABLE IF NOT EXISTS `{$this->getTable('e_promotion/item')}` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `html` text,
  `link` varchar(500) NOT NULL,
  `link_mini` varchar(255) DEFAULT NULL,
  `display` varchar(32) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `store` int(1) NOT NULL,
  `banner` text NOT NULL,
  `small_banner` text NOT NULL,
  `bg_color` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;

");

$installer->endSetup();