<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 02/10/2015
 * Time: 21:37
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$attributeCode = 'phone_work';
$dataAttribute = array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Phone Work',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'visible_on_front' => 1
);
$this->addAttribute('customer_address', $attributeCode, $dataAttribute);
Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', $attributeCode)
    ->setData('used_in_forms', array(
        'customer_register_address',
        'customer_address_edit',
        'adminhtml_customer_address',
        'adminhtml_checkout',
        'checkout_register',
        )
    )
    ->save();

$tableQuote = $this->getTable('sales/quote_address');
$installer->run("
ALTER TABLE  {$tableQuote} ADD  `{$attributeCode}` varchar(255) NOT NULL
");
$tableOrder = $this->getTable('sales/order_address');
$installer->run("
ALTER TABLE  {$tableOrder} ADD  `{$attributeCode}` varchar(255) NOT NULL
");

$installer->endSetup();
/*
 * {{depend phone_work}}Phone Work: {{var phone_work}}{{/depend}}
 * Phone Work: {phone_work}  -- js
 * */