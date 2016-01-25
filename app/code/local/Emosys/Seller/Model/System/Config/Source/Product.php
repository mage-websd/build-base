<?php
/**
 * @category   Emosys
 * @package    Emosys_Seller
 * @copyright  Copyright (c) 2015 Emosys Ltd (http://www.emosys.net)
 */
class Emosys_Seller_Model_System_Config_Source_Product
{
    protected $_options = array();
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (count($this->_options)) {
            return $this->_options;
        }
        $this->_options = Mage_Catalog_Model_Product_Type::getOptions();
        return $this->_options;
    }
}