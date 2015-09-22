<?php
class Gsd_OrderCommentg_Model_Quote extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('ordercommentg/quote');
    }
    public function deleteByQuote($quoteId)
    {
        return $this->getResource()->deleteByQuote($quoteId);
    }
    public function getByQuote($quoteId)
    {
        return $this->getResource()->getByQuote($quoteId);
    }
}