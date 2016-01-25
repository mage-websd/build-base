<?php

class Emosys_Seller_Block_Dashboard_Orders extends Mage_Core_Block_Template
{
    public function getQtyOrdered($state = 'complete')
    {
        $collection = Mage::getResourceModel('sales/order_item_collection');
        $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns(array('qty' => 'SUM(qty_ordered)', 'total' => 'SUM(row_total)'));

        /* Join */
        if ($state == 'complete' || $state == 'completed') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_COMPLETE);
        } elseif ($state == 'pending' || $state == 'new') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_NEW);
        } elseif ($state == 'processing') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_PROCESSING);
        } elseif ($state == 'canceled' || $state = 'cancelled') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_CANCELED);
        } elseif ($state == 'holded' || $state = 'onhold') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_HOLDED);
        } elseif ($state == 'review' || $state = 'payment_review') {
            $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
                ->where('e.state = ?', Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
        }

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

    public function getOrderedCollection()
    {
		$date     = Mage::app()->getRequest()->getParam('date');
        $dateFrom = Mage::app()->getRequest()->getParam('date_from');
        $dateTo   = Mage::app()->getRequest()->getParam('date_to');

        $collection = Mage::getResourceModel('sales/order_item_collection');

        /* Join */
        $collection->getSelect()->join(array('e' => 'sales_flat_order'),'main_table.order_id = e.entity_id')
            ->where('e.state = ?', Mage_Sales_Model_Order::STATE_COMPLETE);

        /* Date diff */
        if ($date) {
    		if ($date == 'yesterday' || $date == 'today') {
    			$collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
    				->columns(
    					array(
    						'time_ordered' => 'HOUR(e.created_at)',
    						'qty' => 'SUM(qty_ordered)',
    						'total' => 'SUM(row_total)'))
    					->group(array('HOUR(e.created_at)'));
    		} else {
    			$collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
    				->columns(
    					array(
    						'time_ordered' => "DATE_FORMAT(e.created_at, '%b %d')",
    						'qty' => 'SUM(qty_ordered)',
    						'total' => 'SUM(row_total)'))
    					->group(array('DATE(e.created_at)'));
    		}
    		if ($date == 'yesterday') {
                $collection->getSelect()->where('DATEDIFF(CURDATE(), e.created_at) = 1');
            } elseif ($date == 'last_week') {
                $collection->getSelect()->where('YEARWEEK(e.created_at) = YEARWEEK( CURDATE() ) - 1');
            } elseif ($date == 'last_month') {
    			$lastMonth = strtotime("-1 month");
                $collection->getSelect()->where('MONTH(e.created_at) = ?', date('n', $lastMonth))
    				->where('YEAR(e.created_at) = ?', date('Y', $lastMonth));
            } elseif ($date == 'last_year') {
                $collection->getSelect()->where('YEAR(e.created_at) = YEAR( CURDATE() ) - 1');
            } elseif ($date == 'current_week') {
                $collection->getSelect()->where('YEARWEEK(e.created_at) = YEARWEEK( CURDATE() )');
            } elseif ($date == 'current_month') {
                $collection->getSelect()->where('MONTH(e.created_at) = MONTH( CURDATE() )')
    				->where('YEAR(e.created_at) = YEAR( CURDATE() )');
            } elseif ($date == 'current_year') {
                $collection->getSelect()->where('YEAR(e.created_at) = YEAR( CURDATE() )');
            } else {
                $collection->getSelect()->where('DATE(e.created_at) = CURDATE()');
            }
        } elseif ($dateFrom || $dateTo) {
            $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)
                ->columns(
                    array(
                        'time_ordered' => "DATE_FORMAT(e.created_at, '%b %d')",
                        'qty' => 'SUM(qty_ordered)',
                        'total' => 'SUM(row_total)'))
                    ->group(array('DATE(e.created_at)'));

            if ($dateFrom) {
                $date = explode('/', $dateFrom);
                if ( isset($date[2]) ) {
                    $collection->getSelect()->where('e.created_at >= ?', $date[2] .'-'. $date[1] .'-'. $date[0]);
                }
            }
            if ($dateTo) {
                $date = explode('/', $dateTo);
                if ( isset($date[2]) ) {
                    $collection->getSelect()->where('e.created_at <= ?', $date[2] .'-'. $date[1] .'-'. $date[0]);
                }
            }
        }

        /* Filter by customer ID */
        $customer= Mage::getSingleton('customer/session')->getCustomer();
        $collection->getSelect()->where('main_table.customer_id = ?', $customer->getId());

        return $collection;
    }

    public function getOrderedLabel()
    {
        $date	  = Mage::app()->getRequest()->getParam('date');
        $dateFrom = Mage::app()->getRequest()->getParam('date_from');
        $dateTo   = Mage::app()->getRequest()->getParam('date_to');

        /* Date diff */
		if ($date) {
			if ($date == 'yesterday') {
				return $this->__('Yesterday');
			} elseif ($date == 'last_week') {
				return $this->__('Last Week');
			} elseif ($date == 'last_month') {
				return $this->__('Last Month');
			} elseif ($date == 'last_year') {
				return $this->__('Last Year');
			} elseif ($date == 'current_week') {
				return $this->__('Week to Date');
			} elseif ($date == 'current_month') {
				return $this->__('Month to Date');
			} elseif ($date == 'current_year') {
				return $this->__('Year to Date');
			}
		} elseif ($dateFrom || $dateTo) {
			if ($dateFrom and $dateTo) {
				return $this->__('From %s - %s', $dateFrom, $dateTo);
			} elseif ($dateFrom) {
				return $this->__('From %s - %s', $dateFrom, Mage::helper('core')->formatDate(time(), 'short', false));
			} elseif ($dateTo) {
				return $this->__('To %s', $dateTo);
			}
		}
        return $this->__('Today');
    }

	public function getOrderedUrl($date = 'today') {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = array('date' => $date);

        return $this->getUrl('*/*/*', $urlParams);
	}

	public function getDisplayUrl($display = 'total_amount') {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = array('display' => $display);

        return $this->getUrl('*/*/*', $urlParams);
	}
}