<?php
/****************************************************** 
 * @author http://www.9magentothemes.com
 * @copyright (C) 2012- 9MagentoThemes.Com
 * @license PHP files are GNU/GPL
*******************************************************/
?>
<?php
class MagenThemes_Megamenu_Model_Mysql4_Megacategory extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {     
        $this->_init('megamenu/megacategory', 'megacategory_id');
    }
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object) { 
    	
        return parent::_afterLoad($object);
    }
    
    protected function _afterSave(Mage_Core_Model_Abstract $object) 
    {    
    	if($object->getParent_id()==0){
    		$condition = $this->_getWriteAdapter()->quoteInto('megacategory_id = ?', $object->getId());
    		$this->_getWriteAdapter()->delete($this->getTable('megacategory_store'), $condition);
    		if (!$object->getData('store_id')) {
    			$storeArray = array();
    			$storeArray['megacategory_id'] = $object->getId();
    			$storeArray['store_id'] = Mage::app()->getStore(true)->getId();
    			$this->_getWriteAdapter()->insert($this->getTable('megacategory_store'), $storeArray);
    		} else {
    			foreach ((array) $object->getData('store_id') as $store) {
    				$storeArray = array();
    				$storeArray['megacategory_id'] = $object->getId();
    				$storeArray['store_id'] = $store;
    				$this->_getWriteAdapter()->insert($this->getTable('megacategory_store'), $storeArray);
    			}
    		}
    	} 
        return parent::_afterSave($object);
    } 
    
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object); 
        if ($object->getStoreId()) {
            $select->join(array('cbs' => $this->getTable('megamenu/megacategory_store')), $this->getMainTable().'.megacategory_id = cbs.megacategory_id')
                    ->where('cbs.store_id in (0, ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
		if($object->getParent_id()==0){
	     	$adapter = $this->_getReadAdapter();
	    	$adapter->delete($this->getTable('megamenu/megacategory_store'), 'megacategory_id='.$object->getId());
    	}
    	return parent::_beforeDelete($object);
    }
}