<?php
/**
 * Created by PhpStorm.
 * User: giangsoda
 * Date: 19/07/2015
 * Time: 09:33
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$tableRating = $installer->getTable('seller/rating');
$tableRatingEntity = $installer->getTable('seller/ratingentity');
$tableReview = $installer->getTable('seller/review');

$installer->run("
CREATE TABLE IF NOT EXISTS `{$tableRating}` (
 `rating_id` int(2) NOT NULL AUTO_INCREMENT,
 `rating_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `position` int(2),
 PRIMARY KEY (`rating_id`),
 UNIQUE KEY `rating_name` (`rating_name`)
);

CREATE TABLE IF NOT EXISTS `{$tableRatingEntity}` (
 `rating_entity_id` int(11) NOT NULL AUTO_INCREMENT,
 `review_id` int(11) NOT NULL,
 `rating_id` int(2) NOT NULL,
 `value` tinyint(1) NOT NULL,
 PRIMARY KEY (`rating_entity_id`)
);

CREATE TABLE IF NOT EXISTS `{$tableReview}` (
 `review_id` int(11) NOT NULL AUTO_INCREMENT,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `customer_id` int(11) DEFAULT NULL,
 `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
 `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
 `message` text COLLATE utf8_unicode_ci,
 `approved` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`review_id`)
);

TRUNCATE `{$tableRating}`;
INSERT INTO `{$tableRating}` (`rating_id`, `rating_name`, `position`) VALUES (NULL, 'quality', 1);
");

/*INSERT INTO `{$tableRating}` (`rating_id`, `rating_name`, `position`) VALUES (NULL, 'price', 2);
INSERT INTO `{$tableRating}` (`rating_id`, `rating_name`, `position`) VALUES (NULL, 'value', 3);
*/
$installer->endSetup();
