<?php

/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 03/08/2015
 * Time: 21:14
 */
class Gsd_Catalogg_Helper_Product extends Mage_Core_Helper_Abstract
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

  public function getWishlistIds() {
    $itemCollection = Mage::helper('wishlist')->getWishlistItemCollection();
    $ids = array();
    foreach ($itemCollection as $item) {
        $product = $item->getProduct();
        $ids[] = $product->getId();
    }
    return $ids;
}

public function getQty() {
    //file catalog/product/view/type/options/configurable.phtml
  ?>
  <script type="text/javascript">
      var simplePrice = [];
      <?php
      if ($_product->getTypeId() == 'configurable') {
          $_store = Mage::app()->getStore()->getStoreId();
          $_products = $_product->getTypeInstance(true)->getUsedProducts(null, $_product);
          foreach ($_products as $_simple) {
              $_simple = Mage::getModel('catalog/product')->setStoreId($_store)->load($_simple->getId());
              ?>
              simplePrice[<?php echo $_simple->getId(); ?>] = '<?php echo $_coreHelper->currency($_simple->getFinalPrice()); ?>'
           <?php }
      }
      ?>
      var spConfig = new Product.Config(<?php echo $this->getJsonConfig() ?>);
      spConfig.getIdOfSelectedProduct = function () {
          var existingProducts = new Object();
          for (var i = this.settings.length - 1; i >= 0; i--) {
              var selected = this.settings[i].options[this.settings[i].selectedIndex];
              if (selected.config) {
                  for (var iproducts = 0; iproducts < selected.config.products.length; iproducts++) {
                      var usedAsKey = selected.config.products[iproducts] + "";
                      if (existingProducts[usedAsKey] == undefined) {
                          existingProducts[usedAsKey] = 1;
                      } else {
                          existingProducts[usedAsKey] = existingProducts[usedAsKey] + 1;
                      }
                  }
              }
          }
          for (var keyValue in existingProducts) {
              for (var keyValueInner in existingProducts) {
                  if (Number(existingProducts[keyValueInner]) < Number(existingProducts[keyValue])) {
                      delete existingProducts[keyValueInner];
                  }
              }
          }
          var sizeOfExistingProducts = 0;
          var currentSimpleProductId = "";
          for (var keyValue in existingProducts) {
              currentSimpleProductId = keyValue;
              sizeOfExistingProducts = sizeOfExistingProducts + 1
          }
          if (sizeOfExistingProducts == 1) {
              setTimeout(function(event) {
                  jQuery('.price-box-wrapper .old-price .price-value').html(simplePrice[currentSimpleProductId]);
              },200);
          }
          else {
              jQuery('.price-box-wrapper .old-price .price-value').html('');
          }
      }
  </script>
  <?php
}
  public function getPriceRange($productId) {
    $max = '';
    $min = '';

    $pricesByAttributeValues = array();

    $product = Mage::getModel('catalog/product')->load($productId);
    $attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
    $basePrice = $product->getFinalPrice();

    foreach ($attributes as $attribute){
      $prices = $attribute->getPrices();
      foreach ($prices as $price){
          if ($price['is_percent']){ //if the price is specified in percents
              $pricesByAttributeValues[$price['value_index']] = (float)$price['pricing_value'] * $basePrice / 100;
          }
          else { //if the price is absolute value
              $pricesByAttributeValues[$price['value_index']] = (float)$price['pricing_value'];
          }
      }
    }


    $simple = $product->getTypeInstance()->getUsedProducts();

    foreach ($simple as $sProduct){
      $totalPrice = $basePrice;

      foreach ($attributes as $attribute){

          $value = $sProduct->getData($attribute->getProductAttribute()->getAttributeCode());
          if (isset($pricesByAttributeValues[$value])){
              $totalPrice += $pricesByAttributeValues[$value];
          }
      }
      if(!$max || $totalPrice > $max)
          $max = $totalPrice;
      if(!$min || $totalPrice < $min)
          $min = $totalPrice;
    }

    return "$min - $max";

    }
}
