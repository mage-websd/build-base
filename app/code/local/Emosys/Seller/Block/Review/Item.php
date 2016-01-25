<?php
class Emosys_Seller_Block_Review_Item extends Mage_Core_Block_Template
{
    protected $_customerId;
    protected $_customerName;
    protected $_customer;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('seller/review/item.phtml');
        $this->_customer = Mage::registry('current_customer');
    }

    public function getMaxRating()
    {
        return Mage::helper('seller/review')->getMaxRating();
    }

    public function getRating()
    {
        return Mage::getResourceModel('seller/rating_collection')->setOrder('position','asc');
    }
    public function getRatingTotalPercent($customerId = null)
    {
        if(!$customerId) {
            $customerId = $this->_customer->getId();
        }
        if(!$customerId) {
            return null;
        }
        $ratingPercent = Mage::getModel('seller/ratingentity')->getCollection()->getRatingTotalPercent($customerId);
        $ratingPercent = $ratingPercent->getFirstItem();
        $ratingPercent = $ratingPercent->getTotalRating() / ($ratingPercent->getTotalReview() * $this->getMaxRating());
        $ratingPercent = $ratingPercent * 100;
        $ratingPercent = round($ratingPercent);
        return $ratingPercent;
    }

    public function getRatingEntityTotal($customerId = null)
    {
        if(!$customerId) {
            $customerId = $this->_customer->getId();
        }
        if(!$customerId) {
            return null;
        }
        $ratingTotal = Mage::getModel('seller/ratingentity')->getCollection()
            ->getRatingEntityTotal($customerId);
        return $ratingTotal;
    }

    public function getRatingValueTotal($customerId = null)
    {
        if(!$customerId) {
            $customerId = $this->_customer->getId();
        }
        if(!$customerId) {
            return null;
        }
        $valueTotal = Mage::getModel('seller/ratingentity')->getCollection()
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

    public function getCustomer()
    {
        return $this->_customer;
    }

    public function setCustomerId($customerId)
    {
        $this->_customerId = $customerId;
        return $this;
    }
    public function getCustomerId()
    {
        return $this->_customerId;
    }

    public function setCustomerName($customerName)
    {
        $this->_customerName = $customerName;
        return $this;
    }
    public function getCustomerName()
    {
        return $this->_customerName;
    }
}