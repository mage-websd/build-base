<?php

class Gsd_SearchAjaxg_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getSuggestUrl()
    {
        return $this->_getUrl('searchajaxg/suggest/result', array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    public function getStyle()
    {
        //$style='';
        $style = '
        <style>
.ajaxsearch{border:solid ' . Mage::getStoreConfig('searchajaxg/settings/border_color') . ' ' . Mage::getStoreConfig('searchajaxg/settings/border_width') . 'px}
.ajaxsearch .suggest{background:' . Mage::getStoreConfig('searchajaxg/suggest/background') . '; color:' . Mage::getStoreConfig('searchajaxg/suggest/suggest_color') . '}
.ajaxsearch .suggest .amount{color:' . Mage::getStoreConfig('searchajaxg/suggest/count_color') . '}
.ajaxsearch .preview {background:' . Mage::getStoreConfig('searchajaxg/preview/background') . '}
.ajaxsearch .preview a {color:' . Mage::getStoreConfig('searchajaxg/preview/pro_title_color') . '}
.ajaxsearch .preview .description {color:' . Mage::getStoreConfig('searchajaxg/preview/pro_description_color') . '}
.ajaxsearch .preview img {float:left; border:solid ' . Mage::getStoreConfig('searchajaxg/preview/image_border_width') . 'px ' . Mage::getStoreConfig('searchajaxg/preview/image_border_color') . ' }
.header .form-search .ajaxsearch li.selected {background-color:' . Mage::getStoreConfig('searchajaxg/settings/hover_background') . '}
</style>';
        return $style;
    }
}
