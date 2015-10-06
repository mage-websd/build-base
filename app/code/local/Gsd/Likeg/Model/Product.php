<?php
class Gsd_Likeg_Model_Product extends Mage_Core_Model_Abstract
{
    public function _construct() {
        $this->_init('likeg/product');
    }

    public function getByProduct($productId)
    {
        return $this->getResource()->getByProduct($productId);
    }

}