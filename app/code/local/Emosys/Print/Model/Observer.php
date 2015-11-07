<?php
class Emosys_Custom_Model_Observer
{
    public function checkoutCartUpdateItemsBefore($observer)
    {
        $cartInfo = $observer->getInfo();
        $cart = $observer->getCart();
        foreach ($cartInfo as $itemId => $itemInfo) {
            $item = $cart->getQuote()->getItemById($itemId);
            if (!$item) continue;
            $maxCart = $item->getProduct()->getData('max_cart');
            if(!$maxCart) continue;
            $updateQty = $itemInfo['qty'];
            if($updateQty <= $maxCart ) {
                continue;
            }
            else {
                Mage::throwException(Mage::helper('core')->__('If you want more than %s items you may want to consider purchasing another meal?',$maxCart));
            }
        }
    }
    public function addPostData(Varien_Event_Observer $observer) {
        $action = Mage::app()->getFrontController()->getAction();
        if ($action->getFullActionName() == 'checkout_cart_add') {
            $item = $observer->getProduct();
                $additionalOptions = array();
                $additionalOptions[] = array(
                    'label' => 'Nutrition Type',
                    'value' => 'nutrition base',
                );
                $item->addCustomOption('additional_options', serialize($additionalOptions));
                $item->setData('nutrition_type','giang test');
            if($action->getRequest()->getParam('nutrition_type')) {
                $item = $observer->getProduct();
                $additionalOptions = array();
                $additionalOptions[] = array(
                    'label' => 'Nutrition Type',
                    'value' => $action->getRequest()->getParam('nutrition_type')
                );
                $item->addCustomOption('additional_options', serialize($additionalOptions));
            }
        }
   }

   public function checkoutCartProductAddAfter($observer)
    {
        $item = $observer->getEvent()->getQuoteItem();
        $item->setData('nutrition_type','aaa test');
        $item->save();
        //print_r($item->getData());exit;
        return $this;
    }
}
