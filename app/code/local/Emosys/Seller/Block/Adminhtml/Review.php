<?php
class Emosys_Seller_Block_Adminhtml_Review extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
    	parent::__construct();
        $this->_controller = 'adminhtml_review';
        $this->_blockGroup = 'seller';
        $this->_headerText = $this->__("Review Customer");
        $this->_removeButton('add');
    }
}