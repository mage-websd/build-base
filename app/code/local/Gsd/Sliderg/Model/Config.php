<?php
class Gsd_Sliderg_Model_Config extends Mage_Core_Model_Abstract
{
    public function _construct() {
        $this->_init('sliderg/config');
    }
    public function getId()
    {
        return $this->getConfigId();
    }
}