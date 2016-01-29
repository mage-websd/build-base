<?php

class Emosys_Seller_Block_Dashboard_Income extends Mage_Core_Block_Template
{
    public function getTotalPurchased($date = 'week')
    {
        $collection = Mage::getResourceModel('sales/order_item_collection');
        $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns(array('qty' => 'SUM(qty_ordered)', 'total' => 'SUM(row_total)'));

        /* Join */
        $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
            ->where('e.state = ?', Mage_Sales_Model_Order::STATE_COMPLETE);
        if ($date == 'week') {
            $collection->getSelect()->where('WEEK(e.created_at) = ( WEEK( CURDATE() ) - 1 ) ')
				->where('YEAR(e.created_at) = YEAR( CURDATE() )');
        } elseif ($date == 'month') {
			$lastMonth = strtotime("-1 month");
            $collection->getSelect()->where('MONTH(e.created_at) = ?', date('n', $lastMonth))
				->where('YEAR(e.created_at) = ?', date('Y', $lastMonth));
        } elseif ($date == 'year') {
            $collection->getSelect()->where('YEAR(e.created_at) = ( YEAR( CURDATE() ) - 1 )');
        } elseif ($date == 'this_week') {
            $collection->getSelect()->where('WEEK(e.created_at) = WEEK( CURDATE() ) ')
                ->where('YEAR(e.created_at) = YEAR( CURDATE() )');
        } elseif ($date == 'this_month') {
            $collection->getSelect()->where('MONTH(e.created_at) = ?', date('n'))
                ->where('YEAR(e.created_at) = ?', date('Y', $lastMonth));
        } elseif ($date == 'this_year') {
            $collection->getSelect()->where('YEAR(e.created_at) = YEAR( CURDATE() )');
        }

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