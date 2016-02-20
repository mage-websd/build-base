<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Adminhtml_Item extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct()
    {
        $this->_controller = "adminhtml_item";
        $this->_blockGroup = "e_promotion";
        $this->_headerText = Mage::helper("adminhtml")->__("Promotion Manager");
        $this->_addButtonLabel = Mage::helper("adminhtml")->__("Add New Item");
        parent::__construct();
    }

}