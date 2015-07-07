<?php
class Gsd_Baseg_Helper_Upload extends Mage_Core_Helper_Abstract
{
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
}