<?php
class Gsd_Sliderg_Model_Resource_Images extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('sliderg/images', 'image_id');
    }
}