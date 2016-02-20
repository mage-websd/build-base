<?php
class Gsd_TaxCaculatorg_Model_Observer extends Mage_Sales_Model_Quote_Address_Total_Abstract{
    public function calculatePartialPrice($observer) {
        if(Mage::app()->getRequest()->isXmlHttpRequest()) {
            return;
        }
        $quote = $observer->getEvent()->getQuote();
        if(!$quote) {
            return;
        }
        $totals = $quote->getTotals();
        if(!$totals) {
            return;
        }
        $quote->setGrandTotal(10);
        $quote->setBaseGrandTotal(10);
        $quote->save();
        return;
        $grandTotal = $quote->getGrandTotal();
        $baseGrandTotal = $quote->getBaseGrandTotal();
        if(isset($totals["discount"]) && $totals["discount"]) {
            $discount = $totals["discount"]->getValue();
        }
        else {
            $discount = 0;
        }
        $subtotal = $totals['subtotal'];
        $subtotalExclTax = $subtotal->getData('value_excl_tax');

        $grandTotalExcl = $subtotalExclTax - abs((float)$discount);
        $tax = $grandTotalExcl * 0.06;
        $grandTotalIncl = $grandTotalExcl + $tax;

        /*$calculator     = Mage::getSingleton('tax/calculation');
        $calculator->setCustomer($quote->getCustomer());

        $taxRateRequest = $calculator->getRateRequest(
            $quote->getShippingAddress(),
            $quote->getBillingAddress(),
            $quote->getCustomerTaxClassId(),
            Mage::app()->getStore()->getId()
        );

        $taxRateRequest->setProductClassId(2);

        $rate = $calculator->getRate($taxRateRequest);
        var_dump($grandTotalExcl,$rate);

        exit;*/


        /*$quote->setGrandTotal(20);
        $quote->setBaseGrandTotal(20);*/

        $quote->setSubtotal(10);
        $quote->setBaseSubtotal(10);

        /*$quote->setSubtotalWithDiscount(0);
        $quote->setBaseSubtotalWithDiscount(0);*/
        //$quote->collectTotals()->save();
        $quote->save();
    }

    public function salesQuoteCollectTotalsBefore($observer) {
        return;
        if(Mage::app()->getRequest()->isXmlHttpRequest()) {
            return;
        }
        $quote = $observer->getEvent()->getQuote();
        if(!$quote) {
            return;
        }
        $totals = $quote->getTotals();
        if(!$totals) {
            return;
        }
        $quote->setSubtotal(10);
        $quote->setBaseSubtotal(10);
        //$quote->setTotalsCollectedFlag(false)->collectTotals();
        $quote->save();
    }

    public function salesQuoteSaveBefore($observer) {
        return;
        $quote = $observer->getEvent()->getQuote();
        if(!$quote) {
            return;
        }
        $totals = $quote->getTotals();
        if(!$totals) {
            return;
        }
        $quote->setSubtotal(10);
        $quote->setBaseSubtotal(10);
        $quote->setTotalsCollectedFlag(false)->collectTotals();
        session_write_close();
        //$quote->save();
    }
}