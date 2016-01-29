<?php
class Emosys_Seller_Model_Catalog_Resource_Layer_Filter_Price
	extends Mage_Catalog_Model_Resource_Layer_Filter_Price
	{

    /**
     * Retrieve clean select with joined price index table
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

        if (!is_null($collection->getCatalogPreparedSelect())) {
            $select = clone $collection->getCatalogPreparedSelect();
        } else {
            $select = clone $collection->getSelect();
        }

        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        // remove join with main table
        $fromPart = $select->getPart(Zend_Db_Select::FROM);
        if (!isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS])
            || !isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS])
        ) {
            return $select;
        }

        // processing FROM part
        $priceIndexJoinPart = $fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS];
        $priceIndexJoinConditions = explode('AND', $priceIndexJoinPart['joinCondition']);
        $priceIndexJoinPart['joinType'] = Zend_Db_Select::FROM;
        $priceIndexJoinPart['joinCondition'] = null;
        $fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS] = $priceIndexJoinPart;
        unset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS]);
        $select->setPart(Zend_Db_Select::FROM, $fromPart);
        foreach ($fromPart as $key => $fromJoinItem) {
            $fromPart[$key]['joinCondition'] = $this->_replaceTableAlias($fromJoinItem['joinCondition']);
        }

        $select->setPart(Zend_Db_Select::FROM, $fromPart);

        // processing WHERE part
        $wherePart = $select->getPart(Zend_Db_Select::WHERE);
        $excludedWherePart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.status';
        foreach ($wherePart as $key => $wherePartItem) {
            if (strpos($wherePartItem, $excludedWherePart) !== false) {
                $wherePart[$key] = new Zend_Db_Expr('1=1');
                continue;
            }
            $wherePart[$key] = $this->_replaceTableAlias($wherePartItem);
            if(strpos($wherePartItem,'attribute_set_id')) {
        		$select->join('catalog_product_entity','e.entity_id=catalog_product_entity.entity_id','catalog_product_entity.attribute_set_id');
        		$whereReplace = str_replace("(`e`.`attribute_set_id`", "(`catalog_product_entity`.`attribute_set_id`", $wherePart[$key]);
        		$wherePart[$key] = $whereReplace;
        	}
        }
        $select->setPart(Zend_Db_Select::WHERE, $wherePart);

        $excludeJoinPart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.entity_id';
        foreach ($priceIndexJoinConditions as $condition) {
            if (strpos($condition, $excludeJoinPart) !== false) {
                continue;
            }
            $select->where($this->_replaceTableAlias($condition));
        }
        $select->where($this->_getPriceExpression($filter, $select) . ' IS NOT NULL');
        /*$_whereCurrents = $select->getPart(Zend_Db_Select::WHERE);
        $_flagWhere = false;
        foreach($_whereCurrents as $_whereCurrent) {
        	if(strpos($_whereCurrent,'attribute_set_id')) {
        		$select->join('catalog_product_entity','e.entity_id=catalog_product_entity.entity_id');
        		$_flagSet = "(`catalog_product_entity`.`attribute_set_id` = '9')";
        		break;
        	}
        }*/
        return $select;
    }
}