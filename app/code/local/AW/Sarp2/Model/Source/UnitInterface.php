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


interface AW_Sarp2_Model_Source_UnitInterface
{
    const DEFAULT_DAY      = 'Day';
    const DEFAULT_WEEK     = 'Week';
    const DEFAULT_MONTH    = 'Month';
    const DEFAULT_YEAR     = 'Year';

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray();

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray();

    /**
     * Translates subscription engine period units to default units
     *
     * @param string $unit
     * @return array['unit', 'qty'] || false
     */
    public function toDefaultUnit($unit);
}