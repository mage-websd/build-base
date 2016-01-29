<?php
class Emosys_Seller_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
	public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = $this->getCurrentCategory()->getProductCollection();
            //filter attribute set
	        $_setId = Mage::app()->getRequest()->getParam('set');
	        if($_setId) {
	            $collection
	                //->addAttributeToSelect('attribute_set_id')
	                ->addAttributeToFilter('attribute_set_id',$_setId);
	        }
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
        //filter customer
        $_customerId = Mage::app()->getRequest()->getParam('customer');
        if($_customerId) {
        	$collection
                ->addAttributeToFilter('customer_id',$_customerId);
        }
        return $collection;
    }
}