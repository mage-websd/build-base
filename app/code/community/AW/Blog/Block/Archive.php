<?php

/**
 * Class AW_Blog_Block_Archive
 *
 * get post search year, month
 */
class AW_Blog_Block_Archive extends AW_Blog_Block_Abstract {
    public function getPosts() {
        $collection = parent::_prepareCollection();
        $params = $this->getRequest()->getParams();
        if(isset($params['year']) && $params['year']) {
            $collection->addYearFilter($params['year']);
        }
        if(isset($params['month']) && $params['month']) {
            $collection->addMonthFilter($params['month']);
        }
        if(isset($params['q'])) {
            $collection->addFieldToFilter('title',array('like'=>"%{$params['q']}%"));
        }
        parent::_processCollection($collection);
        return $collection;
    }
    protected function _prepareLayout()
    {
        $breadcrumbs = $this->getCrumbs();
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb(
                'blog',
                array(
                    'label' => self::$_helper->getTitle(),
                    'title' => $this->__('Return to %s', self::$_helper->getTitle()),
                    'link'  => $this->getBlogUrl(),
                )
            );
            if ($date = $this->getRequest()->getParam('date')) {
                $dateArray = explode('-', $date);
                if (isset($dateArray[1])) {
                    $t = $this->__('%s, %s', $this->getFrontDateMonth($dateArray[1]), $dateArray[0]);
                    $breadcrumbs->addCrumb('blog_page', array('label' => $t, 'title' => $t));
                }
                if (isset($dateArray[0])) {
                    $breadcrumbs->addCrumb('blog_page', array('label' => $dateArray[0], 'title' => $dateArray[0]));
                }
            }
        }
    }

    public function getYears()
    {
        $years = Mage::getResourceSingleton('blog/blog_collection')->getYears();
        $result = array();
        if(count($years)) {
            foreach($years as $year) {
                $result[$year['year']] = $year['year'];
            }
        }
        return $result;
    }

    public function getMonth()
    {
        $months = Mage::getResourceSingleton('blog/blog_collection')->getMonths();
        $result = array();
        if(count($months)) {
            foreach($months as $month) {
                $result[$month['month']] = $month['month'];
            }
        }
        return $result;
    }
}
