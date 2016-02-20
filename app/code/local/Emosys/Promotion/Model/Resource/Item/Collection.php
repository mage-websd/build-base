<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Model_Resource_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    public function _construct(){
        $this->_init("e_promotion/item");
    }

    public function addStoreFilter($store=null){
        $stores = array(0);
        if($store!==null){
            $stores[] = $store;
        }
        $this->addFieldToFilter('store',array('in'=>$stores));
        return $this;
    }
    public function addStatusFilter($status){
        $this->addFieldToFilter('status',$status);
        return $this;
    }
    public function addDateFilter(){
        $date = date('Y-m-d');
        $this->addFieldToFilter('start_date',array('gteq'=>$date));
        $this->addFieldToFilter('end_date',array('lteq'=>$date));
        return $this;
    }
}
