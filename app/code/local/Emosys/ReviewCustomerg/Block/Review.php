<?php
class Emosys_ReviewCustomerg_Block_Review extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('review_customerg/review.phtml');
    }
    public function getRating()
    {
        return Mage::getResourceModel('review_customerg/rating_collection')->setOrder('position','asc');
    }
    public function getRatingCustomerPercent($customerId)
    {
        $ratingPercent = Mage::getModel('review_customerg/ratingentity')->getCollection()->getRatingCustomerPercent($customerId);
        $ratingPercent = $ratingPercent->getFirstItem();
        $ratingPercent = $ratingPercent->getTotalRating() / ($ratingPercent->getTotalReview() * 5);
        $ratingPercent = $ratingPercent * 100;
        $ratingPercent = round($ratingPercent);
        return $ratingPercent;
    }
}