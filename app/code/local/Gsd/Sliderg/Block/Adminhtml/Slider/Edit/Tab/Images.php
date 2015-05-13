<?php
class Gsd_Sliderg_Block_Adminhtml_Slider_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        /*if (Mage::registry('slider_data')) {
            $data = Mage::registry('slider_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_images', array('legend' => $this->__('Upload images')));

        $fieldset->addField('image', 'text', array(
            'label' => $this->__('Image'),
            'class' => '',
            'required' => false,
            'name' => 'image',
        ));
        $form->setValues($data);
        return parent::_prepareForm();*/

        $data = $this->getRequest()->getPost();
        $form = new Varien_Data_Form();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function __construct() {
        parent::__construct();
        $this->setTemplate("sliderg/edit/tab/images.phtml");
        $this->setId("media_gallery_content");
        $this->setHtmlId("media_gallery_content");
    }

    protected function _prepareLayout() {
        $this->setChild("uploader", $this->getLayout()->createBlock("sliderg/adminhtml_media_uploader"));
        $this->getUploader()->getConfig()
            ->setUrl(Mage::getModel("adminhtml/url")->addSessionParam()->getUrl("*/*/image"))
            ->setFileField("image")
            ->setFilters(array("images" => array(
                "label" => Mage::helper("adminhtml")->__("Images (.gif, .jpg, .png)"),
                "files" => array("*.gif", "*.jpg", "*.jpeg", "*.png")))
            );
        $this->setChild("delete_button",
            $this->getLayout()->createBlock("adminhtml/widget_button")
                ->addData(array(
                    "id" => "{{id}}-delete",
                    "class" => "delete",
                    "type" => "button",
                    "label" => Mage::helper("adminhtml")->__("Remove"),
                    "onclick" => $this->getJsObjectName() . ".removeFile('{{fileId}}')")
                )
        );
        return parent::_prepareLayout();
    }

    public function getUploader() {
        return $this->getChild("uploader");
    }

    public function getUploaderHtml() {
        return $this->getChildHtml("uploader");
    }

    public function getJsObjectName() {
        return $this->getHtmlId() . "JsObject";
    }

    public function getAddImagesButton() {
        return $this->getButtonHtml(
            Mage::helper("catalog")->__("Add New Images"),
            $this->getJsObjectName() . ".showUploader()",
            "add", $this->getHtmlId() . "_add_images_button"
        );
    }

    public function getImagesJson() {
        $_model = Mage::registry("slider_data");
        $_data = $_model->getImage();
        if (is_array($_data) and sizeof($_data) > 0) {
            $_result = array();
            foreach ($_data as $_item) {
                $_result[] = array(
                    "value_id" => $_item["image_id"],
                    "url" => Mage::helper('sliderg')->getBaseMediaUrl() . $_item["path_media"],
                    "descriptionbanner" => $_item['description'],
                    "urlbanner" => $_item['url'],
                    "file" => $_item["path_media"],
                    "label" => $_item["name_rename"],
                    "name_origin" => $_item["name_origin"],
                    "position" => $_item["position"],
                    "disabled" => !$_item["enable"],
                );
            }
            return Zend_Json::encode($_result);
        }
        return "[]";
    }

    public function getImagesValuesJson() {
        $values = array();
        return Zend_Json::encode($values);
    }

    public function getMediaAttributes() {

    }

    public function getImageTypes() {
        $type = array();
        $type["gallery"]["label"] = "igallery";
        $type["gallery"]["field"] = "igallery";
        return $type;
    }

    public function getImageTypesJson() {
        return Zend_Json::encode($this->getImageTypes());
    }

    public function getCustomRemove() {
        return $this->setChild("delete_button",
            $this->getLayout()->createBlock("adminhtml/widget_button")
                ->addData(array(
                    "id" => "{{id}}-delete",
                    "class" => "delete",
                    "type" => "button",
                    "label" => Mage::helper("adminhtml")->__("Remove"),
                    "onclick" => $this->getJsObjectName() . ".removeFile('{{fileId}}')")
                )
        );
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml("delete_button");
    }

    public function getCustomValueId() {
        return $this->setChild("value_id",
            $this->getLayout()->createBlock("adminhtml/widget_button")
                ->addData(array(
                    "id" => "{{id}}-value",
                    "class" => "value_id",
                    "type" => "text",
                    "label" => Mage::helper("adminhtml")->__("ValueId"),)
                )
        );
    }

    public function getValueIdHtml() {
        return $this->getChildHtml("value_id");
    }
}