<?php

class Emosys_ReviewCustomerg_Model_Resource_Review extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('review_customerg/review', 'review_id');
    }

    public function loadRating($object)
    {
        $ratingArray = $this->getRatingArray($object);
        if (count($ratingArray) > 0) {
            foreach ($ratingArray as $rating) {
                $object->setData('rating_id_' . $rating['rating_id'], $rating['value']);
            }
        }
        return $object;
    }

    public function getRatingArray($object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('review_customerg/ratingentity'))
            ->where('review_id = ?', $object->getId());
        return $this->_getReadAdapter()->fetchAll($select);
    }

    protected function _beforeSave($object)
    {
        $object->setUpdatedAt(Mage::getSingleton('core/date')->date());
        return parent::_beforeSave($object);
    }
    /**
     * Call-back function
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->__saveToRating($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Call-back function
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('review_customerg/ratingentity'), 'review_id=' . $object->getId());
        return parent::_beforeDelete($object);
    }

    private function __saveToRating($object)
    {
        $prefix = 'rating_id_';
        if (count($object->getData()) > 0) {
            $ratingTable = $this->getTable('review_customerg/ratingentity');
            $_adapter = $this->_getWriteAdapter();
            $_adapter->beginTransaction();
            try {
                $data = array();
                foreach ($object->getData() as $key => $value) {
                    if (!$value) {
                        continue;
                    }
                    if ($prefix == substr($key, 0, strlen($prefix))) {
                        $key = substr($key, strlen($prefix));
                        $data[$key] = $value;
                    }
                }
                if(count($data) > 0) {
                    $ratingCollection = Mage::getModel('review_customerg/ratingentity')->getCollection()
                        ->addFieldToFilter('review_id', $object->getId());
                    if ($ratingCollection->count() > 0) {
                        //check exists rating, update or insert
                        foreach ($ratingCollection as $rating) {
                            if (array_key_exists($rating->getRatingId(), $data)) { //update
                                $_adapter->update($ratingTable, array("value" => $data[$rating->getRatingId()]), "rating_entity_id = {$rating->getId()}");
                                unset($data[$rating->getRatingId()]);
                            }
                        }
                    }
                    //insert
                    if (count($data) > 0) {
                        foreach ($data as $ratingId => $ratingValue) {
                            $_adapter->insert($ratingTable, array(
                                    'review_id' => $object->getId(),
                                    'rating_id' => $ratingId,
                                    'value' => $ratingValue,
                                )
                            );
                        }
                    }
                    $_adapter->commit();
                }
            } catch
            (Exception $e) {
                $_adapter->rollBack();
                echo $e->getMessage();
            }
        }
    }
}