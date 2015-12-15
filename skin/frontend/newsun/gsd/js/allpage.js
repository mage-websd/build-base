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
/*(function($){
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
                                        $(i).replaceWith(k);
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
})(jQuery);*/
!function(a){a.fn.ajaxReturn=function(e,t){return a(".ajax-loading-wrapper").show(),void 0==e.type&&(e.type="get"),void 0==e.data&&(e.data=""),void 0==e.dom&&(e.dom="html"),a.ajax({type:e.type,url:e.url,data:e.data,dataType:"json",success:function(n){if(n){if(n.message&&a.colorbox({html:n.message,className:"colorbox-messages"}),n.status&&n.data)switch(e.dom){case"insert":a.each(n.data,function(e,t){a(e).after(t)});break;case"before":a.each(n.data,function(e,t){a(e).before(t)});break;case"append":a.each(n.data,function(e,t){a(e).append(t)});break;case"prepend":a.each(n.data,function(e,t){a(e).prepend(t)});break;case"replace":a.each(n.data,function(e,t){a(e).replaceWith(t)});break;default:a.each(n.data,function(e,t){a(e).html(t)})}n.dataReturn&&void 0!=t&&t(n.dataReturn)}a(".ajax-loading-wrapper").hide()}}),this}}(jQuery);

    /**
     * submit form ajax
     * @param object
     */
(function($){
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
})(jQuery);
    /**
     * collapse html
     */
/*
(function($){
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
        object.only = assignDefault(object.only,false);
        object.parent = assignDefault(object.parent,false);
        object.clickOut = assignDefault(object.clickOut,false);
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
            if(object.clickOut) {
              $(document).on('click',function (e){
                domTitleOut = $(flagTitle);
                domContentOut = $(flagContent);
                if (domTitleOut.is(e.target)
                    || domTitleOut.has(e.target).length !== 0
                ){}
                else if(domContentOut.is(e.target)
                    || domContentOut.has(e.target).length !== 0) {}
                else {
                  $(thisSelector+' '+flagTitle).each(function(i,k) {
                    domTitleOutThis = $(this);
                    if(domTitleOutThis.hasClass('click-out')) {
                      domTitleOutThis.removeClass(flagClassTitleExpand);
                      domTitleOutThis.next(flagContent).slideUp(object.speed);
                      domTitleOutThis.next(flagContent).removeClass(flagClassContentExpand);
                    }
                  });
                }
              });
            }
            $(document).on('click touchstart',thisSelector+' '+flagTitle,function(event) {
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
})(jQuery);

*/
//!function(e){function s(e,s){return void 0==e?s:e}e.fn.collapse=function(n){function l(e,s,l){s.siblings(r).slideUp(n.speed),n.parent?l.siblings().children(i).removeClass(o):e.siblings(i).removeClass(o),s.siblings(r).removeClass(c),n.changeText&&(e.siblings("."+p).html(n.more),n.parent&&l.siblings().children("."+p).html(n.more))}var t=e(this),a=t.selector,i=".collapse-title",r=".collapse-content",o="expand",c="open",p="change-text",h="collapse-close";n=s(n,{}),n.start=s(n.start,!0),n.click=s(n.click,!0),n.next=s(n.next,!0),n.speed=s(n.speed,500),n.only=s(n.only,!1),n.parent=s(n.parent,!1),n.parent?n.sync=s(n.sync,!1):n.sync=!1,n.changeText=s(n.changeText,!1),n.changeText&&(n.more=s(n.more,"more"),n.less=s(n.less,"less")),n.close=s(n.close,!1),n.start&&e.each(t.find(i),function(){var s,l,t=e(this);n.parent&&(l=e(this).parent()),s=n.next?n.parent?l.next(r):t.next(r):n.parent?l.prev(r):t.prev(r),t.hasClass(o)?(s.show(),s.addClass(c)):(s.hide(),s.removeClass(c))}),n.click&&(e(document).on("click touchstart",a+" "+i,function(s){s.preventDefault();var t,a,i=e(this);n.parent&&(a=e(this).parent()),t=n.next?n.parent?a.next(r):i.next(r):n.parent?a.prev(r):i.prev(r),t.length&&(i.hasClass(o)?(n.only&&l(i,t,a),t.slideUp(n.speed),i.removeClass(o),n.sync&&i.siblings().removeClass(o),t.removeClass(c)):(n.only&&l(i,t,a),t.slideDown(n.speed),i.addClass(o),n.sync&&i.siblings().addClass(o),t.addClass(c)),n.changeText&&(i.hasClass(o)?i.hasClass(p)&&i.html(n.less):i.hasClass(p)&&i.html(n.more),i.siblings("."+p).hasClass(o)?i.siblings("."+p).html(n.less):i.siblings("."+p).html(n.more)))}),n.close&&e(document).on("click","."+h,function(s){s.preventDefault(),n.parent?domTitleClose=e(this).parent().prev().children(i):domTitleClose=e(this).parent().prev(i),domTitleClose.first().trigger("click")}))}}(jQuery);
!function(e){function s(e,s){return void 0==e?s:e}e.fn.collapse=function(t){function l(e,s,l){s.siblings(o).slideUp(t.speed),t.parent?l.siblings().children(a).removeClass(c):e.siblings(a).removeClass(c),s.siblings(o).removeClass(r),t.changeText&&(e.siblings("."+h).html(t.more),t.parent&&l.siblings().children("."+h).html(t.more))}var n=e(this),i=n.selector,a=".collapse-title",o=".collapse-content",c="expand",r="open",h="change-text",d="collapse-close";t=s(t,{}),t.start=s(t.start,!0),t.click=s(t.click,!0),t.next=s(t.next,!0),t.speed=s(t.speed,500),t.only=s(t.only,!1),t.parent=s(t.parent,!1),t.clickOut=s(t.clickOut,!1),t.parent?t.sync=s(t.sync,!1):t.sync=!1,t.changeText=s(t.changeText,!1),t.changeText&&(t.more=s(t.more,"more"),t.less=s(t.less,"less")),t.close=s(t.close,!1),t.start&&e.each(n.find(a),function(){var s,l,n=e(this);t.parent&&(l=e(this).parent()),s=t.next?t.parent?l.next(o):n.next(o):t.parent?l.prev(o):n.prev(o),n.hasClass(c)?(s.show(),s.addClass(r)):(s.hide(),s.removeClass(r))}),t.click&&(t.clickOut&&e(document).on("click",function(s){domTitleOut=e(a),domContentOut=e(o),domTitleOut.is(s.target)||0!==domTitleOut.has(s.target).length||domContentOut.is(s.target)||0!==domContentOut.has(s.target).length||domTitleOut.each(function(s,l){domTitleOutThis=e(this),domTitleOutThis.hasClass("click-out")&&(domTitleOutThis.removeClass(c),domTitleOutThis.next(o).slideUp(t.speed),domTitleOutThis.next(o).removeClass(r))})}),e(document).on("click touchstart",i+" "+a,function(s){s.preventDefault();var n,i,a=e(this);t.parent&&(i=e(this).parent()),n=t.next?t.parent?i.next(o):a.next(o):t.parent?i.prev(o):a.prev(o),n.length&&(a.hasClass(c)?(t.only&&l(a,n,i),n.slideUp(t.speed),a.removeClass(c),t.sync&&a.siblings().removeClass(c),n.removeClass(r)):(t.only&&l(a,n,i),n.slideDown(t.speed),a.addClass(c),t.sync&&a.siblings().addClass(c),n.addClass(r)),t.changeText&&(a.hasClass(c)?a.hasClass(h)&&a.html(t.less):a.hasClass(h)&&a.html(t.more),a.siblings("."+h).hasClass(c)?a.siblings("."+h).html(t.less):a.siblings("."+h).html(t.more)))}),t.close&&e(document).on("click","."+d,function(s){s.preventDefault(),t.parent?domTitleClose=e(this).parent().prev().children(a):domTitleClose=e(this).parent().prev(a),domTitleClose.first().trigger("click")}))}}(jQuery);
/* end collapse---------------------*/
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
/*jQuery(document).ready(function($) {
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
});*/
jQuery(document).ready(function(e){var n='<span class="collapse-title menu-open-child"><span>Open</span></span>';e("#mmenu-left").find("li:has(ul)",this).each(function(){e(this).children("ul").before(n),e(this).children("ul").addClass("collapse-content"),e(this).addClass("has-child")}),e(document).on("click",".mmenu-toggle",function(n){n.preventDefault(),e("#mmenu-left").toggleClass("show")}),e(document).mouseup(function(n){var t=e("#mmenu-left"),s=e(".mmenu-toggle");return s.is(n.target)||0!==s.has(n.target).length?!1:void(t.is(n.target)||0!==t.has(n.target).length||t.removeClass("show"))}),e(document).keyup(function(n){27==n.keyCode&&e("#mmenu-left").removeClass("show")})});
/*----- end menu mobile*/

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
        changeText: false,
        more: 'more',
        less: 'less',
        close: false
    });
});
/*end collapse dom*/