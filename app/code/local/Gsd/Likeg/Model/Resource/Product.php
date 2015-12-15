<?php
class Gsd_Likeg_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('likeg/product','like_id');
    }

    public function getByProduct($productId)
    {
        $storeId = Mage::app()->getStore()->getId();
        $table = $this->getTable('likeg/product');
        $_adapter = $this->_getReadAdapter();
        $select = $_adapter->select()
            ->from($table)
            ->where('product_id = ?', $productId)
            ->where('store_id = ?', $storeId)
        ;
        //$select->reset(Zend_Db_Select::COLUMNS);
        //$select->columns('*');
        return $_adapter->fetchAll($select);
    }
}