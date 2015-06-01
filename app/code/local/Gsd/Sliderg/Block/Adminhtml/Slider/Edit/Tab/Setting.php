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
        $fieldset->addField('format', 'select', array(
            'label' => $this->__('Format'),
            'name' => $prefix.'format',
            'values' => Mage::getSingleton('sliderg/source_setting_format')->toOptionArray(),
        ));
        $sliderType = $fieldset->addField('type', 'select', array(
            'label' => $this->__('Slider type'),
            'name' => $prefix.'type',
            'values' => Mage::getSingleton('sliderg/source_setting_type')->toOptionArray(),
        ));

        $fieldset->addField('width', 'text', array(
            'label' => $this->__('Width'),
            'name' => $prefix.'width',
            'class' => 'validate-number',
        ));
        $fieldset->addField('height', 'text', array(
            'label' => $this->__('Height'),
            'name' => $prefix.'height',
            'class' => 'validate-number',
        ));

        $fieldset->addField('sliders_per_view', 'text', array(
            'label' => $this->__('Sliders per view'),
            'name' => $prefix.'sliders_per_view',
            'class' => 'validate-number',
        ));
        $fieldset->addField('sliders_max_view', 'text', array(
            'label' => $this->__('Sliders max view'),
            'name' => $prefix.'sliders_max_view',
            'class' => 'validate-number',
        ));
        $fieldset->addField('effect', 'select', array(
            'label' => $this->__('Effect'),
            'name' => $prefix.'effect',
            'values' => Mage::getSingleton('sliderg/source_setting_effect')->toOptionArray(),
        ));
        $fieldset->addField('grab_cursor', 'select', array(
            'label' => $this->__('Grab cursor'),
            'name' => $prefix.'grab_cursor',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('direction', 'select', array(
            'label' => $this->__('Direction'),
            'name' => $prefix.'direction',
            'values' => Mage::getSingleton('sliderg/source_setting_direction')->toOptionArray(),
        ));
        $fieldset->addField('pagination_enable', 'select', array(
            'label' => $this->__('Pagination enable'),
            'name' => $prefix.'pagination_enable',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('pagination_clickable', 'select', array(
            'label' => $this->__('Pagination clickable'),
            'name' => $prefix.'pagination_clickable',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('navigation_enable', 'select', array(
            'label' => $this->__('Navigation enable'),
            'name' => $prefix.'navigation_enable',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('loop', 'select', array(
            'label' => $this->__('Loop'),
            'name' => $prefix.'loop',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('space_between', 'text', array(
            'label' => $this->__('Space between every sliders'),
            'name' => $prefix.'space_between',
            'class' => 'validate-number',
        ));
        $fieldset->addField('centered_slides', 'select', array(
            'label' => $this->__('Centered slides'),
            'name' => $prefix.'centered_slides',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('keyboard_control', 'select', array(
            'label' => $this->__('Keyboard control'),
            'name' => $prefix.'keyboard_control',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('mousewheel_control', 'select', array(
            'label' => $this->__('Mousewheel control'),
            'name' => $prefix.'mousewheel_control',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('autoplay', 'text', array(
            'label' => $this->__('Auto play (ms)'),
            'name' => $prefix.'autoplay',
            'class' => 'validate-number',
        ));
        $fieldset->addField('autoplay_disable_on_interaction', 'select', array(
            'label' => $this->__('Autoplay disable on interaction'),
            'name' => $prefix.'autoplay_disable_on_interaction',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('free_mode', 'select', array(
            'label' => $this->__('Free mode'),
            'name' => $prefix.'free_mode',
            'values' => Mage::getSingleton('sliderg/source_setting_truefalse')->toOptionArray(),
        ));
        $fieldset->addField('setting_more_before', 'textarea', array(
            'label' => $this->__('Setting more before'),
            'name' => $prefix.'setting_more_before',
        ));
        $fieldset->addField('setting_more_inner', 'textarea', array(
            'label' => $this->__('Setting more inner'),
            'name' => $prefix.'setting_more_inner',
        ));
        $fieldset->addField('setting_more_after', 'textarea', array(
            'label' => $this->__('Setting more after'),
            'name' => $prefix.'setting_more_after',
        ));
        $settingHintSb = $fieldset->addField('setting_more_hint_slicebox', 'textarea', array(
            'label' => $this->__('Setting more hint'),
        ));
        $data['setting_more_hint_slicebox'] = '
cuboidsRandom: true,
disperseFactor: 30,
perspective: 1200,
cuboidsCount: 5, //number slicer
cuboidsRandom: false,
maxCuboidsCount: 5,
// each cuboid will move x pixels left / top (depending on orientation).
// The middle cuboid doesn\'t move. the middle cuboid\'s neighbors will
// move disperseFactor pixels
disperseFactor: 0,
// color of the hidden sides
colorHiddenSides: \'#222\',
// the animation will start from left to right. The left most
// cuboid will be the first one to rotate
// this is the interval between each rotation in ms
sequentialFactor: 150,
// animation speed
// this is the speed that takes "1" cuboid to rotate
speed: 600,
// transition easing
easing: \'ease\',
// if true the slicebox will start the animation automatically
autoplay: true,
// time (ms) between each rotation, if autoplay is true
interval: 3000,
// the fallback will just fade out / fade in the items
// this is the time fr the fade effect
fallbackFadeSpeed: 300,
//orientation: \'r\', //v,h,r
';

        $form->setValues($data);
        return parent::_prepareForm();
    }
}