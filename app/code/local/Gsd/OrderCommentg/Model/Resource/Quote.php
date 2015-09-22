<?php
class Gsd_OrderCommentg_Model_Resource_Quote extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ordercommentg/quote', 'id');
    }

    public function deleteByQuote($quoteId)
    {
        $table = $this->getTable('ordercommentg/quote');
        $_adapter = $this->_getWriteAdapter();
        $_adapter->beginTransaction();
        $_adapter->delete($table, $_adapter->quoteInto('quote_id = ?', $quoteId, 'INTEGER'));
        $_adapter->commit();
        return $this;
    }

    public function getByQuote($quoteId)
    {
        $table = $this->getTable('ordercommentg/quote');
        $_adapter = $this->_getReadAdapter();
        $select = $_adapter->select()
            ->from($table)
            ->where('quote_id = ?', $quoteId);
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns('key');
        $select->columns('value');
        return $_adapter->fetchAll($select);
    }
}