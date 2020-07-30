/*FitText 1.1*/
!function (a) {
    a.fn.fitText = function (b) {
        var c, d = a.extend({minFontSize: Number.NEGATIVE_INFINITY, maxFontSize: Number.POSITIVE_INFINITY, lineCount: 1, scale: 100}, b);
        return this.each(function () {
            var b = a(this);
            b.css({"white-space": "nowrap", position: "absolute", width: "auto"}), c = parseFloat(b.width()) / parseFloat(b.css("font-size")), b.css({position: "", width: "", "white-space": ""});
            var e = function () {
                b.css("font-size", Math.max(Math.min(d.scale / 100 * d.lineCount * b.width() / c - d.lineCount, parseFloat(d.maxFontSize)), parseFloat(d.minFontSize)));
            };
            e(), a(window).on("tfsmartresize.fittext orientationchange.fittext", e);
        });
    };
}(jQuery);
(function ($) {
    "use strict";
    var isWorking=null,
    do_fittext = function (el) {
        var items = $('.module.module-fittext', el);
        if(el && el[0].classList.contains('module-fittext')){
            items = items.add(el);
        }
        var callback =function () {
            function apply_fittext(el) {
                el.find('span').fitText().fitText(); // applying it twice fixes the issue of text breaking with some fonts.
                el.css('visibility', 'visible');
            }
            items.each(function () {
                var thiz = $(this),
                    _font = thiz.data('fontFamily');
                    if (!_font || _font === 'default' || builderFittext.webSafeFonts.indexOf(_font)!==-1) {
                            apply_fittext(thiz);
                    } else {
                            var load = function(){
                                if ( Themify.is_builder_active && ThemifyConstructor.font_select.loaded_fonts.indexOf(_font)===-1) {
                                    ThemifyConstructor.font_select.loaded_fonts.push(_font); 
                                }  
                            };
                            WebFont.load({
                                    google: {
                                            families: [_font]
                                    },
                                    fontactive: function () {
                                        apply_fittext(thiz);
                                        load();
                                    },
                                    fontinactive: function () { // fail-safe: in case font fails to load, use the fallback font and apply the effect.
                                        apply_fittext(thiz);
                                        load();
                                    }
                            });
                    }
            });
            isWorking=null;
        };
        if (items.length > 0) {
            if (typeof WebFont === 'undefined') {
                Themify.LoadAsync('//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', callback, '1.4.7', false, function () {
                    return typeof WebFont !== 'undefined';
                });
            }
            else {
                callback();
            }
        }
        else{
            isWorking=null;
        }
    }
    isWorking=true;
    if ( Themify.is_builder_active ) {
        var interval,
            timeout=null;
        Themify.body.on('tb_module_sort tb_grid_changed', function (e, el, type) { do_fittext(el); })
        .on( 'tb_module_styling', function( e, type,prop, val,orig ,el) { 
                if(type==='fittext' && prop!=='color' && prop!=='backgroundColor'){
                    if(prop==='fontWeight' || (prop==='fontFamily' && builderFittext.webSafeFonts.indexOf(val)===-1)){
                        interval = setInterval(function(){
                            if(prop==='fontWeight' || ThemifyConstructor.font_select.loaded_fonts.indexOf(val)!==-1){
                                clearInterval(interval);
                                if(timeout!==null){
                                    clearTimeout(timeout);
                                }
                                if(prop==='fontFamily'){
                                    el.data('fontFamily','');
                                }
                                timeout = setTimeout(function(){do_fittext(el);},160); 
                            }
                        },10);
                    }
                    else{
                        do_fittext(el); 
                    }
                    
                }
            } );
        if(Themify.is_builder_loaded){
            do_fittext();
        }
        else{
            isWorking=null;
        }
    }
    else{
        do_fittext();
    }
    Themify.body.on('builder_load_module_partial',function(e,el,type){
        if(isWorking===null){
            do_fittext(el); 
        }
    });
    
})(jQuery);