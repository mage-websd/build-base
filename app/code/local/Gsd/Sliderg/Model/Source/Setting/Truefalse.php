<?php
class Gsd_Sliderg_Model_Source_Setting_Truefalse
{
	public function toOptionArray()
    {
        return array(
        	array('value' => '', 'label' => Mage::helper('sliderg')->__('----')),
            array('value' => 'true', 'label' => Mage::helper('sliderg')->__('True')),
            array('value' => 'false', 'label' => Mage::helper('sliderg')->__('False')),
        );
    }
}