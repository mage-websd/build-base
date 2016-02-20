<?php
class MT_ProductQuestions_Model_Source_Question_Categories
{
	public function toOptionArray() {
        $categories = Mage::getModel('productquestions/categories')->getCollection();
        $result = array(
            array('value' => '', 'label' => Mage::helper('productquestions')->__('----')),
        );
        foreach($categories as $category) {
            $result[] = array(
                'value'=>$category->getData('cat_id'),
                'label'=>$category->getName(),
            );
        }
        return $result;
    }
}
