<?php
class Gsd_Catalogg_Block_Product_Bestseller extends Gsd_Catalogg_Block_Product_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTitle('Best Seller');
        $this->_flagBlock = 'CATALOGg_PRODUCT_BESTSELLER';
        return $this;
    }
    public function getBestSeller() {
        if ($this->_collection) {
            return $this->_collection;
        }
        $collection = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->setStoreId($this->_store)
            ->addStoreFilter($this->_store)
            ->setOrder('ordered_qty', 'desc')
            ->setPageSize($this->_limit)
            ->setCurPage(1);
        $collection = $this->prepareCollection($collection);
        return $collection;
    }

    public function getCollection()
    {
        $this->_collection = $this->getBestSeller();
        return $this->_collection;
    }
}