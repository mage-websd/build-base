<?php
class Emosys_Print_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getOptionsTitle($product)
    {
        $typeInstance = $product->getTypeInstance(true);
        //$typeInstance->setStoreFilter($product->getStoreId(), $product);
        $optionCollection = $typeInstance->getOptionsCollection($product);
        $selectionCollection = $typeInstance->getSelectionsCollection(
            $typeInstance->getOptionsIds($product),
            $product
        );
        $options = $optionCollection->appendSelections($selectionCollection, false,
            Mage::helper('catalog/product')->getSkipSaleableCheck()
        );
        $title = array();
        foreach ($options as $option) {
            $title[] = $option->getData('default_title');
        }
        return $title;
    }
}