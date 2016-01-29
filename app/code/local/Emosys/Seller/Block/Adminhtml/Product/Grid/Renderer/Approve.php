<?php
class Emosys_Seller_Block_Adminhtml_Product_Grid_Renderer_Approve extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value =  $row->getData($this->getColumn()->getIndex());
        if ( $value == Mage_Catalog_Model_Product_Status::STATUS_ENABLED ) {
            return '<a href="' .$this->getUrl('seller/adminhtml_product/deny', array('id' => $row->getId())). '"><span style="color:green;">Deny</span></a>';
        } elseif ( $value == Mage_Catalog_Model_Product_Status::STATUS_DISABLED ) {
            return '<a href="' .$this->getUrl('seller/adminhtml_product/approve', array('id' => $row->getId())). '"><span style="color:red;">Approve</span></a>';
        }
        return '-- None --';
    }
}