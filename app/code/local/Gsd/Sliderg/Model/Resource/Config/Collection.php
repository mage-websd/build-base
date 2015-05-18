<?php
class Gsd_Sliderg_Model_Resource_Config_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('sliderg/config');
    }

    public function addSliderIdFilter($sliderId) {
        $this->getSelect()->where('main_table.slider_id = ?', $sliderId);
        return $this;
    }
}