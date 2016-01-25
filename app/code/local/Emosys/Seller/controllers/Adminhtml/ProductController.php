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
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog product controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Emosys_Seller_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Emosys_Seller');
    }

    /**
     * Product list page
     */
    public function indexAction()
    {
        $this->_title($this->__('Seller'))
             ->_title($this->__('Manage Products'));

        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('seller/product');

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Approve action
     *
     */
    public function approveAction()
    {
        $productIds = (array)$this->getRequest()->getParam('id');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);
        $status     = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;

        try {
            $this->_validateMassStatus($productIds, $status);
            Mage::getSingleton('catalog/product_action')
                ->updateAttributes($productIds, array('status' => $status), $storeId);
            Mage::dispatchEvent('catalog_controller_product_mass_status', array('product_ids' => $productIds));

            $this->_getSession()->addSuccess(
                $this->__('The product has been approved.')
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while approving the product.'));
        }

        $this->_redirect('seller/adminhtml_product', array('store'=> $storeId));
    }

    /**
     * Approve action
     *
     */
    public function denyAction()
    {
        $productIds = (array)$this->getRequest()->getParam('id');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);
        $status     = Mage_Catalog_Model_Product_Status::STATUS_DISABLED;

        try {
            $this->_validateMassStatus($productIds, $status);
            Mage::getSingleton('catalog/product_action')
                ->updateAttributes($productIds, array('status' => $status), $storeId);
            Mage::dispatchEvent('catalog_controller_product_mass_status', array('product_ids' => $productIds));

            $this->_getSession()->addSuccess(
                $this->__('The product has been denied.')
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while deny the product.'));
        }

        $this->_redirect('seller/adminhtml_product', array('store'=> $storeId));
    }

    /**
     * Approve action
     *
     */
    public function massApproveAction()
    {
        $productIds = (array)$this->getRequest()->getParam('product');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);
        $status     = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;

        try {
            $this->_validateMassStatus($productIds, $status);
            Mage::getSingleton('catalog/product_action')
                ->updateAttributes($productIds, array('status' => $status), $storeId);
            Mage::dispatchEvent('catalog_controller_product_mass_status', array('product_ids' => $productIds));

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been approved.', count($productIds))
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while approving the product(s).'));
        }

        $this->_redirect('seller/adminhtml_product', array('store'=> $storeId));
    }

    /**
     * Approve action
     *
     */
    public function massDenyAction()
    {
        $productIds = (array)$this->getRequest()->getParam('product');
        $storeId    = (int)$this->getRequest()->getParam('store', 0);
        $status     = Mage_Catalog_Model_Product_Status::STATUS_DISABLED;

        try {
            $this->_validateMassStatus($productIds, $status);
            Mage::getSingleton('catalog/product_action')
                ->updateAttributes($productIds, array('status' => $status), $storeId);
            Mage::dispatchEvent('catalog_controller_product_mass_status', array('product_ids' => $productIds));

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been denied.', count($productIds))
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while deny the product(s).'));
        }

        $this->_redirect('seller/adminhtml_product', array('store'=> $storeId));
    }

    /**
     * Validate batch of products before theirs status will be set
     *
     * @throws Mage_Core_Exception
     * @param  array $productIds
     * @param  int $status
     * @return void
     */
    public function _validateMassStatus(array $productIds, $status)
    {
        if ($status == Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
            if (!Mage::getModel('catalog/product')->isProductsHasSku($productIds)) {
                throw new Mage_Core_Exception(
                    $this->__('Some of the processed products have no SKU value defined. Please fill it prior to performing operations on these products.')
                );
            }
        }
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/products');
    }
}
