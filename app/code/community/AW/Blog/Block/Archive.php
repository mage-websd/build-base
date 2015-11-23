<?php

/**
 * Class AW_Blog_Block_Archive
 *
 * get post search year, month
 */
class AW_Blog_Block_Archive extends AW_Blog_Block_Abstract {
	protected $_year;
	protected $_month;

	public function __construct()
	{
		parent::__construct();
		$params = $this->getRequest()->getParams();
		if(isset($params['year']) && $params['year']) {
            $this->_year = $params['year'];
        }
        if(isset($params['month']) && $params['month']) {
            $this->_month = $params['month'];
        }
	}

    public function getPosts() {
        $collection = parent::_prepareCollection();
        if($this->_year) {
        	$collection->addYearFilter($this->_year);
        }
        if($this->_month) {
        	$collection->addMonthFilter($this->_month);
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
            if($this->_year) {
        		$breadcrumbs->addCrumb(
	                'blog_year',
	                array(
	                    'label' => $this->_year,
	                    'title' => $this->_year,
	                    'link'  => $this->getBlogUrl().'index/archive/year/'.$this->_year,
	                )
	            );
            }
            if($this->_month) {
        		$breadcrumbs->addCrumb(
	                'blog_month',
	                array(
	                    'label' => $this->_month,
	                    'title' => $this->_month,
	                )
	            );
            }
        }
    }

    public function getYear()
    {
    	return $this->_year;
    }
    public function getMonth()
    {
    	return $this->_month;
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

    public function getMonths()
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
