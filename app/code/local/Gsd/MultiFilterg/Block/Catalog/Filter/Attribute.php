<?php
class Gsd_MultiFilterg_Block_Catalog_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
        parent::__construct();
        if(Mage::helper('multifilterg')->isEnable()) {
            $this->setTemplate('multifilterg/layer/filter.phtml');
        }
    }
}