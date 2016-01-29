<?php

class Emosys_Seller_Block_Dashboard_Today extends Mage_Core_Block_Template
{
    public function getQtyOrdered()
    {
        $collection = Mage::getResourceModel('sales/order_item_collection');
        $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns(array('sku', 'qty' => 'SUM(qty_ordered)', 'total' => 'SUM(row_total)'));

        /* Join */
        $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
            ->where('e.state = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
                ->where('DATE(e.created_at) = CURDATE()');

        /* Filter by customer ID */
        $customer= Mage::getSingleton('customer/session')->getCustomer();
        $collection->getSelect()->where('main_table.customer_id = ?', $customer->getId());

        if ($collection) {
            $result = $collection->getFirstItem();
            if ($result) {
                return number_format( $result->getQty() + 0 );
            }
        }
        return 0;
    }

    public function getTotalPurchased()
    {
        $collection = Mage::getResourceModel('sales/order_item_collection');
        $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns(array('sku', 'qty' => 'SUM(qty_ordered)', 'total' => 'SUM(row_total)'));

        /* Join */
        $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
            ->where('e.state = ?', Mage_Sales_Model_Order::STATE_COMPLETE)
                ->where('DATE(e.created_at) = CURDATE()');

        /* Filter by customer ID */
        $customer= Mage::getSingleton('customer/session')->getCustomer();
        $collection->getSelect()->where('main_table.customer_id = ?', $customer->getId());

        if ($collection) {
            $result = $collection->getFirstItem();
            if ($result) {
                return ( $result->getTotal() + 0 );
            }
        }
        return 0;
    }
}