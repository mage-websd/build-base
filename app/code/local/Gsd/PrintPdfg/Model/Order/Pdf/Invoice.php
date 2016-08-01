<?php
class Gsd_PrintPdfg_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice 
{
    /**
     * Draw header for item table
     *
     * @param Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y -15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 35
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 180,
            'align' => 'right'
        );
        
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Short description'),
            'feed'  => 300,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Qty'),
            'feed'  => 435,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Price'),
            'feed'  => 380,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Subtotal'),
            'feed'  => 565,
            'align' => 'right'
        );

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }
    
//    public function getPdf($invoices = array()) 
//    {
//        $this->_beforeGetPdf();
//        $this->_initRenderer('invoice');
//
//        $pdf = new Zend_Pdf();
//        $this->_setPdf($pdf);
//        $style = new Zend_Pdf_Style();
//        $this->_setFontBold($style, 10);
//
//        foreach ($invoices as $invoice) {
//            if ($invoice->getStoreId()) {
//                Mage::app()->getLocale()->emulate($invoice->getStoreId());
//            }
//            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
//            $pdf->pages[] = $page;
//
//            $order = $invoice->getOrder();
//
//            /* Add image */
//            $this->insertLogo($page, $invoice->getStore());
//
//            /* Add address */
//            $this->insertAddress($page, $invoice->getStore());
//
//            /* Add head */
//            $this->insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId()));
//
//            $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
//            $this->_setFontRegular($page);
//            $page->drawText(Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId(), 35, 780, 'UTF-8');
//
//            /* Add table */
//            $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
//            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
//            $page->setLineWidth(0.5);
//
//            $page->drawRectangle(25, $this->y, 570, $this->y - 15);
//            $this->y -=10;
//
//            /* Add table head */
//            $page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
//            $page->drawText(Mage::helper('sales')->__('Products'), 35, $this->y, 'UTF-8');
//            //Added for custom attribute "inchoo_warehouse_location"
//            $page->drawText(Mage::helper('sales')->__('Short description'), 245, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('SKU'), 325, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');
//
//            $this->y -=15;
//
//            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
//
//            /* Add body */
//            foreach ($invoice->getAllItems() as $item) {
//                if ($item->getOrderItem()->getParentItem()) {
//                    continue;
//                }
//
//                if ($this->y < 15) {
//                    $page = $this->newPage(array('table_header' => true));
//                }
//
//                /* Draw item */
//                $page = $this->_drawItem($item, $page, $order);
//            }
//
//            /* Add totals */
//            $page = $this->insertTotals($page, $invoice);
//
//            if ($invoice->getStoreId()) {
//                Mage::app()->getLocale()->revert();
//            }
//        }
//        $this->_afterGetPdf();
//
//        return $pdf;
//    }
//
//    public function newPage(array $settings = array()) {
//        /* Add new table head */
//        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
//        $this->_getPdf()->pages[] = $page;
//        $this->y = 800;
//
//        if (!empty($settings['table_header'])) {
//            $this->_setFontRegular($page);
//            $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
//            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
//            $page->setLineWidth(0.5);
//            $page->drawRectangle(25, $this->y, 570, $this->y - 15);
//            $this->y -=10;
//
//            $page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
//            $page->drawText(Mage::helper('sales')->__('Product'), 35, $this->y, 'UTF-8');
//            //Added for custom attribute "inchoo_warehouse_location"
//            $page->drawText(Mage::helper('sales')->__('Short description'), 245, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('SKU'), 325, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
//            $page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');
//
//            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
//            $this->y -=20;
//        }
//        return $page;
//    }

}
