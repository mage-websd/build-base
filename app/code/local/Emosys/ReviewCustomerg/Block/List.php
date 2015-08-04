<?php
class Emosys_ReviewCustomerg_Block_List extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('review_customerg/list.phtml');
    }

    public function getListReview()
    {
        $id = $this->getRequest()->getParam('id');
        return Mage::getModel('review_customerg/review')->getCollection()
            ->addFieldToFilter('customer_id',$id)
            ->addFieldToFilter('approved',1)
            ->setOrder('created_at','desc');
    }

    public function getMaxRating()
    {
        return Mage::helper('review_customerg')->getMaxRating();
    }

    public function getRatingReviewPercent($reviewId)
    {
        $ratingPercent = Mage::getModel('review_customerg/ratingentity')->getCollection()
            ->getRatingReviewPercent($reviewId);
        $ratingPercent = $ratingPercent->getFirstItem();
        $ratingPercent = $ratingPercent->getTotalValue() / ($ratingPercent->getTotalRating() * $this->getMaxRating());
        $ratingPercent = $ratingPercent * 100;
        $ratingPercent = round($ratingPercent);
        return $ratingPercent;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setCollection($this->getListReview());
        $pager = $this->getLayout()->createBlock('page/html_pager', 'review_customer_pager')
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}