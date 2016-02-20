<?php
class Emosys_ReviewCustomerg_Block_Adminhtml_Review_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('review_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define above
        $this->setTitle($this->__('Review Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => $this->__('General'),
            'title' => $this->__('General'),
            'content' => $this->getLayout()->createBlock('review_customerg/adminhtml_review_edit_tab_general')->toHtml(),
        ));
        $this->addTab('form_section1', array(
            'label' => $this->__('Rating'),
            'title' => $this->__('Rating'),
            'content' => $this->getLayout()->createBlock('review_customerg/adminhtml_review_edit_tab_rating')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}