<?php

class Gsd_SwatchAttrg_Block_System_Config_Swatches extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $html = '<div style="background: scroll #ccc;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 20px;"><ul><li><h4>';
        $html .= Mage::helper('swatchattrg')->__('CURRENT SWATCH ATTRIBUTES');
        $html .= '</h4><p style="font-size:10px; color:#666666;">&#8226;&nbsp;' . Mage::helper('swatchattrg')->getSwatchList() . '</p></li></ul></div>';
        return $html;
    }

}
