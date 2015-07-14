<?php

class Gsd_CartAjaxg_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_Filterg');
    }
    public function isCartEnable()
    {
        return $this->isEnable() && Mage::getStoreConfig('cartajaxg/cart/enable');
    }
    public function isWishEnable()
    {
        return $this->isEnable() && Mage::getStoreConfig('cartajaxg/cart/wish');
    }
    public function isCompareEnable()
    {
        return $this->isEnable() && Mage::getStoreConfig('cartajaxg/cart/compare');
    }
}