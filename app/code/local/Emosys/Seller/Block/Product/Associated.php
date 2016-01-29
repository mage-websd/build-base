<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales order history block
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Emosys_Seller_Block_Product_Associated extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('seller/product/information/associated.phtml');
        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('sales')->__('My Products'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $collection = $this->geProductsSimple();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'seller.product.pager')
            ->setCollection($collection);
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

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
                    array('attribute'=>'size','neq'=>''),
            ));
        }
        $this->setCollection($_productCollection);
        return $_productCollection;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getViewUrl($product)
    {
        return $this->getUrl('*/*/view', array('product_id' => $product->getId()));
    }

    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
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

    public function getAssociatedProducts()
    {
        $_product = $this->getProduct();
        if(!$_product) {
            return null;
        }
        //$_product->getTypeInstance(true)->getAssociatedProducts($_product);
        $coreResource = Mage::getSingleton('core/resource');
        $conn = $coreResource->getConnection('core_read');
        $select = $conn->select()
            ->from($coreResource->getTableName('catalog/product_relation'), array('child_id'))
            ->where('parent_id = ?', $_product->getId());
        return $conn->fetchCol($select);
    }

    public function getOptionsBundle()
    {
        $product = $this->getProduct();
        return Mage::getModel('bundle/option')->getResourceCollection()
            ->setProductIdFilter($product->getId())
            ->setPositionOrder()
            ->joinValues($this->_storeId)
            ->getItems();

    }
    public function getSelectsBundle()
    {
        $product = $this->getProduct();
        return $product->getTypeInstance(true)
            ->getSelectionsCollection($product->getTypeInstance(true)->getOptionsIds($product), $product);
    }

    public function getOptionInputType()
    {
        return array(
            array('value'=>'select','label'=>$this->__('Drop-down')),
            array('value'=>'radio','label'=>$this->__('Radio Buttons')),
            array('value'=>'checkbox','label'=>$this->__('Checkbox')),
            array('value'=>'multi','label'=>$this->__('Multiple Select')),
        );
    }
    public function getOptionRequired()
    {
        return array(
            array('value'=>'1','label'=>$this->__('Yes')),
            array('value'=>'0','label'=>$this->__('No')),
        );
    }
}
