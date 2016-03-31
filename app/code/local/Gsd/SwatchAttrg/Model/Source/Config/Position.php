<?php

class Gsd_SwatchAttrg_Model_Source_Config_Position {

    public function toOptionArray() {
        return array(
            array('value' => 'right', 'label' => Mage::helper('swatchattrg')->__('Right')),
            array('value' => 'left', 'label' => Mage::helper('swatchattrg')->__('Left')),
            array('value' => 'top', 'label' => Mage::helper('swatchattrg')->__('Top')),
            array('value' => 'bottom', 'label' => Mage::helper('swatchattrg')->__('Bottom')),
            array('value' => 'inside', 'label' => Mage::helper('swatchattrg')->__('Inside'))
        );
    }

}
