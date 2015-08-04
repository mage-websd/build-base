<?php
class Emosys_ReviewCustomerg_Block_Adminhtml_Review_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('review_customerg_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('review_customerg/review')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'type'=>'number',
            'align' => 'right',
            'width' => '10px',
            'index' => 'review_id',
        ));
        $this->addColumn('customer_id', array(
            'header' => $this->__('Customer Id'),
            'width' => '100px',
            'index' => 'customer_id',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'align' => 'left',
            'index' => 'name',
            'width' => '200px',
        ));
        $this->addColumn('summary', array(
            'header' => $this->__('Summary'),
            'align' => 'left',
            'index' => 'summary',
            'width' => '200px',
        ));
        $this->addColumn('approved', array(
            'header' => $this->__('Approved'),
            'align' => 'left',
            'width' => '30px',
            'type'      => 'options',
            'index' => 'approved',
            'options'   => array(
                1 => $this->__('Yes'),
                0 => $this->__('No'),
            ),
        ));


        $this->addColumn('action',
            array(
                'header'    =>  $this->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => $this->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => $this->__('Change status'),
                        'url'       => array('base'=> '*/*/changeStatus'),
                        'field'     => 'id',
                        'confirm'  => $this->__('Are you sure?'),
                    ),
                    array(
                        'caption'   => $this->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id',
                        'confirm'  => $this->__('Are you sure?'),
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('review_id');
        $this->getMassactionBlock()->setFormFieldName('review');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => $this->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => $this->__('Are you sure?'),
        ));

        $statuses = array(
            '1' => $this->__('Yes'),
            '0' => $this->__('No'));
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('approved', array(
            'label'=> $this->__('Change approved'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'approved',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => $this->__('Approved'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}