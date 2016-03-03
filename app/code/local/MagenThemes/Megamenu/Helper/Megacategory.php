<?php
class MagenThemes_Megamenu_Helper_Megacategory extends Mage_Core_Helper_Abstract
{
    public function getMegacategory($category)
    {
        $megaCategorys = Mage::getModel('megamenu/megacategory')->getCollection();
        $megaCategorys->addFieldToFilter('article',$category->getId());
        $megaCategorys->addFieldToFilter('type','category');
        $megaCategory = $megaCategorys->getFirstItem();
        return $megaCategory;
    }
    public function hasChildren($category)
    {
        $megaCategory = $this->getMegacategory($category);
        $childCollection = Mage::getModel('megamenu/megacategory')->getCollection();
        $childCollection->addFieldToFilter('parent_id',$megaCategory->getId())->addFieldToFilter('type','category');
        if($childCollection->count()>0 && $megaCategory->getShowSub()==1){
            return true;
        }
        return false;
    }
}