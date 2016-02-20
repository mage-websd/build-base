<?php

class AW_Blog_Manage_AdminabstractController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction()
    {
        $this
            ->loadLayout()
            //->_setActiveMenu('blog/comment')
            ->_setActiveMenu('gsd')
            ;
        return $this;
    }
}
