<?php
class Gsd_Likeg_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $store = Mage::app()->getStore()->getId();
        $productId = $this->getRequest()->getParam('id');
        if(!$productId) {
            $this->_redirect('');
            return;
        }
        $product = Mage::getModel('catalog/product')->setStoreId($store)->load($productId);
        if(!$product->getId()) {
            $this->_redirect('');
            return;
        }
        $url = $this->getRequest()->getParam('url');
        if($this->getRequest()->isXmlHttpRequest()) {
            $response = array();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
        if($likeCount = $product->getData('like_count')) {
            $product->setLikeCount($likeCount+1);
        }
        else {
            $product->setLikeCount(1);
        }
        $product->save();

        /*if($url) {
            Mage::app()->getResponse()->setRedirect($lastUrl);
        }*/
        $lastUrl = Mage::getSingleton('core/session')->getLastUrl();
        Mage::app()->getResponse()->setRedirect($lastUrl);
        return;
    }

    public function productAction()
    {
        $store = Mage::app()->getStore()->getId();
        $productId = $this->getRequest()->getParam('id');
        if(!$productId) {
            $this->_redirect('');
            return;
        }
        $product = Mage::getModel('catalog/product')->setStoreId($store)->load($productId);
        if(!$product->getId()) {
            $this->_redirect('');
            return;
        }
        $url = $this->getRequest()->getParam('url');
        if($this->getRequest()->isXmlHttpRequest()) {
            $response = array();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
        $model = Mage::getModel('likeg/product');
        $object = $model->getByProduct($productId);
        if(!$object) {
            $model->setData(array(
                'product_id' => $productId,
                'store_id' => $store,
                'count' => 1,
            ));
            $model->save();
        }
        else {
            $object = $object[0];
            $model->load($object['like_id']);
            $model->setData('count', ($object['count']+1));
            $model->save();
        }

        /*if($url) {
            Mage::app()->getResponse()->setRedirect($lastUrl);
        }*/
        $lastUrl = Mage::getSingleton('core/session')->getLastUrl();
        Mage::app()->getResponse()->setRedirect($lastUrl);
        return;
    }

    public function listAction()
    {
        $store = Mage::app()->getStore()->getId();
        $category = Mage::getModel('catalog/category')->load(10);
        /* @var Mage_Catalog_Model_Resource_Product_Collection */
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->setStoreId($store)
            ->addCategoryFilter($category)
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('like_count')
        ;
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($productCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($productCollection);
        echo '<pre>';
        foreach($productCollection as $product) {
            print_r($product->getData());
        }

    }
}