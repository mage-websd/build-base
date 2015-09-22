<?php
class Gsd_OrderCommentg_Model_Order extends Mage_Core_Model_Abstract
{
    public function _construct() {
        $this->_init('ordercommentg/order');
    }

    public function deleteByOrder($orderId)
    {
        return $this->getResource()->deleteByOrder($orderId);
    }
    public function getByOrder($orderId)
    {
        return $this->getResource()->getByOrder($orderId);
    }
}