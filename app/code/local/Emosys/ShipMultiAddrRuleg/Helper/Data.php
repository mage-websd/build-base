<?php
/**
 * Created by PhpStorm.
 * User: giangnt
 * Date: 12/02/2015
 * Time: 10:26
 */ 
class Emosys_ShipMultiAddrRuleg_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * Get max weight of single item for express shipping
     *
     * @return mixed
     */
    public function getPrice()
    {
        return Mage::getStoreConfig('carriers/shipmultiaddrruleg/price');
    }
    public function getOrderAmount()
    {
        return Mage::getStoreConfig('carriers/shipmultiaddrruleg/order_amount');
    }

    public function getSubtotal()
    {
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $price = 0;
        foreach ($cart->getAllItems() as $item) {
            //print_r($item->getData());exit;
            $price += $item->getPrice() * $item->getQty();
        }
        return $price;
    }
}