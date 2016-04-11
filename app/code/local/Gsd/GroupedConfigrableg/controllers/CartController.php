<?php
require Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php';
class Gsd_GroupedConfigrableg_CartController extends Mage_Checkout_CartController {
    public function addAction() {
        if(!$this->getRequest()->isXmlHttpRequest()) {
            $this->_redirect('');
        }
        $params = $this->getRequest()->getPost();
        $response = array();
        if (!$this->_validateFormKey()) {
            $response['error'] = 1;
            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($this->__('Error submit form!')));
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
        if(!$params) {
            $response['error'] = 1;
            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($this->__('Error submit form!')));
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
        unset($params['form_key']);
        $cart   = $this->_getCart();
        foreach ($params as $productId => $query) {
            parse_str($query,$productInfo);
            $this->_addItem($productId,$productInfo,$response);
        }
        $cart->save();
        $this->_getSession()->setCartWasUpdated(true);
        $response['success'] = 1;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
    
    protected function _addItem($productId, $productInfo,&$response=null) {
        $cart   = $this->_getCart();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct($productId);
            /**
             * Check product availability
             */
            if (!$product) {
                return;
            }

            $cart->addProduct($product, $productInfo);

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
            }
        } catch (Mage_Core_Exception $e) {
            $response['error'] = 1;
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }
        } catch (Exception $e) {
            $response['error'] = 1;
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
        }
    }
    
    protected function _initProduct($productId)
    {
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }
}