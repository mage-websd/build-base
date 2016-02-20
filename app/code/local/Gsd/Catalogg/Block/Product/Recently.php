<?php
class Gsd_Catalogg_Block_Product_Recently extends Gsd_Catalogg_Block_Product_Abstract
{
    /**
     * Product Index model instance
     *
     * @var Mage_Reports_Model_Product_Index_Abstract
     */
    protected $_indexModel;

    /**
     * Defines whether specified products ids order should be used
     *
     * @var bool
     */
    protected $_useProductIdsOrder = false;

    protected function _construct()
    {
        parent::_construct();
        $this->setTitle('Recently List');
        $this->_flagBlock = 'CATALOGg_PRODUCT_Recently';
        return $this;
    }
    public function getRecently() {

        $this->_collection = $this->_getModel()
            ->getCollection();

        if ($this->getCustomerId()) {
            $this->_collection->setCustomerId($this->getCustomerId());
        }

        $this->_collection->excludeProductIds($this->_getModel()->getExcludeProductIds())
            ->addUrlRewrite()
            ->setPageSize($this->_limit)
            ->setCurPage(1);
        /* Price data is added to consider item stock status using price index */
        $this->_collection->addPriceData();

        $ids = $this->getProductIds();
        if (empty($ids)) {
            $this->_collection->addIndexFilter();
        } else {
            $this->_collection->addFilterByIds($ids);
        }
        $this->_collection->setAddedAtOrder();
        if ($this-> _useProductIdsOrder && is_array($ids)) {
            $this->_collection->setSortIds($ids);
        }
        $this->_collection = $this->prepareCollection($this->_collection);
        return $this->_collection;
    }

    public function getCollection()
    {
        if ($this->_collection) {
            return $this->_collection;
        }
        $this->_collection = $this->getRecently();
        return $this->_collection;
    }

    protected function _getModel()
    {
        return Mage::getModel('reports/product_index_viewed');
    }

    /**
     * Retrieve product ids, that must not be included in collection
     *
     * @return array
     */
    protected function _getProductsToSkip()
    {
        return array();
    }

    /**
     * Set flag that defines whether products ids order should be used
     *
     * @param bool $use
     * @return Mage_Reports_Block_Product_Abstract
     */
    public function useProductIdsOrder($use = true)
    {
        $this->_useProductIdsOrder = $use;
        return $this;
    }
}