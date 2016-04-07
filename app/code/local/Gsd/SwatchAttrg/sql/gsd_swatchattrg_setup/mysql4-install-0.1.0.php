<?php

$installer = $this;
$installer->startSetup();

$conn = $installer->getConnection();
$theTable = $this->getTable('catalog_product_entity_media_gallery_value');

if ($conn->tableColumnExists($theTable, 'defaultimg')):
    Mage::log('Column defaultimg already exists!');
else:
    $conn->addColumn($theTable, 'defaultimg', 'TINYINT(4) UNSIGNED NOT NULL DEFAULT "0"');
endif;

if ($conn->tableColumnExists($theTable, 'selectorbase')):
    Mage::log('Column selectorbase already exists!');
else:
    $conn->addColumn($theTable, 'selectorbase', 'INT(11) UNSIGNED NOT NULL DEFAULT "0"');
endif;

if ($conn->tableColumnExists($theTable, 'selectormore')):
    Mage::log('Column selectormore already exists!');
else:
    $conn->addColumn($theTable, 'selectormore', 'INT(11) UNSIGNED NOT NULL DEFAULT "0"');
endif;

if ($conn->tableColumnExists($installer->getTable('catalog/product_super_attribute_label'), 'preselect')):
    Mage::log('Column preselect already exists!');
else:
    $installer->getConnection()->addColumn($installer->getTable('catalog/product_super_attribute_label'), 'preselect', 'INT(10) unsigned');
endif;

/*$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'swatchattrg_useimages', array(
    'group' => 'Images',
    'input' => 'select',
    'type' => 'int',
    'label' => 'Use Images As Swatches?',
    'source' => 'eav/entity_attribute_source_boolean',
    'frontend_class' => '',
    'backend' => '',
    'frontend' => '',
    'default_value' => 0,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'note' => 'Do you want to use the products "Base Image For" image as the swatch?'
));
*/
$installer->endSetup();
