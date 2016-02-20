<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Sarp2
 * @version    2.2.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


/**
 * Nominal tax total
 */
class AW_Sarp2_Model_Sales_Quote_Address_Total_Subscription_Tax extends Mage_Tax_Model_Sales_Total_Quote_Tax
{
    /**
     * Don't add amounts to address
     *
     * @var bool
     */
    protected $_canAddAmountToAddress = false;

    /**
     * Custom row total key
     *
     * @var string
     */
    protected $_itemRowTotalKey = 'tax_amount';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $store = $address->getQuote()->getStore();
        $shippingIncludesTax = Mage::getSingleton('tax/config')->shippingPriceIncludesTax($store);

        if (Mage::helper('aw_sarp2/config')->isTaxOnShippingEnabled($store)) {
            if ($address->getShippingTaxable() || $address->getBaseShippingTaxable()) {
                $items = $this->_getAddressItems($address);
                foreach ($items as $item) {
                    $item->setShippingAmount($address->getShippingTaxable());
                    $item->setBaseShippingAmount($address->getBaseShippingTaxable());
                }
            }
            if ($address->getShippingTaxAmount() != 0 || $address->getBaseShippingTaxAmount() != 0) {
                $items = $this->_getAddressItems($address);
                foreach ($items as $item) {
                    if (!$shippingIncludesTax) {
                        $item->setTaxAmount($item->getTaxAmount() + $address->getShippingTaxAmount());
                        $item->setBaseTaxAmount($item->getBaseTaxAmount() + $address->getBaseShippingTaxAmount());
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Don't fetch anything
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return array
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        return Mage_Sales_Model_Quote_Address_Total_Abstract::fetch($address);
    }

    /**
     * Get subscription items only
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return array
     */
    protected function _getAddressItems(Mage_Sales_Model_Quote_Address $address)
    {
        return Mage::helper('aw_sarp2/quote')->getAllSubscriptionItemsFromQuote($address->getQuote());
    }
}
