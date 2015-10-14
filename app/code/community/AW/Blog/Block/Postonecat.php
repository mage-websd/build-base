<?php
class AW_Blog_Block_Postonecat extends AW_Blog_Block_Cat
{
    public function getPosts()
    {
        $category = $this->getCategory();

        if (!$category->getCatId()) {
            return false;
        }
        $collection = parent::_prepareCollection()->addCatFilter($category->getCatId());
        parent::_processCollection($collection, $category);
        return $collection;
    }

    public function getCategory()
    {
        $_idCategory = $this->getCategoryId();
        if($_idCategory) {
            return Mage::getModel('blog/cat')->load($_idCategory);
        }
        return Mage::getSingleton('blog/cat');
    }
}