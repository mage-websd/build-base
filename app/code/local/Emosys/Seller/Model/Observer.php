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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    My
 * @package     My_Icustomer
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Layout generat observer
 *
 * @category   My
 * @package    My_Icustomer
 * @author     Theodore Doan <theodore.doan@gmail.com>
 */
class Emosys_Seller_Model_Observer {

    const XML_PATH_ENABLE = "icustomer/general/enable";
    const XML_PATH_IGRNORED_URL = "icustomer/general/ignored_url";
    #const XML_PATH_ERROR = "icustomer/general/error";

    public function checkLogin($observer) {
        if (!Mage::isInstalled()) {
            return false;
        }
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            return false;
        }

        $r = Mage::app()->getRequest();
        $m = $r->getModuleName();
        $c = $r->getControllerName();
        $a = strtolower($r->getActionName());
        if ($m == 'customer' and $c == 'account'
            and ($a == 'login' || $a == 'loginpost'
                || $a == 'forgotpassword' || $a == 'forgotpasswordpost'
                    || $a == 'resetpassword' || $a == 'resetpasswordpost')) {
            return false;
        }

        $ignoredUrl = Mage::getStoreConfig(self::XML_PATH_IGRNORED_URL);
        if (strpos($_SERVER["REQUEST_URI"], $ignoredUrl) === false) {
            $enable = (int) Mage::getStoreConfig(self::XML_PATH_ENABLE);
            if ($enable == 1) {
                $session = $this->_getSession();
                $session->setBeforeLoginUrl(Mage::helper('core/url')->getCurrentUrl());
                #$session->addError(Mage::getStoreConfig(self::XML_PATH_ERROR));

                $response = Mage::app()->getResponse();
                $response->setRedirect(Mage::getModel('core/url')->getUrl('customer/account/login'));
                $response->sendResponse();
            }
        }
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

}