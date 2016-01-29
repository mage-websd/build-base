<?php
class Emosys_Seller_Model_Resource_Ratingentity extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('seller/ratingentity', 'rating_entity_id');
    }
}