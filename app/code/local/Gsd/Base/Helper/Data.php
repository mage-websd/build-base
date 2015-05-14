<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 14/05/2015
 * Time: 23:22
 */ 
class Gsd_Base_Helper_Data extends Mage_Core_Helper_Abstract {
    public function translateCode($code)
    {
        $processor = Mage::getSingleton('core/email_template_filter');
        return $processor->filter($code);
    }
}