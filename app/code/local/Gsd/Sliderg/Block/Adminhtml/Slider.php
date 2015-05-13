<?php
class Gsd_Sliderg_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_slider';
        $this->_blockGroup = 'sliderg';
        $this->_headerText = $this->__("Manager Slider");
        $this->_addButtonLabel = $this->__('Add');
        parent::__construct();
    }
}