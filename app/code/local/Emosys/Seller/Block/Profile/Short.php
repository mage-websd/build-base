<?php

class Emosys_Seller_Block_Profile_Short extends Mage_Catalog_Block_Product_Abstract
{    
    public function getProductCollectionShort($_customerId,$_limit=20)
    {
        $_product = Mage::registry('current_product');
        $_productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('customer_id', $_customerId)
            ->addAttributeToSort('created_at', 'DESC')
            ->addAttributeToFilter( 'status',
                array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            )
            ->setPageSize($_limit)
            ->addAttributeToSelect('*');
        if($_product) {
            $_productCollection->addAttributeToFilter('entity_id',array('neq'=>$_product->getId()));
        }
        return $_productCollection;
    }
}
