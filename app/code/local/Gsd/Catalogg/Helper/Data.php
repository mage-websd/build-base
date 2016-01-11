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
    public function getBestSell() {
        $storeId = (int)Mage::app()->getStore()->getId();

        // Date
        $date = new Zend_Date();
        $toDate = $date->setDay(1)->getDate()->get('Y-MM-dd');
        $fromDate = $date->subMonth(1)->getDate()->get('Y-MM-dd');

        $collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())->addStoreFilter()->addPriceData()->addTaxPercents()->addUrlRewrite()->setPageSize(6);

        $collection->getSelect()->joinLeft(array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_monthly')), "e.entity_id = aggregation.product_id AND aggregation.store_id={$storeId} AND aggregation.period BETWEEN '{$fromDate}' AND '{$toDate}'", array('SUM(aggregation.qty_ordered) AS sold_quantity'))->group('e.entity_id')->order(array('sold_quantity DESC', 'e.created_at'));

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }

    public function getBestSell2() {
        $storeId = (int)Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('reports/product_collection')->addOrderedQty()->addAttributeToSelect('*')->addAttributeToSelect(array('name', 'price', 'small_image'))->setStoreId($storeId)->addStoreFilter($storeId)->setOrder('ordered_qty', 'desc');

        // most best sellers on top
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        return $products;
    }

    public function getSale($productIdFilter = null) {
        $storeId = $this->_getStoreId();
        $websiteId = Mage::app()->getStore($storeId)->getWebsiteId();
        $custGroup = $this->_getCustomerGroupId();
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time(), false);
        $rulePriceWhere = "({{table}}.rule_date is null) or " . "({{table}}.rule_date='$todayDate' and " . "{{table}}.website_id='$websiteId' and " . "{{table}}.customer_group_id='$custGroup')";
        $specials = $product->setStoreId($storeId)->getResourceCollection()->addAttributeToFilter('special_price', array('gt' => 0), 'left')->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate), 'left')->addAttributeToFilter(array(array('attribute' => 'special_to_date', 'date' => true, 'from' => $todayDate), array('attribute' => 'special_to_date', 'is' => new Zend_Db_Expr('null'))), '', 'left')->addAttributeToSort('special_from_date', 'desc')->joinTable('catalogrule/rule_product_price', 'product_id=entity_id', array('rule_price' => 'rule_price', 'rule_start_date' => 'latest_start_date', 'rule_date' => 'rule_date', 'rule_end_date' => 'earliest_end_date'), $rulePriceWhere, 'left');
        if ($productIdFilter) {
            $specials = $specials->addAttributeToFilter('entity_id', $productIdFilter);
        }
        $rulePriceCollection = Mage::getResourceModel('catalogrule/rule_product_price_collection')->addFieldToFilter('website_id', $websiteId)->addFieldToFilter('customer_group_id', $custGroup)->addFieldToFilter('rule_date', $todayDate);
        $productIds = $rulePriceCollection->getProductIds();
        if (!empty($productIds)) {
            $specials->getSelect()->orWhere('e.entity_id in (' . implode(',', $productIds) . ')');
        }
        $this->prepareProductCollection($specials);
        if ($productIdFilter) {
          foreach ($specials as $special) {
            if($special->getData('entity_id') == $productIdFilter) {
              return $special;
            }
          }
          return null;
        }
        return $specials;
    }

    public function getTimestampUtil($product)
    {
        if (is_object($product)) {
            $product = $product->getId();
        }
        $product = $this->getSale($product);
        if(!$product || !$product->getId()) {
          return null;
        }
        $dateTimeCurrent = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $dateTimeStart = $product->getData('special_from_date');
        $dateTimeEnd = $product->getData('special_to_date');
        $dataTimeEndRule = $product->getData('rule_end_date');
        if (!$dateTimeEnd && !$dataTimeEndRule) {
            return null;
        }
        $timestampCurrent = strtotime($dateTimeCurrent);
        if($dataTimeEndRule) {
          $timestampEnd = strtotime($dataTimeEndRule);
        }
        else {
          $timestampEnd = strtotime($dateTimeEnd);
          $timestampStart = strtotime($dateTimeStart);
          if ($timestampCurrent <= $timestampStart || $timestampCurrent >= $timestampEnd) {
              return null;
          }
        }
        $timestampUtil = $timestampEnd - $timestampCurrent;
        return $timestampUtil;
    }

    public function prepareProductCollection($collection) {
        $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
        ->addMinimalPrice()->addFinalPrice()->addTaxPercents();

        //->addUrlRewrite($this->getCurrentCategory()->getId());
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $this;
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

	public function getOptionsConfigurable()
	{
		$productAttributeOptions = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
		$attributeOptions = array();
		foreach ($productAttributeOptions as $productAttribute) {
			if($productAttribute['attribute_code'] == 'size') {
				foreach ($productAttribute['values'] as $attribute) {
					$attributeOptions[] = $attribute['store_label'];
				}
			}
		}
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

    public function getPercentSale($product)
    {
        $price = $product->getPrice();
        $finalPrice = $product->getFinalPrice();
        if(!$finalPrice || ($price == $finalPrice)) {
            return null;
        }
        return (int)(100 * ($price-$finalPrice) / $price);
    }

    public function getProductCategory($category) {
      $store = Mage::app()->getStore()->getId();
      $category = Mage::getModel('catalog/category')->load(10);
      /* @var Mage_Catalog_Model_Resource_Product_Collection */
      $productCollection = Mage::getResourceModel('catalog/product_collection')
          ->setStoreId($store)
          ->addCategoryFilter($category)
      ;
    }

    public function getCategoryChildren($categoryId) {
      $_categoryCollection = Mage::getModel('catalog/category')->getCollection()
        ->addAttributeToSelect(array('name','url_key'))
        ->addAttributeToFilter('is_active',1)
        ->addAttributeToFilter('parent_id',$categoryId)
    	   ->addAttributeToSort('position');
      return $_categoryCollection;
    }
}
