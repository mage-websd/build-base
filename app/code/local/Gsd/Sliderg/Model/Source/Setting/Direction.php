<?php
class Gsd_Sliderg_Model_Source_Setting_direction
{
	public function toOptionArray()
    {
        return array(
        	array('value' => '', 'label' => Mage::helper('sliderg')->__('----')),
        	array('value' => 'horizontal', 'label' => Mage::helper('sliderg')->__('Horizontal')),
            array('value' => 'vertical', 'label' => Mage::helper('sliderg')->__('Vertical')),
        );
    }
}