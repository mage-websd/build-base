<?php
class Gsd_PriceSlideg_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnable()
    {
        return $this->isModuleOutputEnabled('Gsd_PriceSlideg') && Mage::getStoreConfig('priceslideg/general/enable');
    }

    public function isUseTextbox(){
        return Mage::getStoreConfig('priceslideg/setting/use_textbox');
    }
    public function isUseAjax(){
        return Mage::getStoreConfig('priceslideg/setting/use_ajax');
    }
    public function getTimeout(){
        return Mage::getStoreConfig('priceslideg/setting/timeout') ? Mage::getStoreConfig('priceslideg/setting/timeout') : 0;
    }

    public function getPriceParamCode()
    {
        return 'price_filter';
    }
    public function getPriceMinParamCode()
    {
        return 'price_min';
    }
    public function getPriceMaxParamCode()
    {
        return 'price_max';
    }
    public function getPriceSpaceCode()
    {
        return '-';
    }
    public function getPrice($param=null)
    {
        if(!$param) {
            $param = Mage::app()->getRequest()->getParam($this->getPriceParamCode());
        }
        $price = explode($this->getPriceSpaceCode(),$param);
        if(count($price) != 2) {
            $price = array(0,0);
        }
        else {
            $price[0] = (int)$price[0];
            $price[1] = (int)$price[1];
        }
        return $price;
    }
}
	 