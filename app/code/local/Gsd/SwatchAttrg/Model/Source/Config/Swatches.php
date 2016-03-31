<?php

class Gsd_SwatchAttrg_Model_Source_Config_Swatches {

    public function getPrefixes() {
        $swatchez = Mage::helper('swatchattrg')->getSwatchAttributes();
        foreach ($swatchez as $swatch) {
            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($swatch)->getFirstItem();
            $prefixes[] = array(
                'field' => 'swatchsize_' . $swatch . '_',
                'label' => $attributeInfo['frontend_label'] . ' (' . $swatch . ')',
            );
        }

        return $prefixes;
    }

}
