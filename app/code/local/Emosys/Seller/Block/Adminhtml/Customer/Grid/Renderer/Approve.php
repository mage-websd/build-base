<?php
class Emosys_Seller_Block_Adminhtml_Customer_Grid_Renderer_Approve extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value =  $row->getData($this->getColumn()->getIndex());
        if ( is_null($value) || empty($value) ) {
            return '<a href="' .$this->getUrl('seller/adminhtml_customer/deny', array('id' => $row->getId())). '"><span style="color:green;">Deny</span></a>';
        }
        return '<a href="' .$this->getUrl('seller/adminhtml_customer/approve', array('id' => $row->getId())). '"><span style="color:red;">Approve</span></a>';
    }
}