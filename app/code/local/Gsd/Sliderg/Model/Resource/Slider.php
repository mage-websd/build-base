<?php

class Gsd_Sliderg_Model_Resource_Slider extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('sliderg/slider', 'slider_id');
    }

    /**
     * Load images
     */
    public function loadImage(Mage_Core_Model_Abstract $object)
    {
        return $this->__loadImage($object);
    }

    /**
     * Load images
     */
    public function loadImageForFrontend(Mage_Core_Model_Abstract $object)
    {
        return $this->__loadImageForFrontend($object);
    }

    /**
     * Load thumbnail image
     */
    public function loadThumbnailImageForFrontend(Mage_Core_Model_Abstract $object)
    {
        return $this->__loadThumbnailImageForFrontend($object);
    }

    public function save(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
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

    public function loadConfig($object)
    {
        $configArray = $this->getConfigArray($object);
        if(count($configArray) > 0) {
            foreach($configArray as $config) {
                $object->setData($config['name'], $config['value']);
            }
        }
        return $object;
    }

    public function getConfigArray($object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/config'))
            ->where('slider_id = ?', $object->getId());
        return $this->_getReadAdapter()->fetchAll($select);
    }

    public function getConfig($object,$configName=null)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/config'))
            ->where('slider_id = ?', $object->getId());
        if($configName && is_string($configName)) {
            $select->reset(Zend_Db_Select::COLUMNS);
            $select = $select
                ->columns('value')
                ->where(' name = ? ',$configName);
            return $this->_getReadAdapter()->fetchOne($select);
        }
        $configArray = $this->_getReadAdapter()->fetchAll($select);
        $data = array();
        if(count($configArray) > 0) {
            foreach($configArray as $config) {
                $data[$config['name']] = $config['value'];
            }
        }
        return new Varien_Object($data);
    }

    /**
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
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
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->__saveToImageTable($object);
            $this->__saveToConfigTable($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Call-back function
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('sliderg/images'), 'slider_id=' . $object->getId());
        return parent::_beforeDelete($object);
    }

    /**
     * Load images
     */
    private function __loadImage(Mage_Core_Model_Abstract $object)
    {
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
    private function __loadImageForFrontend(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('sliderg/images'))
            ->where('slider_id = ?', $object->getId())
            ->where('enable = 1');
        if ($object->getIsRandom() == '1') {
            $select->order(array(new Zend_Db_Expr('RAND()'), 'name_rename'));
        } else {
            $select->order(array('position ASC', 'name_rename'));
        }
        $object->setData('front_image', $this->_getReadAdapter()->fetchAll($select));
        return $object;
    }

    /**
     * Load thumbnail Image
     */
    private function __loadThumbnailImageForFrontend(Mage_Core_Model_Abstract $object)
    {
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
    private function __saveToImageTable(Mage_Core_Model_Abstract $object)
    {
        if ($_imageList = $object->getData('images')) {
            $_imageList = Zend_Json::decode($_imageList);
            if (is_array($_imageList) and sizeof($_imageList) > 0) {
                $_imageTable = $this->getTable('sliderg/images');
                $_adapter = $this->_getWriteAdapter();
                $_adapter->beginTransaction();
                try {
                    foreach ($_imageList as &$_item) {
                        if (isset($_item['removed']) and $_item['removed'] == '1') {
                            $_adapter->delete($_imageTable, $_adapter->quoteInto('image_id = ?', $_item['value_id'], 'INTEGER'));
                        } else {
                            $_data = array(
                                'slider_id' => $object->getId(),
                                'name_origin' => $_item['name_origin'],
                                'name_rename' => $_item['label'],
                                'path_media' => $_item['file'],
                                'description' => $_item['descriptionbanner'],
                                'url' => $_item['urlbanner'],
                                'position' => $_item['position'],
                                'enable' => !$_item['disabled'],
                            );
                            if (isset($_item['value_id']) && $_item['value_id']) {
                                $_adapter->update($_imageTable, $_data, "image_id = {$_item['value_id']}");
                            } else {
                                $_adapter->insert($_imageTable, $_data);
                            }
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

    private function __saveToConfigTable(Mage_Core_Model_Abstract $object)
    {
        $prefix = Mage::helper('sliderg')->getPrefixConfigInput();
        if (count($object->getData()) > 0) {
            $configTable = $this->getTable('sliderg/config');
            $_adapter = $this->_getWriteAdapter();
            $_adapter->beginTransaction();
            try {
                $data = array();
                //get config data
                foreach ($object->getData() as $key => $value) {
                    if(!$value) {
                        continue;
                    }
                    if ($prefix == substr($key, 0, strlen($prefix))) {
                        $key = substr($key, strlen($prefix));
                        $data[$key] = array('name'=>$key,'value'=>$value);
                    }
                }
                $configCollection = Mage::getModel('sliderg/config')->getCollection()
                    ->addSliderIdFilter($object->getId())
                    ->addFieldToSelect('config_id')
                    ->addFieldToSelect('name');
                if($configCollection->count() > 0) {
                    //check exists config, update or delete
                    foreach ($configCollection as $config) {
                        if(array_key_exists($config->getName(),$data)) { //update
                            $_adapter->update($configTable, $data[$config->getName()], "config_id = {$config->getId()}");
                            unset($data[$config->getName()]);
                        }
                        else { //delete
                            $_adapter->delete($configTable, $_adapter->quoteInto('config_id = ?', $config->getId(), 'INTEGER'));
                        }
                    }
                }
                if(count($data) > 0) { //insert
                    foreach($data as $_data) {
                        $_data['slider_id'] = $object->getId();
                        $_adapter->insert($configTable, $_data);
                    }
                }
                $_adapter->commit();
            } catch
            (Exception $e) {
                $_adapter->rollBack();
                echo $e->getMessage();
            }
        }
    }
}