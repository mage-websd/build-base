<?php

class Gsd_SearchAjaxg_Model_Resource_Search extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {    
        // Note that the searchautocomplete_id refers to the key field in your database table.
        $this->_init('searchajaxg/search', 'search_id');
    }
}
