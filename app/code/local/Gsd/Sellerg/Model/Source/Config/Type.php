<?php

class Gsd_Sellerg_Model_Source_Config_Type
{
    protected $_options = array();

    public function toOptionArray()
    {
        $this->_options[] = array('value'=>'','label'=>'');
        $this->_options = array_merge($this->_options,Mage_Catalog_Model_Product_Type::getOptions());
        return $this->_options;
    }
}