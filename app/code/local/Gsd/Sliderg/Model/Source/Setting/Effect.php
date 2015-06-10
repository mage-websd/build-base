<?php
class Gsd_Sliderg_Model_Source_Setting_Effect
{
	public function toOptionArray()
    {
        return array(
        	array('value' => '', 'label' => Mage::helper('sliderg')->__('----')),
            array('value' => 'cube', 'label' => Mage::helper('sliderg')->__('Cube')),
            array('value' => 'coverflow', 'label' => Mage::helper('sliderg')->__('Coverflow')),
        );
    }
}