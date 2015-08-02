<?php
class Emosys_ReviewCustomerg_Model_Review extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/review');
    }

    public function getId()
    {
        return $this->getReviewId();
    }

    public function loadRating()
    {
        return $this->_getResource()->loadRating($this);
    }
}