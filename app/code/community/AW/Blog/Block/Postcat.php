<?php
class AW_Blog_Block_Postcat extends AW_Blog_Block_Abstract
{
    protected $_post;

    public function getPosts()
    {
        $collection = parent::_prepareCollection();
        if($this->_post) {
            $cats = $this->_post->getCategoriesId();            
            if(count($cats)) {
                $catsId = '';
                foreach ($cats as $cat) {
                    $catsId .= $cat['cat_id'].',';
                }
                $catsId = substr($catsId,0,-1);
                $collection->addCatsFilter($catsId);
            }
        }
        parent::_processCollection($collection);
        return $collection;
    }

    public function setPost($_post)
    {
        if(!is_object($_post)) {
            $_post = Mage::getModel('blog/post')->load($_post);
        }
        $this->_post = $_post;
        return $this;
    }
}