<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Adminhtml_Item_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("item_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("adminhtml")->__("Item Information"));
    }
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("adminhtml")->__("Item Information"),
            "title" => Mage::helper("adminhtml")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("e_promotion/adminhtml_item_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
