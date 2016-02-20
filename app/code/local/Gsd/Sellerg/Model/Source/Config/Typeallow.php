<?php
class Gsd_Sellerg_Model_Source_Config_Typeallow
{
    public function getAllOptions()
    {
        $allowedIds = Mage::getStoreConfig('sellerg/product/allowed_types');
        if(!$allowedIds) {
            return array(array('value'=>'','label'=>''));
        }
        $allowedIds = explode(',', $allowedIds);
        $types = Mage_Catalog_Model_Product_Type::getOptions();
        foreach ($types as $key => $type) {
            if(!in_array($type['value'],$allowedIds)) {
                unset($types[$key]);
            }
        }
        return $types;
    }
}