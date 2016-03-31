<?php

class Gsd_SwatchAttrg_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tabs {

    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $swatchAttributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        if (in_array(Mage::registry('entity_attribute')->getData('attribute_code'), $swatchAttributes)) {
            $this->addTab('swatches', array(
                'label' => Mage::helper('swatchattrg')->__('Manage Swatches'),
                'title' => Mage::helper('swatchattrg')->__('Manage Swatches'),
                'content' => $this->getLayout()->createBlock('swatchattrg/swatches')->toHtml(),
            ));
            return Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
        }

        return $this;
    }

}
