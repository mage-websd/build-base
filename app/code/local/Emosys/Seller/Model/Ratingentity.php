<?php
class Emosys_Seller_Model_Ratingentity extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('seller/ratingentity');
    }
    public function getId()
    {
        return $this->getRatingEntityId();
    }
}