<?php
class Gsd_MultiFilterg_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::getUrl();
        }
        $query = array(
            $this->getFilter()->getRequestVar().'[]'=>$this->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        if(!Mage::helper('multifilterg')->isEnable()) {
            return parent::getRemoveUrl();
        }
        $paramsQuery = Mage::getSingleton('core/app')->getRequest()->getParams();
        $requestVar = $this->getFilter()->getRequestVar();
        if(isset($paramsQuery[$requestVar])) {
            $selected = $paramsQuery[$requestVar];
            if(in_array($this->getValue(),$selected)) {
                $keySelected = array_search($this->getValue(),$selected);
                unset($paramsQuery[$requestVar][$keySelected]);
            }
        }
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $paramsQuery;
        $params['_escape']      = true;
        return Mage::getUrl('*/*/*', $params);
    }

    public function getRemoveGroupUrl()
    {
        $query = array(
            $this->getFilter()->getRequestVar()=>$this->getFilter()->getValue(),
        );
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        return Mage::getUrl('*/*/*', $params);
    }

    public function isSelected()
    {
        $selected = Mage::getSingleton('core/app')->getRequest()
            ->getParam($this->getFilter()->getRequestVar());
        if(in_array($this->getValue(),$selected)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isFilterSelected($attributeCode)
    {
        if(!$attributeCode) {
            $attributeCode = $this->getFilter()->getRequestVar();
        }
        $params = Mage::getSingleton('core/app')->getRequest()->getParams();
        if(array_key_exists($attributeCode,$params)) {
            return true;
        }
        return false;
    }
}