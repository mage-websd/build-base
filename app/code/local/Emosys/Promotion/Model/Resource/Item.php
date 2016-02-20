<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init("e_promotion/item", "item_id");
    }
}