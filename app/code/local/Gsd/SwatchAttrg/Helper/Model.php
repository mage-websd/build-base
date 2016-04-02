<?php

class Gsd_SwatchAttrg_Helper_Model extends Mage_Core_Helper_Abstract {
    protected $_resource;
    protected $_read;
    
    public function __construct() {
        $this->_resource = Mage::getSingleton('core/resource');
        $this->_read = $this->_resource->getConnection('core_read');
    }

    public function getLabel($optionId,$store=null) {
        if(!$optionId) {
            return null;
        }
        if(!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        $tableOptionValue = $this->_resource->getTableName('eav/attribute_option_value');
        $query = "SELECT `value`,`store_id` FROM `{$tableOptionValue}` WHERE `option_id`='{$optionId}'";
        $result = $this->_read->fetchAll($query);
        if(!$result) {
            return;
        }
        $labelAdmin = null;
        foreach ($result as $value) {
            if($value['store_id'] == 0) {
                $labelAdmin = $value['value'];
            }
            elseif($value['store_id'] == $store) {
                return $value['value'];
            }
        }
        return $labelAdmin;
    }

}
