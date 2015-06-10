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
        /*$.ajax({
            url: baseUrl+'quickviewg/',
            type: 'get',
            data: {'id':productId},
            dataType: 'json',
            success: function(data) {
                $.colorbox({html: data.data});
            }
        });*/
    });
});