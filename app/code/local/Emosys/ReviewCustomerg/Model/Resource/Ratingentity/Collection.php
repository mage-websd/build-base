<?php
class Emosys_ReviewCustomerg_Model_Resource_Ratingentity_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/ratingentity');
    }

    public function getRatingCustomerPercent($customerId)
    {
        $reviewTable = $this->getTable('review_customerg/review');
        $result = $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('count(*) AS total_review, sum(main_table.value) AS total_rating')
            ->join($reviewTable,$reviewTable.'.'.'review_id = main_table.review_id',$reviewTable.'.customer_id')
            ->group($reviewTable.'.customer_id')
            ->where($reviewTable.".customer_id = '{$customerId}'");
        return $this;
    }
}