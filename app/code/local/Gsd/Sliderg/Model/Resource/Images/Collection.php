<?php
class Gsd_Sliderg_Model_Resource_Images_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('sliderg/images');
    }

    /**
     * Add Filter by status
     *
     * @param int $status
     * @return My_Igallery_Model_Mysql4_Banner_Image_Collection
     */
    public function addEnableFilter($status = 1) {
        $this->getSelect()->where('main_table.enable = ?', $status);
        return $this;
    }
}