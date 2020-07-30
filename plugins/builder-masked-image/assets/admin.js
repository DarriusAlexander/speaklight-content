jQuery(function ($) {
        'use strict';
	var cache = [],
            isLoaded=null;
	document.addEventListener( 'tb_editing_masked-image', function ( e) {
               
               var top = window.top.document;
                if(isLoaded===null){
                    isLoaded=true;
                    window.top.Themify.LoadCss(builderMask.admin_css,builderMask.v);
                }
                top.getElementById('mask_preset').addEventListener('click',function(e){
                    var el = e.target.closest('.tfl-icon');
                    if(el!==null){
                        var val = el.id,
                            image=top.getElementById('mask_image');
                        image.value=val;
                        image.closest('.tb_field').getElementsByClassName('thumb_preview')[0].getElementsByTagName('img')[0].src=val;
                        Themify.triggerEvent(image,'change');
                    }
                });
                top.getElementById('mask_icon').addEventListener('change',function(e){
                    function callback( data, status ) {
                                status = !status?'spinhide':'error';
                                ThemifyBuilderCommon.showLoader( status );
                                var el =top.getElementById('mask_icon_data');
                                el.value=data;
                                Themify.triggerEvent( el, 'keyup' );
                        }
                        var val = this.value.trim();
                        if ( cache[val] !== undefined ) {
                                callback( cache[ val ], false );
                                return;
                        }
                        var path = builderMask.path,
                                sub = val.substr( 0, 3 );
                        if ( sub === 'ti-' || sub === 'fa-' ) {
                                path += ( sub === 'tl-' ? 'themify' : 'fa' ) + '/' + val.substr( 3 ) + '.svg';
                        } else {
                                callback( '',true );
                                return;
                        } 
                        ThemifyBuilderCommon.showLoader( 'show' );
                        $.ajax( {
                                url : path,
                                dataType: 'text',
                                success : function( data ) { 
                                        callback(data,false);
                                        cache[val] = data;
                                },
                                error : function( jqXHR, status, errorThrown ) {
                                        callback('',true);
                                }
                        } );
                });
	} );
});
