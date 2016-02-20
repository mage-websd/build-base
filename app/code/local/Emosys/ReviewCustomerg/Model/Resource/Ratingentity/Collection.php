<?php
class Emosys_ReviewCustomerg_Model_Resource_Ratingentity_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/ratingentity');
    }

    public function getRatingTotalPercent($customerId)
    {
        $reviewTable = $this->getTable('review_customerg/review');
        $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('count(*) AS total_review, sum(`main_table`.`value`) AS total_rating')
            ->join($reviewTable,"`{$reviewTable}`.`review_id` = main_table.`review_id`","{$reviewTable}.customer_id")
            ->group("{$reviewTable}.customer_id")
            ->where("`{$reviewTable}`.`customer_id` = '{$customerId}' AND `{$reviewTable}`.`approved`='1'");
        return $this;
    }

    public function getRatingReviewPercent($reviewId)
    {
        $reviewTable = $this->getTable('review_customerg/review');
        $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('count(*) AS total_rating, sum(`main_table`.`value`) AS total_value')
            ->join($reviewTable,"`{$reviewTable}`.`review_id` = main_table.`review_id`","{$reviewTable}.customer_id")
            ->group("main_table.review_id")
            ->where("`{$reviewTable}`.`review_id` = '{$reviewId}' AND `{$reviewTable}`.`approved`='1'");
        return $this;
    }

    public function getRatingEntityTotal($customerId)
    {
        $reviewTable = $this->getTable('review_customerg/review');
        $ratingTable = $this->getTable('review_customerg/rating');
        $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('main_table.rating_id as rating_id, sum(`main_table`.`value`) AS total_value, count(*) AS total_review')
            ->join($reviewTable,"`{$reviewTable}`.`review_id` = main_table.`review_id`","{$reviewTable}.customer_id")
            ->join($ratingTable,"`{$ratingTable}`.`rating_id` = main_table.`rating_id`","{$ratingTable}.rating_name")
            ->group("main_table.rating_id")
            ->where("`{$reviewTable}`.`customer_id` = '{$customerId}' AND `{$reviewTable}`.`approved`='1'");
        return $this;
    }

    public function getRatingValueTotal($customerId)
    {
        $reviewTable = $this->getTable('review_customerg/review');
        $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('`main_table`.`value` AS value, count(*) AS total_value')
            ->join($reviewTable,"`{$reviewTable}`.`review_id` = main_table.`review_id`","{$reviewTable}.customer_id")
            ->group("main_table.value")
            ->where("`{$reviewTable}`.`customer_id` = '{$customerId}' AND `{$reviewTable}`.`approved`='1'");
        return $this;
    }
}