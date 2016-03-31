<?php

class Gsd_Baseg_Helper_Data extends Mage_Core_Helper_Abstract {

    public function translateCode($code) {
        $processor = Mage::getSingleton('core/email_template_filter');
        return $processor->filter($code);
    }

    public function getReviewHtml($product) {
        if (!$product->getId()) {
            return null;
        }
        return Mage::app()->getLayout()->createBlock('review/helper')->getSummaryHtml($product, false, false);
        ;
        //$this->getReviewsSummaryHtml($product) //block Mage_Catalog_Block_Product_Abstract
    }

    public function printG($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function formatWord($word) {
        return Mage::getSingleton('catalog/product_url')->formatUrlKey($word);
    }

    public function cutText($brandDes) {
        $stripbrandDes = strip_tags($brandDes);
        $sortbrandDes = '';
        if (mb_strlen(strip_tags($brandDes), 'utf-8') > 985) {
            $sortbrandDes = mb_substr(strip_tags($brandDes), 0, 985, 'utf-8');
            $sortbrandDes = trim($sortbrandDes);
            $lastSpace = mb_strrpos($sortbrandDes, ' ', 'utf-8');
            $sortbrandDes = mb_substr($sortbrandDes, 0, $lastSpace, 'utf-8');
        }
    }

}
