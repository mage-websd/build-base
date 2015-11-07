<?php
class Emosys_PrintOrderBundle_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        $type = array(
            array('value'=>0,'label'=>Mage::helper('sales')->__('Individual')),
            array('value'=>1,'label'=>Mage::helper('sales')->__('A4')),
        );
        $this->getMassactionBlock()->addItem('print_bundle', array(
            'label'=> Mage::helper('sales')->__('Print Order Meal'),
            'url'  => $this->getUrl('*/order_print/bundle', array('_current'=>true)),
            'additional' => array(
                /*'visibility' => array(
                    'name' => 'print',
                    'type' => 'text',
                    'class' => 'input-text',
                    'label' => Mage::helper('catalog')->__('Date Print'),
                ),*/
                'type_page' => array(
                    'name' => 'type_page',
                    'type' => 'select',
                    'class' => 'input-select',
                    'label' => Mage::helper('sales')->__('Type'),
                    'values' => $type,
                )
            )
        ));
    }
}