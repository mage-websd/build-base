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
                        type: 'post',
                        dom: 'replace'
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
    var flagScrollToTop = '.scroll-to-top';
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $(flagScrollToTop).fadeIn(duration);
        } else {
            $(flagScrollToTop).fadeOut(duration);
        }
    });

    $(flagScrollToTop).click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});
/*end backtotop */

/*menu mobile*/
jQuery(document).ready(function(e){var n='<span class="collapse-title menu-open-child"><span>Open</span></span>';e("#mmenu-left").find("li:has(ul)",this).each(function(){e(this).children("ul").before(n),e(this).children("ul").addClass("collapse-content"),e(this).addClass("has-child")}),e(document).on("click",".mmenu-toggle",function(n){n.preventDefault(),e("#mmenu-left").toggleClass("show")}),e(document).mouseup(function(n){var t=e("#mmenu-left"),s=e(".mmenu-toggle");return s.is(n.target)||0!==s.has(n.target).length?!1:void(t.is(n.target)||0!==t.has(n.target).length||t.removeClass("show"))}),e(document).keyup(function(n){27==n.keyCode&&e("#mmenu-left").removeClass("show")})});
/*----- end menu mobile*/

/* scroll animate--- */
jQuery(document).ready(function($) {
	$(document).on('click','a.js-scroll-animate',function() {
		var rev = $(this).attr('data-to');
		if($(rev).length) {
			var roteY = $(rev).offset().top;
			$('html, body').animate({
			    scrollTop: roteY
		 	}, 1000);
		 	return false;
		}
	});
});
/* end scroll animate----------------------------------- */
