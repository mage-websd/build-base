<?php
class Emosys_ReviewCustomerg_Model_Rating extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/rating');
    }
    public function getId()
    {
        return $this->getRatingId();
    }
}