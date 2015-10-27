<?php
class Gsd_MultiFilterg_Model_Resource_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Resource_Layer_Filter_Attribute
{
    public function applyFilterToCollection($filter, $value)
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::applyFilterToCollection($filter, $value);
        }
        $collection = $filter->getLayer()->getProductCollection();
        $attribute  = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx'.$value;
        if (!is_array($value)) {
            $conditions = array(
                "{$tableAlias}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
                $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
                $connection->quoteInto("{$tableAlias}.value = ?", $value)
            );
            $collection->getSelect()->join(
                array($tableAlias => $this->getMainTable()),
                implode(' AND ', $conditions),
                array()
            );
        }
        else {
            $conditions = $connection->quoteInto("{$tableAlias}.value IN (?)", $value);
            $conditionsOne = array(
                "{$tableAlias}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
                $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
                $conditions,
            );
            $conditions = implode(' AND ', $conditionsOne);
            $collection->getSelect()->join(
                array($tableAlias => $this->getMainTable()),
                $conditions,
                array()
            )
                ->group('e.entity_id');
        }
        return $this;
    }

    public function getCount($filter)
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::getCount($filter);
        }
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        $joinAndFromExists = $select->getPart(Zend_Db_Select::FROM);
        $select->reset(Zend_Db_Select::FROM);
        if(Mage::helper('multifilterg')->getTypeShow() == Gsd_MultiFilterg_Model_Source_Config_Typeshow::SHOW_ORIGIN) {
            foreach ($joinAndFromExists as $key_tableAlias => $dataJoinFrom) {
                if ($dataJoinFrom['tableName'] == 'catalog_product_index_eav') {
                    continue;
                }
                /*elseif ($dataJoinFrom['tableName'] == 'catalog_category_product') {
                    continue;
                }*/
                elseif ($dataJoinFrom['joinType'] == 'from') {
                    $select->from(array($key_tableAlias => $dataJoinFrom['tableName']));
                } else {
                    $select->join(
                        array($key_tableAlias => $dataJoinFrom['tableName']),
                        $dataJoinFrom['joinCondition']
                    );
                }
            }
            $whereSelect = $select->getPart(ZEND_Db_Select::WHERE);
            $select->reset(Zend_Db_Select::WHERE);
            foreach($whereSelect as $whereSelectElement) {
                if(stripos($whereSelectElement,'category_id')) {
                    continue;
                }
                else {
                    $select->where($whereSelectElement);
                }
            }
        }
        else {
            $attributeCode = $filter->getAttributeModel()->getAttributeCode();
            foreach ($joinAndFromExists as $key_tableAlias => $dataJoinFrom) {
                if ($dataJoinFrom['tableName'] == 'catalog_product_index_eav') {
                    $itemFilter = Mage::getSingleton('multifilterg/catalog_layer_filter_item');
                    if($itemFilter->isFilterSelected($attributeCode)) {
                        continue;
                    }
                    else {
                        $select->join(
                            array($key_tableAlias => $dataJoinFrom['tableName']),
                            $dataJoinFrom['joinCondition']
                        );
                    }
                } elseif ($dataJoinFrom['joinType'] == 'from') {
                    $select->from(array($key_tableAlias => $dataJoinFrom['tableName']));
                } else {
                    $select->join(
                        array($key_tableAlias => $dataJoinFrom['tableName']),
                        $dataJoinFrom['joinCondition']
                    );
                }
            }
        }
        $groupExists = $select->getPart(ZEND_Db_Select::GROUP);
        $select->reset(ZEND_Db_Select::GROUP);
        foreach ($groupExists as $group) {
            if ($group == 'e.entity_id') {
                continue;
            } else {
                $select->group($group);
            }
        }

        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute  = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );

        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array('value', 'count' => new Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")))
            ->group("{$tableAlias}.value");

        return $connection->fetchPairs($select);
    }
}