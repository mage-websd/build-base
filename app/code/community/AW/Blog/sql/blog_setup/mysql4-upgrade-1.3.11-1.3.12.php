<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/cat')} ADD `parent_id` INT(11) NOT NULL DEFAULT '0' ;
    ALTER TABLE {$this->getTable('blog/blog')} ADD `thumbnail` VARCHAR(255) NULL , ADD `image` VARCHAR(255) NULL ;
");

$installer->endSetup();