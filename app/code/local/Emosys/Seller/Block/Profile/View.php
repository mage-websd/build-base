<?php

class Emosys_Seller_Block_Profile_View extends Mage_Catalog_Block_Product_Abstract //Mage_Core_Block_Template
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

    public function getCustomer()
    {
        return Mage::registry('current_customer');
    }

    public function getProductCollectionAll($_customerId = null)
    {
        if(!$_customerId) {
            $_customerId = $this->getCustomer()->getId();
        }
        $_productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('customer_id', $_customerId)
            ->addAttributeToSort('created_at', 'DESC')
            ->addAttributeToFilter( 'status',
                array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            )
            ->addAttributeToSelect('*');
        return $_productCollection;
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setCollection($this->getProductCollectionAll());
        $pager = $this->getLayout()
            ->createBlock('page/html_pager', 'seller.product.all.pager')
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
