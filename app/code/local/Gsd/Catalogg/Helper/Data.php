<?php

/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 03/08/2015
 * Time: 21:14
 */
class Gsd_Catalogg_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected function _getStoreId() {
        $storeId = Mage::app()->getStore()->getId();
        return $storeId;
    }
    protected function _getCustomerGroupId() {
        $custGroupID = null;
        if ($custGroupID == null) {
            $custGroupID = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }
        return $custGroupID;
    }

    /**
     *object : catalog_product or category
     *
     */
    public function getLabelAttributeOption($_object) {
        $_attribute = $_object->getResource()->getAttribute('grid_column');
        $_attributeLabel = $_attribute->getFrontend()->getValue($_object);
        $_storeLabel = $_attribute->getData('store_label');
        return $_attributeLabel;
    }

    public function getAllOptions($code,$entity="catalog_product")
    {
        $storeId   = Mage::app()->getStore()->getId();
        $config    = Mage::getModel('eav/config');
        $attribute = $config->getAttribute($entity, $code);
        $attrSource = $attribute->setStoreId($storeId)->getSource();

        $attrOptions = $attrSource->getAllOptions(false);
        $storeLabel = $attrSource->getAttribute()->getData('frontend_label');
    }

    public function getCategoryChildren($categoryId,$limit=null) {
      if(is_object($categoryId)) {
        $categoryId = $categoryId->getId();
      }
        $collection = Mage::getModel('catalog/category')->getCollection()
          ->addAttributeToSelect(array('name','url_key'))
          ->addAttributeToFilter('is_active',1)
          ->addAttributeToFilter('parent_id',$categoryId)
          ->addAttributeToSort('position');
        if($limit) {
	   		    $collection->setPageSize($limit);
        }
        return $collection;
    }

}
