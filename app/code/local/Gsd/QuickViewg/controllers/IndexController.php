<?php
class Gsd_QuickViewg_IndexController extends Mage_Core_Controller_Front_Action //Mage_Catalog_ProductController
{
    public function indexAction()
    {
        if(!Mage::helper('quickviewg')->isEnable()) {
            return '';
        }
        if($this->getRequest()->isXmlHttpRequest()) {
            $response = array();
            $productId = $this->getRequest()->getParam('id');
            if(!$productId) {
                $response['status'] = 0;
            }
            else {
                $product = Mage::getModel('catalog/product')->load($productId);
                if(!$product->getId()) {
                    $response['status'] = 0;
                }
                else {
                    $response['status'] = 1;
                }
            }
            if(!$response['status']) {
                $response['data'] = $this->__('Not found product!');
            }
            else {
                Mage::unregister('product');
                Mage::unregister('current_product');
                Mage::register('product',$product);
                Mage::register('current_product',$product);
                $this->loadLayout();
                $design = Mage::getSingleton('catalog/design');
                $settings = $design->getDesignSettings($product);

                if ($settings->getCustomDesign()) {
                    $design->applyCustomDesign($settings->getCustomDesign());
                }

                $update = $this->getLayout()->getUpdate();
                $this->addActionLayoutHandles();

                $update->addHandle('PRODUCT_TYPE_' . $product->getTypeId());
                $update->addHandle('PRODUCT_' . $product->getId());
                $this->loadLayoutUpdates();

                // Apply custom layout update once layout is loaded
                $layoutUpdates = $settings->getLayoutUpdates();
                if ($layoutUpdates) {
                    if (is_array($layoutUpdates)) {
                        foreach ($layoutUpdates as $layoutUpdate) {
                            $update->addUpdate($layoutUpdate);
                        }
                    }
                }
                $this->generateLayoutXml()->generateLayoutBlocks();
                $this->renderLayout();
                return;
            }
            echo $response['data'];
            return;
        }
        $this->_redirect('/');
    }
}