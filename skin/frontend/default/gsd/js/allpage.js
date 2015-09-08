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
        })
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
    $.fn.collapseStartActivity = function() {
        var flagTitleClick = '.collapse-title';
        $.each($(flagTitleClick), function() {
            var domContent;
            if($(this).hasClass('prev')) {
                domContent = $(this).prev('.collapse-content');
            }
            else {
                domContent = $(this).next('.collapse-content');
            }
            if(domContent.length) {
                if($(this).hasClass('expand')) {
                    domContent.slideDown();
                    domContent.addClass('open');
                }
                else {
                    domContent.slideUp();
                    domContent.removeClass('open');
                }
            }
        });
    };
    $.fn.collapseClickActivity = function() {
        var flagTitleClick = '.collapse-title';
        var flagContent = '.collapse-content';
        $(document).on('click',flagTitleClick,function() {
            var domContent;
            if($(this).hasClass('prev')) {
                domContent = $(this).prev(flagContent);
            }
            else {
                domContent = $(this).next(flagContent);
            }
            if(domContent.length) {
                if($(this).hasClass('expand')) {
                    $(this).removeClass('expand');
                    domContent.slideUp();
                    domContent.removeClass('open');
                }
                else {
                    if($(this).hasClass('only')) {
                      $(this).siblings(flagTitleClick).removeClass('expand');
                      $(this).siblings(flagContent).removeClass('open');
                      $(this).siblings(flagContent).slideUp();
                    }
                    $(this).addClass('expand');
                    domContent.slideDown();
                    domContent.addClass('open');
                }
            }
            return false;
        });
        /* end collapse---------------------*/
    };
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
    $().collapseStartActivity();
    $().collapseClickActivity();
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
