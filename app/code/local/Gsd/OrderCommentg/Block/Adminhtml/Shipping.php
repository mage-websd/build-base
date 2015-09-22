<?php
class Gsd_OrderCommentg_Block_Adminhtml_Shipping extends Mage_Adminhtml_Block_Sales_Order_Abstract{
    public function getCustomVars(){
        $model = Mage::getModel('ordercommentg/order');
        return $model->getByOrder($this->getOrder()->getId());
    }
}
