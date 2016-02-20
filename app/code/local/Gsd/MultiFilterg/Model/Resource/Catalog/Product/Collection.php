<?php

class Gsd_MultiFilterg_Model_Resource_Catalog_Product_Collection
    extends
        Mage_Catalog_Model_Resource_Product_Collection
        //Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('catalog/product');
        //$this->setMainTable($this->getTable('catalog/product'));
    }

    public function loadSelect($select)
    {
        $joinAndFromExists = $select->getPart(Zend_Db_Select::FROM);
        $this->getSelect()->reset(Zend_Db_Select::FROM);
        //if(Mage::helper('multifilterg')->getTypeShow() == Gsd_MultiFilterg_Model_Source_Config_Typeshow::SHOW_ORIGIN) {
            //$select->reset(Zend_Db_Select::FROM);
            foreach ($joinAndFromExists as $key_tableAlias => $dataJoinFrom) {
                if ($dataJoinFrom['tableName'] == 'catalog_product_index_eav') {
                    continue;
                } elseif ($dataJoinFrom['joinType'] == 'from') {
                    $this->getSelect()->from(array($key_tableAlias => $dataJoinFrom['tableName']));
                }
                elseif($dataJoinFrom['tableName'] == 'catalog_category_product_index') {
                    $condition = $dataJoinFrom['joinCondition'];
                    $conditionArray = explode('AND',$condition);
                    $condition = '';
                    foreach ($conditionArray as $cond) {
                        if(stripos($cond,'category_id')) {
                            continue;
                        }
                        $condition .= trim($cond) . ' AND ';
                    }
                    if($condition) {
                        $condition = substr($condition,0,-5);
                    }
                    $this->getSelect()->join(
                        array($key_tableAlias => $dataJoinFrom['tableName']),
                        $condition
                    );
                }
                else {
                    $this->getSelect()->join(
                        array($key_tableAlias => $dataJoinFrom['tableName']),
                        $dataJoinFrom['joinCondition']
                    );
                }
            }
        //}
        $this->getSelect()->reset(Zend_Db_Select::COLUMNS);
        $arrayColumns = array();
        $dataReset = $select->getPart(Zend_Db_Select::COLUMNS);
        foreach($dataReset as $columns) {
            if($columns[2]) {
                $arrayColumns[$columns[2]] = "{$columns[0]}.{$columns[1]}";
            }
            else {
                $arrayColumns[] = "{$columns[0]}.{$columns[1]}";
            }
        }
        $this->getSelect()->columns($arrayColumns);

        /*$this->getSelect()->reset(Zend_Db_Select::GROUP);
        $this->getSelect()->group($select->getPart(Zend_Db_Select::GROUP));

        $this->getSelect()->reset(Zend_Db_Select::WHERE);
        $this->getSelect()->where($select->getPart(Zend_Db_Select::WHERE));

        $this->getSelect()->reset(Zend_Db_Select::HAVING);
        $this->getSelect()->having($select->getPart(Zend_Db_Select::HAVING));

        $this->getSelect()->reset(Zend_Db_Select::ORDER);
        $this->getSelect()->order($select->getPart(Zend_Db_Select::ORDER));

        $this->getSelect()->reset(Zend_Db_Select::DISTINCT);
        $this->getSelect()->distinct($select->getPart(Zend_Db_Select::DISTINCT));

        $this->getSelect()->reset(Zend_Db_Select::LIMIT_COUNT);
        $this->getSelect()->reset(Zend_Db_Select::LIMIT_OFFSET);
        $this->getSelect()->limit($select->getPart(Zend_Db_Select::LIMIT_COUNT),$select->getPart(Zend_Db_Select::LIMIT_OFFSET));*/

        //$this->getSelect()->query($select->__toString());
        return $this;
    }

    public function resetCategory($select,$where=null)
    {
        $joinAndFromExists = $select->getPart(Zend_Db_Select::FROM);
        $this->getSelect()->reset(Zend_Db_Select::FROM);
        //if(Mage::helper('multifilterg')->getTypeShow() == Gsd_MultiFilterg_Model_Source_Config_Typeshow::SHOW_ORIGIN) {
        //$select->reset(Zend_Db_Select::FROM);
        foreach ($joinAndFromExists as $key_tableAlias => $dataJoinFrom) {
            if ($dataJoinFrom['joinType'] == 'from') {
                $this->getSelect()->from(array($key_tableAlias => $dataJoinFrom['tableName']));
            }
            elseif($dataJoinFrom['tableName'] == 'catalog_category_product_index') {
                $condition = $dataJoinFrom['joinCondition'];
                $conditionArray = explode('AND',$condition);
                $condition = '';
                foreach ($conditionArray as $cond) {
                    if(stripos($cond,'category_id')) {
                        continue;
                    }
                    $condition .= trim($cond) . ' AND ';
                }
                if($condition) {
                    $condition = substr($condition,0,-5);
                }
                if($where && count($where)) {
                    $condition .= ' AND cat_index.category_id IN (';
                    $condition .= implode(",",$where);
                    $condition .= ')';
                }
                $this->getSelect()->join(
                    array($key_tableAlias => $dataJoinFrom['tableName']),
                    $condition
                );
            }
            else {
                $this->getSelect()->join(
                    array($key_tableAlias => $dataJoinFrom['tableName']),
                    $dataJoinFrom['joinCondition']
                );
            }
        }
        //}
        $this->getSelect()->reset(Zend_Db_Select::COLUMNS);
        $arrayColumns = array();
        $dataReset = $select->getPart(Zend_Db_Select::COLUMNS);
        foreach($dataReset as $columns) {
            if($columns[2]) {
                $arrayColumns[$columns[2]] = "{$columns[0]}.{$columns[1]}";
            }
            else {
                $arrayColumns[] = "{$columns[0]}.{$columns[1]}";
            }
        }
        $this->getSelect()->columns($arrayColumns);
        return $this;
    }

    public function whereCustom($where)
    {
        $this->getSelect()->where($where);
        return $this;
    }
}