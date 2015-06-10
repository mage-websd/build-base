<?php
//require Mage::getModuleDir('controllers','Mage_Catalog').DS.'ProductController.php';
class Gsd_QuickViewg_IndexController extends Mage_Core_Controller_Front_Action //Mage_Catalog_ProductController
{
    public function indexAction()
    {
        if($this->getRequest()->isXmlHttpRequest()) {
            $respon = array();
            $productId = $this->getRequest()->getParam('id');
            if(!$productId) {
                $respon['status'] = 0;
            }
            else {
                $product = Mage::getModel('catalog/product')->load($productId);
                if(!$product->getId()) {
                    $respon['status'] = 0;
                }
                else {
                    $respon['status'] = 1;
                }
            }
            if(!$respon['status']) {
                $respon['data'] = $this->__('Not found product!');
            }
            else {
                Mage::unregister('product');
                Mage::unregister('current_product');
                Mage::register('product',$product);
                Mage::register('current_product',$product);
                $this->loadLayout(false);
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
            //echo Mage::helper('core')->jsonEncode($respon);
            echo $respon['data'];
            return;
        }
        $this->_redirect('/');
    }
}