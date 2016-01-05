<?php
class Gsd_TaxCaculatorg_Model_Observer extends Mage_Sales_Model_Quote_Address_Total_Abstract{
    public function calculatePartialPrice($observer) {
        $quote = $observer->getEvent()->getQuote();

        $grandTotal = $quote->getGrandTotal();
        $baseGrandTotal = $quote->getBaseGrandTotal();

        $totals = $quote->getTotals();

        $subtotal = $totals['subtotal'];
        $subtotalExclTax = $subtotal->getData('value_excl_tax');

        $discount = $totals["discount"]->getValue();

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


        $quote->setGrandTotal(20);
        $quote->setBaseGrandTotal(20);

        $quote->setSubtotal(10);
        $quote->setBaseSubtotal(10);

        /*$quote->setSubtotalWithDiscount(0);
        $quote->setBaseSubtotalWithDiscount(0);*/
        $quote->collectTotals()->save();
        $quote->save();
    }
}