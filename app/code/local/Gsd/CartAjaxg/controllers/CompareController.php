<?php
require_once Mage::getModuleDir('controllers','Mage_Catalog'). DS . 'Product/CompareController.php';
class Gsd_CartAjaxg_CompareController extends  Mage_Catalog_Product_CompareController
{
    public function addAction(){
        if(!Mage::helper('cartajaxg')->isCompareEnable()){
            return parent::addAction();
        }
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->_redirect('');
        }
        if (!$this->_validateFormKey()) {
            $this->_redirectReferer();
            return;
        }
        $response = array();
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId
            && (Mage::getSingleton('log/visitor')->getId() || Mage::getSingleton('customer/session')->isLoggedIn())
        ) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

            if ($product->getId()/* && !$product->isSuper()*/) {
                Mage::getSingleton('catalog/product_compare_list')->addProduct($product);
                $response['status'] = 1;
                $response['message'] = $this->__('The product %s has been added to comparison list.', Mage::helper('core')->escapeHtml($product->getName()));
                Mage::dispatchEvent('catalog_product_compare_add_product', array('product'=>$product));
                $this->loadLayout();
                $compareSidebar = $this->getLayout()->getBlock('catalog.compare.sidebar')->toHtml();
                $response['data']['.block-list.block-compare'] = $compareSidebar;
            }
            else {
                $response['status'] = 0;
                $response['message'] = $this->__('Product not found');
            }
            Mage::helper('catalog/product_compare')->calculate();
        }
        else {
            $response['status'] = 0;
            $response['message'] = $this->__('Product not found');
        }
        Mage::register('referrer_url', $this->_getRefererUrl());
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
}