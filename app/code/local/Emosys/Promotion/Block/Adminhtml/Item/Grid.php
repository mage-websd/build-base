<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Adminhtml_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("itemGrid");
        $this->setDefaultSort("item_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("e_promotion/item")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("item_id", array(
            "header" => Mage::helper("adminhtml")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "item_id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("adminhtml")->__("name"),
            "index" => "name",
        ));
        /*
        $this->addColumn("banner", array(
            "header" => Mage::helper("adminhtml")->__("Banner"),
            "index" => "banner",
            'filter'=> false,
            'renderer'  => new Kids_Custom_Block_Adminhtml_Brand_Grid_Renderer_Thumb(),
        ));
        */
        $this->addColumn("small_banner", array(
            "header" => Mage::helper("adminhtml")->__("Banner"),
            "index" => "small_banner",
            'filter'=> false,
            'renderer'  => new Emosys_Promotion_Block_Adminhtml_Item_Edit_Renderer_Banner(),
        ));

        $this->addColumn('start_date', array(
            'header' => Mage::helper('adminhtml')->__('Start Date'),
            'index' => 'start_date',
            'type' => 'date',
        ));
        $this->addColumn("end_date", array(
            "header" => Mage::helper("adminhtml")->__("End date"),
            "index" => "end_date",
            'type' => 'date',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('adminhtml')->__('status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
        ));
        
        $this->addColumn("link", array(
            "header" => Mage::helper("adminhtml")->__("link to page redirect"),
            "index" => "link",
        ));
        /*
        $this->addExportType('*//*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*//*/exportExcel', Mage::helper('sales')->__('Excel'));
        */
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }


    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_item', array(
            'label' => Mage::helper('adminhtml')->__('Remove Item'),
            'url' => $this->getUrl('*/adminhtml_item/massRemove'),
            'confirm' => Mage::helper('adminhtml')->__('Are you sure?')
        ));
        return $this;
    }

    static public function getOptionArray1()
    {
        $data_array = array();
        $data_array[0] = 'All store';
        $data_array[1] = 'Hanoi';
        $data_array[2] = 'Ho Chi Minh';
        return ($data_array);
    }

    static public function getValueArray1()
    {
        $data_array = array();
        foreach (Emosys_Promotion_Block_Adminhtml_Item_Grid::getOptionArray1() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);
    }

    static public function getOptionArray6()
    {
        $data_array = array();
        $data_array[0] = 'No';
        $data_array[1] = 'Yes';
        return ($data_array);
    }

    static public function getValueArray6()
    {
        $data_array = array();
        foreach (Emosys_Promotion_Block_Adminhtml_Item_Grid::getOptionArray6() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return ($data_array);
    }

}