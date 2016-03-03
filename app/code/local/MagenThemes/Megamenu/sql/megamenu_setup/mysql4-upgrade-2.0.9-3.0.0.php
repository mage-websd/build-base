<?php
/****************************************************** 
 * @author http://www.9magentothemes.com
 * @copyright (C) 2012- 9MagentoThemes.Com
 * @license PHP files are GNU/GPL
*******************************************************/
?>
<?php

$installer = $this;

$installer->startSetup();

$conn = $installer->getConnection();
$menuTable = $this->getTable('megacategory');
    $installer->run("
    CREATE TABLE IF NOT EXISTS `megacategory` (
  `megacategory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_label` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `is_group` smallint(6) NOT NULL DEFAULT '2',
  `width` varchar(255) DEFAULT NULL,
  `subitem_width` varchar(255) DEFAULT NULL,
  `article` int(11) DEFAULT NULL,
  `col` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `is_content` smallint(6) NOT NULL DEFAULT '2',
  `show_title` smallint(6) NOT NULL DEFAULT '1',
  `level` smallint(6) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `show_sub` smallint(6) NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `nofollow` smallint(6) NOT NULL DEFAULT '0',
  `label` varchar(20) NOT NULL,
  PRIMARY KEY (`megacategory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
    ");

$installer->endSetup();