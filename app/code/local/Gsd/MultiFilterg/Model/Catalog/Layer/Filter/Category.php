<?php
class Gsd_MultiFilterg_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
    /**
     * Apply category filter to layer
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::apply($request, $filterBlock);
        }
        if(Mage::app()->getRequest()->getModuleName() == 'catalogsearch'){
            return parent::apply($request, $filterBlock);
        }
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }
        $this->_categoryId = $filter;
        Mage::register('current_category_filter', $this->getCategory(), true);
        if(!count($filter)) {
            return $this;
        }
        $categoriesIdApplied = array();
        $categoriesNameApplied = array();
        foreach($filter as $categoryId) {
            $filterCategory = Mage::getModel('catalog/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);
            if ($this->_isValidCategory($filterCategory)) {
                $categoriesIdApplied[] = $categoryId;
                $categoriesNameApplied[] = $filterCategory->getName();
                $this->_appliedCategory[] = $filterCategory;
            }
        }
        $this->getLayer()->getState()->addFilter(
            $this->_createItem($categoriesNameApplied, $categoriesIdApplied)
        );
        if($categoriesIdApplied) {
            if(Mage::app()->getRequest()->getModuleName() == 'catalogsearch'){
                $collection = $this->getLayer()->getProductCollection()
                    ->joinField('category_id', 'catalog/category_product', 'category_id',
                        'product_id = entity_id', null, 'left');
                    $collection
                    ->addAttributeToFilter('category_id', array('in' => $categoriesIdApplied))
                    ;
                $select = $collection->getSelect();
                $formJoin = $select->getPart(ZEND_Db_Select::FROM);
                $select->reset(ZEND_Db_Select::FROM);
                foreach ($formJoin as $key_tableAlias => $dataJoinFrom) {
                    if ($dataJoinFrom['tableName'] == 'catalog_category_product_index') {
                        $conditionJoin = $dataJoinFrom['joinCondition'];
                        $conditionJoin = explode('AND',$conditionJoin);
                        foreach($conditionJoin as $keyCond => $cond) {
                            if(stripos($cond,'category_id')) {
                                unset($conditionJoin[$keyCond]);
                            }
                        }
                        $conditionJoin = implode(' AND ',$conditionJoin);
                        $select->join(
                            array($key_tableAlias => $dataJoinFrom['tableName']),
                            $conditionJoin
                        );
                    }
                    elseif ($dataJoinFrom['joinType'] == 'from') {
                        $select->from(array($key_tableAlias => $dataJoinFrom['tableName']));
                    } else {
                        $select->join(
                            array($key_tableAlias => $dataJoinFrom['tableName']),
                            $dataJoinFrom['joinCondition']
                        );
                    }
                }
            }
            else {
                $this->getLayer()->getProductCollection()
                    ->joinField('category_id', 'catalog/category_product', 'category_id',
                        'product_id = entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => $categoriesIdApplied));
            }
        }
        else {
            $this->getLayer()->getProductCollection()
                ->addCategoryFilter($this->getCategory());
        }
        return $this;
    }

    /**
     * Get selected category object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        if(Mage::app()->getRequest()->getModuleName() == 'catalogsearch'){
            return parent::getCategory();
        }
        return $this->getLayer()->getCurrentCategory();
    }

    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        if(Mage::app()->getRequest()->getModuleName() == 'catalogsearch'){
            return parent::_getItemsData();
        }
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $category   = $this->getCategory();
            /** @var $category Mage_Catalog_Model_Category */
            $categories = $category->getChildrenCategories();
            $customCollection = $this->_getCollectionCustom();
            $customCollection
                ->addCountToCategories($categories);

            $data = array();
            foreach ($categories as $category) {
                if ($category->getIsActive() && $category->getProductCount()) {
                    $data[] = array(
                        'label' => Mage::helper('core')->escapeHtml($category->getName()),
                        'value' => $category->getId(),
                        'count' => $category->getProductCount(),
                    );
                }
            }
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }

    protected function _getCollectionCustom()
    {
        $productCollection = clone $this->getLayer()->getProductCollection()->getSelect();
        $customCollection = new Gsd_MultiFilterg_Model_Resource_Catalog_Product_Collection();
        $customCollection->loadSelect($productCollection);
        return $customCollection;
    }
}