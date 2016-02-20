<?php
class AW_Blog_Model_Source_Setting_Categories
{
    public function toOptionArray($id)
    {
        $_categoriesOption = array(
            array('value' => '0', 'label' => Mage::helper('sliderg')->__('----')),
        );
        $_categoies = Mage::getModel('blog/cat')->getCollection();
        foreach($_categoies as $_category) {
            if($_category->getCatId() == $id) {
                continue;
            }
            $_categoriesOption[] = array('value'=>$_category->getCatId(),'label'=>$_category->getTitle());
        }
        return $_categoriesOption;
    }
}