<?php
class Gsd_Catalogg_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$collection = Mage::helper('catalogg')->getSale(884);
		
		//Mage::helper('baseg')->printG($collection);

		Mage::helper('baseg')->printG(Mage::helper('catalogg')->getTimestampUtil(884));
	}
}