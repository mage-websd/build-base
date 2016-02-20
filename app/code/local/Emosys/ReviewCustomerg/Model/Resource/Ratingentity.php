<?php
class Emosys_ReviewCustomerg_Model_Resource_Ratingentity extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/ratingentity', 'rating_entity_id');
    }
}