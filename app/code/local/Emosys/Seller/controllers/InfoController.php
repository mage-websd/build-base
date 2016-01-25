<?php
class Emosys_Seller_InfoController extends Mage_Core_Controller_Front_Action
{
	protected function _initLayout()
	{
		$this->loadLayout();
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs
				->addCrumb('home', array(
					'label'=> $this->__('Home'), 
					'title'=> $this->__('Home'), 
					'link' => Mage::getUrl(),
					)
				);
			}
    }
	
	public function indexAction()
	{
		$this->_initLayout();
		$this->getLayout()->getBlock('head')
			->setTitle($this->__('Click to buy'));
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs
				->addCrumb('seller_profile', array(
					'label'=> $this->__('Seller Profile'), 
					'title'=> $this->__('Seller Profile'), 
					)
				);
		}
		$this->renderLayout();
	}
	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id');
		if(!$id) {
			$this->_redirect('*/*/index');
			return;
		}
		$customer = Mage::getModel('customer/customer')->load($id);
		if(!$customer->getId()) {
			$this->_redirect('*/*/index');
			return;
		}
		Mage::register('current_customer',$customer);
		$this->_initLayout();
		$this->getLayout()->getBlock('head')
			->setTitle($this->__('%s Profile - Click to buy',$customer->getName()));
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs
				->addCrumb('seller_profile', array(
					'label'=> $this->__('Seller Profile'), 
					'title'=> $this->__('Seller Profile'), 
					'link' => Mage::getUrl('seller/info/view',array('id'=>$id)),
					)
				);
				$breadcrumbs
					->addCrumb('seller_name', array(
						'label'=> $customer->getName(), 
						'title'=> $customer->getName(), 
						)
					);
		}
		$this->renderLayout();
	}

	public function reviewAction()
	{
		$id = $this->getRequest()->getParam('id');
        if(!$id) {
            $this->_redirect('*/*/index');
            return;
        }
        $customer = Mage::getModel('customer/customer')->load($id);
		if(!$customer->getId()) {
			$this->_redirect('*/*/index');
			return;
		}
        $this->_initLayout();
		$this->getLayout()->getBlock('head')
			->setTitle($this->__('Review list of %s - Click to buy',$customer->getName()));
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs
				->addCrumb('seller_profile', array(
					'label'=> $this->__('Seller Profile'), 
					'title'=> $this->__('Seller Profile'), 
					'link' => Mage::getUrl('seller/info/index'),
					)
				);
				$breadcrumbs
					->addCrumb('seller_name', array(
						'label'=> $customer->getName(), 
						'title'=> $customer->getName(), 
						'link' => Mage::getUrl('seller/info/view',array('id'=>$id)),
						)
					);
				$breadcrumbs
					->addCrumb('seller_review', array(
						'label'=> $this->__('Review'), 
						'title'=> $this->__('Review'),
						)
					);
		}
        $this->renderLayout();
	}
}