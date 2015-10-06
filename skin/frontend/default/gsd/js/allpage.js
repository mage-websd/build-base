/*
* get Base url
* */
var baseUrl = jQuery('#base-url-page').attr('href');

(function($){
    /**
     * function return ajax
     *
     * data return format json
     * {status: 1,message: 'message alert',data:{'dom1':'html1','dom2':'html2'}}
     */
    $.fn.ajaxReturn = function(object,callback) {
        $('.ajax-loading-wrapper').show();
        if(object.type == undefined) {
            object.type = 'get';
        }
        if(object.data == undefined) {
            object.data = '';
        }
        if(object.dom == undefined) {
            object.dom = 'html';
        }
        $.ajax({
            type: object.type,
            url: object.url,
            data: object.data,
            dataType: 'json',
            success: function(data){
                if(data) {
                    if(data.message) {
                        $.colorbox({
                            html: data.message,
                            className: 'colorbox-messages'
                        });
                    }
                    if(data.status) {
                        if(data.data) {
                            switch (object.dom){
                                case 'insert':
                                    $.each(data.data,function(i,k) {
                                        $(i).after(k);
                                    });
                                    break;
                                case 'before':
                                    $.each(data.data,function(i,k) {
                                        $(i).before(k);
                                    });
                                    break;
                                case 'append':
                                    $.each(data.data,function(i,k) {
                                        $(i).append(k);
                                    });
                                    break;
                                case 'prepend':
                                    $.each(data.data,function(i,k) {
                                        $(i).prepend(k);
                                    });
                                    break;
                                case 'replace':
                                    $.each(data.data,function(i,k) {
                                        $(i).replace(k);
                                    });
                                    break;
                                default:
                                    $.each(data.data,function(i,k) {
                                        $(i).html(k);
                                    });
                                    break;
                            }
                        }
                    }
                    if(data.dataReturn) {
                        if(callback != undefined) {
                            callback(data.dataReturn);
                        }
                    }
                }
                $('.ajax-loading-wrapper').hide();
            }
        });
        return this;
    };

    /**
     * submit form ajax
     * @param object
     */
    $.fn.formSubmitAjax = function(object) {
        this.submit(function (event) {
            event.preventDefault();
            if(object == undefined) {
                object = {};
            }
            if(object.url == undefined) {
                object.url = $(this).attr('action');
            }
            if(object.type == undefined) {
                object.type = $(this).attr('method');
            }
            object.data = $(this).serialize();
            $().ajaxReturn(object);
            return this;
        });
    };

    /**
     * collapse html
     */
    function assignDefault(name,value) {
        if(name == undefined) {
            return value;
        }
        return name;
    }

    $.fn.collapse = function(object) {
        var thisWrapper = $(this),
        thisSelector = thisWrapper.selector;
        var flagTitle = '.collapse-title',
        flagContent = '.collapse-content',
        flagClassTitleExpand = 'expand',
        flagClassContentExpand  = 'open',
        flagClassChangeText='change-text',
        flagClassClose ='collapse-close';

        object = assignDefault(object,{});
        object.start = assignDefault(object.start,true);
        object.click = assignDefault(object.click,true);
        object.next = assignDefault(object.next,true);
        object.speed = assignDefault(object.speed,500);
        object.only = assignDefault(object.only,true);
        object.parent = assignDefault(object.parent,false);
        if(!object.parent) {
            object.sync = false;
        }
        else {
            object.sync = assignDefault(object.sync,false);
        }
        object.changeText = assignDefault(object.changeText,false);
        if(object.changeText) {
            object.more = assignDefault(object.more,'more');
            object.less = assignDefault(object.less,'less');
        }
        object.close = assignDefault(object.close,false);

        if(object.start) {
            $.each(thisWrapper.find(flagTitle), function() {
                var domContent;
                var domTitle = $(this);
                var domTitleParent;
                if(object.parent) {
                    domTitleParent = $(this).parent();
                }
                if(object.next) {
                    if(object.parent) {
                        domContent = domTitleParent.next(flagContent);
                    }
                    else {
                        domContent = domTitle.next(flagContent);
                    }
                }
                else {
                    if(object.parent) {
                        domContent = domTitleParent.prev(flagContent);
                    }
                    else {
                        domContent = domTitle.prev(flagContent);
                    }
                }
                if(domTitle.hasClass(flagClassTitleExpand)) {
                    domContent.show();
                    domContent.addClass(flagClassContentExpand);
                }
                else {
                    domContent.hide();
                    domContent.removeClass(flagClassContentExpand);
                }
            });
        }
        if(object.click) {
            function collapseOnly(domTitle,domContent,domTitleParent) {
                domContent.siblings(flagContent).slideUp(object.speed);
                if(object.parent) {
                    domTitleParent.siblings().children(flagTitle).removeClass(flagClassTitleExpand);
                }
                else {
                    domTitle.siblings(flagTitle).removeClass(flagClassTitleExpand);
                }
                domContent.siblings(flagContent).removeClass(flagClassContentExpand);
                if(object.changeText) {
                    domTitle.siblings('.'+flagClassChangeText).html(object.more);
                    if(object.parent) {
                        domTitleParent.siblings().children('.'+flagClassChangeText).html(object.more);
                    }
                }
            }

            $(document).on('click',thisSelector+' '+flagTitle,function(event) {
                event.preventDefault();
                var domContent;
                var domTitle = $(this);
                var domTitleParent;
                if(object.parent) {
                    domTitleParent = $(this).parent();
                }
                if(object.next) {
                    if(object.parent) {
                        domContent = domTitleParent.next(flagContent);
                    }
                    else {
                        domContent = domTitle.next(flagContent);
                    }
                }
                else {
                    if(object.parent) {
                        domContent = domTitleParent.prev(flagContent);
                    }
                    else {
                        domContent = domTitle.prev(flagContent);
                    }
                }
                if(domContent.length) {
                    if(domTitle.hasClass(flagClassTitleExpand)) {
                        if(object.only) {
                            collapseOnly(domTitle,domContent,domTitleParent);
                        }
                        domContent.slideUp(object.speed);
                        domTitle.removeClass(flagClassTitleExpand);
                        if(object.sync) {
                            domTitle.siblings().removeClass(flagClassTitleExpand);
                        }
                        domContent.removeClass(flagClassContentExpand);
                    }
                    else {
                        if(object.only) {
                            collapseOnly(domTitle,domContent,domTitleParent);
                        }
                        domContent.slideDown(object.speed);
                        domTitle.addClass(flagClassTitleExpand);
                        if(object.sync) {
                            domTitle.siblings().addClass(flagClassTitleExpand);
                        }
                        domContent.addClass(flagClassContentExpand);
                    }
                    if(object.changeText) {
                        if(domTitle.hasClass(flagClassTitleExpand)) {
                            if(domTitle.hasClass(flagClassChangeText)) {
                                domTitle.html(object.less);
                            }
                        }
                        else {
                            if(domTitle.hasClass(flagClassChangeText)) {
                                domTitle.html(object.more);
                            }
                        }
                        if(domTitle.siblings('.'+flagClassChangeText).hasClass(flagClassTitleExpand)) {
                            domTitle.siblings('.'+flagClassChangeText).html(object.less);
                        }
                        else {
                            domTitle.siblings('.'+flagClassChangeText).html(object.more);
                        }
                    }
                }
            });
            if(object.close) {
                $(document).on('click','.'+flagClassClose,function(event) {
                    event.preventDefault();
                    if(object.parent) {
                        domTitleClose = $(this).parent().prev().children(flagTitle);
                    }
                    else {
                        domTitleClose = $(this).parent().prev(flagTitle);
                    }
                    domTitleClose.first().trigger('click');
                })
            }
        }
    };
    //function assignDefault(s,e){return void 0==s?e:s}$.fn.collapse=function(s){function e(e,n,a){n.siblings(t).slideUp(s.speed),s.parent?a.siblings().children(l).removeClass(i):e.siblings(l).removeClass(i),n.siblings(t).removeClass(r),s.changeText&&(e.siblings("."+o).html(s.more),s.parent&&a.siblings().children("."+o).html(s.more))}var n=$(this),a=n.selector,l=".collapse-title",t=".collapse-content",i="expand",r="open",o="change-text",c="collapse-close";s=assignDefault(s,{}),s.start=assignDefault(s.start,!0),s.click=assignDefault(s.click,!0),s.next=assignDefault(s.next,!0),s.speed=assignDefault(s.speed,500),s.only=assignDefault(s.only,!0),s.parent=assignDefault(s.parent,!1),s.sync=s.parent?assignDefault(s.sync,!1):!1,s.changeText=assignDefault(s.changeText,!1),s.changeText&&(s.more=assignDefault(s.more,"more"),s.less=assignDefault(s.less,"less")),s.close=assignDefault(s.close,!1),s.start&&$.each(n.find(l),function(){var e,n,a=$(this);s.parent&&(n=$(this).parent()),e=s.next?s.parent?n.next(t):a.next(t):s.parent?n.prev(t):a.prev(t),a.hasClass(i)?(e.show(),e.addClass(r)):(e.hide(),e.removeClass(r))}),s.click&&($(document).on("click",a+" "+l,function(n){n.preventDefault();var a,l,c=$(this);s.parent&&(l=$(this).parent()),a=s.next?s.parent?l.next(t):c.next(t):s.parent?l.prev(t):c.prev(t),a.length&&(c.hasClass(i)?(s.only&&e(c,a,l),a.slideUp(s.speed),c.removeClass(i),s.sync&&c.siblings().removeClass(i),a.removeClass(r)):(s.only&&e(c,a,l),a.slideDown(s.speed),c.addClass(i),s.sync&&c.siblings().addClass(i),a.addClass(r)),s.changeText&&(c.hasClass(i)?c.hasClass(o)&&c.html(s.less):c.hasClass(o)&&c.html(s.more),c.siblings("."+o).html(c.siblings("."+o).hasClass(i)?s.less:s.more)))}),s.close&&$(document).on("click","."+c,function(e){e.preventDefault(),domTitleClose=s.parent?$(this).parent().prev().children(l):$(this).parent().prev(l),domTitleClose.first().trigger("click")}))};
    /* end collapse---------------------*/
})(jQuery);
/*
* quick view product
* */
jQuery(document).ready(function($) {
    if(!$('.module-quickview-enable').length) {
        return;
    }
    if(!$('.module-quickview-enable').data('check')) {
        return;
    }
    baseUrl = jQuery('#base-url-page').attr('href');
    $(document).on('click','.quick-view-product',function(e) {
        e.preventDefault();
        var productId = $(this).data('productid');
        $.colorbox({
            href: baseUrl+'quickviewg/index/index/id/'+productId,
            className: 'colorbox-product-quick-view'
        });
    });
});

/**
 *  Ajax filter product list
 */

jQuery(document).ready(function($) {
    if(!$('.module-filter-enable').length) {
        return;
    }
    if(!$('.module-filter-enable').data('check')) {
        return;
    }
    //get param query in url
    function getParameterByName(name) {
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = name;

        var urlParams = {};
        while (match = search.exec(query))
            urlParams[decode(match[1])] = decode(match[2]);
        return urlParams;
    }
    function getStringFromParamObject(param)
    {
        var result = '';
        $.each(param,function(i,v) {
            result += i+'='+v+'&';
        });
        return result;
    }

    baseUrl = jQuery('#base-url-page').attr('href');
    var urlCurrent = window.location.href;
    var urlNotParam = baseUrl + window.location.pathname;
    var classWrapperProductList = 'category-products-wrapper';
    var classProductList = 'category-products';
    $('.'+classProductList).wrap('<div class="'+classWrapperProductList+'"></div>');
    var urlParams = {};
    var positionParam = urlCurrent.indexOf('?');
    if( positionParam > 0 ) {
        var paramQuery = urlCurrent.substring(positionParam+1);
        if(paramQuery) {
            urlParams = getParameterByName(paramQuery);
        }
    }

    $(document).on('click','#narrow-by-list a',function(e) {
        e.preventDefault();
        var linkClick = $(this).attr('href');
        positionParam = linkClick.indexOf('?');
        stringQuery = '';
        if( positionParam > 0 ) {
            paramQuery = linkClick.substring(positionParam+1);
            if(paramQuery) {
                urlParamsClick = getParameterByName(paramQuery);
                urlParams = $.extend(urlParams,urlParamsClick);
                stringQuery = getStringFromParamObject(urlParams);
            }
        }
        stringQuery = urlNotParam + '?' + stringQuery;
        $().ajaxReturn({
            url: stringQuery
        });
    });
});

/**
 *  Mini Cart Header
 */

jQuery(document).ready(function($) {
    $(document).on('mouseenter','.mini-cart-header',function() {
        $(this).children('.block-content').fadeIn(1000);
    });
    $(document).on('mouseleave','.mini-cart-header',function() {
        $(this).children('.block-content').fadeOut(1000);
    });
});

/**
 * Add cart ajax - list product
 */
jQuery(document).ready(function($) {
    if(!$('.module-cart-ajax-enable').length) {
        return;
    }
    if(!$('.module-cart-ajax-enable').data('check')) {
        return;
    }
    $(document).on('click','.btn-cart',function() {
        var dataCart = $(this).data('cart');
        var dataUrlAdd = $(this).data('url-add');
        if(dataCart) {
            if (dataCart == 'option') {
                $.colorbox({
                    href: dataUrlAdd,
                    className: 'colorbox-addcart'
                })
            }
            else if (dataCart == 'add') {
                try {
                    jQuery().ajaxReturn({
                        url: dataUrlAdd,
                        type: 'post'
                    });
                } catch (e) {
                }
            }
        }
    });
});
/**
 * Compare Ajax
 */
jQuery(document).ready(function($) {
    if(!$('.module-compare-ajax-enable').length) {
        return;
    }
    if(!$('.module-compare-ajax-enable').data('check')) {
        return;
    }
    $(document).on('click','.link-compare',function() {
        var link = $(this).attr('href');
        link = link.replace('/catalog/product_compare/add/','/cart-ajaxg/compare/add/');
        $().ajaxReturn({
            url: link,
            type: 'post'
        });
        return false;
    });
});

/**
 * wishlist Ajax
 */
jQuery(document).ready(function($) {
    if(!$('.module-wish-ajax-enable').length) {
        return;
    }
    if(!$('.module-wish-ajax-enable').data('check')) {
        return;
    }
    $(document).on('click','.link-wishlist-list',function() {
        var link = $(this).attr('href');
        link = link.replace("/wishlist/index/","/cart-ajaxg/whishlist/");
        $().ajaxReturn({
            url: link,
            type: 'post'
        });
        return false;
    });
});

/*collapse dom*/
jQuery(document).ready(function($) {
    $('.collapse-wrapper').collapse({
        start: true,
        click: true,
        next: true,
        speed: 500,
        parent: false,
        only: false,
        sync: false, // parent = true
        changeText: true,
        more: 'more',
        less: 'less',
        close: true,
    });
});
/*end collapse dom*/

/*back to top*/
jQuery(document).ready(function($) {
    var offset = 250;
    var duration = 300;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('#back-to-top').fadeIn(duration);
        } else {
            $('#back-to-top').fadeOut(duration);
        }
    });

    $('#back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});
/*end backtotop */

/*menu mobile*/
jQuery(document).ready(function($) {
    var domOpenChild = '<span class="collapse-title menu-open-child"><span>Open</span></span>';
    $("#mmenu-left").find('li:has(ul)',this).each(function() {
        $(this).children('ul').before(domOpenChild);
        $(this).children('ul').addClass('collapse-content');
        $(this).addClass('has-child');
    });
    $(document).on('click',".mmenu-toggle",function(e){
        e.preventDefault();
        $("#mmenu-left").toggleClass("show");
    });
    $(document).mouseup(function (e){
        var container = $("#mmenu-left");
        var mMenuToggle = $('.mmenu-toggle');
        if (mMenuToggle.is(e.target)
            || mMenuToggle.has(e.target).length !== 0
        ){
            return false;
        }
        else if (!container.is(e.target)
            && container.has(e.target).length === 0
        ){
            container.removeClass('show');
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $("#mmenu-left").removeClass('show');
        }
    });
});
/*-- end menu mobile*/

/*
* pager load more
*/
jQuery(document).ready(function($) {
    var htmlAttrBtnLoadMore = '.btn-pager-load-more';
    var url = $(htmlAttrBtnLoadMore).attr('data-url');
    var varPager = $(htmlAttrBtnLoadMore).attr('data-varpager');
    $(document).on('click',htmlAttrBtnLoadMore,function(event) {
        event.preventDefault();
        $('.ajax-loading-wrapper').show();
        var pagerCurrent = $(this).attr('data-pager');
        if(!pagerCurrent) {
            $(this).remove();
            return;
        }
        var pagerNext = parseInt(pagerCurrent)+1;
        var dataSend = varPager+'='+pagerNext;
        $().ajaxReturn({
            type: 'get',
            url: url,
            data: dataSend,
            dom: 'append'
        },function(dataReturn) {
            if(dataReturn.end == 1) {
                $(htmlAttrBtnLoadMore).remove();
                return;
            }
            $(htmlAttrBtnLoadMore).attr('data-pager',dataReturn.pager);
        });
    });
});

/* opacity blur ---*/
jQuery(document).ready(function($) {
    var flagOpacity = 'opacity-blur';
    var flagDomOpacity = '.' + flagOpacity;
    if($(flagDomOpacity).length) {
        var heightOpacityFlag = $(flagDomOpacity).outerHeight(true);
        var scrollTopFlag = $(flagDomOpacity).offset().top;
        $(window).scroll(function () {
            var minusScrollDom = $(this).scrollTop() - scrollTopFlag;
            if (minusScrollDom <= 0) {
                $(flagDomOpacity)[0].className = $(flagDomOpacity)[0].className.replace(/\bopacity-blur--\d*\b/g, '');
            }
            else if (0 < minusScrollDom && minusScrollDom <= heightOpacityFlag) {
                var percent = ( minusScrollDom / heightOpacityFlag ) * 10;
                percent = Math.round(percent);
                $(flagDomOpacity)[0].className = $(flagDomOpacity)[0].className.replace(/\bopacity-blur--\d*\b/g, '');
                $(flagDomOpacity).addClass(flagOpacity + '--' + percent);
            }
            else {
                $(flagDomOpacity)[0].className = $(flagDomOpacity)[0].className.replace(/\bopacity-blur--\d*\b/g, '');
                $(flagDomOpacity).addClass(flagOpacity + '--10');
            }
        });
    }
});
/* end opacity blur ----------------------------------------*/

/* scroll animate--- */
jQuery(document).ready(function($) {
	$(document).on('click','a.js-menu',function() {
		var rev = $(this).attr('rev');
		if($(rev).length) {
			var roteY = $(rev).offset().top-90;
			$('html, body').animate({
			    scrollTop: roteY
		 	}, 1000);
		 	return false;
		}
	});
});
/* end scroll animate----------------------------------- */
