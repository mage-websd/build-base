<?php
class Gsd_Downloadableg_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $linkRedirect = $this->getRequest()->getParam('back_url');
        if(!$linkRedirect) {
            $linkRedirect = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        }
        if (!$this->_validateFormKey()) {
            Mage::getSingleton('core/session')->addError('Error form key');
            Mage::app()->getResponse()->setRedirect($linkRedirect);
            return;
        }
        $productId = $this->getRequest()->getParam('id');
        if(!$productId) {
            Mage::getSingleton('core/session')->addError('Not found Product');
            Mage::app()->getResponse()->setRedirect($linkRedirect);
            return;
        }
        $store = Mage::app()->getStore()->getId();
        $product = Mage::getModel('catalog/product')->setStoreId($store)->load($productId);
        if(!$product->getId()) {
            Mage::getSingleton('core/session')->addError('Not found Product');
            Mage::app()->getResponse()->setRedirect($linkRedirect);
            return;
        }
        if($product->getData('type_id') != Mage::helper('downloadableg')->getProductTypeAllow()) {
            Mage::getSingleton('core/session')->addError('Not found Product');
            Mage::app()->getResponse()->setRedirect($linkRedirect);
            return;
        }
        $cart = Mage::getSingleton('checkout/cart');
        $links= Mage::getModel('downloadable/product_type')->getLinks($product);
        $linkIds = array();
        foreach ( $links as $link ) {
            $linkIds[] = $link->getData('link_id');
        }
        $input = array( 'qty' => 1, 'links' => $linkIds );
        $request = new Varien_Object();
        $request->setData($input);
        try {
            $cart->addProduct($product, $request);
            $cart->save();
            $result = null;
        }
        catch (Mage_Core_Exception $e) {
            Mage::log($e->getMessage());
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
        Mage::getSingleton('core/session')->addSuccess('Add Product '.$product->getName().' Success');
        Mage::app()->getResponse()->setRedirect($linkRedirect);
        return;
    }

    protected function _redirectCart()
    {
        $this->_redirect('');
        return;
    }
}