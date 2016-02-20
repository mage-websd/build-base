<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Model_System_Config_Source_Display
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'image_only', 'label'=>Mage::helper('adminhtml')->__('Image Only')),
            array('value' => 'html_only', 'label'=>Mage::helper('adminhtml')->__('HTML Only')),
            array('value' => 'image_html', 'label'=>Mage::helper('adminhtml')->__('Image Before HTML')),
            array('value' => 'html_image', 'label'=>Mage::helper('adminhtml')->__('Image After HTML'))
        );
    }
}