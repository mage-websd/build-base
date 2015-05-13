<?php
/**
 * Created by PhpStorm.
 * User: GiangSoda
 * Date: 9/23/14
 * Time: 5:04 PM
 */ 
class Gsd_Sliderg_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getPathMedia()
    {
        return 'sliderg/';
    }
    public function getBaseMediaPath()
    {
        return Mage::getBaseDir('media') . DS . $this->getPathMedia();
    }

    public function getBaseMediaUrl()
    {
        return Mage::getBaseUrl('media') . $this->getPathMedia();
    }

    public function getBaseTmpMediaPath()
    {
        return Mage::getBaseDir('media') . DS . $this->getPathMedia() . 'tmp/';
    }

    public function getBaseTmpMediaUrl()
    {
        return Mage::getBaseUrl('media') . $this->getPathMedia() . 'tmp/';
    }
}