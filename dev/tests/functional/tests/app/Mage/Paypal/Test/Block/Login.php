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
 * @category    Tests
 * @package     Tests_Functional
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Mage\Paypal\Test\Block;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Element\SimpleElement as Element;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Login to Pay Pal account.
 */
class Login extends Form
{
    /**
     * 'Log in to Pay Pal' button selector.
     *
     * @var string
     */
    protected $submitButton = '.loginBtn';

    /**
     * Loader selector.
     *
     * @var string
     */
    protected $loader = '#spinner';

    /**
     * Click 'Log in to Pay Pal' button.
     *
     * @return void
     */
    public function submit()
    {
        $this->_rootElement->find($this->submitButton)->click();
    }

    /**
     * Fill the root form.
     *
     * @param FixtureInterface $customer
     * @param Element|null $element
     * @return $this
     */
    public function fill(FixtureInterface $customer, Element $element = null)
    {
        $this->waitForElementNotVisible($this->loader);
        return parent::fill($customer, $element);
    }
}
