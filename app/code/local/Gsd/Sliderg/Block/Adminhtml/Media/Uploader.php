<?php
class Gsd_Sliderg_Block_Adminhtml_Media_Uploader extends Mage_Adminhtml_Block_Media_Uploader {

    public function __construct() {
        parent::__construct();
        $this->setTemplate("sliderg/media/uploader.phtml");
    }

}