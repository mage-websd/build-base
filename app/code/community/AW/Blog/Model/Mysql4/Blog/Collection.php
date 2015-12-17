<?php

class AW_Blog_Model_Mysql4_Blog_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/blog');
    }

    public function addEnableFilter($status)
    {
        $this->getSelect()->where('main_table.status = ?', $status);
        return $this;
    }

    public function addPresentFilter()
    {
        $this->getSelect()->where('main_table.created_time<=?', Mage::getSingleton('core/date')->date('Y-m-d H:i:s'));
        return $this;
    }

    public function addCatFilter($catId)
    {
        $this
            ->getSelect()
            ->join(
                array('cat_table' => $this->getTable('post_cat')), 'main_table.post_id = cat_table.post_id', array()
            )
            ->where('cat_table.cat_id = ?', $catId)
        ;

        return $this;
    }

    public function addCatsFilter($catIds)
    {
        $this
            ->getSelect()
            ->join(
                array('cat_table' => $this->getTable('post_cat')), 'main_table.post_id = cat_table.post_id', array()
            )
            ->where('cat_table.cat_id IN (' . $catIds . ')')
            ->group('cat_table.post_id')
        ;
        return $this;
    }

    public function joinComments()
    {
        $select = new Zend_Db_Select($connection = Mage::getSingleton('core/resource')->getConnection('read'));
        $select
            ->from(
                Mage::getSingleton('core/resource')->getTableName('blog/comment'),
                array('post_id', 'comment_count' => new Zend_Db_Expr('COUNT(IF(status = 2, post_id, NULL))'))
            )
            ->group('post_id')
        ;

        $this
            ->getSelect()
            ->joinLeft(
                array('comments_select' => $select),
                'main_table.post_id = comments_select.post_id',
                'comment_count'
            )
        ;
        return $this;
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     *
     * @return Mage_Cms_Model_Mysql4_Page_Collection
     */
    public function addStoreFilter($store = null)
    {
        if ($store === null) {
            $store = Mage::app()->getStore()->getId();
        }
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this
                ->getSelect()
                ->joinLeft(
                    array('store_table' => $this->getTable('store')),
                    'main_table.post_id = store_table.post_id',
                    array()
                )
                ->where('store_table.store_id in (?)', array(0, $store))
            ;
        }
        return $this;
    }

    public function addTagFilter($tag)
    {
        if ($tag = trim($tag)) {
            $whereString = sprintf(
                "main_table.tags = %s OR main_table.tags LIKE %s OR main_table.tags LIKE %s OR main_table.tags LIKE %s",
                $this->getConnection()->quote($tag), $this->getConnection()->quote($tag . ',%'),
                $this->getConnection()->quote('%,' . $tag), $this->getConnection()->quote('%,' . $tag . ',%')
            );
            $this->getSelect()->where($whereString);
        }
        return $this;
    }

    //more than core
    public function groupByMonthAndYear() {
        $this->getSelect()->columns(
            array(
                'date_month' => 'MONTH(created_time)',
                'date_year' => 'YEAR(created_time)',
                'total_post' => 'COUNT(*)')
        )
            ->group('MONTH(created_time)')
            ->group('YEAR(created_time)')
            ->order('YEAR(created_time) DESC')
            ->order('MONTH(created_time) DESC');
        return $this;
    }
    public function getYears() {
        $select = $this->getSelect();
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::ORDER);
        $select->group('YEAR(created_time)')
            ->order('YEAR(created_time) DESC');
        $select->columns(array(
            'year' => 'YEAR(created_time)'));
        return $this->getConnection()->fetchAll($select);
    }
    public function getMonths() {
        $select = $this->getSelect();
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::ORDER);
        $select->group('MONTH(created_time)')
            ->order('MONTH(created_time) DESC');
        $select->columns(array(
            'month' => 'MONTH(created_time)'));
        return $this->getConnection()->fetchAll($select);
    }
    public function addMonthFilter($month) {
        $this->getSelect()->where('MONTH(`main_table`.created_time) = ?', $month);

        return $this;
    }
    public function addYearFilter($year) {
        $this->getSelect()->where('YEAR(`main_table`.created_time) = ?', $year);

        return $this;
    }
}
