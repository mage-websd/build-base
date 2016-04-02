<?php

class Gsd_SwatchAttrg_Helper_Config extends Mage_Core_Helper_Abstract {

    public function getConfig($group, $field, $store = null) {
        if (!$group || !$field) {
            return false;
        }
        $methodClass = ucfirst(strtolower($group)) . ucfirst(strtolower($field));
        if(method_exists($this, $methodClass)) {
            return $this->$methodClass();
        }
        if(!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('swatchattrg/' . $group . '/' . $field, $store);
    }
    
    public function getSizeBase() {
        return '300x300';
    }
    public function getSizeList() {
        return '200x200';
    }
    public function getSizeMore() {
        return '80x80';
    }
}
