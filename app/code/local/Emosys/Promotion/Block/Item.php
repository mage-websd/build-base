<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Item extends Mage_Core_Block_Template
{
    protected $_limit = 1;
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate('emosys/promotion/items.phtml');
    }
    public function getItems(){
        $collection = Mage::getResourceModel('e_promotion/item_collection')
            ->addStoreFilter()
            ->addStatusFilter()
            ->addDateFilter();
        return $collection;
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $collection = $this->getItems();
        $this->setCollection($collection);
        $pager = $this->getLayout()->createBlock('page/html_pager', 'promotion.images.pager');
        $this->_pageVarName = $pager->getPageVarName();
        /*$pager->setAvailableLimit(
                    array(1=>1,2=>2,5=>5,10=>10,)
                );*/
        $pager->setLimit($this->_limit);
        $pager->setCollection($collection);
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}