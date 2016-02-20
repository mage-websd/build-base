<?php

class Gsd_Sellerg_Model_Source_Config_Set
{
    protected $_options = array();

    public function toOptionArray()
    {
        if (count($this->_options)) {
            return $this->_options;
        }
        $collection = Mage::getModel('eav/entity_attribute_set')->getCollection()
            ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() );
        $this->_options[] = array('value'=>'','label'=>'');
        foreach ($collection as $_item) {
            $this->_options[] = array('value' => $_item->getAttributeSetId(), 'label' => $_item->getAttributeSetName());
        }
        return $this->_options;
    }
}