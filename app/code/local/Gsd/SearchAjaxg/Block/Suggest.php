<?php
class Gsd_SearchAjaxg_Block_Suggest extends Mage_Core_Block_Template
{
     public function getSearchautocomplete()     
     { 
        if (!$this->hasData('searchautocomplete')) {
            $this->setData('searchautocomplete', Mage::registry('searchautocomplete'));
        }
        return $this->getData('searchautocomplete');
     }

     public function getSuggestProducts()     
     {
        $query = Mage::helper('catalogsearch')->getQuery();
        $query->setStoreId(Mage::app()->getStore()->getId());

                if ($query->getRedirect()){
                    $query->save();
                }
                else {
                    $query->prepare();
                }
            Mage::helper('catalogsearch')->checkNotes();
            $results=$query->getResultCollection();//->setPageSize(5);
//        $results=Mage::getResourceModel('catalogsearch/search_collection')->addSearchFilter(Mage::app()->getRequest()->getParam('q'));
        $results->addAttributeToFilter('visibility', array('neq' => 1));
        if(Mage::getStoreConfig('searchajaxg/preview/number_product'))
        {
            $results->setPageSize(Mage::getStoreConfig('searchajaxg/preview/number_product'));
        }
        else
        {
            $results->setPageSize(5);
        }
        $results->addAttributeToSelect('description');
        $results->addAttributeToSelect('name');
        $results->addAttributeToSelect('thumbnail');
        $results->addAttributeToSelect('small_image');
        $results->addAttributeToSelect('url_key');


        return $results;
    }
     public function enabledSuggest()     
     {
        return Mage::getStoreConfig('searchajaxg/suggest/enable');
      }

     public function enabledPreview()     
     {
        return Mage::getStoreConfig('searchajaxg/preview/enable');
     }

     public function getImageWidth()
     {
        return Mage::getStoreConfig('searchajaxg/preview/image_width');
     }

     public function getImageHeight()
     {
        return Mage::getStoreConfig('searchajaxg/preview/image_height');
     }
     public function getEffect()
     {
        return Mage::getStoreConfig('searchajaxg/settings/effect');
     }

     public function getPreviewBackground()
     {
        return Mage::getStoreConfig('searchajaxg/preview/background');
     }

     public function getSuggestBackground()
     {
        return Mage::getStoreConfig('searchajaxg/suggest/background');
     }

     public function getSuggestColor()
     {
        return Mage::getStoreConfig('searchajaxg/suggest/suggest_color');
     }

     public function getSuggestCountColor()
     {
        return Mage::getStoreConfig('searchajaxg/suggest/count_color');
     }

     public function getBorderColor()
     {
        return Mage::getStoreConfig('searchajaxg/settings/border_color');
     }

     public function getBorderWidth()
     {
        return Mage::getStoreConfig('searchajaxg/settings/border_width');
     }

     public function isShowImage()
     {
        return Mage::getStoreConfig('searchajaxg/preview/show_image');
     }

     public function isShowName()
     {
        return Mage::getStoreConfig('searchajaxg/preview/show_name');
     }
     public function getProductNameColor()
     {
        return Mage::getStoreConfig('searchajaxg/preview/pro_title_color');
     }

     public function getProductDescriptionColor()
     {
        return Mage::getStoreConfig('searchajaxg/preview/pro_description_color');
     }


     public function isShowDescription()
     {
        return Mage::getStoreConfig('searchajaxg/preview/show_description');
     }

     public function getNumDescriptionChar()
     {
        return Mage::getStoreConfig('searchajaxg/preview/num_char_description');
     }


     public function getImageBorderWidth()
     {
        return Mage::getStoreConfig('searchajaxg/preview/image_border_width');
     }
     public function getImageBorderColor()
     {
        return Mage::getStoreConfig('searchajaxg/preview/image_border_color');
     }

     public function getHoverBackground()
     {
        return Mage::getStoreConfig('searchajaxg/settings/hover_background');
     }

}
