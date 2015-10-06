<?php

class Gsd_PriceSlideg_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    	
	public $_currentCategory;
	public $_searchSession;
	public $_productCollection;
	public $_maxPrice;
	public $_minPrice;
	public $_currMinPrice;
	public $_currMaxPrice;
	public $_imagePath;
	public $_pHelper;

	public $_price;
	
	/*
	* 
	* Set all the required data that our slider will require
	* Set current _currentCategory, _searchSession, setProductCollection, setMinPrice, setMaxPrice, setCurrentPrices, _imagePath
	* 
	* @set all required data
	* 
	*/
	public function __construct()
	{
		$this->_pHelper = $this->helper('priceslideg');
		$this->_price = $this->_pHelper->getPrice();
		$this->_currentCategory = Mage::registry('current_category');
		$this->_searchSession = Mage::getSingleton('catalogsearch/session');
		$this->setProductCollection();
		$this->setMinPrice();
		$this->setMaxPrice();
		$this->setCurrentPrices();
		parent::__construct();
		if($this->_pHelper->isEnable()) {
			$this->setTemplate('priceslideg/price.phtml');
		}
		else {
			$this->setTemplate('catalog/layer/filter.phtml');
		}
	}

	public function setNewPrices(){
		$this->setInSession('_newCurrMinPrice', $this->_currMinPrice);	
		$this->setInSession('_newCurrMaxPrice', $this->_currMaxPrice);	
		if(!is_numeric($this->_currMinPrice)){
			$this->_currMinPrice = 0;
			$this->setInSession('_currMinPrice', 0);
		}
		if(!is_numeric($this->_currMaxPrice)){
			$this->_currMaxPrice = 0;
			$this->setInSession('_currMaxPrice', 0);
		}
		/*$sMin = $this->getFromSession('_minPrice');
		$sMax = $this->getFromSession('_maxPrice');*/
		$csMin = $this->getFromSession('_currMinPrice');
		$csMax = $this->getFromSession('_currMaxPrice');
		$ncsMin = $this->getFromSession('_newCurrMinPrice');
		$ncsMax = $this->getFromSession('_newCurrMaxPrice');

		// if Filters are called
		$a[0][] = 'price_index.min_price';
		$a[0][] = 'ASC';
		$loadedCollection = $this->getLayout()->getBlockSingleton('catalog/product_list')
			->getLoadedProductCollection()
			->setOrder('min_price','DESC')
			->getSelect()
			->setPart('order',$a)->query()->fetchAll();
		$countCollection = count($loadedCollection);
		if($countCollection > 0){
			$loadedMin = $loadedCollection[0]['min_price'];
			$loadedMax = $loadedCollection[$countCollection-1]['min_price'];
		}
		else {
			$loadedMin = 0;
			$loadedMax = 0;
		}
		if($this->_currMinPrice == $csMin && $this->_currMaxPrice == $csMax){
			if($this->_minPrice != $ncsMin){
				$this->setInSession('_minPrice', $loadedMin);
				$this->_minPrice = $loadedMin;
			}
			if($loadedMin >= $csMin){
				$this->_currMinPrice = $loadedMin;
				$this->setInSession('_currMinPrice', $loadedMin);
			}
			if($this->_maxPrice != $ncsMax){
				$this->setInSession('_maxPrice', $loadedMin);
				$this->_maxPrice = $loadedMax;
			}
			if($loadedMax <= $csMax){
				$this->_currMaxPrice = $loadedMax;
				$this->setInSession('_currMaxPrice', $loadedMax);
			}
		}else{
			if($ncsMin == $loadedMin){
				$this->setInSession('_minPrice', $loadedMin);
				$this->_minPrice = $loadedMin;
			}
			if($ncsMax == $loadedMax){
				$this->setInSession('_maxPrice', $loadedMin);
				$this->_maxPrice = $loadedMax;
			}
		}
	}
	
	/*
	* Prepare query string that was in the original url 
	*
	* @return queryString
	*/
	public function prepareParams(){
		$url='';
		$params=$this->getRequest()->getParams();
		foreach ($params as $key=>$val) {
			if($key=='id'){
				continue;
			}
			if($key == $this->_pHelper->getPriceParamCode()) {
				continue;
			}
			$url.="&{$key}={$val}";
		}
		return $url;
	}
	
	/*
	* Fetch Current Currency symbol
	* 
	* @return currency
	*/
	public function getCurrencySymbol(){
		return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
	}
	
	/*
	* Fetch Current Minimum Price
	* 
	* @return price
	*/
	public function getCurrMinPrice(){
		if($this->_currMinPrice > 0){
			$min = $this->_currMinPrice;
		} else{
			$min = $this->_minPrice;
		}
		return $min;
	}
	
	/*
	* Fetch Current Maximum Price
	* 
	* @return price
	*/
	public function getCurrMaxPrice(){
		if($this->_currMaxPrice > 0){
			$max = $this->_currMaxPrice;
		} else{
			$max = $this->_maxPrice;
		}
		return $max;
	}

	/*
	* Gives you the current url without parameters
	* 
	* @return url
	*/
	public function getCurrentUrlWithoutParams(){
		$baseUrl = explode('?',Mage::helper('core/url')->getCurrentUrl());
		$baseUrl = $baseUrl[0];
		return $baseUrl;
	}
	
	public function getMinPrice()
	{
		return $this->_minPrice;
	}
	public function getMaxPrice()
	{
		return $this->_maxPrice;
	}

	/*
	* Set the Actual Min Price of the search and catalog collection
	*
	* @use category | search collection
	*/
	public function setMinPrice(){
		if( (isset($_GET['q']) && !$this->_price[0]) || !isset($_GET['q'])){
			if(Mage::getVersion() < '1.7.0.2'){
				$this->_productCollection->getSelect()->reset('order');
				$this->_productCollection->getSelect()->order('final_price','asc');
				$this->_minPrice = round($this->_productCollection->getFirstItem()->getFinalPrice());
			}else{
				$this->_minPrice = floor($this->_productCollection->getMinPrice());
			}
			$this->_searchSession->setMinPrice($this->_minPrice);		
		}else{
			$this->_minPrice = $this->_searchSession->getMinPrice();	
		}
		
	}
	
	/*
	* Set the Actual Max Price of the search and catalog collection
	*
	* @use category | search collection
	*/
	public function setMaxPrice(){
		if( (isset($_GET['q']) && !$this->_price[1]) || !isset($_GET['q'])){
			if(Mage::getVersion() < '1.7.0.2'){
				$this->_productCollection->getSelect()->reset('order');
				$this->_productCollection->getSelect()->order('final_price','asc');
				$this->_maxPrice = round($this->_productCollection->getLastItem()->getFinalPrice());
			}else{
				$this->_maxPrice = ceil($this->_productCollection->getMaxPrice());	
			}
			
			$this->_searchSession->setMaxPrice($this->_maxPrice);
		}else{
			$this->_maxPrice = $this->_searchSession->getMaxPrice();
		}
		
		
	}
	
	/*
	* Set the Product collection based on the page server to user 
	* Might be a category or search page
	*
	* @set /*
	* Set the Product collection based on the page server to user 
	* Might be a category or search page
	*
	* @set Mage_Catalogsearch_Model_Layer 
	* @set Mage_Catalog_Model_Layer    
	*/
	public function setProductCollection(){
		if($this->_currentCategory){
			$this->_productCollection = $this->_currentCategory
				->getProductCollection()
				->addAttributeToSelect('*')
				->setOrder('price', 'ASC');
		}
		else{
			$this->_productCollection = Mage::getSingleton('catalogsearch/layer')
				->getProductCollection()
				->addAttributeToSelect('*')
				->setOrder('price', 'ASC');
		}
	}

	/*
	* Set Current Max and Min Prices choosed by the user
	*
	* @set price
	*/
	public function setCurrentPrices()
	{
		$this->_currMinPrice = $this->_price[0];
		$this->_currMaxPrice = $this->_price[1];
	}
	
	/*
	* Set Current Max and Min Prices choosed by the user
	*
	* @set price
	*/
	public function baseToCurrent($srcPrice){
		$store = $this->getStore();
        return $store->convertPrice($srcPrice, false, false);
	}
	
	
	public function setInSession($var, $value){
		$set = "set".$var;
		Mage::getSingleton('catalog/session')->$set($value);	
	}
	
	public function getFromSession($var){
		$get = "get".$var;
		return Mage::getSingleton('catalog/session')->$get();	
	}
	
	/*
	* Retrive store object
	*
	* @return object
	*/
	public function getStore(){
		return Mage::app()->getStore();
	}
}
