<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Adminhtml_Item_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
        public function __construct()
        {
            parent::__construct();
            $this->_objectId = "item_id";
            $this->_blockGroup = "e_promotion";
            $this->_controller = "adminhtml_item";
            $this->_updateButton("save", "label", Mage::helper("adminhtml")->__("Save Item"));
            $this->_updateButton("delete", "label", Mage::helper("adminhtml")->__("Delete Item"));

            $this->_addButton("saveandcontinue", array(
                "label"     => Mage::helper("adminhtml")->__("Save And Continue Edit"),
                "onclick"   => "saveAndContinueEdit()",
                "class"     => "save",
            ), -100);

            $this->_formScripts[] = "
                        function saveAndContinueEdit(){
                            editForm.submit($('edit_form').action+'back/edit/');
                        }
                    ";
        }

        public function getHeaderText()
        {
            if( Mage::registry("e_promotion_item_data") && Mage::registry("e_promotion_item_data")->getId() ){
                return Mage::helper("adminhtml")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("e_promotion_item_data")->getId()));
            }
         	return Mage::helper("adminhtml")->__("Add Item");
        }
}