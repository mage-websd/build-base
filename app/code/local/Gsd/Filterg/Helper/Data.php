<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 18/06/2015
 * Time: 22:11
 */ 
class Gsd_Filterg_Helper_Data extends Mage_Core_Helper_Abstract {
    public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_Filterg') && Mage::getStoreConfig('filterg/general/enable');
    }
}