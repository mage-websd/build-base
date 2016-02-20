<?php

class Emosys_PrintOrderBundle_Adminhtml_Order_PrintController  extends Mage_Adminhtml_Controller_Action
{
    public function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales');
    }
    public function bundleAction()
    {
        $this->_title($this->__("Print a order meal"));
        $orderIds = $this->getRequest()->getParam('order_ids');
        if (!is_array($orderIds) || !$orderIds || count($orderIds) != 1) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select a order'));
            return $this->_redirect('*/sales_order/index');
        }
        try {
            $order = null;
            $products = array();
            foreach ($orderIds as $orderId) {
                $order = Mage::getModel('sales/order')
                    ->load($orderId);
                $productAll = $order->getAllVisibleItems();
                foreach($productAll as $item) {
                    if($item->getData('product_type') == 'bundle') {
                        $products[] = $item;
                    }
                }
                break;
            }
            if(!count($products)) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select a order have bundle product'));
                return $this->_redirect('*/sales_order/index');
            }
            $printType = $this->getRequest()->getParam('type_page');
            //$this->printPdf($product);
            Mage::register('current_print_order',$order);
            Mage::register('current_print_product',$products);
            Mage::register('current_print_data',$printType);
            $this->_initLayout();
            $this->renderLayout();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/sales_order/index');
        }
        //return $this->_redirect('*/catalog_product/index');
        //$this->_initLayout();
        //$this->_addContent($this->getLayout()->createBlock('sliderg/adminhtml_slider'));
        //$this->renderLayout();
    }

    protected function printPdf($product)
    {
        try{
            $this->_isExport = true;
            $pdf = new Zend_Pdf();
            $pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $page=$pdf->pages[0];
            $style = new Zend_Pdf_Style();
            $style->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
            $style->setFont($font,12);
            $page->setStyle($style);
            $page->drawText($product->getName(),100,($page->getHeight()-100));
            // Hear Add your data as per your requrement.
            // Blank page and pdf headers 
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="downloaded.pdf"');
            echo $pdf->render();
            //return true;
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return false;
        }
    }
}