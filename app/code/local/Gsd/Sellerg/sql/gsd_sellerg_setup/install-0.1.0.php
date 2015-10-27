<?php
/* @var $installer Mage_Catalog_Model_Resource_Setup */
/**
 * customer
 * class: Mage_Customer_Model_Entity_Setup
 $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$dataAttribute = array(
    "type"     => "varchar",
    "backend"  => "",
    "label"    => "Custom Attribute",
    "input"    => "text",
    "source"   => "",
    "visible"  => true,
    "required" => false,
    "default" => "",
    "frontend" => "",
    "unique"     => false,
    "note"       => "Custom Attribute"
);

$installer->addAttribute("customer", "customattribute", $dataAttribute);

    $attribute   = Mage::getSingleton("eav/config")
        ->getAttribute("customer", "customattribute");


$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'customattribute',
    '999'  //sort_order
);

$used_in_forms=array();

$used_in_forms[]="adminhtml_customer";
//$used_in_forms[]="checkout_register";
//$used_in_forms[]="customer_account_create";
//$used_in_forms[]="customer_account_edit";
//$used_in_forms[]="adminhtml_checkout";
        $attribute->setData("used_in_forms", $used_in_forms)
                ->setData("is_used_for_customer_segment", true)
                ->setData("is_system", 0)
                ->setData("is_user_defined", 1)
                ->setData("is_visible", 1)
                ->setData("sort_order", 100)
                ;
        $attribute->save();

$installer->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'menu_nav_bottom', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Belong Menu Nav bottom',
    'source'        => 'eav/entity_attribute_source_boolean',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'sort_order' => 100,
));
 *
 *
 */

$installer = $this;
$installer->startSetup();
$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'customer_id');
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
    'user_defined' => '1',
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

$installer->removeAttribute(Mage_Catalog_Model_Product::ENTITY, 'approved');
$dataAttribute = array(
    'attribute_set' => 'Default',
    'group' => 'General',
    'label' => 'Approved',
    'visible' => true,
    'type' => 'int',
    'input' => 'boolean',
    'required' => false,
    /*'source' => 'eav/entity_attribute_source_boolean',*/
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'sort_order' => 110,
    'default' => '0',
    'system' => 0,
    'user_defined' => '1',
);
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY,'approved',$dataAttribute);

$installer->endSetup();