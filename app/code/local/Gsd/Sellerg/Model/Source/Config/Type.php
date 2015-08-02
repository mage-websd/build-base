<?php

class Gsd_Sellerg_Model_Source_Config_Type
{
    protected $_options = array();

    public function toOptionArray()
    {
        $this->_options = Mage_Catalog_Model_Product_Type::getOptions();
        return $this->_options;
    }
}