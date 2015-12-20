<?php

class Gsd_SearchAjaxg_Block_Ajaxsearch extends Mage_Core_Block_Template
{
    public function getSearchautocomplete()
    {
        if (!$this->hasData('searchautocomplete')) {
            $this->setData('searchautocomplete', Mage::registry('searchautocomplete'));
        }
        return $this->getData('searchautocomplete');

    }
}
