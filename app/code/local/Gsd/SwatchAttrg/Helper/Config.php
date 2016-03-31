<?php

class Gsd_SwatchAttrg_Helper_Config extends Mage_Core_Helper_Abstract {

    public function getConfig($group, $field, $store = null) {
        if (!$group || !$field) {
            return false;
        }
        if(!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('swatchattrg/' . $group . '/' . $field, $store);
    }

}
