<?php

class Gsd_SwatchAttrg_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_swatchAttributes;
    protected $_imageSize;
    protected $_swatchJson;
    protected $_productImagesDecode;

    public function isEnable() {
        return $this->isModuleOutputEnabled('Gsd_SwatchAttrg') &&
                Mage::helper('swatchattrg/config')->getConfig('general', 'enable') &&
                count($this->getSwatchAttributes());
    }

    public function canShowjQuery() {
        if (Mage::getStoreConfig('swatchattrg/zoom/enabled') == true && Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == false) {
            return 'cjm/swatchattrg/jquery-1.6.4.min.js';
        }
    }

    public function canShowCloudZoom() {
        if (Mage::getStoreConfig('swatchattrg/zoom/enabled') == true && Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == false) {
            return 'cjm/swatchattrg/cloud-zoom.1.0.2.min.js';
        }
    }

    public function canShowCloudCSS() {
        if (Mage::getStoreConfig('swatchattrg/zoom/enabled') == true && Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == false) {
            return 'css/cloud-zoom.css';
        }
    }

    public function canShowMediaTemplate() {
        if (Mage::getStoreConfig('swatchattrg/zoom/enabled') == true && Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == false) {
            return 'swatchattrg/catalog/product/view/media.phtml';
        }
    }

    public function getZoomRel($product_gallery, $product_image, $frontendlabel) {

        if (Mage::getStoreConfig('swatchattrg/zoom/zoomtag') !== '' && Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == true) {
            $zoomId = Mage::getStoreConfig('swatchattrg/zoom/zoomtag');
        } else {
            $zoomId = 'cloudZoom';
        }

        if (Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == true) {
            $rel = 'useZoom: \'' . $zoomId . '\', smallImage: \'' . $product_image . '\'" class="cloud-zoom-gallery" title="' . $frontendlabel . '';
        } else {
            $rel = 'popupWin:\'' . $product_gallery . '\', useZoom: \'' . $zoomId . '\', smallImage: \'' . $product_image . '\'" class="cloud-zoom-gallery" title="' . $frontendlabel . '';
        }

        return 'rel="' . $rel . '"';
    }

    public function getTheOptionValues($product) {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $confAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        $anArray = array();

        foreach ($confAttributes as $confAttribute) {
            $thecode = $confAttribute["attribute_code"];
            if (in_array($thecode, $swatch_attributes)) {
                $options = $confAttribute["values"];
                foreach ($options as $option) {
                    $anArray[] = $option["value_index"];
                }
            }
        }

        return $anArray;
    }

    public function getImageSizes() {
        if ($this->_imageSize) {
            return $this->_imageSize;
        }
        $sizesArray = array();

        $b = Mage::helper('swatchattrg/config')->getConfig('size', 'base');
        $b = !$b ? '265x265' : strtolower($b);
        $exploder = preg_match("/x/i", $b) ? $b : $b . 'x' . $b;
        $sizes = explode("x", $exploder);
        $sizesArray['base']['width'] = $sizes[0];
        $sizesArray['base']['height'] = $sizes[1];

        $m = Mage::helper('swatchattrg/config')->getConfig('size', 'more');
        $m = !$m ? '56x56' : strtolower($m);
        $exploder = preg_match("/x/i", $m) ? $m : $m . 'x' . $m;
        $sizes = explode("x", $exploder);
        $sizesArray['more']['width'] = $sizes[0];
        $sizesArray['more']['height'] = $sizes[1];

        $l = Mage::helper('swatchattrg/config')->getConfig('size', 'list');
        $l = !$l ? '135x135' : strtolower($l);
        $exploder = preg_match("/x/i", $l) ? $l : $l . 'x' . $l;
        $sizes = explode("x", $exploder);
        $sizesArray['list']['width'] = $sizes[0];
        $sizesArray['list']['height'] = $sizes[1];

        $this->_imageSize = $sizesArray;
        return $sizesArray;
    }

    public function getUsesSwatchAttribs($_product) {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $confAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
        foreach ($confAttributes as $confAttribute):
            $thecode = $confAttribute["attribute_code"];
            if (in_array($thecode, $swatch_attributes)) {
                return 'yes';
            }
        endforeach;

        return 'no';
    }

    public function getConfigQueryString($_options, $confAttributes) {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $query = array();

        foreach ($confAttributes as $confAttribute) :
            if (in_array($confAttribute['attribute_code'], $swatch_attributes)) {
                $attributeCode = $confAttribute['attribute_code'];
                $attributeName = $confAttribute['label'];

                foreach ($_options as $_option) :
                    if ($attributeName == $_option['label']) {
                        $attributeoption = $_option['value'];
                    }
                endforeach;

                if ($attributeoption) {
                    $query[$attributeCode] = $attributeoption;
                }
            }
        endforeach;

        return '?' . http_build_query($query);
    }

    public function getSortedByPosition($array) {
        $new = '';
        $new1 = '';
        foreach ($array as $k => $na)
            $new[$k] = serialize($na);
        $uniq = array_unique($new);
        foreach ($uniq as $k => $ser)
            $new1[$k] = unserialize($ser);
        if (isset($new1)) {
            return $new1;
        } else {
            return '';
        }
    }

    public function getAssociatedOptions($product, $att) {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $confAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        $availattribs = array();
        $thecode = '';
        $html = '';

        foreach ($confAttributes as $confAttribute) {
            $thecode = $confAttribute["attribute_code"];
            if (in_array($thecode, $swatch_attributes)) {
                $attributeCode = $confAttribute['attribute_code'];
                $attributeName = $confAttribute['label'];
                $options = $confAttribute["values"];

                foreach ($options as $option) {
                    $string = $option["label"];
                    $label = trim(substr($string, 0, strpos("$string#", "#")));
                    $optvalue = $option["value_index"];
                    $availattribs['value'][] = $optvalue;
                    $availattribs['label'][] = $label;
                    ;
                }
            }
        }

        if ($availattribs) {
            $count = count($availattribs['value']);
        } else {
            $count = 0;
        }

        if ($count < 1) {
            $html .= '<select class="' . $att . '" disabled="disabled" style="width:100px;">';
        } else {
            $html .= '<select class="' . $att . '" style="width:100px;" onchange="media_gallery_contentJsObject.updateImage(\'__file__\')">';
            if ($att == 'cjm_moreviews') {
                $html .= '<option value="0">';
                $html .= Mage::helper('swatchattrg')->__('*Always Visible*');
                $html .= '</option>';
                $html .= '<option value="99999999">';
                $html .= Mage::helper('swatchattrg')->__('*None*');
                $html .= '</option>';
            } else {
                $html .= '<option value="0">&nbsp;</option>';
            }
            for ($s = 0; $s < $count; $s++) {
                $html .= '<option value="' . $availattribs['value'][$s] . '">' . $availattribs['label'][$s] . '</option>';
            }
            $html .= '</select><br />';
        }

        return $html;
    }

    public function getMoreViews($_product) {
        $html = '';
        $html_first = '';
        $onloads = '';
        $defaults = '';
        $defaultmoreviews = 0;
        $product_base = Mage::helper('swatchattrg')->decodeImages($_product);
        $theSizes = Mage::helper('swatchattrg')->getImageSizes();

        if ($product_base):
            $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
            $confAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
            $images = $product_base['image'];

            foreach ($images as $key => $image):
                if ($product_base['defaultimg'][$key] == 1 && $product_base['morev'][$key] != 99999999):
                    $defaultmoreviews = 1;
                    $product_image = $product_base['image'][$key];
                    $product_thumb = $product_base['thumb'][$key];
                    $product_label = $product_base['label'][$key];
                    $onloads .= '<li class="onload-moreviews"><a href="' . $product_image . '" onclick="$(\'image\').src = this.href; return false;"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                elseif ($product_base['morev'][$key] == 0):
                    $defaultmoreviews = 1;
                    $product_image = $product_base['image'][$key];
                    $product_thumb = $product_base['thumb'][$key];
                    $product_label = $product_base['label'][$key];
                    $defaults .= '<li class="default-moreviews"><a href="' . $product_image . '" onclick="$(\'image\').src = this.href; return false;"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                endif;
            endforeach;

            if ($defaultmoreviews == 1) {
                $html_first = '<h2 id="moreviews-title">' . Mage::helper('swatchattrg')->__('More Views') . '</h2><ul id="ul-moreviews">';
            } else {
                $html_first = '<h2 id="moreviews-title" style="display:none;">' . Mage::helper('swatchattrg')->__('More Views') . '</h2><ul id="ul-moreviews">';
            }

            $html = $html_first .= $html;

            if ($onloads) {
                $html .= $onloads;
            }
            if ($defaults) {
                $html .= $defaults;
            }

            foreach ($confAttributes as $confAttribute):
                $thecode = $confAttribute["attribute_code"];
                if (in_array($thecode, $swatch_attributes)):
                    $options = $confAttribute["values"];
                    foreach ($options as $option):
                        $value = $option['value_index'];
                        foreach ($images as $key => $image):
                            if ($product_base['morev'][$key] == $value):
                                $product_image = $product_base['image'][$key];
                                $product_thumb = $product_base['thumb'][$key];
                                $product_label = $product_base['label'][$key];
                                $html .= '<li class="moreview' . $value . '" style="display:none;"><a href="' . $product_image . '" onclick="$(\'image\').src = this.href; return false;"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                            endif;
                        endforeach;
                    endforeach;
                endif;
            endforeach;

            $html .= '</ul>';
            return $html;
        else:
            return '';
        endif;
    }

    public function getMoreViewsZoom($_product) {
        $html = '';
        $html_first = '';
        $onloads = '';
        $defaults = '';
        $defaultmoreviews = 0;
        $product_base = Mage::helper('swatchattrg')->decodeImages($_product);
        $theSizes = Mage::helper('swatchattrg')->getImageSizes();

        if (Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == true && Mage::getStoreConfig('swatchattrg/zoom/ulclass') !== '') {
            $ulClass = Mage::getStoreConfig('swatchattrg/zoom/ulclass');
        } else {
            $ulClass = '';
        }

        if (Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == true && Mage::getStoreConfig('swatchattrg/zoom/ulid') != '') {
            $ulId = 'id="' . Mage::getStoreConfig('swatchattrg/zoom/ulid') . '"';
        } else {
            $ulId = 'id="ul-moreviews"';
        }

        if ($product_base):
            $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
            $confAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
            $images = $product_base['image'];

            foreach ($images as $key => $image):
                if ($product_base['defaultimg'][$key] == 1 && $product_base['morev'][$key] != 99999999):
                    $defaultmoreviews = 1;
                    $product_image = $product_base['image'][$key];
                    $product_thumb = $product_base['thumb'][$key];
                    $product_label = $product_base['label'][$key];
                    $product_gallery = Mage::helper('swatchattrg')->getGalUrl($product_base['id'][$key], $_product->getId());
                    $rel = Mage::helper('swatchattrg')->getZoomRel($product_gallery, $product_image, $product_label);
                    $onloads .= '<li class="onload-moreviews"><a href="' . $product_base['full'][$key] . '" ' . $rel . ' class="cloud-zoom-gallery" title="' . $product_label . '"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                elseif ($product_base['morev'][$key] == 0):
                    $defaultmoreviews = 1;
                    $product_image = $product_base['image'][$key];
                    $product_thumb = $product_base['thumb'][$key];
                    $product_label = $product_base['label'][$key];
                    $product_gallery = Mage::helper('swatchattrg')->getGalUrl($product_base['id'][$key], $_product->getId());
                    $rel = Mage::helper('swatchattrg')->getZoomRel($product_gallery, $product_image, $product_label);
                    $defaults .= '<li class="default-moreviews"><a href="' . $product_base['full'][$key] . '" ' . $rel . ' class="cloud-zoom-gallery" title="' . $product_label . '"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                endif;
            endforeach;

            if (Mage::getStoreConfig('swatchattrg/zoom/zoomexists') == true) {
                $html_first = '<ul ' . $ulId . ' class="' . $ulClass . '">';
            } else {
                if ($defaultmoreviews == 1) {
                    $html_first = '<h2 id="moreviews-title">' . Mage::helper('swatchattrg')->__('More Views') . '</h2><ul id="ul-moreviews" class="' . $ulClass . '">';
                } else {
                    $html_first = '<h2 id="moreviews-title" style="display:none;">' . Mage::helper('swatchattrg')->__('More Views') . '</h2><ul id="ul-moreviews" class="' . $ulClass . '">';
                }
            }

            $html = $html_first .= $html;

            if ($onloads) {
                $html .= $onloads;
            }
            if ($defaults) {
                $html .= $defaults;
            }

            foreach ($confAttributes as $confAttribute):
                $thecode = $confAttribute["attribute_code"];
                if (in_array($thecode, $swatch_attributes)):
                    $options = $confAttribute["values"];
                    foreach ($options as $option):
                        $value = $option['value_index'];
                        foreach ($images as $key => $image):
                            if ($product_base['morev'][$key] == $value):
                                $product_image = $product_base['image'][$key];
                                $product_thumb = $product_base['thumb'][$key];
                                $product_label = $product_base['label'][$key];
                                $product_gallery = Mage::helper('swatchattrg')->getGalUrl($product_base['id'][$key], $_product->getId());
                                $rel = Mage::helper('swatchattrg')->getZoomRel($product_gallery, $product_image, $product_label);
                                $html .= '<li class="moreview' . $value . '" style="display:none;"><a href="' . $product_base['full'][$key] . '" ' . $rel . ' class="cloud-zoom-gallery" title="' . $product_label . '"><img src="' . $product_thumb . '" width="' . $theSizes['more']['width'] . '" height="' . $theSizes['more']['height'] . '" alt="' . $product_label . '" title="' . $product_label . '" /></a></li>';
                            endif;
                        endforeach;
                    endforeach;
                endif;
            endforeach;

            $html .= '</ul>';
            return $html;
        else:
            return '';
        endif;
    }

    public function getQueryString($_product) {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $query = array();
        $configurable_product = Mage::getModel('catalog/product_type_configurable');
        $parentIdArray = $configurable_product->getParentIdsByChild($_product->getId());

        if ($_product->getTypeId() == 'simple' && isset($parentIdArray[0])) {
            foreach ($_product->getAttributes() as $_attribute):
                if (in_array($_attribute->getAttributeCode(), $swatch_attributes)) {
                    $attributename = $_attribute->getAttributeCode();
                    $attributeoption = Mage::getModel('catalog/product')->load($_product->getId())->getAttributeText($attributename);
                    if ($attributeoption) {
                        $query[$attributename] = $attributeoption;
                    }
                }
            endforeach;
        }

        if ($query) {
            return $query;
        }

        return '';
    }

    public function getSwatchList() {
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $html = '';

        $count = count($swatch_attributes);

        for ($i = 0; $i < $count; $i++) {

            if ($i == $count - 1) {
                $html .= $swatch_attributes[$i];
            } else {
                $html .= $swatch_attributes[$i] . '&nbsp;&#8226;&nbsp;';
            }
        }
        return $html;
    }

    public function getSwatchAttributes() {
        if ($this->_swatchAttributes) {
            return $this->_swatchAttributes;
        }
        $swatch_attributes = array();
        $swatchattributes = Mage::getStoreConfig('swatchattrg/general/colorattributes', Mage::app()->getStore());
        $swatch_attributes = explode(",", $swatchattributes);

        foreach ($swatch_attributes as &$attribute) {
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute)->getAttributeCode();
        }
        unset($attribute);
        $this->_swatchAttributes = $swatch_attributes;
        return $swatch_attributes;
    }

    public function getSwatchSize($theCode) {
        if ($theCode == 'list'):
            $swatchsize = str_replace(" ", "", Mage::helper('swatchattrg/config')->getConfig('general', 'listsize'));
            if (!$swatchsize) {
                $swatchsize = '12x12';
            }
            if (preg_match("/x/i", $swatchsize)) {
                return $swatchsize;
            } else {
                return $swatchsize . 'x' . $swatchsize;
            }
            return $swatchsize;
        elseif ($theCode == 'shopby'):
            $swatchsize = str_replace(" ", "", Mage::helper('swatchattrg/config')->getConfig('general', 'layersize', Mage::app()->getStore()));
            if (!$swatchsize) {
                $swatchsize = '15x15';
            }
            if (preg_match("/x/i", $swatchsize)) {
                return $swatchsize;
            } else {
                return $swatchsize . 'x' . $swatchsize;
            }
            return $swatchsize;
        elseif ($theCode != 'null'):
            $swatchsize = str_replace(" ", "", Mage::getStoreConfig('swatchattrg/swatchsizes/swatchsize_' . $theCode . '_swatchsizes', Mage::app()->getStore()));
            if ($swatchsize) {
                if (preg_match("/x/i", $swatchsize)) {
                    return $swatchsize;
                } else {
                    return $swatchsize . 'x' . $swatchsize;
                }
            } else {
                $swatchsize = str_replace(" ", "", Mage::getStoreConfig('swatchattrg/general/size', Mage::app()->getStore()));
                if (!$swatchsize) {
                    $swatchsize = '25x25';
                }
                if (preg_match("/x/i", $swatchsize)) {
                    return $swatchsize;
                } else {
                    return $swatchsize . 'x' . $swatchsize;
                }
                return $swatchsize;
            }
        else:
            $swatchsize = str_replace(" ", "", Mage::getStoreConfig('swatchattrg/general/size', Mage::app()->getStore()));
            if (!$swatchsize) {
                $swatchsize = '25x25';
            }
            if (preg_match("/x/i", $swatchsize)) {
                return $swatchsize;
            } else {
                return $swatchsize . 'x' . $swatchsize;
            }
            return $swatchsize;
        endif;
    }

    public function findColorImage($value, $arr, $key, $type) {
        $found = '';
        if (isset($arr[$key])) {
            $total = count($arr[$key]);
            if ($total > 0) {
                for ($i = 0; $i < $total; $i++) {
                    if ($value == ucwords($arr[$key][$i])) {//if it matches the color listed in the attribute
                        $found = $arr[$type][$i]; //return the image src
                    }
                }
            }
        }
        return $found;
    }

    public function decodeImages($_product) {
        if(!$_product || !$_product->getId()) {
            return null;
        }
        if($this->_productImagesDecode[$_product->getId()]) {
            return $this->_productImagesDecode[$_product->getId()];
        }
        $_gallery = $_product->getMediaGalleryImages();
        $theSizes = $this->getImageSizes();
        if (count($_gallery) > 1) {
            $product_base = array();
            foreach ($_gallery as $_image) {
                $i = $this->getImageSize($_image, $theSizes['base']['width'], $theSizes['base']['height']);
                $t = $this->getImageSize($_image, $theSizes['more']['width'], $theSizes['more']['height']);
                $product_base['full'][] = strval(Mage::helper('catalog/image')->init($_product, 'image', $_image->getFile()));
                $product_base['image'][] = strval(Mage::helper('catalog/image')->init($_product, 'image', $_image->getFile())->resize($i->getWidth(), $i->getHeight()));
                $product_base['thumb'][] = strval(Mage::helper('catalog/image')->init($_product, 'thumbnail', $_image->getFile())->resize($t->getWidth(), $t->getHeight()));
                $product_base['morev'][] = $_image['selectormore'];
                $product_base['color'][] = $_image['selectorbase'];
                $product_base['label'][] = $_image['label'];
                $product_base['id'][] = $_image['value_id'];
                $product_base['defaultimg'][] = $_image['defaultimg'];
            }
            $this->_productImagesDecode[$_product->getId()] = $product_base;
            return $product_base;
        }
        return '';
    }

    public function getImageSize($image = null, $w, $h, $fix = false) {
        if (file_exists($image->getPath())) {
            try {
                $i = new Varien_Image($image->getPath());
                $s = array($i->getOriginalWidth(), $i->getOriginalHeight());
                $_defaultW = $w;
                $_defaultH = $h;
                if ($s[0] / $s[1] > $_defaultW / $_defaultH) {
                    $_defaultW *= ( floatval($s[0] / $s[1]) / floatval($_defaultW / $_defaultH));
                } else {
                    $_defaultH *= ( floatval($_defaultW / $_defaultH) / floatval($s[0] / $s[1]));
                }

                if ($fix == 'width') {
                    if ($_defaultW > $w) {
                        $_defaultH *= $w / $_defaultW;
                        $_defaultW = $w;
                    }
                } elseif ($fix == 'height') {
                    if ($_defaultH > $h) {
                        $_defaultW *= $h / $_defaultH;
                        $_defaultH = $h;
                    }
                } else {
                    if ($_defaultW > $w) {
                        $_defaultH *= $w / $_defaultW;
                        $_defaultW = $w;
                    } elseif ($_defaultH > $h) {
                        $_defaultW *= $h / $_defaultH;
                        $_defaultH = $h;
                    }
                }
                return new Varien_Object(array(
                    'width' => round($_defaultW),
                    'height' => round($_defaultH)
                ));
            } catch (Exception $e) {
                
            }
        }
        return new Varien_Object(array(
            'width' => round($w),
            'height' => round($h)
        ));
    }

    public function getSwatchUrl($optionId) {
        $uploadDir = Mage::getBaseDir('media') . DS . 'swatchattrg' . DS . 'swatches' . DS;
        if (file_exists($uploadDir . $optionId . '.jpg')) {
            return Mage::getBaseUrl('media') . 'swatchattrg' . '/' . 'swatches' . '/' . $optionId . '.jpg';
        }
        return FALSE;
    }

    /*
     public function getSwatchHtml($attributeCode, $atid, $_product) {
        $storeId = Mage::app()->getStore();
        $frontendlabel = 'null';
        $urloption = '';
        $html = '';
        $cnt = 1;
        $_option_vals = array();
        $_colors = array();
        $zoomenabled = Mage::getStoreConfig('swatchattrg/zoom/enabled');
        $hide = Mage::getStoreConfig('swatchattrg/general/hidedropdown', $storeId);
        $frontText = Mage::getStoreConfig('swatchattrg/general/dropdowntext', $storeId);
        $swatchsize = Mage::helper('swatchattrg')->getSwatchSize($attributeCode);
        $sizes = explode("x", $swatchsize);
        $width = $sizes[0];
        $height = $sizes[1];

        if (isset($_GET[$attributeCode])) {
            $urloption = $_GET[$attributeCode];
        }


        $html = $html . '<div class="swatchesContainer"><ul id="ul-attribute' . $atid . '">';
        $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setPositionOrder('asc')->setAttributeFilter($atid)->setStoreFilter(0)->load();

        foreach ($_collection->toOptionArray() as $_option) {
            $_option_vals[$_option['value']] = array(
                'internal_label' => $_option['label'],
                'order' => $cnt
            );
            $cnt++;
        }

        $configAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
        foreach ($configAttributes as $attribute) {
            if ($attribute['attribute_code'] == $attributeCode) {
                foreach ($attribute["values"] as $value) {
                    array_push($_colors, array(
                        'id' => $value['value_index'],
                        'frontlabel' => $value['store_label'],
                        'adminlabel' => $_option_vals[$value['value_index']]['internal_label'],
                        'order' => $_option_vals[$value['value_index']]['order']
                    ));
                }
                break;
            }
        }

        $_color_swatch = Mage::helper('swatchattrg')->getSortedByPosition($_colors);
        $_color_swatch = array_values($_color_swatch);

        foreach ($_color_swatch as $key => $val) {
            $sortSingle[$key] = $_color_swatch[$key]['order'];
        }

        asort($sortSingle);
        reset($sortSingle);

        while (list ($singleKey, $singleVal) = each($sortSingle)) {
            $newArr[] = $_color_swatch[$singleKey];
        }

        $_color_swatch = $newArr;

        foreach ($_color_swatch as $_inner_option_id) {
            $zoomStuff = '';
            $theId = $_inner_option_id['id'];
            $adminLabel = $_inner_option_id['adminlabel'];
            $altText = $_inner_option_id['frontlabel'];
            if ($frontText == 0) {
                $frontendlabel = $altText;
            } else {
                $frontendlabel = 'null';
            }
            preg_match_all('/((#?[A-Za-z0-9]+))/', $adminLabel, $matches);

            if (count($matches[0]) > 0) {
                $color_value = $matches[1][count($matches[0]) - 1];
                $findme = '#';
                $pos = strpos($color_value, $findme);

                $product_base = $this->decodeImages($_product);
                $product_image = $this->findColorImage($theId, $product_base, 'color', 'image'); //returns url for base image
                $product_full_image = $this->findColorImage($theId, $product_base, 'color', 'full'); //returns url for base image

                /*
                  if($zoomenabled && $product_image):
                  $product_image_full = Mage::helper('swatchattrg')->findColorImage($theId, $product_base, 'color', 'full');
                  $product_gallery = Mage::helper('swatchattrg')->getGalUrl(Mage::helper('swatchattrg')->findColorImage($theId, $product_base, 'color', 'id'), $_product->getId());
                  $rel = Mage::helper('swatchattrg')->getZoomRel($product_gallery, $product_image, $frontendlabel);
                  $zoomStuff = '<a id="anchor'.$theId.'" href="'.$product_image_full.'" '.$rel.'>';
                  endif;
                 * /

                if ($urloption == $altText) {
                    $html = $html . '<script type="text/javascript">Event.observe';
                    $html = $html . "(window, 'load', function() {";
                    $html = $html . "colorSelected('attribute" . $atid . "','" . $theId . "','" . $product_image . "','" . $frontendlabel . "','" . $product_full_image . "');clicker(" . $theId . ");});</script>";
                }

                $imageSwatch = $this->getSwatchUrl($theId);
                if (!$imageSwatch && $attributeCode == 'color') {
                    $bgSwatch = strtolower($frontendlabel);
                } else {
                    $bgSwatch = false;
                }
                if ($_product->getData('swatchattrg_useimages') == 1 && $imageSwatch) {
                    $html = $html . '<li class="swatchContainer">';
                    $html = $html . '<img src="' . $imageSwatch . '" id="swatch' . $theId . '" class="swatch" alt="' . $altText . '" title="' . $altText . '" ';
                    $html = $html . 'onclick="colorSelected';
                    $html = $html . "('attribute" . $atid . "','" . $theId . "','" . $product_image . "','" . $frontendlabel . "','" . $product_full_image . "')";
                    $html = $html . '" />';
                    $html = $html . '</li>';
                } 
                else{
                    $html = $html . '<li class="swatchContainer">';
                    $html = $html . '<span class="swatch item-label'. ($bgSwatch ? ' swatches-has-bg' : '').'"';
                    $html = $html . ''.($bgSwatch ? ' style="background-color: '.$bgSwatch.'"' : '');
                    $html = $html . ' id="swatch' . $theId.'"' . ' alt="' . $altText . '" title="' . $altText . '"';
                    $html = $html . 'onclick="colorSelected';
                    $html = $html . "('attribute" . $atid . "','" . $theId . "','" . $product_image . "','" . $frontendlabel . "','" . $product_full_image . "')";
                    $html = $html .'">';
                    $html = $html .''. $frontendlabel .'</span>';
                    $html = $html . '</li>';
                }
            }
        }
        $html = $html . '</ul></div><p class="float-clearer"></p>';
        return $html;
    } */
    
    public function getSwatchHtml($attributeCode, $atid, $_product) {
        $storeId = Mage::app()->getStore();
        $frontendlabel = 'null';
        $urloption = '';
        $html = '';
        $cnt = 1;
        $_option_vals = array();
        $_colors = array();
        $zoomenabled = Mage::getStoreConfig('swatchattrg/zoom/enabled');
        $hide = Mage::getStoreConfig('swatchattrg/general/hidedropdown', $storeId);
        $frontText = Mage::getStoreConfig('swatchattrg/general/dropdowntext', $storeId);
        $swatchsize = Mage::helper('swatchattrg')->getSwatchSize($attributeCode);
        $sizes = explode("x", $swatchsize);
        $width = $sizes[0];
        $height = $sizes[1];

        if (isset($_GET[$attributeCode])) {
            $urloption = $_GET[$attributeCode];
        }


        $html = $html . '<div class="swatchesContainer"><ul id="ul-attribute' . $atid . '">';
        $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setPositionOrder('asc')->setAttributeFilter($atid)->setStoreFilter(0)->load();

        foreach ($_collection->toOptionArray() as $_option) {
            $_option_vals[$_option['value']] = array(
                'internal_label' => $_option['label'],
                'order' => $cnt
            );
            $cnt++;
        }

        $configAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
        foreach ($configAttributes as $attribute) {
            if ($attribute['attribute_code'] == $attributeCode) {
                foreach ($attribute["values"] as $value) {
                    array_push($_colors, array(
                        'id' => $value['value_index'],
                        'frontlabel' => $value['store_label'],
                        'adminlabel' => $_option_vals[$value['value_index']]['internal_label'],
                        'order' => $_option_vals[$value['value_index']]['order']
                    ));
                }
                break;
            }
        }

        $_color_swatch = Mage::helper('swatchattrg')->getSortedByPosition($_colors);
        $_color_swatch = array_values($_color_swatch);

        foreach ($_color_swatch as $key => $val) {
            $sortSingle[$key] = $_color_swatch[$key]['order'];
        }

        asort($sortSingle);
        reset($sortSingle);

        while (list ($singleKey, $singleVal) = each($sortSingle)) {
            $newArr[] = $_color_swatch[$singleKey];
        }

        $_color_swatch = $newArr;

        foreach ($_color_swatch as $_inner_option_id) {
            $zoomStuff = '';
            $theId = $_inner_option_id['id'];
            $adminLabel = $_inner_option_id['adminlabel'];
            $altText = $_inner_option_id['frontlabel'];
            if ($frontText == 0) {
                $frontendlabel = $altText;
            } else {
                $frontendlabel = 'null';
            }
            preg_match_all('/((#?[A-Za-z0-9]+))/', $adminLabel, $matches);

            if (count($matches[0]) > 0) {
                $color_value = $matches[1][count($matches[0]) - 1];
                $findme = '#';
                $pos = strpos($color_value, $findme);

                $product_base = $this->decodeImages($_product);
                $product_image = $this->findColorImage($theId, $product_base, 'color', 'image'); //returns url for base image
                $product_full_image = $this->findColorImage($theId, $product_base, 'color', 'full'); //returns url for base image

                /*
                  if($zoomenabled && $product_image):
                  $product_image_full = Mage::helper('swatchattrg')->findColorImage($theId, $product_base, 'color', 'full');
                  $product_gallery = Mage::helper('swatchattrg')->getGalUrl(Mage::helper('swatchattrg')->findColorImage($theId, $product_base, 'color', 'id'), $_product->getId());
                  $rel = Mage::helper('swatchattrg')->getZoomRel($product_gallery, $product_image, $frontendlabel);
                  $zoomStuff = '<a id="anchor'.$theId.'" href="'.$product_image_full.'" '.$rel.'>';
                  endif;
                 */

                if ($urloption == $altText) {
                    $html = $html . '<script type="text/javascript">Event.observe';
                    $html = $html . "(window, 'load', function() {";
                    $html = $html . "colorSelected('attribute" . $atid . "','" . $theId . "','" . $product_image . "','" . $frontendlabel . "','" . $product_full_image . "');clicker(" . $theId . ");});</script>";
                }

                $imageSwatch = $this->getSwatchUrl($theId);
                if (!$imageSwatch && $attributeCode == 'color') {
                    $bgSwatch = strtolower($frontendlabel);
                } else {
                    $bgSwatch = false;
                }
                $this->_swatchJson[$theId] = array(
                    'attributeId' => $atid,
                    'optionId' => $theId,
                    'label' => $frontendlabel,
                    'imageBase' => $product_image,
                    'imaegFull' => $product_full_image,
                );
                if ($imageSwatch) {
                    $html = $html . '<li class="swatchContainer">';
                    $html = $html . '<img data-option="'.$theId.'" src="' . $imageSwatch . '" id="swatch' . $theId . '" class="swatch swatches-option" alt="' . $altText . '" title="' . $altText . '" ';
                    $html = $html . 'onclick="colorSelected('.$theId.');"';
                    $html = $html . ' />';
                    $html = $html . '</li>';
                } 
                else{
                    $html = $html . '<li class="swatchContainer">';
                    $html = $html . '<span data-option="'.$theId.'" class="swatch swatches-option item-label'. ($bgSwatch ? ' swatches-has-bg' : '').'"';
                    $html = $html . ''.($bgSwatch ? ' style="background-color: '.$bgSwatch.'"' : '');
                    $html = $html . ' id="swatch' . $theId.'"' . ' alt="' . $altText . '" title="' . $altText . '"';
                    $html = $html . 'onclick="colorSelected('.$theId.');"';
                    $html = $html . ' />';
                    $html = $html .''. $frontendlabel .'</span>';
                    $html = $html . '</li>';
                }
            }
        }
        $html = $html . '</ul></div><p class="float-clearer"></p>';
        return $html;
    }
    
    public function getSwatchJson() {
        return json_encode($this->_swatchJson);
    }

    public function getSwatchImg($option) {
        return $this->getSwatchUrl($option->getId());
    }

    public function getAttribOptions() {
        $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setAttributeFilter(Mage::registry('entity_attribute')->getId())->setPositionOrder('asc', true)->load();
        return $optionCollection;
    }

    public function gettheUrl() {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
        }

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    public function getShopByHtml($_item) {
        $html = '';
        $swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
        $ss = Mage::helper('swatchattrg/data')->getSwatchSize('shopby');
        $sizes = explode("x", $ss);
        $width = $sizes[0];
        $height = $sizes[1];
        $theAttribute = $_item['code'];
        $theUrl = $_item['url'];
        $theImage = $_item['image'];
        $theBGcolor = $_item['bgcolor'];
        $theLabel = $_item['label'] . $_item['count'];

        if (in_array($theAttribute, $swatch_attributes) && Mage::getStoreConfig('swatchattrg/general/showonlayer', Mage::app()->getStore()) == 1):
            if ($theImage != ''):
                $html = '<a href="' . $theUrl . '"><img class="swatch-shopby" src="' . $theImage . '" title="' . $theLabel . '" alt="' . $theLabel . '" style="width:' . $width . 'px; height:' . $height . 'px;"></a>';
            elseif ($theBGcolor != ''):
                $html = '<a href="' . $theUrl . '">';
                $html .= '<div class="swatch-shopby" style="background-color:' . $theBGcolor . '; width:' . $width . 'px; height:' . $height . 'px;" title="' . $theLabel . '"></div>';
                $html .= '</a>';
            else:
                $html = '<a href="' . $theUrl . '" class="swatch-shopby-text">' . $theLabel . '</a>';
            endif;
        else:
            $html = '<a href="' . $theUrl . '">' . $_item['label'] . '</a>' . $_item['count'];
        endif;

        return $html;
    }

    public function getZoomOptions() {
        $options = '';

        $width = Mage::getStoreConfig('swatchattrg/zoom/width');
        if (empty($width) || !is_numeric($width)) {
            $width = 'auto';
        }

        $height = Mage::getStoreConfig('swatchattrg/zoom/height');
        if (empty($height) || !is_numeric($height)) {
            $height = 'auto';
        }

        $options .= "zoomWidth: '" . $width . "',";
        $options .= "zoomHeight: '" . $height . "',";
        $options .= "position: '" . Mage::getStoreConfig('swatchattrg/zoom/position') . "',";
        $options .= "smoothMove: " . (int) Mage::getStoreConfig('swatchattrg/zoom/smoothmove') . ",";
        $options .= "showTitle: " . Mage::getStoreConfig('swatchattrg/zoom/title') . ",";
        $options .= "titleOpacity: " . (float) (Mage::getStoreConfig('swatchattrg/zoom/titleopacity') / 100) . ",";

        $adjustX = (int) Mage::getStoreConfig('swatchattrg/zoom/xoffset');
        if ($adjustX > 0) {
            $options .= "adjustX: " . $adjustX . ",";
        }

        $adjustY = (int) Mage::getStoreConfig('swatchattrg/zoom/yoffset');
        if ($adjustY > 0) {
            $options .= "adjustY: " . $adjustY . ",";
        }

        $options .= "lensOpacity: " . (float) (Mage::getStoreConfig('swatchattrg/zoom/lensopacity') / 100) . ",";

        $tint = Mage::getStoreConfig('swatchattrg/zoom/tint');
        if (!empty($tint)) {
            $options .= "tint: '" . (Mage::getStoreConfig('swatchattrg/zoom/softfocus') == 'true' ? '' : Mage::getStoreConfig('swatchattrg/zoom/tint')) . "',";
        }

        $options .= "tintOpacity: " . (float) (Mage::getStoreConfig('swatchattrg/zoom/tintopacity') / 100) . ",";
        $options .= "softFocus: " . Mage::getStoreConfig('swatchattrg/zoom/softfocus') . "";

        return $options;
    }

    public function getGalUrl($image = null, $prodId) {
        $params = array('id' => $prodId);
        if ($image) {
            $params['image'] = $image;
            return Mage::getUrl('*/*/gallery', $params);
        }
        return Mage::getUrl('*/*/gallery', $params);
    }

    // TO DO -- DISPLAYS SELECT BOXES FOR EACH SWATCH ATTRIBUTE
    //public function getAssociatedOptions($product)
//	{
//		$swatch_attributes = Mage::helper('swatchattrg')->getSwatchAttributes();
//		$confAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
//      $availattribs = array();
//		$thecode = '';
//		$thename = '';
//		$html = '';   	
//		
//		foreach ($confAttributes as $confAttribute) {
//			$thename = $confAttribute["label"];
//			$thecode = $confAttribute["attribute_code"];
//			if(in_array($thecode, $swatch_attributes))
//			{
//           		$html .= '<label>'.$thename.'</label>';
//				$html .= '<select class="imageSwitch" id="imageSwitch__value_id__" name="imageSwitch[__value_id__]" style="width:100px;">';
//				$html .= '<option value="">&nbsp;</option>';
//              	$options = $confAttribute["values"];
//				foreach($options as $option) {
//    				$string = $option["label"];
//					$result = trim(substr($string, 0, strpos("$string#", "#")));
//					$availattribs[] = $result;
//                    $html .= '<option value="'.$result.'">'.$result.'</option>';
//				}
//				$html .= '</select><br />';
//			}
//		}
//		
//		return $html;	
//	}
}
