<?php
class MagenThemes_Megamenu_Block_Megacategory extends Mage_Core_Block_Template
{
    public function _construct(){
        parent::_construct();
        $this->setTemplate('kidsplaza/catalog/view.phtml');
    }
    public function getCurrentCategory()
    {
        return $this->getCategory();
    }

    public function getChildrenCategories($category)
    {
        $categories = array();
        $megacategory = Mage::helper('megamenu/megacategory')->getMegacategory($category);
        $childCollection = Mage::getModel('megamenu/megacategory')->getCollection();
        $childCollection->addFieldToFilter('parent_id',$megacategory->getId())->addFieldToFilter('type','category');
        $childCollection->setOrder('position');
        foreach($childCollection as $child){
            $category = Mage::getModel('catalog/category')->load($child->getArticle());
            $category->setData('mega_title',$child->getTitle());
            $categories[$child->getId()] = $category;
        }
        return $categories;
    }
    public function getChildrenMegaCategories($id)
    {
        $categories = array();
        $childCollection = Mage::getModel('megamenu/megacategory')->getCollection();
        $childCollection->addFieldToFilter('parent_id',$id);
        $childCollection->setOrder('position');
        foreach($childCollection as $child){
            if($child->getType()=="category"){
                $category = Mage::getModel('catalog/category')->load($child->getArticle());
                $categories[$child->getId()] = array(
                    'url'  => $category->getUrl(),
                    'name' => $child->getTitle()
                );
            }
            if($child->getType()=="external_link"){
                $categories[$child->getId()] = array(
                    'url'  => $child->getUrl(),
                    'name' => $child->getTitle()
                );
            }
        }
        return $categories;
    }
}