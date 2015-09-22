<?php
class Gsd_OrderCommentg_Model_Resource_Order extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ordercommentg/order', 'id');
    }

    public function deleteByOrder($orderId)
    {
        $table = $this->getTable('ordercommentg/order');
        $_adapter = $this->_getWriteAdapter();
        $_adapter->beginTransaction();
        $_adapter->delete($table, $_adapter->quoteInto('order_id = ?', $orderId, 'INTEGER'));
        $_adapter->commit();
        return $this;
    }

    public function getByOrder($orderId)
    {
        $table = $this->getTable('ordercommentg/order');
        $_adapter = $this->_getReadAdapter();
        $select = $_adapter->select()
            ->from($table)
            ->where('order_id = ?', $orderId);
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns('key');
        $select->columns('value');
        return $_adapter->fetchAll($select);
    }
}