<?php
class Emosys_Seller_Model_Resource_Rating extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('seller/rating', 'rating_id');
    }
}