<?php
class Gsd_Catalogg_Block_Product_Abstract extends Mage_Catalog_Block_Product_Abstract
{
    protected $_title;
    protected $_column;
    protected $_limit;
    protected $_collection;
    protected $_store;
    protected $_website;
    protected $_flagBlock;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('catalog/product/list/slider.phtml');
        $this->_title = $this->__('List Product');
        $this->_column = 4;
        $this->_limit = 20;
        $this->_collection = null;
        $this->_store = Mage::app()->getStore()->getData('store_id');
        $this->_website = Mage::app()->getStore($this->_store)->getWebsiteId();
        $this->addCacheTag(Mage_Catalog_Model_Product::CACHE_TAG);
        $this->_flagBlock = 'CATALOGg_PRODUCT_LIST';
        return $this;
    }
    protected function _getCustomerGroupId() {
        return Mage::getSingleton('customer/session')->getCustomerGroupId();
    }
    public function getCollection()
    {
        return $this->_collection;
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

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
            $this->_flagBlock,
            $this->_store,
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            $this->_getCustomerGroupId(),
            'template' => $this->getTemplate(),
            $this->_limit,
        );
    }
}