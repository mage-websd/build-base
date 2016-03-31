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

    public function getTheItems() {
        $items = array();

        foreach (parent::getItems() as $_item) {
            $attributeCode = $_item->getFilter()->getAttributeModel()->getAttributeCode();
            $optionId = $_item->getValueString();

            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($attributeCode)->getFirstItem();

            $attributeId = $attributeInfo->getAttributeId();
            $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setPositionOrder('asc')->setAttributeFilter($attributeId)->setStoreFilter(0)->load();
            foreach ($_collection->toOptionArray() as $_cur_option) {
                if ($_cur_option['value'] == $optionId) {
                    $theLabel = $_cur_option['label'];
                }
            }

            preg_match_all('/((#?[A-Za-z0-9]+))/', $theLabel, $matches);

            if (count($matches[0]) > 0) {
                $color_value = $matches[1][count($matches[0]) - 1];
                $findme = '#';
                $pos = strpos($color_value, $findme);
            } else {
                $pos = false;
            }

            $item = array();
            $item['url'] = $this->htmlEscape($_item->getUrl());
            $item['label'] = $_item->getLabel();
            $item['code'] = $attributeCode;

            $item['count'] = '';
            if (!$this->getHideCounts())
                $item['count'] = ' (' . $_item->getCount() . ')';

            $item['image'] = '';
            $item['bgcolor'] = '';

            if (Mage::helper('swatchattrg')->getSwatchUrl($optionId)):
                $item['image'] = Mage::helper('swatchattrg')->getSwatchUrl($optionId);
            elseif ($pos !== false):
                $item['bgcolor'] = $color_value;
            else:
                $item['image'] = Mage::helper('swatchattrg')->getSwatchUrl('empty');
            endif;
            if ($_item->isSelected()) {
                $item['is_selected'] = 1;
            } else {
                $item['is_selected'] = 0;
            }
            $items[] = $item;
        }

        return $items;
    }

    public function getRequestValue() {
        return $this->_filter->getAttributeModel()->getAttributeCode();
    }

}
