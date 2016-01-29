<?php
class Emosys_Seller_Adminhtml_ReviewController extends Mage_Adminhtml_Controller_Action
{
    public function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('customer');
    }
    public function indexAction()
    {
        $this->_title($this->__("Review Customer"));
        $this->_initLayout();
        $this->_addContent($this->getLayout()->createBlock('seller/adminhtml_review'));
        $this->renderLayout();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('seller/review');
                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('seller/review');
            $id = $this->getRequest()->getParam('id');
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(',', $this->getRequest()->getParam($key));
                }
            }
            if ($id) {
                $model->load($id);
            }
            $model->setData($data);
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
                if (!$model->getId()) {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('Error saving review details'));
                }
                else {
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Details was successfully saved.'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                }
                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }

            return;
        }
        Mage::getSingleton('adminhtml/session')->addError($this->__('No data found to save'));
        $this->_redirect('*/*/');
    }


    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('seller/review');
        if ($id) {
            $model->load((int)$id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Review does not exist'));
                $this->_redirect('*/*/');
            }
        }
        $model->loadRating();
        $customer = $model->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customer);
        $customer = $customer->getName();
        $model->setCustomerName($customer);
        Mage::register('review_data', $model);
        $this->_title($this->__('Edit Review '.$model->getSummary()));
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('seller/adminhtml_review_edit'))
            ->_addLeft($this->getLayout()->createBlock('seller/adminhtml_review_edit_tabs'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('seller/adminhtml_review_grid')->toHtml()
        );
    }

    public function massDeleteAction()
    {
        $reviewIds = $this->getRequest()->getParam('review');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select review object'));
            return $this->_redirect('*/*/index');
        }
        try {
            foreach ($reviewIds as $reviewId) {
                Mage::getModel('seller/review')
                    ->setIsMassDelete(true)
                    ->load($reviewId)
                    ->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count($reviewIds)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $reviewIds = $this->getRequest()->getParam('review');
        if (!is_array($reviewIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select review object'));
            return $this->_redirect('*/*/index');
        }
        try {
            $statusChange = $this->getRequest()->getParam('approved');
            if($statusChange != 1) {
                $statusChange = 0;
            }
            foreach ($reviewIds as $reviewId) {
                Mage::getModel('seller/review')
                    ->setIsMassStatus(true)
                    ->load($reviewId)
                    ->setApproved($statusChange)
                    ->save();
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully updated', count($reviewIds))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }
    public function changeStatusAction()
    {
        $reviewId = $this->getRequest()->getParam('id');
        if(!$reviewId || $reviewId <= 0) {
            $this->_redirect('*/*/');
            return;
        }
        try {
            $model = Mage::getModel('seller/review')->load($reviewId);
            $approved = $model->getData('approved');
            if($approved == 1) {
                $approved = 0;
            }
            else {
                $approved = 1;
            }
            $model->setData('approved',$approved);
            $model->save();
            $message = 'Item "'.$reviewId.'" was successfully change status: ';
            if($approved) {
                $message .= 'Approved';
            }
            else {
                $message .= 'Unapproved';
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $message
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}