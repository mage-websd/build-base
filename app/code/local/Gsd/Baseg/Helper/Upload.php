<?php
class Gsd_Baseg_Helper_Upload extends Mage_Core_Helper_Abstract
{
    /*add into form to upload
     *   enctype="multipart/form-data"
    */
    
    public function image($inputName,$folder='template_image',$filesDispersion = false)
    {
        if(isset($_FILES[$inputName]) &&
            isset($_FILES[$inputName]['name']) &&
            $_FILES[$inputName]['name']) {
            try {
                $uploader = new Varien_File_Uploader($inputName);
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion($filesDispersion); //split folder of image
                $path = Mage::getBaseDir('media') . DS . $folder .DS ;
                $destFile = $path.$_FILES[$inputName]['name'];
                $filename = $uploader->getNewFileName($destFile);
                $result = $uploader->save($path, $filename);
                if($result);
                    return $folder. DS . $result['file'];
            }catch(Exception $e) {
                Mage::throwException($e);
                return null;
            }
        }
        return null;
    }

    public function images($inputName,$folder='template_image',$filesDispersion = false)
    {
        if(isset($_FILES[$inputName]) &&
            isset($_FILES[$inputName]['name']) &&
            count($_FILES[$inputName]['name'])
        ) {
            try {
                $path = Mage::getBaseDir('media') . DS . $folder .DS ;
                $result = array();
                foreach ($_FILES[$inputName]['name'] as $key => $_nameImage) {
                    if(!$_nameImage) {
                        continue;
                    }
                    $inputNameArray = array(
                        'name' => $_FILES[$inputName]['name'][$key],
                        'type' => $_FILES[$inputName]['type'][$key],
                        'tmp_name' => $_FILES[$inputName]['tmp_name'][$key],
                        'error' => $_FILES[$inputName]['error'][$key],
                        'size' => $_FILES[$inputName]['size'][$key],
                    );
                    $uploader = new Varien_File_Uploader($inputNameArray);
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion($filesDispersion); //split folder of image
                    $destFile = $_nameImage;
                    $filename = $uploader->getNewFileName($destFile);
                    $resultObject = $uploader->save($path, $filename);
                    $result[] = $path . $resultObject['file'];
                }
                if($result)
                    return $result;
            }catch(Exception $e) {
                Mage::throwException($e);
                return null;
            }
        }
        return null;
    }

    public function deleteImageProduct($_product)
    {
        $entityTypeId = $_product->getEntityTypeId(); //Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $mediaGalleryAttribute = Mage::getModel('catalog/resource_eav_attribute')->loadByCode($entityTypeId, 'media_gallery');
        $gallery = $_product->getMediaGalleryImages();
        foreach ($gallery as $image) {
            $mediaGalleryAttribute->getBackend()->removeImage($_product, $image->getFile());
        }
    }
}