/*
* get Base url
* */
var baseUrl = jQuery('#base-url-page').attr('href');

/*
* quick view product
* */
jQuery(document).ready(function($) {
    baseUrl = jQuery('#base-url-page').attr('href');
    $(document).on('click','.quick-view-product',function(e) {
        e.preventDefault();
        var productId = $(this).data('productid');
        $.colorbox({href: baseUrl+'quickviewg/index/index/id/'+productId});
    });
});

/**
 *  Ajax filter product list
 */

jQuery(document).ready(function($) {
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
        $('.ajax-loading-wrapper').show();
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
        console.log(stringQuery);
        $.ajax({
            type: 'get',
            url: stringQuery,
            dataType: 'json',
            success: function(data){
                $('.'+classWrapperProductList).html(data.productList);
                $('.ajax-loading-wrapper').hide();
            }
        });
    });
});