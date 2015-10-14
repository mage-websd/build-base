<?php
class AW_Blog_IndexController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::helper('blog')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
        Mage::helper('blog')->ifStoreChangedRedirect();
    }

    public function indexAction()
    {
        $this->_forward('list');
    }

    public function listAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('root')->setTemplate(Mage::helper('blog')->getLayout());
        $this->renderLayout();
    }

    public function searchAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function archiveAction()
    {
        echo '<pre>';
        $posts = Mage::getResourceSingleton('blog/blog_collection')->getYears();
        print_r($posts);

        $posts = Mage::getResourceSingleton('blog/blog_collection')->getMonth();
        print_r($posts);
            exit;
        $this->loadLayout();
        $this->renderLayout();
    }
}