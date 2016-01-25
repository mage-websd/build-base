<?php
/**
* 
*/
class Emosys_Seller_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
	protected $_setVarName     = 'set';
	protected $_availableSet = array();

	protected function _construct()
	{
		$this->_availableSet[$this->_getBuySet()] = 'Buy';
		$this->_availableSet[$this->_getRentSet()] = 'Rent';
		parent::_construct();
	}

	protected function _getBuySet()
	{
		return Mage::getStoreConfig('seller/product/for_buy');
	}
	protected function _getRentSet()
	{
		return Mage::getStoreConfig('seller/product/for_rent');
	}
	public function getAvailableSet()
	{
		return $this->_availableSet;
	}
	public function getSetVarName()
	{
		return $this->_setVarName;
	}
	/**
     * Get grit products sort order field
     *
     * @return string
     */
    public function getCurrentSet()
    {
        $set = $this->_getData('_current_grid_set');
        if ($set) {
            return $set;
        }
        $sets = $this->getAvailableSet();
        $set = $this->getRequest()->getParam($this->getSetVarName());
        if ($set && isset($sets[$set])) {
        } 
        else {
            $set = null;
        }
        $this->setData('_current_grid_set', $set);
        return $set;
    }

    /**
     * Compare defined order field vith current order field
     *
     * @param string $order
     * @return bool
     */
    public function isSetCurrent($set)
    {
        return ($set == $this->getCurrentSet());
    }
    /**
     * Retrieve Pager URL
     *
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function getSetUrl($set)
    {
        if (is_null($set)) {
            $set = $this->getCurrentSet() ? $this->getCurrentSet() : $this->getAvailableSet[$this->_getBuySet()];
        }
        return $this->getPagerUrl(array(
            $this->getSetVarName()=>$set,
            $this->getPageVarName() => null
        ));
    }
}