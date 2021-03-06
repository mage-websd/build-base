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


class AW_Sarp2_Block_Adminhtml_Profile_View_Tab_Info extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('Info');
    }

    public function getTabTitle()
    {
        return $this->__('Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getReferenceHtml()
    {
        return $this->getChildHtml('reference');
    }

    public function getPurchaseHtml()
    {
        return $this->getChildHtml('purchase');
    }

    public function getScheduleHtml()
    {
        return $this->getChildHtml('schedule');
    }

    public function getPaymentsHtml()
    {
        return $this->getChildHtml('payments');
    }

    public function getBillingBlock()
    {
        return $this->getChild('billing');
    }

    public function getShippingBlock()
    {
        return $this->getChild('shipping');
    }
}