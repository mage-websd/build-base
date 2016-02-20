<?php
class Gsd_Sellerg_Model_Source_Config_Setallow
{
    public function getAllOptions()
    {
        $options = array(array('value'=>'','label'=>''));
        $allowedIds = Mage::getStoreConfig('sellerg/product/allowed_attribute_set');
        if(!$allowedIds) {
            return $options;
        }
        $options = array();
        $allowedIds = explode(',', $allowedIds);
        $collection = Mage::getModel('eav/entity_attribute_set')->getCollection()
            ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId() );
        foreach ($collection as $_item) {
            if(in_array($_item->getAttributeSetId(), $allowedIds)) {
                $options[] = array('value' => $_item->getAttributeSetId(), 'label' => $_item->getAttributeSetName());
            }
        }
        return $options;
    }
}