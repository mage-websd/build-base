<?php
class Threemauto_News_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo 1;
        $data = Mage::getModel('news/news')->getCollection()->getData();
        var_dump($data);
    }
}