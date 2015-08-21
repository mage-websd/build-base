<?php
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->removeAttribute('catalog_product', 'customer_id');
$dataAttribute = array(
    'attribute_set' => 'Default',
    'group' => 'General',
    'label' => 'Customer Id',
    'visible' => true,
    'type' => 'int', // multiselect uses comma-sep storage
    'input' => 'text',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order' => 100,
    'system' => 0,
    'user_defined' => 0,
    /*'user_defined' => 1,
    'default' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order' => '100',
    'backend_type' => 'int',
    'system' => '0',*/
    /*'searchable' => '0',
    'filterable' => '0',
    'comparable' => '0',
    'visible_on_front' => '1',
    'is_html_allowed_on_front' => '0',
    'is_used_for_price_rules' => '1',
    'filterable_in_search' => '0',
    'used_in_product_listing' => '0',
    'used_for_sort_by' => '0',
    'is_configurable' => '0',
    'apply_to' => 'simple',
    'visible_in_advanced_search' => '1',
    'position' => '1',
    'wysiwyg_enabled' => '0',
    'used_for_promo_rules' => '1',
    'option' =>
        array (
            'values' =>
                array (
                    0 => 'Green',
                    1 => 'Silver',
                    2 => 'Black',
                    3 => 'Blue',
                    4 => 'Red',
                    5 => 'Pink',
                    6 => 'Magneta',
                    7 => 'Brown',
                    8 => 'White',
                    9 => 'Gray',
                ),
        )*/

);
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY,'customer_id',$dataAttribute);

$installer->removeAttribute('catalog_product', 'approved');
$dataAttribute = array(
    'attribute_set' => 'Default',
    'group' => 'General',
    'label' => 'Approved',
    'visible' => true,
    'type' => 'int',
    'input' => 'select',
    'required' => false,
    'source' => 'eav/entity_attribute_source_boolean',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order' => 110,
    'default' => '0',
    'system' => 0,
    'user_defined' => false,
);
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY,'approved',$dataAttribute);

$installer->endSetup();