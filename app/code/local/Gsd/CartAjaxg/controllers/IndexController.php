<?php
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'CartController.php';

class Gsd_CartAjaxg_IndexController extends Mage_Checkout_CartController
{
    public function addAction()
    {
        if(!Mage::helper('cartajaxg')->isCartEnable()){
            parent::addAction();
            return $this->_redirect('');
        }
        if (!$this->_validateFormKey()) {
            $this->_goBack();
            return;
        }
        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $response = array();
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    $response['status'] = 0;
                    $response['message'] = $this->__('Unable to find Product ID');
                }

                $cart->addProduct($product, $params);
                if (!empty($related)) {
                    $cart->addProductsByIds(explode(',', $related));
                }

                $cart->save();

                $this->_getSession()->setCartWasUpdated(true);

                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );

                if (!$cart->getQuote()->getHasError()) {
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    $response['status'] = 1;
                    $response['message'] = $message;
                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $miniCart = $this->getLayout()->getBlock('mini_cart_header')->toHtml();
                    $response['data']['.links'] = $toplink;
                    $response['data']['.mini-cart-header'] = $miniCart;
                }
            } catch (Mage_Core_Exception $e) {
                $msg = "";
                if ($this->_getSession()->getUseNotice(true)) {
                    $msg = $e->getMessage();
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $msg .= $message . '<br/>';
                    }
                }

                $response['status'] = 0;
                $response['message'] = $msg;
            } catch (Exception $e) {
                $response['status'] = 0;
                $response['message'] = $this->__('Cannot add the item to shopping cart.');
                Mage::logException($e);
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        } else {
            return parent::addAction();
        }
    }

    public function optionsAction()
    {
        $response = array();
        $productId = $this->getRequest()->getParam('product');
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
            $response['message'] = $this->__('Not found product!');
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
        echo $response['message'];
        return;
        /*$productId = $this->getRequest()->getParam('product');
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
        $params = new Varien_Object();
        $params->setCategoryId(false);
        $params->setSpecifyOptions(false);
        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }*/
    }
}