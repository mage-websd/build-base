<?php
class Gsd_Sliderg_Model_Source_Setting_Fill
{
	public function toOptionArray()
    {
        return array(
        	array('value' => '', 'label' => Mage::helper('sliderg')->__('----')),
            array('value' => 'column', 'label' => Mage::helper('sliderg')->__('Column')),
            array('value' => 'row', 'label' => Mage::helper('sliderg')->__('Row')),
        );
    }
}