<?php
/**
 * @category    Emosys
 * @package     Emosys_Banner
 * @copyright   Copyright (c) 2015 Emosys Ltd. (http://www.emosys.com)
 * @license     http://www.emosys.com/EMOSYS-LICENSE.txt
 */
class Emosys_Promotion_Adminhtml_Promotion_ItemController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("cms/emosys_banner")->_addBreadcrumb(Mage::helper("adminhtml")->__("Banner Manager"), Mage::helper("adminhtml")->__("Banner Manager"));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Manager Promotion Banner"));

        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('e_promotion/adminhtml_Item'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("e_promotion/item")->load($id);
        if ($model->getId()) {
            Mage::register("e_promotion_item_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("cms/e_promotion");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Manager"), Mage::helper("adminhtml")->__("Banner Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Description"), Mage::helper("adminhtml")->__("Banner Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("e_promotion/adminhtml_item_edit"))
                ->_addLeft($this->getLayout()->createBlock("e_promotion/adminhtml_item_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {

        $this->_title($this->__("Promotion"));
        $this->_title($this->__("Promotion"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("e_promotion/item")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("e_promotion_item_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("cms/e_promotion");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Manager"), Mage::helper("adminhtml")->__("Promotion Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotion Description"), Mage::helper("adminhtml")->__("Promotion Description"));

        $this->_addContent($this->getLayout()->createBlock("e_promotion/adminhtml_item_edit"))
            ->_addLeft($this->getLayout()->createBlock("e_promotion/adminhtml_item_edit_tabs"));

        $this->renderLayout();

    }

    public function saveAction()
    {
        $post_data = $this->getRequest()->getPost();
        if ($post_data) {
            try {
                $model = Mage::getModel("e_promotion/item")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Promotion was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setPopupData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setPopupData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }

        }
        $this->_redirect("*/*/");
    }


    public function deleteAction()
    {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("e_promotion/item");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("e_promotion/item");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /*
     public function exportCsvAction()
    {
        $fileName = 'emosys_banner_item.csv';
        $grid = $this->getLayout()->createBlock('emosys_banner/adminhtml_item_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportExcelAction()
    {
        $fileName = 'emosys_banner_item.xml';
        $grid = $this->getLayout()->createBlock('emosys_banner/adminhtml_item_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }*/
}
