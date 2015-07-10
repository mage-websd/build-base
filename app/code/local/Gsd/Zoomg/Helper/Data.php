<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 10/06/2015
 * Time: 15:42
 */ 
class Gsd_Zoomg_Helper_Data extends Mage_Core_Helper_Abstract {
	public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_QuickViewg') && Mage::getStoreConfig('zoomg/general/enable');
    }

    public function getConfig($path)
    {
        return Mage::getStoreConfig('zoomg/gallery/'.$path);
    }
}