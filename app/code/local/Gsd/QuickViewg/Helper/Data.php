<?php
/**
 * Created by PhpStorm.
 * User: GiangSoda
 * Date: 10/06/2015
 * Time: 9:36
 */ 
class Gsd_QuickViewg_Helper_Data extends Mage_Core_Helper_Abstract {
    public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_QuickViewg');
    }
}