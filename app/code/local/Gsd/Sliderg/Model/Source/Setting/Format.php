<?php
class Gsd_Sliderg_Model_Source_Setting_Format
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'slider', 'label' => Mage::helper('sliderg')->__('Slider')),
            array('value' => 'static', 'label' => Mage::helper('sliderg')->__('Static')),
        );
    }
}