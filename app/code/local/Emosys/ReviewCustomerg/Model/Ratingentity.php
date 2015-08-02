<?php
class Emosys_ReviewCustomerg_Model_Ratingentity extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/ratingentity');
    }
    public function getId()
    {
        return $this->getRatingEntityId();
    }
}