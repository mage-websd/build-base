<?php

class Gsd_Sellerg_Block_Product_Associatedsimple extends Gsd_Sellerg_Block_Product_Associated
{
    public function geProductsSimple()
    {
        $_productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addAttributeToSort('created_at', 'DESC')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type_id','simple');
        if($this->getProduct()->getTypeId() == 'configurable') {
            $_productCollection->addAttributeToFilter(array(
                array('attribute'=>'color','neq'=>''),
                /*array('attribute'=>'size','neq'=>''),*/
            ));
        }
        $this->setCollection($_productCollection);
        return $_productCollection;
    }

    public function getProduct()
    {
        if($product = Mage::registry('current_product_seller')) {
            return $product;
        }
        $product = $this->getProductId();
        if(!is_object($product)) {
            $product = Mage::getModel('catalog/product')->load($product);
        }
        return $product;
    }
}