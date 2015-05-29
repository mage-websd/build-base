<?php
class Gsd_Sliderg_Block_Slider extends Mage_Core_Block_Template
{
    protected $_sliderId;
    protected $_slider;
    protected $_collection;
    protected $_type;

    public function __construct()
    {
        return null;
    }

    public function _beforeToHtml()
    {
        if(!Mage::helper('sliderg')->isModuleEnabled()){
            return null;
        }
        if(!$this->_sliderId) {
            return null;
        }
        $this->_type =  $this->_slider->getConfig('type');
        if(!$this->_type) {
            $this->_type = 'slicebox';
        }
        echo $this->getSkinFile();
        parent::_beforeToHtml();
        if(!$this->getTemplate()) {
            $template = $this->helper('sliderg')->getTemplateFile($this->_type);
            $this->setTemplate($template);
        }
        return $this;
    }

    public function getSkinFile()
    {
        return $this->helper('sliderg')->getSkinFile($this->_type);
    }

    public function getCollection() {
        return $this->_getCollection();
    }

    public function getConfig()
    {
        return $this->_slider->getConfig();
    }
    protected function _getCollection() {
        if ($this->_collection) {
            return $this->_collection;
        }

        $this->_collection = Mage::getModel('sliderg/images')
            ->getCollection()->addEnableFilter()
            ->setOrder('position', Varien_Data_Collection::SORT_ORDER_ASC)
            ->setOrder('name_rename', Varien_Data_Collection::SORT_ORDER_ASC)
            ->addSliderIdFilter($this->_sliderId);
        $max = $this->_slider->getConfig('sliders_max_view');
        if($max && $max > 0) {
            $this->_collection->setPageSize($max);
        }


            //$imageLimit = (int)Mage::getStoreConfig(self::XML_PATH_IMAGE_LIMIT);
            //$this->_collection->setPageSize($imageLimit)->setCurPage(1);
            //$isRandom = (int)Mage::getStoreConfig(self::XML_PATH_IS_RANDOM);
            //if ($isRandom) {
            //    $this->_collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
            //}
        return $this->_collection;
    }



    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    /**
     * more information of slider
     */


    public function setSliderId($sliderId)
    {
        $this->_sliderId = $sliderId;
        $this->_slider = $this->_getModel()->load($sliderId,'code');
        $this->setCode($this->_sliderId);
        if(!$this->_slider->getId()) {
            $this->_slider = null;
            $this->_sliderId = null;
        }
        elseif(!$this->_slider->getEnable()) {
            $this->_slider = null;
            $this->_sliderId = null;
        }
        else {
            $this->_sliderId = $this->_slider->getId();
        }
        return $this;
    }
    public function getSliderId()
    {
        return $this->_sliderId;
    }
    public function getSlider()
    {
        return $this->_slider;
    }

    protected function _getModel()
    {
        return Mage::getModel('sliderg/slider');
    }
}