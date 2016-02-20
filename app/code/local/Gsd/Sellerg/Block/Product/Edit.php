<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales order history block
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Gsd_Sellerg_Block_Product_Edit extends Mage_Core_Block_Template
{
    protected $_categoriesProduct = array();

    public function attributes()
    {
        $product = $this->getProduct();
        $fieldShow = $this->fieldsShow();
        $fieldShowCodes = $this->_getValuesOfArray2($fieldShow);
        if($product) {
            $entityTypeId = $product->getEntityTypeId();
        }
        else {
            $entityTypeId = Mage::getModel('eav/entity')->setType(Mage_Catalog_Model_Product::ENTITY)->getTypeId();
        }
        $eavAttribute = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entityTypeId)
            ->addFieldToFilter('attribute_code', array('in'=>$fieldShowCodes));
        if($this->getProduct()) {
            $eavAttribute = $eavAttribute->setAttributeSetFilter($this->getProduct()->getAttributeSetId());
        }
        $eavAttributeSelect = clone $eavAttribute->getSelect();
        $eavAttributeSelect->reset(Zend_Db_Select::COLUMNS);
        $eavAttributeSelect->columns(array(
            'attribute_id',
            'attribute_code',
            'frontend_input',
            'frontend_label',
            'source_model',
            'is_required',
            'additional_table.apply_to',
            'additional_table.is_wysiwyg_enabled',
        ));
        $resource = Mage::getModel('core/resource');
        $connect = $resource->getConnection('core_read');
        $result = $connect->fetchAll((string)($eavAttributeSelect));
        $eavAttribute = array();
        foreach($result as $code => $data) {
            $eavAttribute[$data['attribute_code']] = $data;
        }
        return $eavAttribute;
    }

    public function fieldsShow()
    {
        $fieldShow = array(
            array(
                'name',
                'sku',
            ),
            array(
                'type_id' => array(
                    'source_model' => 'sellerg/source_config_typeallow',
                    'attribute_code' => 'product_type',
                    'frontend_input' => 'select',
                    'frontend_label' => 'Type',
                    'is_required' => 1,
                    'apply_to' => -1,
                    'is_wysiwyg_enabled' => 0,
                ),
                'attribute_set_id'=> array(
                    'source_model' => 'sellerg/source_config_setallow',
                    'attribute_code' => 'product_set',
                    'frontend_input' => 'select',
                    'frontend_label' => 'Attribute Set',
                    'is_required' => 1,
                    'apply_to' => -1,
                    'is_wysiwyg_enabled' => 0,
                ),
            ),
            array(
                'weight',
                'country_of_manufacture',
            ),
            array(
                'status',
                'visibility',
            ),
            array(
                'price',
                'special_price',
            ),
            array(
                'tax_class_id',
            ),
            array(
                'special_from_date',
                'special_to_date',
            ),
            array(
                'inventory_stock_availability',
                'qty',
            ),
            array(
                'short_description',
            ),
            array(
                'description',
            ),
            array(
                'color',
            ),
        );
        return $fieldShow;
    }

    public function getAttributeOptions($code)
    {
        $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($code)->getFirstItem();
        $attributeId = $attributeInfo->getAttributeId();
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $attributeOptions = $attribute->getSource()->getAllOptions(false);
        return $attributeOptions;
    }

    public function getCountryOptions()
    {
        return Mage::getResourceModel('directory/country_collection')
            ->loadData()
            ->toOptionArray(false);
    }

    public function getClassTax()
    {
        return Mage::getModel('tax/class_source_product')->getAllOptions();
        /*return Mage::getModel('tax/class')
            ->getCollection()
            ->addFieldToFilter('class_type','PRODUCT');*/
    }

    public function getProduct()
    {
        return Mage::registry('current_product_seller');
    }

    public function getFormData()
    {
        if(Mage::registry('current_product_seller')) {
            return Mage::registry('current_product_seller');
        }
        return new Varien_Object();
    }

    public function isConfigurableProduct()
    {
        if($_product = $this->getProduct()) {
            if($_product->getTypeId() == 'configurable') {
                return true;
            }
        }
        return false;
    }

    public function getMaxUploadImage()
    {
        return Mage::getStoreConfig('seller/product/max_upload_image') ? Mage::getStoreConfig('seller/product/max_upload_image') : 5;
    }


    /**
     * return string menu category format html
     *
     * @return string
     */
    public function getCategoryTree()
    {
        if($this->getProduct()) {
            $this->_categoriesProduct = $this->getProduct()->getCategoryIds();
        }
        $rootCategoryID = Mage::app()->getStore()->getRootCategoryId();
        $html ='';
        $html .= '<ul class="category-tree">';
        $html .= $this->_getSubCategory($rootCategoryID,0);
        $html .= '</ul>';
        return $html;
    }

    /**
     * get category and all sub category
     *   format html
     * 
     * @param $idCategory: id category
     * @param $no: #no category same level
     * @return string
     */
    protected function _getSubCategory($idCategory, $no)
    {
        $html = '';
        $category = $this->_getModelCategory()->load($idCategory);
        /*if(!$category->getData('include_in_menu') || !$category->getData('is_active'))
            return '';*/
        $level = $category->getData('level');
        if ($level > 1) {
            $checked = '';
            if(in_array($idCategory, $this->_categoriesProduct)) {
                $checked = ' checked';
            }
            $input = '<input type="checkbox" name="category_ids[] class="input-checkbox" value="'.$idCategory.'"'.$checked.' id="tree-'.$category->getId().'"/>';
            $optionTagli = ' class="level'.($level-2).' no-'.$no.'"'; // add class for li
            $html .= '<li' . $optionTagli . '>'.
                '<div class="input-box">'.$input.'<label for="tree-'.$category->getId().'">' . $category->getData('name').'</label></div>';
        }
        $categoriesSub = $category->getChildren();
        if ($categoriesSub) {
            if($level > 1) {
                $optionTagul = ' class="level'.($level-2).' no-'.$no.'"'; //add class for ul
                $html .= '<ul'.$optionTagul.' style="margin-left: '.(($level-1)*50).'px;">';
            }
            $noSub = 0;
            foreach (explode(',', $categoriesSub) as $chidId) {
                $html .= $this->_getSubCategory($chidId,$noSub); // call recursive with sub category
                $noSub++;
            }
            $html .= '</ul>';
        }
        if ($level > 1) {
            $html .= '</li>';
        }
        return $html;
    }
    
    /**
     * get model catalog/category
     *
     * @return false|Mage_Core_Model_Abstract
     */
    private function _getModelCategory()
    {
        return Mage::getModel('catalog/category');
    }

    protected function _getValuesOfArray2($array)
    {
        $result = array();
        if(count($array)) {
            foreach ($array as $value1) {
                if(count($value1)) {
                    foreach ($value1 as $key2 => $value2) {
                        if(is_array($value2)) {
                            $result[] = $key2;
                        }
                        else {
                            $result[] = $value2;
                        }
                    }
                }
            }
        }
        return $result;
    }
}
