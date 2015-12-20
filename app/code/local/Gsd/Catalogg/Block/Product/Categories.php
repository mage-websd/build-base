<?php
class Gsd_Catalogg_Block_Product_Categories extends Gsd_Catalogg_Block_Product_Abstract
{
    protected $_title;
    protected $_column ;
    protected $_categories;

    public function _construct() {
        parent::_construct();
        $this->_title = null;
        $this->_flagBlock = 'CATALOGg_PRODUCT_CATEGORY';
        $this->setTemplate('catalog/product/list/categories.phtml');
        return $this;
    }

    public function getCategories() {
        return $this->_categories;
    }

    public function setCategories($categoriesId) {
        if(!$categoriesId) {
            return $this;
        }
        if(!is_array($categoriesId)) {
            $categoriesId = explode(',',$categoriesId);
        }
        if(!count($categoriesId)) {
            return $this;
        }
        $this->_categories = Mage::getModel('catalog/category')->setStoreId($this->_store)
            ->getCollection()
            ->addAttributeToFilter('entity_id',array('in'=>$categoriesId))
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('is_active',1)
            ;
        return $this;
    }
    public function getCollection() {
        if ($this->_collection) {
            return $this->_collection;
        }
        if(!$this->_categories || !count($this->_categories)) {
            return null;
        }
        $collection = array();
        $i=0;
        foreach($this->_categories as $category) {
            $collectionItem =
                Mage::getResourceModel('catalog/product_collection')
                    ->setStoreId($this->_store)
                    ->addCategoryFilter($category)
                    ->setPageSize($this->_limit)
                    ->setCurPage(1)
            ;
            $collectionItem = $this->prepareCollection($collectionItem);
            if(count($collectionItem)) {
                $collection[$i]['product'] = $collectionItem;
                $collection[$i]['category'] = $category;
                $i++;
            }
        }
        $this->_collection = $collection;
        return $this->_collection;
    }
}