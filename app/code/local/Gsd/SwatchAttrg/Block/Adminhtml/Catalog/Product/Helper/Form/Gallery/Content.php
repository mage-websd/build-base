<?php

class Gsd_SwatchAttrg_Block_Adminhtml_Catalog_Product_Helper_Form_Gallery_Content extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content {

    /**
     * This method has been overridden merely for the purpose of setting up a new view file
     * to be used in place of the default theme folder.
     *
     * @see app/code/core/Mage/Core/Block/Mage_Core_Block_Template#fetchView($fileName)
     */
    public function fetchView($fileName) {
        extract($this->_viewVars);
        $do = $this->getDirectOutput();

        if (!$do) {
            ob_start();
        }
        require Mage::getModuleDir('', 'Gsd_SwatchAttrg').DS.'blocks/Adminhtml/catalog/product/helper/gallery.phtml';
        if (!$do) {
            $html = ob_get_clean();
        } else {
            $html = '';
        }

        return $html;
    }

}
