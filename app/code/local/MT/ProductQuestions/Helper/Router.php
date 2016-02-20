<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category     MT
 * @package      MT_ProductQuestions
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2008-2013 MagentoThemes.net. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       MagentoThemes.net
 * @email        support@magentothemes.net
 * ------------------------------------------------------------------------------
 *
 */
Class MT_ProductQuestions_Helper_Router extends Mage_Core_Helper_Abstract
{
    const FRONTEND_URI_CODE = 'productquestions';
    
    const FRONTEND_URI_SEO = 'hoi-dap';
    
    public function getRouterSeo() {
        return self::FRONTEND_URI_SEO;
    }
}