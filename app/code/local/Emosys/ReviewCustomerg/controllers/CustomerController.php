<?php
class Emosys_ReviewCustomerg_CustomerController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirectReview();
            return;
        }
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('review_customerg/review');
            $ratings = Mage::getResourceModel('review_customerg/rating_collection')->setOrder('position','asc');
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
                    Mage::getSingleton('core/session')->addSuccess($this->__('Details was successfully saved.'));
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