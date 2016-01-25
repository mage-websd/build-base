<?php
class Emosys_Seller_ReviewController extends Mage_Core_Controller_Front_Action
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
        $this->loadLayout();
        $this->renderLayout();
    }
    public function addPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirectReview();
            return;
        }
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('seller/review');
            $ratings = Mage::getResourceModel('seller/rating_collection')->setOrder('position','asc');
            foreach($ratings as $rating) {
                if(!isset($data['rating_id_'.$rating->getId()]) || !$data['rating_id_'.$rating->getId()]) {
                    Mage::getSingleton('core/session')->addError($this->__('Please fill full data required!'));
                    $this->_redirectReview();
                    return;
                }
            }
            if(!$data['name'] || !$data['summary']) {
                Mage::getSingleton('core/session')->addError($this->__('Please fill full data required!'));
                $this->_redirectReview();
                return;
            }
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(',', $this->getRequest()->getParam($key));
                }
            }
            $model->setData($data);
            try {
                $model->save();
                if (!$model->getId()) {
                    Mage::getSingleton('core/session')->addError($this->__('Error saving review details'));
                }
                else {
                    Mage::getSingleton('core/session')->addSuccess($this->__('Review was successfully submit.'));
                }
                // The following line decides if it is a "save" or "save and continue"
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            $this->_redirectReview();
            return;
        }
        return;
    }

    public function listAction()
    {
        $id = $this->getRequest()->getParam('id');
        if(!$id) {
            $this->_redirectReview();
            return;
        }
        $customer = Mage::getModel('customer/customer')->load($id);
        if(!$customer->getId()) {
            $this->_redirect('');
            return;
        }
        Mage::register('current_customer',$customer);
        $this->_initLayout();
        $this->getLayout()->getBlock('head')
            ->setTitle($this->__('%s Review List',$customer->getName()));
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs
                ->addCrumb('seller_profile', array(
                    'label'=> $customer->getName(),
                    'title'=> $customer->getName(),
                    'link' => Mage::getUrl('seller/info/view',array('id'=>$id)),
                    )
                );
                $breadcrumbs
                    ->addCrumb('seller_name', array(
                        'label'=> $this->__('Review List'),
                        'title'=> $this->__('Review List'),
                        )
                    );
        }
        $this->renderLayout();
    }

    protected function _redirectReview()
    {
        $currentUrl = $this->getRequest()->getParam('current_url');
        if($currentUrl) {
            Mage::app()->getResponse()->setRedirect($currentUrl);
        }
        else {
            $this->_redirect('');
        }
    }
}