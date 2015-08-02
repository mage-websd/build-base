<?php
class Emosys_ReviewCustomerg_Block_Adminhtml_Review_Edit_Tab_Rating extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        if (Mage::registry('review_data')) {
            $data = Mage::registry('review_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('review_rating', array('legend' => $this->__("Rating")));

        $ratings = Mage::getModel('review_customerg/rating')->getCollection()->setOrder('position','asc');
        $optionRating = array();
        for($i = 1 ; $i < 6 ; $i++) {
            $optionRating[$i] = $i.' '.$this->__('Start');
        }
        foreach($ratings as $rating) {
            $fieldset->addField('rating_id_'.$rating->getId(), 'select', array(
                'label' => $this->__($rating->getRatingName()),
                'name' => 'rating_id_'.$rating->getId(),
                'options'   => $optionRating,
            ));
        }

        $form->setValues($data);
        return parent::_prepareForm();
    }
}