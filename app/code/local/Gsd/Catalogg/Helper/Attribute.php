<?php
class Gsd_Catalogg_Helper_Attribute extends Mage_Core_Helper_Abstract {
  public function getLabelAttribute($attrCode) {
	    $_attribute = Mage::getResourceModel('catalog/product')->getAttribute($attrCode);
	    //$_attributeLabel = $_attribute->getFrontend()->getValue($_object);
	    $_storeLabel = $_attribute->getData('store_label');
	    return $_storeLabel;
	}
	public function getAttributesCode($_currentProduct) {
	    $setId = $_currentProduct->getData('attribute_set_id');
	    $groups = Mage::getModel('eav/entity_attribute_group')
	            ->getResourceCollection()
	            ->setAttributeSetFilter($setId)
	            ->addFieldToFilter('attribute_group_name','Extract')
	            ->setSortOrder()
	            ->load();
	    $attributeCodes = array();
	    foreach ($groups as $group) {
	        $groupName          = $group->getAttributeGroupName();
	        $groupId            = $group->getAttributeGroupId();
	        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
	            ->setAttributeGroupFilter($group->getId())
	            ->addVisibleFilter()
	            ->checkConfigurableProducts()
	            ->load();
	        if ($attributes->getSize() > 0) {
	            foreach ($attributes->getItems() as $attribute) {
	                $attributeCodes[] = $attribute->getAttributeCode();
	            }
	        }
	    }
	    return $attributeCodes;
	}
}
