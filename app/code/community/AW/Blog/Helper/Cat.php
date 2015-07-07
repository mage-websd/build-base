<?php

class AW_Blog_Helper_Cat extends Mage_Core_Helper_Abstract
{
    protected $_treeAdmin = array();
    protected $_treeHtml = '';

    /**
     * Renders CMS page
     * Call from controller action
     *
     * @param Mage_Core_Controller_Front_Action $action
     * @param integer                           $identifier
     *
     * @return bool
     */
    public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier = null)
    {
        if (!$catId = Mage::getSingleton('blog/cat')->load($identifier)->getCatId()) {
            return false;
        }

        $pageTitle = Mage::getSingleton('blog/cat')->load($identifier)->getTitle();
        $blogTitle = Mage::getStoreConfig('blog/blog/title') . " - " . $pageTitle;

        $action->loadLayout();
        if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
        $action->getLayout()->getBlock('head')->setTitle($blogTitle);

        $action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('blog/blog/layout'));
        $action->renderLayout();

        return true;
    }

    public function getTreeAdmin()
    {
        $this->_treeAdmin = array();
        $this->_getTreeAdmin(0);
        return $this->_treeAdmin;
    }

    protected function _getTreeAdmin($_category,$_level=0)
    {
        $_children = Mage::getResourceModel('blog/cat_collection')->getChildren($_category);
        if(count($_children) > 0) {
            foreach($_children as $_child) {
                $_labelPrefix = '';
                for($i = 0 ; $i < $_level ; $i++) {
                    $_labelPrefix .= '--';
                }
                $_labelPrefix .= ' ';
                $this->_treeAdmin[] = array('label'=>$_labelPrefix.$_child->getTitle(), 'value'=>$_child->getCatId());
                $this->_getTreeAdmin($_child->getId(),($_level+1));
            }
        }
    }

    public function getTreeFrontend()
    {
        $this->_treeHtml = '';
        $this->_getTreeFrontend(0);
        return $this->_treeHtml;
    }

    protected function _getTreeFrontend($_category,$_level=0)
    {
        $_classAddition = '';
        if($_level==0) {
            $_classAddition = ' blog-categories-menu';
        }
        $_children = Mage::getResourceModel('blog/cat_collection')->getChildren($_category);
        if(count($_children) > 0) {
            $this->_treeHtml .= '<ul class="level-'.$_level.$_classAddition.'">';
            foreach($_children as $_child) {
                $this->_treeHtml .= '<li class="level-'.$_level.'">';
                $this->_treeHtml .= '<a href="'.Mage::helper('blog')->getAddressCategory($_child).'">'.$_child->getTitle().'</a>';
                $this->_getTreeFrontend($_child->getId(),($_level+1));
                $this->_treeHtml .= '</li>';
            }
            $this->_treeHtml .= '</ul>';
        }
    }
}