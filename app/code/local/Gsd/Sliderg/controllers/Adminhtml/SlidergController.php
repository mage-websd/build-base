<?php
class Gsd_Sliderg_Adminhtml_SlidergController extends Mage_Adminhtml_Controller_Action
{
    public function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('gsd');
    }
    public function indexAction()
    {
        $this->_title($this->__("Manager slider"));
        $this->_initLayout();
        $this->_addContent($this->getLayout()->createBlock('sliderg/adminhtml_slider'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('sliderg/adminhtml_slider_edit'))
            ->_addLeft($this->getLayout()->createBlock('sliderg/adminhtml_slider_edit_tabs'));
        $this->renderLayout();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('sliderg/slider');

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
            $model = Mage::getModel('sliderg/slider');
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
                    Mage::getSingleton('adminhtml/session')->addError($this->__('Error saving slider details'));
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
        $model = Mage::getModel('sliderg/slider');
        if ($id) {
            $model->load((int)$id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Slider does not exist'));
                $this->_redirect('*/*/');
            }
        }
        $model->loadConfig();
        Mage::register('slider_data', $model);
        $this->_title($this->__('Edit slider '.$model->getTitle()));
        $this->_initLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('sliderg/adminhtml_slider_edit'))
            ->_addLeft($this->getLayout()->createBlock('sliderg/adminhtml_slider_edit_tabs'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('sliderg/adminhtml_slider_grid')->toHtml()
        );
    }

    public function massDeleteAction()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if (!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select gallery(s)'));
            return $this->_redirect('*/*/index');
        }
        try {
            foreach ($sliderIds as $sliderId) {
                Mage::getModel('sliderg/slider')
                    ->setIsMassDelete(true)
                    ->load($sliderId)
                    ->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count($sliderIds)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if (!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select gallery(s)'));
            return $this->_redirect('*/*/index');
        }
        try {
            $statusChange = $this->getRequest()->getParam('status');
            if($statusChange != 1) {
                $statusChange = 0;
            }
            foreach ($sliderIds as $sliderId) {
                Mage::getSingleton('sliderg/slider')
                    ->setIsMassStatus(true)
                    ->load($sliderId)
                    ->setEnable($statusChange)
                    ->save();
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully updated', count($sliderIds))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    public function imageAction()
    {
        $result = array();
        try {
            if($sliderId = $this->getRequest()->getParam('slider_id')) {
                $sliderId = $sliderId.'/';
            }
            else {
                $sliderId = 'zero/';
            }
            $uploader = new Varien_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            Mage::log($uploader,null,'giang.log');
            $result = $uploader->save(
                Mage::helper('sliderg')->getBaseMediaPath().$sliderId
            );
            $result['label'] = $result['file'];
            $result['name_origin'] = $result['name'];
            $result['file'] = $sliderId.$result['file'];
            $result['url'] = Mage::helper('sliderg')->getBaseMediaUrl().$result['file'];
            $result['cookie'] = array(
                'name' => session_name(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
}