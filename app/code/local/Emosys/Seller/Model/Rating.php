<?php
class Emosys_Seller_Model_Rating extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('seller/rating');
    }
    public function getId()
    {
        return $this->getRatingId();
    }
}