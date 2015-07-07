<?php

class AW_Blog_Block_Manage_Blog_Edit_Tab_Media extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('media_form', array('legend' => Mage::helper('blog')->__('Media')));

        $fieldset->addField(
            'image',
            'image',
            array(
                'name'  => 'image',
                'label' => Mage::helper('blog')->__('Image'),
                'title' => Mage::helper('blog')->__('Image'),
            )
        );

        $fieldset->addField(
            'thumbnail',
            'image',
            array(
                'name'  => 'thumbnail',
                'label' => Mage::helper('blog')->__('Thumbnail'),
                'title' => Mage::helper('blog')->__('Thumbnail'),
            )
        );


        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ($data = Mage::registry('blog_data')) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }

        return parent::_prepareForm();
    }
}