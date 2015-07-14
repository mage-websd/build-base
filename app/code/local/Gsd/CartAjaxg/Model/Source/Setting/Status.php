<?php

class Gsd_CartAjaxg_Model_Source_Setting_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('addajaxg')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('addajaxg')->__('Disabled')
        );
    }
}