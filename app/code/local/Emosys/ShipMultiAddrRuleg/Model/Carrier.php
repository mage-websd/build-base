<?php

class Emosys_ShipMultiAddrRuleg_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Carrier's code, as defined in parent class
     *
     * @var string
     */
    protected $_code = 'shipmultiaddrruleg';
    /**
     * Returns available shipping rates for GiangNT Shipping carrier
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');
        if(Mage::app()->getRequest()->getControllerName() != 'multishipping') {
            return $result;
        }

        $hamberShipping = false;
        foreach ($request->getAllItems() as $item) {
            if ($item->getProduct()->getData('is_hamber')) {
                $hamberShipping = true;
                break;
            }
        }
        $subTotal = Mage::helper('shipmultiaddrruleg')->getSubtotal();
        $orderAmount = Mage::helper('shipmultiaddrruleg')->getOrderAmount();
        $orderAmount = (float)$orderAmount;
        if ($hamberShipping) {
            $result->append($this->_getHamberRate());
        }
        else {
            if($subTotal > $orderAmount) {
                if(Mage::registry('ship_multi_addr_rule_amount_first')) {
                    $result->append($this->_getStandardRate());
                }
                else {
                    Mage::register('ship_multi_addr_rule_amount_first',1);
                    $result->append($this->_getStandardFirstRate());
                }
            }
            else {
                $result->append($this->_getStandardRate());
            }
        }
        return $result;
    }
    /**
     * Returns Allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'standard'    =>  'Standard delivery',
            'hamber'     =>  'Hamber delivery',
        );
    }
    /**
     * Get Standard rate object
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getStandardRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('standard');
        $rate->setMethodTitle($this->getConfigData('title_standard'));
        $rate->setPrice(Mage::helper('shipmultiaddrruleg')->getPrice());
        $rate->setCost(0);
        return $rate;
    }

    protected function _getStandardFirstRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('standard_first');
        $rate->setMethodTitle($this->getConfigData('title_standard_free'));
        $rate->setPrice(0);
        $rate->setCost(0);
        return $rate;
    }
    /**
     * Get Express rate object
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getHamberRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('hamber');
        $rate->setMethodTitle($this->getConfigData('title_hamber'));
        $rate->setPrice(0);
        $rate->setCost(0);
        return $rate;
    }
}