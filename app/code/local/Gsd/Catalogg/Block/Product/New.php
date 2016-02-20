<?php
class Gsd_Catalogg_Block_Product_New extends Gsd_Catalogg_Block_Product_Abstract
{
    protected $_title;
    protected $_column ;
    public function _construct()
    {
        parent::_construct();
        $this->_title = $this->__('New Product');
        $this->_flagBlock = 'CATALOGg_PRODUCT_NEW';
        return $this;
    }
    public function getCollection()
    {
        if ($this->_collection) {
            return $this->_collection;
        }
        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                )
            )
            ->addAttributeToSort('news_from_date', 'desc')
            ->setPageSize($this->_limit)
            ->setCurPage(1)
        ;
        $this->_collection = $collection;
        return $collection;
    }

}