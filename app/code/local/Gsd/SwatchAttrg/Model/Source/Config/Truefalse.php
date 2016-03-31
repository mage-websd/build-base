<?php

class Gsd_SwatchAttrg_Model_Source_Config_Truefalse {

    public function toOptionArray() {
        return array(
            array('value' => 'true', 'label' => Mage::helper('swatchattrg')->__('True')),
            array('value' => 'false', 'label' => Mage::helper('swatchattrg')->__('False'))
        );
    }

}
