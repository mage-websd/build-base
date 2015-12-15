<?php

class Emosys_Print_Adminhtml_PrintController  extends Mage_Adminhtml_Controller_Action
{
    public function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
    }
    public function indexAction()
    {
        $this->_title($this->__("Print Product"));
        $productIds = $this->getRequest()->getParam('product');
        if (!is_array($productIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select a product'));
            return $this->_redirect('*/catalog_product/index');
        }
        try {
            $product = null;
            foreach ($productIds as $productId) {
                $product = Mage::getModel('catalog/product')
                    ->load($productId);
                if($product->getId() && $product->getTypeId() == 'bundle') {
                    break;
                }
            }
            if(!$product || !$product->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select a bundle product'));
                return $this->_redirect('*/catalog_product/index');
            }
            //$printDate = $this->getRequest()->getParam('print');
            $printType = $this->getRequest()->getParam('type_page');
            //$this->printPdf($product);
            Mage::register('current_product',$product);
            Mage::register('current_print_data',$printType);
            $this->_initLayout();
            $this->renderLayout();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/catalog_product/index');
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