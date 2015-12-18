<?php
class Gsd_Catalogg_Block_Product_New extends Mage_Catalog_Block_Product_New
{
    protected $_title;
    protected $_column ;
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('catalog/product/list/slider.phtml');
        $this->_title = $this->__('New Product');
        $this->_column = 4;
        return $this;
    }
    public function getCollection()
    {
        return $this->getProductCollection();
    }

    public function setLimit($limit)
    {
        return $this->setProductsCount($limit);
    }

    public function getLimit()
    {
        return $this->getProductsCount();
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

}