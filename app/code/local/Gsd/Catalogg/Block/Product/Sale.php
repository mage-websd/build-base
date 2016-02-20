<?php
class Gsd_Catalogg_Block_Product_Sale extends Gsd_Catalogg_Block_Product_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTitle('Sale Product');
        $this->_flagBlock = 'CATALOGg_PRODUCT_SALE';
        return $this;
    }
    public function getSale() {
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time(), false);
        $rulePriceWhere = "({{table}}.rule_date is null) or " . "({{table}}.rule_date='$todayDate' and " . "{{table}}.website_id='$websiteId' and " . "{{table}}.customer_group_id='$custGroup')";
        $collection = $product->setStoreId($this->_store)
            ->getResourceCollection()
            ->addAttributeToFilter('special_price', array('gt' => 0), 'left')
            ->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate), 'left')
            ->addAttributeToFilter(array(
                array('attribute' => 'special_to_date', 'date' => true, 'from' => $todayDate),
                array('attribute' => 'special_to_date', 'is' => new Zend_Db_Expr('null'))
                ), '', 'left')
            ->addAttributeToSort('special_from_date', 'desc')
            ->joinTable('catalogrule/rule_product_price',
                'product_id=entity_id',
                array('rule_price' => 'rule_price', 'rule_start_date' => 'latest_start_date', 'rule_date' => 'rule_date', 'rule_end_date' => 'earliest_end_date'),
                $rulePriceWhere, 'left');
        $rulePriceCollection = Mage::getResourceModel('catalogrule/rule_product_price_collection')
            ->addFieldToFilter('website_id', $this->_website)
            ->addFieldToFilter('customer_group_id', $this->_getCustomerGroupId())
            ->addFieldToFilter('rule_date', $todayDate);
        $productIds = $rulePriceCollection->getProductIds();
        if (!empty($productIds)) {
            $collection->getSelect()->orWhere('e.entity_id in (' . implode(',', $productIds) . ')');
        }
        if($this->getLimit()) {
            $collection->getSelect()->limit($this->getLimit());
        }
        $collection = $this->prepareCollection($collection);
        return $collection;
    }

    public function getCollection()
    {
        if ($this->_collection) {
            return $this->_collection;
        }
        $this->_collection = $this->getSale();
        return $this->_collection;
    }

    public function getTimestampUtil($product)
    {
        $dateTimeCurrent = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $dateTimeStart = $product->getData('special_from_date');
        $dateTimeEnd = $product->getData('special_to_date');
        $dataTimeEndRule = $product->getData('rule_end_date');
        if (!$dateTimeEnd && !$dataTimeEndRule) {
            return null;
        }
        $timestampCurrent = strtotime($dateTimeCurrent);
        if ($dataTimeEndRule) {
            $timestampEnd = strtotime($dataTimeEndRule);
        } else {
            $timestampEnd = strtotime($dateTimeEnd);
            $timestampStart = strtotime($dateTimeStart);
            if ($timestampCurrent <= $timestampStart || $timestampCurrent >= $timestampEnd) {
                return null;
            }
        }
        $timestampUtil = $timestampEnd - $timestampCurrent;
        return $timestampUtil;
    }
}