<?php
class Gsd_MultiFilterg_Model_Source_Config_Typeshow {
    const SHOW_DYNAMIC = 1;
    const SHOW_ORIGIN = 2;

    public function toOptionArray()
    {
        return array(
            array('value' => self::SHOW_DYNAMIC, 'label'=>Mage::helper('adminhtml')->__('Show Filter Dynamic')),
            array('value' => self::SHOW_ORIGIN, 'label'=>Mage::helper('adminhtml')->__('Show Filter Origin')),
        );
    }
}