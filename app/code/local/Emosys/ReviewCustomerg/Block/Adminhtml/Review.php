<?php
class Emosys_ReviewCustomerg_Block_Adminhtml_Review extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_review';
        $this->_blockGroup = 'review_customerg';
        $this->_headerText = $this->__("Review Customer");
        $this->_removeButton('add_new');
        parent::__construct();
    }
}