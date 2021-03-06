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
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales order history block
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Gsd_Sellerg_Block_Product_List extends Mage_Core_Block_Template
{
    protected $_pageVarName = 'p';
    protected $_lastPagerNum = null;
    protected $_limit = 5;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('sellerg/product/list.phtml');

        $_productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getId())
            ->addAttributeToSort('created_at', 'DESC')
            ->addAttributeToSelect('*')
            ->addStoreFilter()
            ;

        $this->setCollection($_productCollection);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'seller.product.pager');
        $this->_pageVarName = $pager->getPageVarName();
        /*$pager->setAvailableLimit(
                    array(1=>1,2=>2,5=>5,10=>10,)
                );*/
        $pager->setLimit($this->_limit);
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        $this->_lastPagerNum = $pager->getLastPageNum();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function getPagerBlock()
    {
        return $this->getLayout()->getBlock('seller.product.pager');
    }

    public function getPageVarName()
    {
        return $this->_pageVarName;
    }
    public function getLastPageNum()
    {
        return $this->_lastPagerNum;
    }
    public function setLimit($_limit)
    {
        $this->_limit = $_limit;
        return $this;
    }
    public function getLimit()
    {
        return $this->_limit;
    }
    public function getAttributeSetName($_product)
    {
        return Mage::getModel('eav/entity_attribute_set')->load($_product->getAttributeSetId())->getAttributeSetName();
    }
}
