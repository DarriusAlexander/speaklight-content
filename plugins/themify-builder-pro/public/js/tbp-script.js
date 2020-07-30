(function ($) {
    'use strict';
    /*
     * 
     * Add To Cart Module Quantity Change
     */
    function tbp_cart_icon_module_quantity(){
        $(this).closest('.tb_pro_add_to_cart').find('.button')[0].setAttribute('data-quantity',parseInt(this.value));
    }
    
    /*
     * 
     * Add To Cart Module
     */
    function tbp_add_to_cart_module(el){
        var p = el?el[0]:document;
        if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.option_ajax_add_to_cart==='yes') {//is ajax add to cart, when exist
             var addClass=function(){
                 var items = p.getElementsByClassName('tb_pro_add_to_cart');
                 for(var i=items.length-1;i>-1;--i){
                     var btn = items[i].getElementsByClassName('single_add_to_cart_button')[0];
                     if(btn!==undefined && !btn.classList.contains('ajax_add_to_cart')){
                         btn.classList.add('add_to_cart_button');
                         btn.classList.add('ajax_add_to_cart');
                         btn.setAttribute('data-product_id',btn.value);
                     }
                 }  
             };
             if(!Themify.is_builder_active){
                $( document ).on('ajaxSuccess',function(e, xhr, settings) {
                    if(settings['dataTypes'].indexOf('html')!==-1){
                        addClass();
                    }
                });
            }
            addClass();
         }
        if(Themify.is_builder_active){
            var items = p.getElementsByClassName('single_add_to_cart_button');
            for(var i=items.length-1;i>-1;--i){
                items[i].classList.add('disabled');
            }  
        }
    }

    /*
     * Cart Icon Module
     * */
    function tbp_cart_icon_module() {
        var mods=document.getElementsByClassName('module-cart-icon'),
        modsLength=mods.length;
        if (modsLength === 0 || 'undefined' === typeof $.fn || 'undefined' === typeof $.fn.themifySideMenu) {
            return;
        }
        // Slide cart icon
        var id;
        for(var i=modsLength-1;i>=0;i--){
            id=mods[i].dataset.id;
            $('a[href="#'+id+'_tbp_cart"]').themifySideMenu({
                panel: '.tbp_slide_cart',
                close: '#'+id+'_tbp_close'
            });
            // Set Body Overlay Show/Hide /////////////////////////
            var $overlay = $('.body-overlay');
            if ($overlay.length) {
                $overlay.on('click.themify touchend.themify', function () {
                    $('a[href="#'+id+'_tbp_cart"]').themifySideMenu('hide');
                });
            }
            // Show & Hide cart icon on add to cart event
            Themify.body.on('added_to_cart', function (e) {
                var slideCart = document.getElementById(id+'_tbp_cart'),
                    cartIconDropdowns = Themify.body.find('.tbp_cart_icon_container');
                if (null !== slideCart) {
                    var slideClassList = slideCart.classList;
                    slideClassList.remove('sidemenu-on');
                    slideClassList.add('sidemenu-off');
                }
                if (cartIconDropdowns.length) {
                    cartIconDropdowns.removeClass('tbp_show_cart');
                }
                setTimeout(function () {
                    if (null !== slideCart) {
                        slideClassList.remove('sidemenu-off');
                        slideClassList.add('sidemenu-on');
                    }
                    if (cartIconDropdowns.length) {
                        cartIconDropdowns.addClass('tbp_show_cart');
                    }
                    setTimeout(function () {
                        if (null !== slideCart) {
                            slideClassList.remove('sidemenu-on');
                            slideClassList.add('sidemenu-off');
                        }
                        if (cartIconDropdowns.length) {
                            cartIconDropdowns.removeClass('tbp_show_cart');
                        }
                    }, +themifyScript.ajaxCartSeconds || 1000);
                }, +themifyScript.ajaxCartSeconds || 1000);

            });
        }
    }
    $(window).one('load',function () {
        Themify.isoTop('.tbp_masonry',{itemSelector:'.tbp_masonry>.post'});
        tbp_cart_icon_module();
        Themify.body.on('change','.tb_pro_add_to_cart input',tbp_cart_icon_module_quantity);
        if ( Themify.is_builder_active ) {
            Themify.body.on( 'builder_load_module_partial', function(e,el,type){
                tbp_add_to_cart_module(el);
            } );
            if( Themify.is_builder_loaded ) {
                tbp_add_to_cart_module();
            }
        } else {
            tbp_add_to_cart_module();
        }
    });

})(jQuery);
