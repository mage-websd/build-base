<?php

class Gsd_Sellerg_Block_Product_Associated extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('sellerg/product/information/associated.phtml');

        $_productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addAttributeToSort('created_at', 'DESC')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type_id','simple')
            ->addAttributeToFilter(array(
                    array('attribute'=>'color','neq'=>''),
                    //array('attribute'=>'size','neq'=>''),
                )
            );

        $this->setCollection($_productCollection);
        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('sales')->__('My Products'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'seller.product.pager')
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getProduct()
    {
        return Mage::registry('current_product_seller');
    }

    public function getAssociatedProducts()
    {
        $_product = $this->getProduct();
        if(!$_product) {
            return null;
        }
        $coreResource = Mage::getSingleton('core/resource');
        $conn = $coreResource->getConnection('core_read');
        $select = $conn->select()
            ->from($coreResource->getTableName('catalog/product_relation'), array('child_id'))
            ->where('parent_id = ?', $_product->getId());

        return $conn->fetchCol($select);
    }
}
