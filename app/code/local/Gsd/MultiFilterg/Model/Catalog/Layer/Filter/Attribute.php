<?php
class Gsd_MultiFilterg_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    /**
     * Apply attribute option filter to product collection
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Varien_Object $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::apply($request, $filterBlock);
        }
        $collection = Mage::getSingleton('catalog/layer')->getProductCollection();
        if(Mage::helper('core')->isModuleEnabled('Gsd_PriceSlideg') &&
            Mage::helper('priceslideg')->isEnable()) {
            if(!Mage::registry('flag_filter_price')) {
                $pHelper = Mage::helper('priceslideg');
                $currentRate = Mage::app()->getStore()->getCurrentCurrencyRate();
                $price = Mage::app()->getRequest()->getParam($pHelper->getPriceParamCode());
                $price = $pHelper->getPrice($price);
                $max = round($price[1]  / $currentRate);
                $min = round($price[0] / $currentRate);
                if($min && $max){
                    $collection->getSelect()->where(' final_price >= "'.$min.'" AND final_price <= "'.$max.'" ');
                }
                Mage::register('flag_filter_price',1);
            }
        }
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            $text = array();
            foreach ($filter as $f) {
                array_push($text, $this->_getOptionText($f));
            }
            if ($filter && $text) {
                $this->_getResource()->applyFilterToCollection($this, $filter);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
                //$this->_items = array();
            }
            return $this;
        }
        $text = $this->_getOptionText($filter);
        if ($filter && strlen($text)) {
            $this->_getResource()->applyFilterToCollection($this, $filter);
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            //$this->_items = array();
        }
        return $this;
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::_getItemsData();
        }
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $key = $this->getLayer()->getStateKey().'_'.$this->_requestVar;
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $options = $attribute->getFrontend()->getSelectOptions();
            $optionsCount = $this->_getResource()->getCount($this);
            $data = array();
            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])) {
                    // Check filter type
                    if ($this->_getIsFilterableAttribute($attribute) == self::OPTIONS_ONLY_WITH_RESULTS && (!empty($optionsCount[$option['value']])) ) {
                        if (!empty($optionsCount[$option['value']])) {
                            $data[] = array(
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'count' => $optionsCount[$option['value']],
                            );
                        }
                    }
                    else {
                        $data[] = array(
                            'label' => $option['label'],
                            'value' => $option['value'],
                            'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                        );
                    }
                }
            }

            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG.':'.$attribute->getId()
            );

            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
}
