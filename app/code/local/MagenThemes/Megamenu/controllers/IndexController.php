<?php
class MagenThemes_Megamenu_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    { 
        echo 1;
        echo '<br/>';
        $pageId = 3;
        $page = Mage::getModel('cms/page')->load($pageId);
        if($page->getIdentifier() == Mage::getStoreConfig('web/default/cms_home_page')) {
            var_dump(Mage::getBaseUrl());
        } else {
            var_dump( Mage::helper('cms/page')->getPageUrl(3));
        }
        
        Mage::getModel(
                $this->getModelOfType())->load($this->_menu->getArticle(), array('url'))->getUrl();
    }
}