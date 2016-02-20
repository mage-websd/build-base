<?php

class MT_ProductQuestions_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard//Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
   {
        $front = $observer->getEvent()->getFront();
        $blog = new MT_ProductQuestions_Controller_Router();
        $front->addRouter(MT_ProductQuestions_Helper_Router::FRONTEND_URI_CODE, $this);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
    
        $identifier = trim($request->getPathInfo(), '/');
        $identifier = str_replace('.html', '', $identifier);
        if ($identifier == '') {
            $request->setModuleName('cms')
                ->setControllerName('index')
                ->setActionName('index');
            return true;
        }
        
        //follow question home view
        if($identifier == MT_ProductQuestions_Helper_Router::FRONTEND_URI_SEO) {
            $request->setModuleName(MT_ProductQuestions_Helper_Router::FRONTEND_URI_CODE)
                    ->setControllerName('questions')
                    ->setActionName('index');
            return true;
        }
        
        //follow category question
        
        $categoryQuestions = Mage::getModel('productquestions/categories')->getCollection()
                ->addVisibilityFilter()
                ->addFieldToFilter('identifier',$identifier)
                ->addStoreFilter()
                ;
        if(count($categoryQuestions)) {
            $categoryQuestions = $categoryQuestions->getFirstItem();
            $request->setModuleName(MT_ProductQuestions_Helper_Router::FRONTEND_URI_CODE)
                    ->setControllerName('questions')
                    ->setActionName('index')
                    ->setParam('category', $categoryQuestions->getId());
            return true;
        }
        
        //follow question view
        $productQuestions = Mage::getModel('productquestions/productquestions')->getCollection()
                ->addFieldToFilter('identifier',$identifier)
                ->addVisibilityFilter()
                ->addStoreFilter();
        if(!count($productQuestions)) {
            return false;
        }
        $productQuestions = $productQuestions->getFirstItem();
        $request->setModuleName(MT_ProductQuestions_Helper_Router::FRONTEND_URI_CODE)
                    ->setControllerName('questions')
                    ->setActionName('view')
                    ->setParam('id', $productQuestions->getId());
        return true;
    }
}