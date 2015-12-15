<?php
$product = Mage::getModel('catalog/product')->load($_product->getId());

foreach ($product->getMediaGalleryImages() as $image) {
    echo var_export($image->getUrl());
    $image->getFile();
} 


$media_gallery = $_product->getMediaGallery(); ?>
<?php foreach ($media_gallery['images'] as $_image):?>
    <img src="<?php echo $this->helper('catalog/image')->init($_product, 'image', $_image['file'])->resize(135); ?>" alt="<?php echo $this->escapeHtml($_image['label']) ?>" />
<?php endforeach;