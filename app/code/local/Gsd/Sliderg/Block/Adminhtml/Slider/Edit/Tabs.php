<?php
class Gsd_Sliderg_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define above
        $this->setTitle($this->__('News Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => $this->__('General'),
            'title' => $this->__('General'),
            'content' => $this->getLayout()->createBlock('sliderg/adminhtml_slider_edit_tab_general')->toHtml(),
        ));

        $this->addTab('form_section1', array(
            'label' => $this->__('Setting'),
            'title' => $this->__('Setting'),
            'content' => $this->getLayout()->createBlock('sliderg/adminhtml_slider_edit_tab_setting')->toHtml(),
        ));

        if(Mage::registry('slider_data') && Mage::registry('slider_data')->getId()) {
            $this->addTab('form_section2', array(
                'label' => $this->__('Images'),
                'title' => $this->__('Images'),
                'content' => $this->getLayout()->createBlock('sliderg/adminhtml_slider_edit_tab_images')->toHtml(),
            ));
        }

        return parent::_beforeToHtml();
    }
}