<?php
 /**
 *
 * ------------------------------------------------------------------------------
 * @category     MT
 * @package      MT_ProductQuestions
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2008-2013 MagentoThemes.net. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       MagentoThemes.net
 * @email        support@magentothemes.net
 * ------------------------------------------------------------------------------
 *
 */
class MT_ProductQuestions_Model_Mysql4_Categories_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('productquestions/categories');
    }

    public function addVisibilityFilter($visibility = MT_ProductQuestions_Model_Source_Question_Status::STATUS_PUBLIC)
    {
        $this->getSelect()->where('main_table.status=?', $visibility);

        return $this;
    }
    public function addStoreFilter($store=null) {
        if ($store === null) {
            $store = Mage::app()->getStore();
        }
        if(is_numeric($store)) {
            $storeId = $store;
            $store = Mage::app()->getStore($store);
        }
        else {
            $storeId = $store->getId();
        }
        if($store->isAdmin()) {
            return $this;
        }
        $storeAdminId = Mage_Core_Model_App::ADMIN_STORE_ID;
        $this->getSelect()->where("find_in_set('{$storeId}',main_table.stores) OR ".
                "find_in_set('{$storeAdminId}',main_table.stores)");
        return $this;
    }
}