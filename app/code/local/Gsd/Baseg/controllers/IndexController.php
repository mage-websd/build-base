<?php
class Gsd_Baseg_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if($this->getRequest()->isXmlHttpRequest()) {
            $baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
            echo Mage::helper('core')->jsonEncode($baseUrl);
            return;
        }
        $this->_redirect('/');
    }
}