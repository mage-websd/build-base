<?php
class Emosys_Print_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
	protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        $type = array(
            array('value'=>0,'label'=>Mage::helper('catalog')->__('Individual')),
            array('value'=>1,'label'=>Mage::helper('catalog')->__('A4')),
        );
        $this->getMassactionBlock()->addItem('print', array(
            'label'=> Mage::helper('catalog')->__('Print Bundle Product'),
            'url'  => $this->getUrl('*/print/index', array('_current'=>true)),
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
                        'label' => Mage::helper('catalog')->__('Type'),
                        'values' => $type,
                     )
            )
        ));
    }
}