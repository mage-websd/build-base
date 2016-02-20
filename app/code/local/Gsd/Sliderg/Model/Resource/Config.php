<?php
class Gsd_Sliderg_Model_Resource_Config extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('sliderg/config', 'config_id');
    }
}