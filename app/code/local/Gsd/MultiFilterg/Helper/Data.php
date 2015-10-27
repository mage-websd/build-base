<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 17/10/2015
 * Time: 16:17
 */ 
class Gsd_MultiFilterg_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_MultiFilterg') && Mage::getStoreConfig('multifilterg/general/enable');
    }

    public function getTypeShow()
    {
        return Mage::getStoreConfig('multifilterg/general/type_show');
    }
}