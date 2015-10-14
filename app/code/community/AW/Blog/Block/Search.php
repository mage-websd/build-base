<?php
class AW_Blog_Block_Search extends AW_Blog_Block_Blog
{
    public function getPosts()
    {
        $query = $this->getRequest()->getParam('q');
        $collection = parent::_prepareCollection();
        $collection->addFieldToFilter('title',array('like'=>"%{$query}%"));

        $tag = $this->getRequest()->getParam('tag');
        if ($tag) {
            $collection->addTagFilter(urldecode($tag));
        }
        parent::_processCollection($collection);
        return $collection;
    }

    protected function _prepareLayout()
    {
        if ($breadcrumbs = $this->getCrumbs()) {
            parent::_prepareMetaData(self::$_helper);
            $breadcrumbs->addCrumb(
                'blog', 
                array(
                    'label' => self::$_helper->getTitle(),
                    'title' => self::$_helper->getTitle(),
                    'link'  => self::$_helper->getRouteUrl(),
                )
            );
            $breadcrumbs->addCrumb(
                'search', 
                array(
                    'label' => $this->__('Search'),
                    'title' => $this->__('Search'),
                )
            );            
        }
    }
}