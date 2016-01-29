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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account controller
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Emosys_Seller_ProductController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_PRODUCT_APPROVAL = 'seller/general/product_approval';

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        parent::preDispatch();

        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }
    }

    /**
     * Default customer account page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('List product - Vendor Account'));
        $this->renderLayout();
    }

    /**
     * Default customer account page
     */
    public function addAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Add Product - Vendor Account'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $product = $this->_checkProductVendor($id);
        if(!$product) {
            return;
        }
        Mage::register('current_product_seller',$product);
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('customer/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Edit Product - Vendor Account'));
        $this->renderLayout();
    }

    /**
     * Default customer account page
     */
    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('');
            return;
        }
        if($data = $this->getRequest()->getPost()) {
            Mage::app()->setCurrentStore(Mage::app()->getStore()->getStoreId());
            $product = Mage::getModel('catalog/product');
            if(isset($data['entity_id']) && $data['entity_id']) {
                $product = $this->_checkProductVendor($data['entity_id']);
                if(!$product) {
                    return;
                }
            } else {
                if ( Mage::getStoreConfig(self::XML_PATH_PRODUCT_APPROVAL) == '1' ) {
                    $data['status'] = 2;
                }
            }
            try {
                $data['website_ids'] = array(Mage::app()->getWebsite()->getId());
                if(isset($data['category_ids']) && is_array($data['category_ids'])) {
                }
                else {
                    $data['category_ids'] = array();
                }
                $data['msrp_enabled'] = 2;
                $data['msrp_display_actual_price_type'] = 4;
                $data['meta_title'] = $data['name'];
                $data['meta_keyword'] = $data['name'];
                $data['meta_description'] = $data['name'];
                $data['stock_data'] = array(
                           'use_config_manage_stock' => 0, //'Use config settings' checkbox
                           'manage_stock'=>1, //manage stock
                           'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                           'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                           'is_in_stock' => $data['inventory_stock_availability'], //Stock Availability
                           'qty' => $data['qty'] //qty
                        );
                $data['customer_id'] = $this->_getSession()->getId();

                $product = $this->_uploadImageProduct($product);
                foreach ($data as $key => $value) {
                    $product->setData($key,$value);
                }
                $product = $this->_associatedPost($product);
                $product->save();
                if($product->getId()) {
                    Mage::getSingleton('core/session')->addSuccess('Save prodcut success');
                    if(isset($data['submit_edit'])) {
                        $this->_redirect('*/*/edit',array('id'=>$product->getId()));
                    }
                    else {
                        $this->_redirect('*/*/index');
                    }
                    return;
                }
                else {
                    Mage::getSingleton('core/session')->addError('Error save product');
                }
            } catch(Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function _uploadImageProduct(&$product)
    {
        Mage::app()->setCurrentStore(Mage::app()->getStore()->getStoreId());
        $_productImagePath = $this->_uploadImage('product_image');
        if($_productImagePath) {
            if(is_array($_productImagePath) && count($_productImagePath)) {
                foreach($_productImagePath as $_productImagePathItem) {
                    $product->addImageToMediaGallery( $_productImagePathItem , null, false, false );
                    if(file_exists($_productImagePathItem)) {
                        unlink($_productImagePathItem);
                    }
                }
            }
            else {
                $product->addImageToMediaGallery( $_productImagePath , null, false, false );
                if(file_exists($_productImagePath)) {
                    unlink($_productImagePath);
                }
            }
        }
        $data = $this->getRequest()->getPost();
        $mediaGallery = $product->getMediaGallery();
        /* @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getModel('core/resource');
        $write = $resource->getConnection('core_write');
        $table = $resource->getTableName('catalog/product_attribute_media_gallery_value');
        if (isset($mediaGallery['images']) && count($mediaGallery['images'])){
            foreach ($mediaGallery['images'] as &$image) {
                //delete image
                if(isset($data['product_image_remove']) && $data['product_image_remove']) {
                    if(in_array($image['value_id'], $data['product_image_remove'])) {
                        $image['removed'] = 1;
                    }
                }
                //update disable image
                $flagUpdate = false;
                if(isset($data['product_image_exclude']) && $data['product_image_exclude']) {
                    if(in_array($image['value_id'], $data['product_image_exclude'])) {
                        $image['disabled'] = 1;
                        $image['disable_default'] = 1;
                        $write->query(
                            "UPDATE {$table} SET `disabled`='1' WHERE `value_id`='{$image['value_id']}'"
                        );
                        $flagUpdate = true;
                    }
                }
                if(!$flagUpdate) {
                    $image['disabled'] = 0;
                    $image['disable_default'] = 0;
                    $write->query(
                        "UPDATE {$table} SET `disabled`='0' WHERE `value_id`='{$image['value_id']}'"
                    );
                }
                //update label
                $image['label'] = $data['product_image_label'][$image['value_id']];
                $write->query(
                    "UPDATE {$table} SET `label`='{$data['product_image_label'][$image['value_id']]}' WHERE `value_id`='{$image['value_id']}'"
                );
            }
        }
        $product->setData('media_gallery', $mediaGallery);
        return $product;
    }

    protected function _associatedPost(&$product)
    {
        if(!$product->getId()) {
            return $product;
        }
        if(!in_array($product->getTypeId(),Mage::helper('seller')->getProductTypesAssociated())){
            return $product;
        }
        switch ($product->getTypeId()) {
            case 'configurable':
                return $this->_associatedPostConfigurable($product);
                break;
            case 'grouped':
                return $this->_associatedPostGrouped($product);
                break;
            case 'bundle':
                return $this->_associatedPostBundle($product);
                break;
            default:
                return $product;
                break;
        }
        return $product;
    }

    protected function _associatedPostConfigurable(&$product)
    {
        $data = $this->getRequest()->getPost();
        Mage::app()->setCurrentStore(Mage::app()->getStore()->getStoreId());
        try {
            $_attributeCodeAllow = array('color','size');
            $_attributeAllow = Mage::getModel('eav/entity_attribute')->getCollection()
                ->addFieldToFilter('attribute_code',array('in'=>$_attributeCodeAllow));
            $arrayAttributeAllow = array();
            if(count($_attributeAllow)) {
                foreach ($_attributeAllow as $_attr) {
                    $arrayAttributeAllow[$_attr->getData('attribute_id')] = $_attr->getData('attribute_code');
                    $_attributeIdsAllow[] = $_attr->getData('attribute_id');
                }

                $product->getTypeInstance()->setUsedProductAttributeIds($_attributeIdsAllow); //attribute ID of attribute 'color' in my store
                $configurableAttributesData = $product->getTypeInstance()->getConfigurableAttributesAsArray();

                $configurableProductsData = array();
                if ($data['associated'] && count($data['associated'])) {
                    foreach ($data['associated'] as $_productSimpleId) {
                        $_productSimple = Mage::getModel('catalog/product')->load($_productSimpleId);
                        foreach ($arrayAttributeAllow as $_attrId => $_attrCode) {
                            if ($_productSimple->getData($_attrCode)) {
                                $_attributeValue = $_productSimple->getResource()->getAttribute($_attrCode);
                                $configurableProductsData[$_productSimpleId][] = array( //['920'] = id of a simple product associated with this configurable
                                    'label' => $_attributeValue->getFrontend()->getValue($_productSimple), //attribute label
                                    'attribute_id' => $_attrId, //attribute ID of attribute 'color' in my store
                                    'value_index' => $_productSimple->getData($_attrCode), //value of 'Green' index of the attribute 'color'
                                    'is_percent' => '0', //fixed/percent price for this option
                                    'pricing_value' => '0' //value for the pricing
                                );
                            }
                        }
                    }
                }
                $product->setCanSaveConfigurableAttributes(true);
                $product->setConfigurableAttributesData($configurableAttributesData);
                $product->setConfigurableProductsData($configurableProductsData);

                $resource = Mage::getSingleton('core/resource');
                $write = $resource->getConnection('core_write');
                $table = $resource->getTableName('catalog/product_super_attribute');
                $write->delete($table, "product_id = " . $product->getId());
            }
        } catch(Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        return $product;
    }

    protected function _associatedPostGrouped(&$product)
    {
        $data = $this->getRequest()->getPost();
        Mage::app()->setCurrentStore(Mage::app()->getStore()->getStoreId());
        if ($data['associated'] && count($data['associated'])) {
            $arrayDataAssociated = array();
            foreach ($data['associated'] as $productIdSimple) {
                $arrayDataAssociated[$productIdSimple] = array(
                    'qty' => 1,
                    'position' => 0,
                );
            }
            $product->setGroupedLinkData($arrayDataAssociated);
        }
        return $product;
    }

    protected function _associatedPostBundle(&$product)
    {
        $data = $this->getRequest()->getPost();
        $data = $data['bundle_options'];
        Mage::app()->setCurrentStore(Mage::app()->getStore()->getStoreId());
        $bundleOptions = array();
        $bundleSelections = array();
        $indexItem = -1;
        foreach($data as $bundleData) {
            $indexItem++;
            $bundleOptions[$indexItem] = array(
                'title' => $bundleData['title'], //option title
                'option_id' => (isset($bundleData['option_id']) && $bundleData['option_id']) ? $bundleData['option_id'] : '',
                'delete' => (isset($bundleData['delete']) && $bundleData['delete']) ? 1 : '',
                'type' => $bundleData['type'], //option type
                'required' => $bundleData['required'], //is option required
                'position' => $bundleData['position'], //option position
                'default_title' => $bundleData['title'], //option title
            );
            foreach($bundleData['product'] as $bundleDataProduct) {
                $bundleSelections[$indexItem][] = array(
                    'product_id' => $bundleDataProduct['id'], //if of a product in selection
                    'delete' => (isset($bundleDataProduct['delete']) && $bundleDataProduct['delete']) ? 1 : '',
                    'selection_price_value' => 0,
                    'selection_price_type' => 0,
                    'selection_qty' => 1,
                    'selection_can_change_qty' => 0,
                    'position' => 0,
                    'is_default' => 0,
                    'selection_id' => '',
                    'option_id' => '',
                );
            }
        }
        //flags for saving custom options/selections
        //registering a product because of Mage_Bundle_Model_Selection::_beforeSave

        Mage::register('product', $product);
        Mage::register('current_product', $product);

        $product->setCanSaveConfigurableAttributes(false);
        $product->setCanSaveCustomOptions(true);

        //setting the bundle options and selection data
        $product->setBundleOptionsData($bundleOptions);
        $product->setBundleSelectionsData($bundleSelections);

        $product->setCanSaveBundleSelections(true);
        $product->setAffectBundleProductSelections(true);

        return $product;
    }

    protected function _uploadImage($inputName,$folder='template_image',$filesDispersion = false)
    {
        if(isset($_FILES[$inputName]) &&
            isset($_FILES[$inputName]['name']) &&
            $_FILES[$inputName]['name'] &&
            file_exists($_FILES[$inputName]["tmp_name"])) {
            try {
                $uploader = new Varien_File_Uploader($inputName);
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion($filesDispersion); //split folder of image
                $path = Mage::getBaseDir('media') . DS . $folder .DS ;
                $destFile = $path.$_FILES[$inputName]['name'];
                $filename = $uploader->getNewFileName($destFile);
                $result = $uploader->save($path, $filename);
                if($result)
                    return $path . $result['file'];
            }catch(Exception $e) {
                Mage::throwException($e);
                return null;
            }
        }
        return null;
    }

    protected function _checkProductVendor($_id)
    {
        if(!$_id) {
            $this->_redirect('*/*/index');
            return false;
        }
        $product = Mage::getModel('catalog/product')->load($_id);
        if(!$product->getId()) {
            $this->_redirect('*/*/index');
            return false;
        }
        if($product->getCustomerId() != $this->_getSession()->getId()) {
            $this->_redirect('*/*/index');
            return false;
        }
        return $product;
    }
}