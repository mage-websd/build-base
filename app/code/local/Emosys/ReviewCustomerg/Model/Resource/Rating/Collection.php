<?php
class Emosys_ReviewCustomerg_Model_Resource_Rating_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/rating');
    }

}