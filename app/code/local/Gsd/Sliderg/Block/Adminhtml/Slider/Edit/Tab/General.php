<?php

class Gsd_Sliderg_Block_Adminhtml_Slider_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            //$this->getLayout()->getBlock('head')->addJs('mage/adminhtml/browser.js');
            //js/mage/adminhtml/browser.js
        }
    }

    protected function _prepareForm() {

        if (Mage::registry('slider_data')) {
            $data = Mage::registry('slider_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_general', array('legend' => $this->__("General")));

        $fieldset->addField('title', 'text', array(
            'label' => $this->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
        $afterHtml = '';
        if (Mage::registry('slider_data') && Mage::registry('slider_data')->getId()) {
            $code = Mage::registry('slider_data')->getCode();
            $afterHtml = '<div><small>insert code \'<b>{{block type="sliderg/slider" name="your_block_name" slider_id="' . $code . '"}}</b>\' in content<br/>' .
                    'or \'<b>&lt;block type="sliderg/slider" name="your_block_name"&gt;&lt;action method="setSliderId"&gt;&lt;slider_id>' . $code . '&lt;/slider_id&gt;&lt;/action&gt;&lt;/block></b>\' in xml to show slider</small></div>';
        }
        $fieldset->addField('code', 'text', array(
            'label' => $this->__('Code'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'code',
            'after_element_html' => $afterHtml,
        ));
        $fieldset->addField('enable', 'select', array(
            'label' => $this->__('Enable'),
            'name' => 'enable',
            'options' => array('1' => 'Yes', '0' => 'No'),
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(/*array('add_variables' => false,
            'add_widgets' => true,
            'add_images' => true,
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
            'files_browser_window_width' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
            'files_browser_window_height' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height')
        )*/);
        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => $this->__('Description'),
            'title' => $this->__('Description'),
            'style' => 'width:700px; height:150px;',
            'config' => $wysiwygConfig,
            'required' => false,
            'wysiwyg' => true
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }

}
