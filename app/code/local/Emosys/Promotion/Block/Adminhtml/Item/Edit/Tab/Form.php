<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Block_Adminhtml_Item_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("item_form", array("legend" => Mage::helper("adminhtml")->__("Item information")));

        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("adminhtml")->__("Name"),
            "class" => "required-entry",
            "required" => true,
            "name" => "name",
        ));

        /*$fieldset->addField("html", "editor", array(
            "label" => Mage::helper("adminhtml")->__("HTML"),
            "class" => "required-entry",
            "required" => false,
            "name"      => "html",
            'style'     => 'height:15em',
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg'   => true,
        ));

        $thumb = $fieldset->addField('banner', 'text', array(
            'label' => Mage::helper('adminhtml')->__('Banner'),
            'name' => 'banner',
            "class" => "required-entry",
            'note' => '(*.jpg, *.png, *.gif)',
        ));
        $form->getElement('banner')->setRenderer(
            $this->getLayout()->createBlock('mtext/adminhtml_widget_form_element_browser', '', array(
                'element' => $thumb
            ))
        );
        $fieldset->addField("link", "text", array(
            "label" => Mage::helper("adminhtml")->__("Link for Banner"),
            "class" => "required-entry",
            "required" => true,
            "name" => "link",
        ));
        */

        $smallBanner = $fieldset->addField('small_banner', 'text', array(
            'label' => Mage::helper('adminhtml')->__('Mini banner before menu'),
            'name' => 'small_banner',
            'note' => '(*.jpg, *.png, *.gif)',
        ));
        $form->getElement('small_banner')->setRenderer(
            $this->getLayout()->createBlock('mtext/adminhtml_widget_form_element_browser', '', array(
                'element' => $smallBanner
            ))
        );
        $fieldset->addField("link_mini", "text", array(
            "label" => Mage::helper("adminhtml")->__("Link for Mini Banner"),
            "class" => "required-entry",
            "required" => true,
            "name" => "link_mini",
        ));

        /*$fieldset->addField('bg_color', 'text', array(
            'label' => Mage::helper('adminhtml')->__('Background Color'),
            'name' => 'bg_color',
            'note' => 'Color code (eg: #EE1C24)',
        ));

        $fieldset->addField('display', 'select', array(
            'label' => Mage::helper('adminhtml')->__('Display'),
            'values' => Mage::getSingleton('e_promotion/system_config_source_display')->toOptionArray(),
            'name' => 'display',
            "class" => "required-entry",
            "required" => true,
        ));*/

        $fieldset->addField('start_date', 'date', array(
            'label' => Mage::helper('adminhtml')->__('Start Date'),
            'name' => 'start_date',
            "class" => "required-entry",
            "required" => true,
            'time' => true,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => 'yyyy-MM-dd'
        ));
        $fieldset->addField("end_date", "date", array(
            "label" => Mage::helper("adminhtml")->__("End Date"),
            "class" => "required-entry",
            "required" => true,
            "name" => "end_date",
            'time' => true,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => 'yyyy-MM-dd'
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('adminhtml')->__('Published'),
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'name' => 'status',
            "class" => "required-entry",
            "required" => true,
        ));

        $fieldset->addField('store', 'select', array(
            'label' => Mage::helper('adminhtml')->__('Store'),
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),//Emosys_Promotion_Block_Adminhtml_Item_Grid::getValueArray1(),
            'name' => 'store',
            "class" => "required-entry",
            "required" => true,
        ));

        if (Mage::getSingleton("adminhtml/session")->getData('e_promotion_item_data')) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getData('e_promotion_item_data'));
            Mage::getSingleton("adminhtml/session")->setData('e_promotion_item_data',null);
        } elseif (Mage::registry("e_promotion_item_data")) {
            $form->setValues(Mage::registry("e_promotion_item_data")->getData());
        }
        return parent::_prepareForm();
    }
}
