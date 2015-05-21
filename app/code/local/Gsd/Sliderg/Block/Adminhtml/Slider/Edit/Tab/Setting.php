<?php
class Gsd_Sliderg_Block_Adminhtml_Slider_Edit_Tab_Setting extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm() {
        $prefix = Mage::helper('sliderg')->getPrefixConfigInput();
        if (Mage::registry('slider_data')) {
            $data = Mage::registry('slider_data')->getData();
        } else {
            $data = array();
        }
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('slider_setting', array('legend' => $this->__("Setting")));
        $fieldset->addField('type', 'text', array(
            'label' => $this->__('Slider type'),
            'name' => $prefix.'type',
        ));

        $fieldset->addField('column_count', 'text', array(
            'label' => $this->__('Column Count'),
            'name' => $prefix.'column_count',
            'class' => 'validate-number',
        ));
        $fieldset->addField('column_max', 'text', array(
            'label' => $this->__('Column Max'),
            'name' => $prefix.'column_max',
            'class' => 'validate-number',
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}