<?php
class Gsd_Catalogg_Block_Product_List extends Mage_Catalog_Block_Product_Abstract {
	protected $_title;
    protected $_column;
    protected $_limit;
    protected $_collection;
    protected $_store;
    protected $_website;
    protected $_flagBlock;
    protected $_category;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('catalog/product/list/slider.phtml');
        $this->_title = $this->__('List Product');
        $this->_column = 4;
        $this->_limit = 20;
        $this->_store = Mage::app()->getStore()->getData('store_id');
        $this->_website = Mage::app()->getStore($this->_store)->getWebsiteId();
        return $this;
    }

    public function setLimit($limit)
    {
        return $this->_limit = $limit;
    }

    public function getLimit()
    {
        return $this->_limit;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function setColumn($column)
    {
        $this->_column = $column;
        return $this;
    }
    public function getColumn()
    {
        return $this->_column;
    }

    public function prepareCollection($collection) {
        $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addMinimalPrice()->addFinalPrice()->addTaxPercents();

        //->addUrlRewrite($this->getCurrentCategory()->getId());
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }
    public function getCategory() {
        return $this->_category;
    }

    public function setCategory($categoryId) {
        if(!$categoryId) {
            return $this;
        }
        $this->_category = Mage::getModel('catalog/category')
        	->setStoreId($this->_store)
            ->load($categoryId)
            ;
        if(!$this->_category->getId()) {
        	$this->_category = null;
        }
        $this->_title = $this->_category->getName();
        return $this;
    }
    public function getCollection() {
        if ($this->_collection) {
            return $this->_collection;
        }
        $collection =
            Mage::getResourceModel('catalog/product_collection')
                ->setStoreId($this->_store)
                ->setCurPage(1)
        ;
        if($this->_limit) {
        	$collection->setPageSize($this->_limit);
        }
        if($this->_category) {
        	$collection->addCategoryFilter($this->_category);
        }
        $collection = $this->prepareCollection($collection);
        $this->_collection = $collection;
        return $this->_collection;
    }
}
