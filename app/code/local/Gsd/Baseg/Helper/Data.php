<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 18/05/2015
 * Time: 13:34
 */ 
class Gsd_Baseg_Helper_Data extends Mage_Core_Helper_Abstract {
    public function translateCode($code)
    {
        $processor = Mage::getSingleton('core/email_template_filter');
        return $processor->filter($code);
    }
    public function getReviewHtml($product)
    {
        if(!$product->getId()) {
            return null;
        }
        return Mage::app()->getLayout()->createBlock('review/helper')->getSummaryHtml($product, false,false);;
        //$this->getReviewsSummaryHtml($product) //block Mage_Catalog_Block_Product_Abstract

    }
}