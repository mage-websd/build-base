<?php
class Emosys_Seller_Block_Adminhtml_Product_Grid_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected static $_customer = array();

    public function render(Varien_Object $row) {
        $value =  $row->getData($this->getColumn()->getIndex());
        if ( isset(self::$_customer[$value]) ) {
            return '<a href="' .$this->getUrl('adminhtml/customer/edit', array('id' => self::$_customer[$value]['id'])). '" target="_blank"><span>' .self::$_customer[$value]['name']. '</span></a>';
        }
        $customer = Mage::getModel('customer/customer')->load($value);
        if ( $customer->getId() ) {
            self::$_customer[$value] = array(
                'id' => $customer->getId(),
                'name' => sprintf('%s %s', $customer->getFirstname(), $customer->getLastname())
            );
        }
        if ( isset(self::$_customer[$value]) ) {
            return '<a href="' .$this->getUrl('adminhtml/customer/edit', array('id' => self::$_customer[$value]['id'])). '" target="_blank"><span>' .self::$_customer[$value]['name']. '</span></a>';
        }
        return '-- None --';
    }
}