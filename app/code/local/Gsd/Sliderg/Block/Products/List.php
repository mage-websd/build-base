<?php
class Gsd_Sliderg_Block_Products_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_typeCollection;
    protected $_collection;
    protected $_type;
    protected $_sliderMaxViewDefault = 5;

    public function _beforeToHtml()
    {
        if(!$this->_typeCollection) {
            return null;
        }
        echo $this->getSkinFile();
        parent::_beforeToHtml();
        if(!$this->getTemplate()) {
            $template = $this->helper('sliderg')->getTemplateFileProducts($this->_type);
            $this->setTemplate($template);
        }
        return $this;
    }

    public function getSkinFile()
    {
        return $this->helper('sliderg')->getSkinFile($this->_type);
    }

    protected function _getCollection() {
        if ($this->_collection) {
            return $this->_collection;
        }

        switch($this->_typeCollection) {
            case 'upsell':
                $this->callUpsell();
                break;
            case 'crosssell':
                $this->callCrossSell();
                break;
            case 'related':
                $this->callRelated();
                break;
            default:
                $this->callRandom();
                break;
        }

        $this->setCollection($this->_collection);
        return $this->_collection;
    }

    public function callUpsell()
    {
        if(!$product = Mage::registry('product')) {
            return null;
        }
        /* @var $product Mage_Catalog_Model_Product */
        $this->_collection = $product->getUpSellProductCollection()
            ->setPositionOrder()
            ->addStoreFilter();
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Checkout')) {
            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($this->_collection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );
            $this->_addProductAttributesAndPrices($this->_collection);
        }
        //Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_itemCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_collection);
        $maxNumber = $this->getConfig('sliders_max_view') ? $this->getConfig('sliders_max_view') : $this->_sliderMaxViewDefault;
        $this->_collection->setPageSize($maxNumber);
        $this->_collection->load();
        foreach ($this->_collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    public function callCrossSell()
    {
        if(!$product = Mage::registry('product')) {
            return null;
        }
        /* @var $product Mage_Catalog_Model_Product */
        $this->_collection = $product->getCrossSellProductCollection()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->setPositionOrder()
            ->addStoreFilter();
        //Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_itemCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_collection);
        $maxNumber = $this->getConfig('sliders_max_view') ? $this->getConfig('sliders_max_view') : $this->_sliderMaxViewDefault;
        $this->_collection->setPageSize($maxNumber);
        $this->_collection->load();
        foreach ($this->_collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    public function callRelated()
    {
        if(!$product = Mage::registry('product')) {
            return null;
        }
        $this->_collection = $product->getRelatedProductCollection()
            ->addAttributeToSelect('required_options')
            ->setPositionOrder()
            ->addStoreFilter()
        ;

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Checkout')) {
            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($this->_collection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );
            $this->_addProductAttributesAndPrices($this->_collection);
        }
//        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($this->_itemCollection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_collection);
        $maxNumber = $this->getConfig('sliders_max_view') ? $this->getConfig('sliders_max_view') : $this->_sliderMaxViewDefault;
        $this->_collection->setPageSize($maxNumber);
        $this->_collection->load();
        foreach ($this->_collection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;
    }

    public function callRandom()
    {
        $collection = Mage::getResourceModel('catalog/product_collection');
        Mage::getModel('catalog/layer')->prepareProductCollection($collection);
        $collection->getSelect()->order('rand()');
        $collection->addStoreFilter();
        $maxNumber = $this->getConfig('sliders_max_view') ? $this->getConfig('sliders_max_view') : $this->_sliderMaxViewDefault;
        $this->_collection->setPageSize($maxNumber);
        $this->_collection = $collection;
        return $this;
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    /**
     * more information of slider
     */
    public function setTypeCollection($typeCollection='random')
    {
        if(!Mage::helper('sliderg')->isProductsEnable()){
            $this->_reset();
            return null;
        }
        $this->_typeCollection = $typeCollection;
        return $this;
    }
    public function getConfig($path3)
    {
        return Mage::getStoreConfig("sliderg/{$this->_typeCollection}/{$path3}");
    }
    public function setType($type='swiper') {
        $this->_type = $type;
        return $this;
    }
    public function getType()
    {
        return $this->_type;
    }
    public function getTypeCollection()
    {
        return $this->_typeCollection;
    }
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this;
    }
    public function getCollection()
    {
        return $this->_getCollection();
    }
    protected function _reset()
    {
        $this->_collection = null;
        $this->_typeCollection = null;
        $this->_type = null;
        $this->setCollection(null);
        return null;
    }
}