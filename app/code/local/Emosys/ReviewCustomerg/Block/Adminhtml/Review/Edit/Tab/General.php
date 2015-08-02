<?php
class Emosys_ReviewCustomerg_Block_Adminhtml_Review_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        if (Mage::registry('review_data')) {
            $data = Mage::registry('review_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('review_general', array('legend' => $this->__("General")));

        $fieldset->addField('customer_id', 'text', array(
            'label' => $this->__('Customer Id'),
            'class' => 'disable',
            'name' => 'customer_id',
            'disable' => true,
            'readonly' => true,
        ));
        $fieldset->addField('customer_name', 'text', array(
            'label' => $this->__('Customer Name'),
            'class' => 'disable',
            'name' => 'customer_name',
            'disable' => true,
            'readonly' => true,
        ));
        $fieldset->addField('name', 'text', array(
            'label' => $this->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
        ));
        $fieldset->addField('summary', 'text', array(
            'label' => $this->__('Summary'),
            'name' => 'summary',
            'class' => 'required-entry',
            'required' => true,
        ));
        $fieldset->addField('approved', 'select', array(
            'label' => $this->__('Approved'),
            'name' => 'approved',
            'options'   => array('1'=>'Yes','0'=>'No'),
        ));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array('add_variables' => false,
            'add_widgets' => true,
            'add_images' => true,
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
            'files_browser_window_width' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
            'files_browser_window_height' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height')
        ));
        $fieldset->addField('message', 'editor', array(
            'name' => 'message',
            'label' => $this->__('Message'),
            'title' => $this->__('Message'),
            'style' => 'width:700px; height:150px;',
            'config' => $wysiwygConfig,
            'required' => false,
            'wysiwyg' => true,
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}