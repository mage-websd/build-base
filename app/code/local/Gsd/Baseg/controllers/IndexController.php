<?php
class Gsd_Baseg_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        var_dump(Mage::getRoot());
    }
    public function productAction()
    {
        $_products = Mage::getResourceModel('reports/product_collection')
            ->addOrderedQty()
            ->setOrder('ordered_qty','desc')
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('name','Compact mp3 Player')
            ->setPageSize(50)
        ;
        echo '<pre>';

        /*foreach($_products as $_product) {
            echo 'qty sold: ' . $_product->getData('ordered_qty'). '-- name: '.$_product->getData('order_items_name').'-- id: '.$_product->getData('entity_id').'<br/>';
        }*/
        //print_r(Mage::getModel('catalog/product')->load(377)->getData());
        //print_r($_products->getFirstItem());
        Mage::getModel('sales/order');
        $orders = Mage::getResourceModel('sales/order_item_collection')
            ->addFieldToFilter('product_id',337)
            ->setOrder('created_at','desc')
            ->getFirstItem()
            //->getAllVisibleItems()
            ;
        print_r($orders->getData());
    }
}