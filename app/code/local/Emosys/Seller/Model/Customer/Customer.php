<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Customer
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer model
 *
 * @category    Mage
 * @package     Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Emosys_Seller_Model_Customer_Customer extends Mage_Customer_Model_Customer
{
    /**#@+
     * Configuration pathes for email templates and identities
     */
    const XML_PATH_SELLER_APPROVAL = 'seller/general/seller_approval';

    /**
     * Check if accounts confirmation is required in config
     *
     * @return bool
     */
    public function isConfirmationRequired()
    {
        if (Mage::app()->getStore()->isAdmin() || Mage::getDesign()->getArea() == 'adminhtml') {
            return parent::isConfirmationRequired();
        }

        if ( $this->getConfirmation() && ($this->getIsVendor() == '1') ) {
            $storeId = $this->getStoreId() ? $this->getStoreId() : null;
            return (bool)Mage::getStoreConfig(self::XML_PATH_SELLER_APPROVAL, $storeId);
        }
        return parent::isConfirmationRequired();
    }
}