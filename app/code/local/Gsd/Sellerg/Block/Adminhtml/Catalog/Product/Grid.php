<?php
class Gsd_Sellerg_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        parent::_prepareCollection();
        /*$collection = $this->getCollection();
        $collection->addAttributeToSelect('approved');
        $this->setCollection($collection);
        //$this->getCollection()->addWebsiteNamesToResult();*/
        return $this;
    }
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumn('approval',
            array(
                'header'=> Mage::helper('catalog')->__('Approved'),
                'width' => '20px',
                'index' => 'approval',
                'type'  => 'options',
                'options' => array(
                    '1'=>'Yes',
                    '0'=>'No',
                ),
            ));
    }
}