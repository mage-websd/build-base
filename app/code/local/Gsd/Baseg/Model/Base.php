<?php

class Gsd_Baseg_Model_Base {

    //add store, column in db format: 0,1,2,3,4,5
    public function addStoresFilter($store = null) {
        if ($store === null) {
            $store = Mage::app()->getStore();
        }
        if (is_numeric($store)) {
            $storeId = $store;
            $store = Mage::app()->getStore($store);
        } else {
            $storeId = $store->getId();
        }
        if ($store->isAdmin()) {
            return $this;
        }
        $storeAdminId = Mage_Core_Model_App::ADMIN_STORE_ID;
        $this->getSelect()->where("find_in_set('{$storeId}',main_table.stores) OR " .
                "find_in_set('{$storeAdminId}',main_table.stores)");
        return $this;
    }

}
