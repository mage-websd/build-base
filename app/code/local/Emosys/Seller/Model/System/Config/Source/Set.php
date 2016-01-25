<?php
/**
 * @category   Emosys
 * @package    Emosys_Seller
 * @copyright  Copyright (c) 2015 Emosys Ltd (http://www.emosys.net)
 */
class Emosys_Seller_Model_System_Config_Source_Set
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
        $collection = Mage::getModel('eav/entity_attribute_set')->getCollection()
            ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() );
        foreach ($collection as $_item) {
            $this->_options[] = array('value' => $_item->getAttributeSetId(), 'label' => $_item->getAttributeSetName());
        }
        return $this->_options;
    }
}