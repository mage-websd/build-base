<?php
class Gsd_Sliderg_Model_Resource_Slider extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct() {
        $this->_init('sliderg/slider', 'slider_id');
    }

    /**
     * Load images
     */
    public function loadImage(Mage_Core_Model_Abstract $object) {
        return $this->__loadImage($object);
    }

    /**
     * Load images
     */
    public function loadImageForFrontend(Mage_Core_Model_Abstract $object) {
        return $this->__loadImageForFrontend($object);
    }

    /**
     * Load thumbnail image
     */
    public function loadThumbnailImageForFrontend(Mage_Core_Model_Abstract $object) {
        return $this->__loadThumbnailImageForFrontend($object);
    }

    public function save(Mage_Core_Model_Abstract $object)
    {
        if(!$object->getId()) {
            $code = $object->getData('code');
            $collection = Mage::getModel('sliderg/slider')->getCollection()
                ->addFieldToFilter('code', $code)
                ->addFieldToSelect('slider_id');
            if ($collection->count() > 0) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Code is exists'));
                return false;
            }
        }
        return parent::save($object);
    }

    /**
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassDelete()) {
            $object = $this->__loadImage($object);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @return Zend_Db_Select
     */
    /*protected function _getLoadSelect($field, $value, $object) {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($data = $object->getStoreId()) {
            $select->join(
                array('store' => $this->getTable('igallery/banner_store')), $this->getMainTable().'.banner_id = `store`.banner_id')
                ->where('`store`.store_id in (0, ?) ', $data);
        }
        $select->order('name DESC')->limit(1);

        return $select;
    }*/

    /**
     * Call-back function
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassStatus()) {
            $this->__saveToImageTable($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Call-back function
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('sliderg/images'), 'slider_id='.$object->getId());
        return parent::_beforeDelete($object);
    }

    /**
     * Load images
     */
    private function __loadImage(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/images'))
            ->where('slider_id = ?', $object->getId())
            ->order(array('position ASC', 'name_rename'));
        $object->setData('image', $this->_getReadAdapter()->fetchAll($select));
        return $object;
    }

    /**
     * Load images
     */
    private function __loadImageForFrontend(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/images'))
            ->where('slider_id = ?', $object->getId())
            ->where('enable = 1');
        if ($object->getIsRandom() == '1') {
            $select->order(array(new Zend_Db_Expr('RAND()'),'name_rename'));
        } else {
            $select->order(array('position ASC', 'name_rename'));
        }
        $object->setData('front_image', $this->_getReadAdapter()->fetchAll($select));
        return $object;
    }

    /**
     * Load thumbnail Image
     */
    private function __loadThumbnailImageForFrontend(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/images'))
            ->where('slider_id = ?', $object->getId())
            ->where('enable = 1')
            //->where('thumb = 1')
            ->order(array('position ASC', 'name_rename'));

        $object->setData('thumbnail_image', new Gsd_Sliderg_Model_Slider($this->_getReadAdapter()->fetchRow($select)));
        return $object;
    }

    /**
     * Save to image table
     */
    private function __saveToImageTable(Mage_Core_Model_Abstract $object) {
        if ($_imageList = $object->getData('images')) {
            $_imageList = Zend_Json::decode($_imageList);
            if (is_array($_imageList) and sizeof($_imageList) > 0) {
                $_imageTable = $this->getTable('sliderg/images');
                $_adapter = $this->_getWriteAdapter();
                $_adapter->beginTransaction();
                try {
                    $condition = $this->_getWriteAdapter()->quoteInto('slider_id = ?', $object->getId());
                    $this->_getWriteAdapter()->delete($_imageTable, $condition);
                    foreach ($_imageList as &$_item) {
                        if (isset($_item['removed']) and $_item['removed'] == '1') {
                            //$_adapter->delete($_imageTable, $_adapter->quoteInto('image_id = ?', $_item['value_id'], 'INTEGER'));
                        } else {
                            $_data = array(
                                'slider_id' => $object->getId(),
                                'name_origin' => $_item['name_origin'],
                                'name_rename'     => $_item['label'],
                                'path_media'      => $_item['file'],
                                'description' => $_item['descriptionbanner'],
                                'url' => $_item['urlbanner'],
                                'position'  => $_item['position'],
                                'enable'  => !$_item['disabled'],
                            );
                            $_adapter->insert($_imageTable, $_data);
                        }
                    }
                    $_adapter->commit();
                } catch (Exception $e) {
                    $_adapter->rollBack();
                    echo $e->getMessage();
                }
            }
        }
    }
}