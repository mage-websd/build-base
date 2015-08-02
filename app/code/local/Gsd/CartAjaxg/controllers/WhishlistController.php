<?php
require_once Mage::getModuleDir('controllers','Mage_Wishlist') . DS . 'IndexController.php';
class Gsd_CartAjaxg_WhishlistController extends Mage_Wishlist_IndexController
{
	/*protected function _getWishlist()
	{
		$wishlist = Mage::registry('wishlist');
		if ($wishlist) {
			return $wishlist;
		}

		try {
			$wishlist = Mage::getModel('wishlist/wishlist')
			->loadByCustomer(Mage::getSingleton('customer/session')->getCustomer(), true);
			Mage::register('wishlist', $wishlist);
		} catch (Mage_Core_Exception $e) {
			Mage::getSingleton('wishlist/session')->addError($e->getMessage());
		} catch (Exception $e) {
			Mage::getSingleton('wishlist/session')->addException($e,
			Mage::helper('wishlist')->__('Cannot create wishlist.')
			);
			return false;
		}

		return $wishlist;
	}*/
	public function addAction()
	{
		if (!$this->_validateFormKey()) {
			return $this->_redirect('*/*');
		}
		if(!Mage::helper('cartajaxg')->isWishEnable()){
			return $this->_redirect('');
		}
		if (!$this->getRequest()->isXmlHttpRequest()) {
			return;
		}
		$response = array();
		if (!Mage::getStoreConfigFlag('wishlist/general/active')) {
			$response['status'] = 0;
			$response['message'] = $this->__('Wishlist has been disabled');
		}
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
			$response['status'] = 0;
			$response['message'] = $this->__('Please login');
		}

		if(empty($response)){
			$session = Mage::getSingleton('customer/session');
			$wishlist = $this->_getWishlist();
			if (!$wishlist) {
				$response['status'] = 0;
				$response['message'] = $this->__('Unable to create wishlist');
			}else{
				$productId = (int) $this->getRequest()->getParam('product');
				if (!$productId) {
					$response['status'] = 0;
					$response['message'] = $this->__('Product not found');
				}else{
					$product = Mage::getModel('catalog/product')->load($productId);
					if (!$product->getId() || !$product->isVisibleInCatalog()) {
						$response['status'] = 0;
						$response['message'] = $this->__('Cannot specify product.');
					}else{
						try {
							$requestParams = $this->getRequest()->getParams();
							$buyRequest = new Varien_Object($requestParams);
							$result = $wishlist->addNewItem($product, $buyRequest);
							if (is_string($result)) {
								Mage::throwException($result);
							}
							$wishlist->save();
							Mage::dispatchEvent(
                				'wishlist_add_product',
								array(
									'wishlist'  => $wishlist,
									'product'   => $product,
									'item'      => $result
								)
							);

							Mage::helper('wishlist')->calculate();

							$message = $this->__('%1$s has been added to your wishlist.', $product->getName()/*, $referer*/);
							$response['status'] = 1;
							$response['message'] = $message;
							Mage::unregister('wishlist');
							$this->loadLayout();
							$toplink = $this->getLayout()->getBlock('top.links')->toHtml();
							//$sidebar_block = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
							$response['data']['.links'] = $toplink;
							//$response['sidebar'] = $sidebar;
						}
						catch (Mage_Core_Exception $e) {
							$response['status'] = 0;
							$response['message'] = $this->__('An error occurred while adding item to wishlist: %s', $e->getMessage());
						}
						catch (Exception $e) {
							mage::log($e->getMessage());
							$response['status'] = 0;
							$response['message'] = $this->__('An error occurred while adding item to wishlist.');
						}
					}
				}
			}

		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
		return;
	}
}