<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Banner_Block_Item extends Mage_Core_Block_Template
{
    public function getItem(){
        $collection = Mage::getResourceModel('emosys_banner/item_collection');
        $collection->addStoreFilter(Mage::app()->getStore()->getId());
        $collection->addStatusFilter(1);
        $collection->addDateFilter();
        if($collection->getSize()){
            return  $collection->getFirstItem();
        }
        return false;
    }
}