<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 07/11/2015
 * Time: 14:27
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

/* @var $installerCatalog Mage_Catalog_Model_Resource_Setup */
//$installer = Mage::getResourceModel('catalog/setup');// new Mage_Catalog_Model_Resource_Setup();
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'is_hamber');
$dataAttribute = array(
    'attribute_set' => 'Default',
    'group' => 'General',
    'label' => 'Is hamber',
    'visible' => true,
    'type' => 'int', // multiselect uses comma-sep storage
    'input' => 'boolean',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order' => 100,
    'system' => 0,
    'user_defined' => '1',
    'is_visible_on_front' => 1,
    'used_in_product_listing' => 1,
);
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY,'is_hamber',$dataAttribute);

$installer->endSetup();