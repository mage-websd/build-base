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

$tableSlider = $installer->getTable('sliderg/slider');
$tableImages = $installer->getTable('sliderg/images');
$installer->run("

CREATE TABLE IF NOT EXISTS `{$tableSlider}`(
  `slider_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `column_count` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`slider_id`),
  UNIQUE KEY `UNIQUE_CODE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$tableImages}` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_id` int(11) NOT NULL,
  `name_origin` varchar(255) NOT NULL,
  `name_rename` varchar(255) NOT NULL,
  `path_media` text NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `position` int(5) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`image_id`),
  KEY `FOREIGNKEY_SLIDER_IMAGES` (`slider_id`),
  CONSTRAINT `FOREIGNKEY_SLIDER_IMAGES` FOREIGN KEY (`slider_id`) REFERENCES `sliderg_slider` (`slider_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
");

$installer->endSetup();