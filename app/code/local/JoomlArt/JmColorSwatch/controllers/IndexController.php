<?php
class JoomlArt_JmColorSwatch_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
        //$this->loadLayout();
        //return $this->renderLayout();
        $requests =  $this->getRequest()->getParams();
        $product_arr = Mage::helper("jmcolorswatch")->getassociatedproducts($requests["mainproduct"]);
        
        if(is_array($product_arr)){
          foreach ($product_arr as $productid) {
            $product = Mage::getModel('catalog/product')->load($productid);
            echo $product->getData("size");
            echo '<br/>';
          }
        }

	  
    }
}