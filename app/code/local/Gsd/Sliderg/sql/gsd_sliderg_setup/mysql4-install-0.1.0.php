<?php
/**
 * Created by PhpStorm.
 * User: GiangSoda
 * Date: 9/23/14
 * Time: 5:04 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `sliderg_slider`;
CREATE TABLE IF NOT EXISTS `sliderg_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
);
ALTER TABLE `sliderg_slider` ADD PRIMARY KEY (`id`);
");

$installer->endSetup();