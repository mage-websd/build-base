<?php
class Gsd_Sliderg_Model_Source_Setting_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'swiper', 'label' => Mage::helper('sliderg')->__('Swiper')),
            array('value' => 'slicebox', 'label' => Mage::helper('sliderg')->__('Slice box')),
        );
    }
}