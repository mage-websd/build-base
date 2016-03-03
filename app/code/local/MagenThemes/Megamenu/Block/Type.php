<?php
/******************************************************
 * @author http://www.9magentothemes.com
 * @copyright (C) 2012- 9MagentoThemes.Com
 * @license PHP files are GNU/GPL
 *******************************************************/
?>
<?php
class MagenThemes_Megamenu_Block_Type extends Mage_Core_Block_Template
{
    protected $_hasContent = true;
    protected $_hasLink = true;
    protected $_type = null;
    protected $_menu = null;
    protected $_level = 0;
    protected $_routeName = null;
    protected $_paramName = null;

    public function setMenu($menu, $level=0) {
        $this->_menu = $menu;
        $this->_level = $level;
        return $this;
    }

    public function hasContent() {
        if($this->_hasContent == true) {
            return true;
        }
        return false;
    }

    public function hasLink() {
        if($this->_hasLink == true) {
            return true;
        }
        return false;
    }

    public function getContentType() {
        if($this->hasContent()) {
            return $this->getLayout()->createBlock('cms/block')->setBlockId($this->_menu->getArticle())->toHtml();
        }
        return null;
    }

    public function getWidgetType() {
        if($this->hasContent()) {
            $widget = Mage::getModel('widget/widget_instance')->load($this->_menu->getArticle());
            $pg = $widget->getPageGroups();
            return $this->getLayout()->createBlock($widget->getType())
                    ->setTemplate($pg[0]['page_template'])
                    ->setData($widget->getWidgetParameters())
                    ->setPageId(2)
                    ->toHtml();
        }
        return null;
    }

    public function getObjectType() {
        return $this->getLayout()->getBlock('megamenu.nav')->getType($this->_type);
    }

    public function getModelOfType() {
        return $this->getObjectType()->getModel();
    }

    public function getUrlType() {
        return Mage::getModel($this->getModelOfType())->load($this->_menu->getArticle(), array('url'))->getUrl();
    }

    public function getCategorySummary() {
        return Mage::getModel($this->getModelOfType())->load($this->_menu->getArticle(), array('summary'))->getSummary();
    }

    private function _getObjectType($type) {
        return $this->getLayout()->getBlock('megamenu.nav')->getType($type);
    }

    public function activeMenu($param) {
        if($this->_routeName == null) {
            return false;
        }

        if($this->getRequest()->getRouteName() == $this->_routeName) {
            if($this->_paramName == null) {
                return false;
            }

            if($this->getRequest()->getParam($this->_paramName) == $param) {
                return true;
            }
        }
        return false;
    }

    public function isActive(){
        /*switch ($this->_menu->getType()){
            case 'category':
                $currentCategory = Mage::registry('current_category');
                $path = $currentCategory ? $currentCategory->getPath() : '';
                if (in_array($this->_menu->getArticle(), explode('/', $path))) return true;
                break;
        }*/
        return false;
    }

    public function drawItem()
    {
        $html = '';
        if($this->_type == null) {
            return $html;
        }

        if(!$this->_menu instanceof MagenThemes_Megamenu_Model_Megamenu) {
            return $html;
        }

        if($this->_menu->getStatus() == MagenThemes_Megamenu_Model_Status::STATUS_DISABLED) {
            return $html;
        }

        $modelType = $this->getModelOfType();
        if ( !$modelType ) {
            return '';
        }

        if ($modelType == 'catalog/category') {
            $model = Mage::getModel($modelType)->load($this->_menu->getArticle(), array('summary', 'thumbnail'));
        } else {
            $model = Mage::getModel($modelType)->load($this->_menu->getArticle());
        }

        if ( !$model->getId() ) {
            return '';
        }

        if ($this->_level == 0) {
            $html .= '<li class="root level-'.$this->_level;
            if ( $this->isActive() ) {
                $html .= ' active';
            }
            if ( $this->_menu->hasChild(true) ) {
                $html .= ' parent';
            }

            /* Added custom CSS class */
            $category = Mage::getSingleton('catalog/category');
            $html .= ' ' . $category->formatUrlKey( $this->_menu->getTitle() );

            $html .= '" ';
        } else {
            if ( $this->_menu->isGroup() ) {
                if($this->_menu->hasChild(true)) {
                    $class = 'class="group level-'.$this->_level.' parent"';
                }else{
                    $class = 'class="group level-'.$this->_level.'"';
                }
                $html .= '<li '.$class;
                if($this->activeMenu($this->_menu->getArticle()))
                    $html .= ' active';
                $html .= '" ';
            } else {
                if($this->_menu->hasChild(true)) {
                    if($this->activeMenu($this->_menu->getArticle())){
                        $class = 'class="active level-'.$this->_level.' parent"';
                    }else{
                        $class = 'class="level-'.$this->_level.' parent"';
                    }
                }else{
                    $class = 'class="level-'.$this->_level.'"';
                }
                $html .= '<li '.$class;
            }
        }
//        if($this->_menu->hasChild(true) && $this->_menu->showSub()) {
//            if(!$this->_menu->isGroup() && $this->_level != 0) {
//                $html .= ' onmouseover="megamenu.showSubMegamenu(this, 1);" onmouseout="megamenu.showSubMegamenu(this, 0);"';
//            }
//        }
        $html .= '>';

        if($this->_menu->getType() == 'external_link') {
            $html .= '<a class="megamenu-title" ';
            if($this->_menu->getUrl())
                $html .= 'href="'.$this->_menu->getUrl().'" ';
            if($this->_menu->getNofollow() == 1) {
                $html .= 'rel="nofollow"';
            }
            $html .= '>';
            if($this->_menu->getImage())
                $html .= '<img alt="'.$this->_menu->getTitle().'" src="'.Mage::getBaseUrl('media').$this->_menu->getImage().'" width="13" height="13" class="icon-megamenu" />';
            else
                if($this->_level != 0)
                    $html .= '<span class="no-icon-megamenu"></span>';
            $html .= '<span>'.$this->_menu->getTitle().'';
            if($this->_menu->getLabel()){
                $label = $this->_menu->getLabel() == 'label1' ? $this->__('New') : $this->__('Hot!');
                $html .= '<span class="menu-label '.$this->_menu->getLabel().'">'.$label.'</span>';
            }
            $html .='</span></a>';
        } else {
            if($this->_menu->showTitle()) {
                if($this->_menu->getIsLabel()){
                    $url = 'javascript:void(0);';
                    $linkClass = 'link-label';
                    $style = "font-weight:bold;";
                }else{
                    $url = $model->getUrl();
                    $linkClass = '';
                    $style = '';
                }
                $html .= '<a style="'.$style.'" href="'.$url.'" class="megamenu-title '.$linkClass.'" ';
                if($this->_menu->getNofollow() == 1) {
                    $html .= 'rel="nofollow" ';
                }
                if(!$this->hasLink())
                    $html .= 'onclick="return false;"';
                $html .= '>';
                if($this->_menu->getImage())
                    $html .= '<img alt="'.$this->_menu->getTitle().'" src="'.Mage::getBaseUrl('media').$this->_menu->getImage().'" width="13" height="13" class="icon-megamenu" />';
                else
                    if($this->_level != 0)
                        $html .= '<span class="no-icon-megamenu"></span>';
                $html .= '<span>'.$this->escapeHtml($this->_menu->getTitle()).'';
                if($this->_menu->getLabel()){
                    $label = $this->_menu->getLabel() == 'label1' ? $this->__('New') : $this->__('Hot!');
                    $html .= '<span class="menu-label '.$this->_menu->getLabel().'">'.$label.'</span>';
                }
                $html .='</span></a>';

               /* if($this->_level == 1 && $this->_type == 'category')
                    $html .= '<span class="cat-summary">'.$this->escapeHtml($model->getSummary()).'</span>';*/

            }
            if($this->_menu->isContent()){
                if($this->_menu->getType() == 'widget'){
                    $html .= $this->getLayout()->createBlock($this->_getObjectType($this->_menu->getType())->getBlock())
                        ->setMenu($this->_menu, $this->_level+1)
                        ->getWidgetType();
                }else{
                    $html .= $this->getLayout()->createBlock($this->_getObjectType($this->_menu->getType())->getBlock())
                        ->setMenu($this->_menu, $this->_level+1)
                        ->getContentType();
                }
            }

        }
        if($this->_menu->hasChild(true) && $this->_menu->showSub()) {
            if($this->_level != 0 && !$this->_menu->isGroup()) {
                $html .= '<div class="sub-megamenu" style="';
            } else {
                $html .= '<div class="childcontent" style="';
            }

            if($this->_menu->getWidth()) {
                $html .= sprintf('width:%dpx;', $this->_menu->getWidth());
            }

            //if ($model->getThumbnail() && $model->getLevel()==3){
                //$html .= sprintf('background-image:url(%s);', Mage::getBaseUrl('media').'catalog/category/'.$model->getThumbnail());
            //}

            $html .= '"';
            if ($model->getThumbnail()){
                $html .= sprintf('dataimage="%s" ', $model->getThumbnail());
            }
            $html.= '>';


            $colPositions = array();
            if($this->_menu->getSubitemWidth()) {
                $colPositions = Mage::helper('megamenu')->getColpositions($this->_menu->getSubitemWidth());
            }

            if(count($colPositions)) {
                $i = 1;
                foreach($colPositions as $col => $width) {
                    if($i == 1){
                        $html .= '<ul class="first '.$col.'" style="width:'.$width.'px">';
                    }else if($i==count($colPositions)){
                        $html .= '<ul class="last '.$col.'" style="width:'.$width.'px">';
                    }else {
                        $html .= '<ul class="'.$col.'" style="width:'.$width.'px">';
                    }
                    $childItemsWidthCol = $this->_menu->getChildItem($col);
                    foreach($childItemsWidthCol as $childItem) {
                        $html .= $this->getLayout()->createBlock($this->_getObjectType($childItem->getType())->getBlock())
                            ->setMenu($childItem, $this->_level+1)
                            ->drawItem();
                    }
                    $html .= '</ul>';
                    $i++;
                }
            } else {
                $html .= '<ul style="width:'.$this->_menu->getWidth().'px">';
                $childItemsWidthCol = $this->_menu->getChildItem();
                foreach($childItemsWidthCol as $childItem) {
                    $html .= $this->getLayout()->createBlock($this->_getObjectType($childItem->getType())->getBlock())
                        ->setMenu($childItem, $this->_level+1)
                        ->drawItem();
                }
                $html .= '</ul>';
            }
            $html .= '</div>';
        }
        $html .= '</li>';

        return $html;
    }

    public function drawDrillItem()
    {
        $html = '';
        if($this->_type == null) {
            return $html;
        }

        if(!$this->_menu instanceof MagenThemes_Megamenu_Model_Megamenu) {
            return $html;
        }

        if($this->_menu->getStatus() == MagenThemes_Megamenu_Model_Status::STATUS_DISABLED) {
            return $html;
        }

        if($this->_level == 0) {
            $html .= '<li class="item ';
            if($this->activeMenu($this->_menu->getArticle()))
                $html .= 'active ';
            if($this->_menu->hasChild(true))
                $html .= 'hasChild';
            $html .= '" ';
        } else {
            if($this->_menu->isGroup()) {
                $html .= '<li class="group ';
                if($this->activeMenu($this->_menu->getArticle()))
                    $html .= 'active ';
                $html .= '" ';
            } else {
                $html .= '<li ';
                if($this->activeMenu($this->_menu->getArticle())) {
                    $html .= 'class="active" ';
                }
            }
        }
        $html .= '>';
        if($this->_menu->getType() == 'external_link') {
            $html .= '<a class="megamenu-title" ';
            if($this->_menu->getUrl())
                $html .= 'href="'.$this->_menu->getUrl().'" ';
            if($this->_menu->getNofollow() == 1) {
                $html .= 'rel="nofollow"';
            }
            $html .= '>';
            if($this->_menu->getImage())
                $html .= '<img alt="'.$this->_menu->getTitle().'" src="'.Mage::getBaseUrl('media').$this->_menu->getImage().'" width="13" height="13" class="icon-megamenu" />';

            $html .= $this->_menu->getTitle().'</a>';
        } else {
            if($this->_menu->showTitle() && $this->_menu->getType()!='static_block') {
                $html .= '<a href="'.$this->getUrlType().'" class="megamenu-title" ';
                if($this->_menu->getNofollow() == 1) {
                    $html .= 'rel="nofollow" ';
                }
                if(!$this->hasLink())
                    $html .= 'onclick="return false;"';
                $html .= '>';
                if($this->_menu->getImage())
                    $html .= '<img alt="'.$this->_menu->getTitle().'" src="'.Mage::getBaseUrl('media').$this->_menu->getImage().'" width="13" height="13" class="icon-megamenu" />';

                $html .= $this->_menu->getTitle().'</a>';
            }

        }
        if($this->_menu->hasChild(true) && $this->_menu->showSub()) {
            if($this->_level != 0 && !$this->_menu->isGroup()) {
                $html .= '<div class="childcontent" ';
            } else {
                $html .= '<div class="childcontent" ';
            }
            $html .= '>';

            $colPositions = array();
            if($this->_menu->getSubitemWidth()) {
                $colPositions = Mage::helper('megamenu')->getColpositions($this->_menu->getSubitemWidth());
            }

            if(count($colPositions)) {
                foreach($colPositions as $col => $width) {
                    $html .= '<ul class="'.$col.'">';
                    $childItemsWidthCol = $this->_menu->getChildItem($col);
                    foreach($childItemsWidthCol as $childItem) {
                        $html .= $this->getLayout()->createBlock($this->_getObjectType($childItem->getType())->getBlock())
                            ->setMenu($childItem, $this->_level+1)
                            ->drawDrillItem();
                    }
                    $html .= '</ul>';
                }
            } else {
                $html .= '<ul>';
                $childItemsWidthCol = $this->_menu->getChildItem();
                foreach($childItemsWidthCol as $childItem) {
                    $html .= $this->getLayout()->createBlock($this->_getObjectType($childItem->getType())->getBlock())
                        ->setMenu($childItem, $this->_level+1)
                        ->drawDrillItem();
                }
                $html .= '</ul>';
            }

            $html .= '</div>';
        }
        $html .= '</li>';
        return $html;
    }
}