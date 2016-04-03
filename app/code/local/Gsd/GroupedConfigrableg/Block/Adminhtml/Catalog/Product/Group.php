<?php

class Gsd_GroupedConfigrableg_Block_Adminhtml_Catalog_Product_Group extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Group {
    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Group
     */
    protected function _prepareCollection()
    {
        $allowProductTypes = array();
        $allowProductTypeNodes = Mage::getConfig()
            ->getNode('global/catalog/product/type/grouped/allow_product_types')->children();
        foreach ($allowProductTypeNodes as $type) {
            $allowProductTypes[] = $type->getName();
        }

        $collection = Mage::getModel('catalog/product_link')->useGroupedLinks()
            ->getProductCollection()
            ->setProduct($this->_getProduct())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type_id', $allowProductTypes);

        if ($this->getIsReadonly() === true) {
            $collection->addFieldToFilter('entity_id', array('in' => $this->_getSelectedProducts()));
        }
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
}
