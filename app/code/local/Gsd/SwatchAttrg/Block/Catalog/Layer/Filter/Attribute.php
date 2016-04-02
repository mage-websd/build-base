<?php

class Gsd_SwatchAttrg_Block_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute {

    protected $_code;
    protected $_swatchAttributes;

    public function __construct() {
        parent::__construct();
        if (Mage::helper('swatchattrg')->isEnable()) {
            $this->setTemplate('swatchattrg/catalog/layer/filter.phtml');
            $this->_swatchAttributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        }
        return $this;
    }

    public function getCode() {
        if (!$this->_code) {
            $this->_code = $this->_filter->getAttributeModel()->getAttributeCode();
        }
        return $this->_code;
    }

    public function isShowLayerFilter() {
        if (in_array($this->getCode(), $this->_swatchAttributes) &&
                Mage::helper('swatchattrg/config')->getConfig('general', 'showonlayer')) {
            return true;
        }
        return false;
    }

    public function getRequestValue() {
        return $this->_filter->getAttributeModel()->getAttributeCode();
    }

}
