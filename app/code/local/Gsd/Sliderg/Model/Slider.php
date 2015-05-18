<?php
class Gsd_Sliderg_Model_Slider extends Mage_Core_Model_Abstract
{
    public function _construct() {
        $this->_init('sliderg/slider');
    }
    public function getId()
    {
        return $this->getSliderId();
    }

    public function getImageList() {
        if (!$this->hasData('image')) {
            $_object = $this->_getResource()->loadImage($this);
        }
        return $this->getData('image');
    }

    /*
     * Load image
     *
     */
    public function getImageListForFrontend() {
        if (!$this->hasData('front_image')) {
            $_object = $this->_getResource()->loadImageForFrontend($this);
        }
        return $this->getData('front_image');
    }

    /*
     * Load image
     *
     */
    public function getThumbnailImageListForFrontend() {
        if (!$this->hasData('thumbnail_image')) {
            $_object = $this->_getResource()->loadThumbnailImageForFrontend($this);
        }
        return $this->getData('thumbnail_image');
    }

    public function loadConfig()
    {
        return $this->_getResource()->loadConfig($this);
    }

    public function getConfigArray() {
        return $this->_getResource()->getConfigArray($this);
    }
    public function getConfig() {
        return $this->_getResource()->getConfig($this);
    }
}