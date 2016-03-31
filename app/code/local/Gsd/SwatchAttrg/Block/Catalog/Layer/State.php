<?php

class Gsd_SwatchAttrg_Block_Catalog_Layer_State extends Mage_Catalog_Block_Layer_State {
    public function __construct() {
        parent::__construct();
        if(Mage::helper('swatchattrg')->isEnable()) {
            $this->setTemplate('swatchattrg/catalog/layer/state.phtml');
        }
    }
}
