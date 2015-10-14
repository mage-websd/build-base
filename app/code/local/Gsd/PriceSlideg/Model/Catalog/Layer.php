<?php


class Gsd_PriceSlideg_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
	/*
	* Add Filter in product Collection for new price
	*
	* @return object
	*/
    public function getProductCollection()
    {
		$pHelper = Mage::helper('priceslideg');
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        }
		else {
            $collection = $this->getCurrentCategory()->getProductCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
		$currentRate = Mage::app()->getStore()->getCurrentCurrencyRate();
		$price = Mage::app()->getRequest()->getParam($pHelper->getPriceParamCode());
		$price = $pHelper->getPrice($price);
		$max = round($price[1]  / $currentRate);
		$min = round($price[0] / $currentRate);
		if($min && $max){
			$collection->getSelect()->where(' final_price >= "'.$min.'" AND final_price <= "'.$max.'" ');
		}
        return $collection;
    }
}
