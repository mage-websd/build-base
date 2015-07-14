/*
* get Base url
* */
var baseUrl = jQuery('#base-url-page').attr('href');

/**
 * function return ajax
 *
 * data return format json
 * {status: 1,message: 'message alert',data:{'dom1':'html1','dom2':'html2'}}
 */
(function($){
$.fn.ajaxReturn = function(object) {
    $('.ajax-loading-wrapper').show();
    if(object.type == undefined) {
        object.type = 'get';
    }
    if(object.data == undefined) {
        object.data = '';
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
                        $.each(data.data,function(i,k) {
                            $(i).html(k);
                        })
                    }
                }
            }
            $('.ajax-loading-wrapper').hide();
        }
    });
}
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