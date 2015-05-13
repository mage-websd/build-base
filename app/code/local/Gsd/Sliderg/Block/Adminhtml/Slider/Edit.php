<?php
class Gsd_Sliderg_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'sliderg';
        $this->_controller = 'adminhtml_slider';
        $this->_mode = 'edit';
        $this->_updateButton('save', 'label', $this->__('Save'));
        $this->_updateButton('delete', 'label', $this->__('Delete'));
        $this->_addButton('saveandcontinue', array(
            'label' => $this->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /*
     * This function is responsible for Including TincyMCE in Head.
     */
    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }


    public function getHeaderText() {
        if (Mage::registry('slider_data') && Mage::registry('slider_data')->getId()) {
            return $this->__('Edit Slider "%s"', $this->htmlEscape(Mage::registry('slider_data')->getTitle()));
        } else {
            return $this->__('New Slider');
        }
    }
}