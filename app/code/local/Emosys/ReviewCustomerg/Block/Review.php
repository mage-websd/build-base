<?php
class Emosys_ReviewCustomerg_Block_Review extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('review_customerg/review.phtml');
    }

    public function getMaxRating()
    {
        return Mage::helper('review_customerg')->getMaxRating();
    }

    public function getRating()
    {
        return Mage::getResourceModel('review_customerg/rating_collection')->setOrder('position','asc');
    }
    public function getRatingTotalPercent($customerId)
    {
        $ratingPercent = Mage::getModel('review_customerg/ratingentity')->getCollection()->getRatingTotalPercent($customerId);
        $ratingPercent = $ratingPercent->getFirstItem();
        $ratingPercent = $ratingPercent->getTotalRating() / ($ratingPercent->getTotalReview() * $this->getMaxRating());
        $ratingPercent = $ratingPercent * 100;
        $ratingPercent = round($ratingPercent);
        return $ratingPercent;
    }

    public function getRatingEntityTotal($customerId)
    {
        $ratingTotal = Mage::getModel('review_customerg/ratingentity')->getCollection()
            ->getRatingEntityTotal($customerId);
        return $ratingTotal;
    }

    public function getRatingValueTotal($customerId)
    {
        $valueTotal = Mage::getModel('review_customerg/ratingentity')->getCollection()
            ->getRatingValueTotal($customerId);
        $result = array(
            'total' => 0,
            'value' => array(),
        );
        $valueArray = array();
        foreach($valueTotal as $value) {
            $valueArray[$value->getData('value')] = $value->getData('total_value');
            $result['total'] += $value->getData('total_value');
        }
        for($i = 1 ; $i <= $this->getMaxRating(); $i++) {
            if(isset($valueArray[$i])) {
                $result['value'][$i] = $valueArray[$i];
                unset($valueArray[$i]);
            }
            else {
                $result['value'][$i] = 0;
            }
        }
        return $result;
    }
}