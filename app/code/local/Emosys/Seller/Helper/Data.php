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
 * @category    My
 * @package     My_Icustomer
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Banner Helper
 *
 * @category   My
 * @package    My_Icustomer
 * @author     Theodore Doan <theodore.doan@gmail.com>
 */
class Emosys_Seller_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function getAvatarUrl($url = null) {
        return Mage::getSingleton('seller/config')->getBaseMediaUrl() . $url;
    }

    public function isAvatarExisted($url = null) {
        $file = str_replace('/', DS, Mage::getSingleton('seller/config')->getBaseMediaPath() . $url);

        if ( is_file($file) && file_exists($file) ) {
            return true;
        }
        return false;
    }
    public function getAvatarCustomer($_customer) 
    {
        if(!is_object($_customer)) {
            $_customer = Mage::getModel('customer/customer')->load($_customer);
        }
        if ( $avatar = $_customer->getAvatar() ) {
            if ( $this->isAvatarExisted($avatar) ) {
                return $this->getAvatarUrl($avatar);
            }
        }
        return Mage::getBaseUrl('media').'wysiwyg/swatches/blue-jean.png';
    }

    public function getAllowedProductTypes()
    {
        $allowedIds = Mage::getStoreConfig('seller/product/allowed_types');
        if(!$allowedIds) {
            return array(array('value'=>'','label'=>''));
        }
        $allowedIds = explode(',', $allowedIds);
        $types = Mage_Catalog_Model_Product_Type::getOptions();
        foreach ($types as $key => $type) {
            if(!in_array($type['value'],$allowedIds)) {
                unset($types[$key]);
            }
        }
        return $types;
    }

    public function getAllowedAttributeSet()
    {
        $options = array(array('value'=>'','label'=>''));
        $allowedIds = Mage::getStoreConfig('seller/product/allowed_attribute_set');
        if(!$allowedIds) {
            return $options;
        }
        $options = array();
        $allowedIds = explode(',', $allowedIds);
        $collection = Mage::getModel('eav/entity_attribute_set')->getCollection()
            ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() );
        foreach ($collection as $_item) {
            if(in_array($_item->getAttributeSetId(), $allowedIds)) {
                $options[] = array('value' => $_item->getAttributeSetId(), 'label' => $_item->getAttributeSetName());
            }
        }
        return $options;
    }

    public function getProductTypesAssociated()
    {
        return array('grouped','configurable','bundle');
    }

    public function getCustomerProduct($_product)
    {
        if(!$_product || !$_product->getId()) {
            return null;
        }
        $_customerId = $_product->getCustomerId();
        if($_customerId) {
            $_customer = Mage::getModel('customer/customer')->load($_customerId);
            if($_customer->getId()) {
                return array(
                    'customer' => $_customer,
                    'avatar'=>$this->getAvatarCustomer($_customer),
                );
            }
        }
        return null;
    }

    public function getProductCollectionBestSell($_customerId=null,$_limit=20)
    {
        if(!$_customerId) {
            $_customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        }
        $storeId = (int) Mage::app()->getStore()->getId(); 
        $products = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->addAttributeToSelect('*')
            ->addAttributeToSelect(array('name', 'price', 'small_image'))
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            ->setPageSize($_limit)
            ->addAttributeToFilter('customer_id',$_customerId)
            ->setOrder('ordered_qty', 'desc'); // most best sellers on top
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        return $products;
    }
}