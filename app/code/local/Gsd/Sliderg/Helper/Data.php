<?php
/**
 * Created by PhpStorm.
 * User: GiangSoda
 * Date: 9/23/14
 * Time: 5:04 PM
 */ 
class Gsd_Sliderg_Helper_Data extends Mage_Core_Helper_Abstract {
    public function isModuleEnable()
    {
        return true;
    }

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
    public function getPrefixConfigInput()
    {
        return 'config_';
    }
    public function isExistsImage($image)
    {
        if(file_exists($this->getBaseMediaPath().$image)) {
            return true;
        }
        return false;
    }
    //const XML_PATH_BASE = 'igallery/gallery/';
    /*
     * Get image url of a banner
     */
    /*public function getConfig($name = null) {
        return Mage::getStoreConfig(self::XML_PATH_BASE . $name);
    }*/

    /*
     * Get image url of a banner
     */
    public function getImageUrl($url = null) {
        return $this->getBaseMediaUrl() . $url;
    }

    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * @param mixed $valueToEncode
     * @param  boolean $cycleCheck Optional; whether or not to check for object recursion; off by default
     * @param  array $options Additional options used during encoding
     * @return string
     */
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array())
    {
        $json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
        /* @var $inline Mage_Core_Model_Translate_Inline */
        $inline = Mage::getSingleton('core/translate_inline');
        if ($inline->isAllowed()) {
            $inline->setIsJson(true);
            $inline->processResponseBody($json);
            $inline->setIsJson(false);
        }
        return $json;
    }

    /*
     * Get banner
     */
    public function getSlider($sliderId = null) {
        return Mage::getSingleton('core/layout')->createBlock('sliderg/slider')->setSliderId($sliderId)->toHtml();
    }

    public function getSkinFile($type)
    {
        $html ='';
        switch($type) {
            case 'slicebox':
                if(!Mage::registry('sliderg_skin_file_slicebox')) {
                    $html .= '<link rel="stylesheet" type="text/css" href="' . Mage::getBaseUrl('js') . 'sliderg/slicebox/css/slicebox.css" />';
                    $html .= '<script src="' . Mage::getBaseUrl('js') . 'sliderg/slicebox/js/modernizr.custom.46884.js"></script>';
                    $html .= '<script src="' . Mage::getBaseUrl('js') . 'sliderg/slicebox/js/jquery.slicebox.js"></script>';
                    Mage::register('sliderg_skin_file_slicebox',true);
                }
                break;
            /*case 'swiper':
                if(!Mage::registry('sliderg_skin_file_swiper')) {
                    $html .= '<link rel="stylesheet" type="text/css" href="' . Mage::getBaseUrl('js') . 'sliderg/swiper/css/swiper.min.css" />';
                    $html .= '<script src="' . Mage::getBaseUrl('js') . 'sliderg/swiper/js/swiper.min.js"></script>';
                    $html .= '<script src="' . Mage::getBaseUrl('js') . 'sliderg/swiper/js/swiper.jquery.min.js"></script>';
                    Mage::register('sliderg_skin_file_swiper',true);
                }
                break;*/
        }
        return $html;
    }

    public function getTemplateFile($type)
    {
        $html ='';
        switch($type) {
            case 'slicebox':
                $html = 'sliderg/slicebox.phtml';
                break;
            case 'swiper':
                $html = 'sliderg/swiper.phtml';
                break;
        }
        return $html;
    }

    public function getImageSize($image = null, $_maxW = 125, $_maxH = 125, $fix = false) {
        $_baseSrc = $this->getBaseMediaPath();
        if (file_exists($imagePath = $_baseSrc . $image->getPathMedia())) {
            $_imageObject = new Varien_Image($imagePath);
            $_sizeArray = array($_imageObject->getOriginalWidth(), $_imageObject->getOriginalHeight());
            $_defaultW = $_maxW;
            $_defaultH = $_maxH;
            if ($_sizeArray[0] / $_sizeArray[1] > $_defaultW / $_defaultH) {
                $_defaultW *= ( floatval($_sizeArray[0] / $_sizeArray[1]) / floatval($_defaultW / $_defaultH));
            } else {
                $_defaultH *= ( floatval($_defaultW / $_defaultH) / floatval($_sizeArray[0] / $_sizeArray[1]));
            }

            if ($fix == 'width') {
                if ($_defaultW > $_maxW) {
                    $_defaultH *= $_maxW / $_defaultW;
                    $_defaultW = $_maxW;
                }
            } elseif ($fix == 'height') {
                if ($_defaultH > $_maxH) {
                    $_defaultW *= $_maxH / $_defaultH;
                    $_defaultH = $_maxH;
                }
            } else {
                if ($_defaultW > $_maxW) {
                    $_defaultH *= $_maxW / $_defaultW;
                    $_defaultW = $_maxW;
                } elseif ($_defaultH > $_maxH) {
                    $_defaultW *= $_maxH / $_defaultH;
                    $_defaultH = $_maxH;
                }
            }

            return new Varien_Object(array(
                'width' => round($_defaultW),
                'height' => round($_defaultH)
            ));
        }
        return false;
    }
}