( function ( $ ) {

    $.themeSetup = function () {
        this.scope = $( document );
        this.init();
    };

    $.themeSetup.prototype = {
        init : function() {
            var fw = this;

            fw.init_tabs();
            
            // Init theme registration form
            fw.init_theme_registration();
            
            // Init tooltips
            fw.init_tooltips();
            
            // Init plugin ajax actions
            fw.init_addons_ajax();
        },


		/*--------------------------------------------------------------
		Tooltips
		--------------------------------------------------------------*/

        init_tooltips : function() {
            $( '.tooltip-me' ).tooltip( {
                position : { my: 'center bottom', at: 'center top-10' }
            } );
        },


		/*--------------------------------------------------------------
		Theme setup tabs
		--------------------------------------------------------------*/

        init_tabs : function() {

            var tabs = $( '.gp-tabs' );

            tabs.each(function(){
                var tab = $(this),
                    tabItems = tab.find( 'ul.gp-tabs-navigation' ),
                    tabContentWrapper = tab.children( 'ul.gp-tabs-content' ),
                    tabNavigation = tab.find( 'nav' );

                tabItems.on( 'click', 'a', function( event ) {
                    event.preventDefault();
                    var selectedItem = $( this );
                    if( !selectedItem.hasClass( 'selected' ) ) {
                        var selectedTab = selectedItem.data( 'content' ),
                            selectedContent = tabContentWrapper.find( 'li[data-content="' + selectedTab + '"]' );
                        tabItems.find( 'a.selected' ).removeClass( 'selected' );
                        selectedItem.addClass( 'selected' );
                        selectedContent.addClass( 'selected' ).siblings( 'li' ).removeClass( 'selected' );
                        window.location.hash = selectedTab;
                    }
                });

            });

            // Activate specific link on new page
            var hash = window.location.hash;
            var nav_li = $( '.gp-tabs-navigation > li' );
            var string = hash.replace( '-link', '' );
            if ( hash !== '' && nav_li.find( 'a[href="' + string + '"]' ).length ) {
                nav_li.find( 'a[href="' + string + '"]' ).trigger( 'click' );
            }

        },


		/*--------------------------------------------------------------
		Theme updates form
		--------------------------------------------------------------*/

        init_theme_registration : function(){
            $ ( '.gp-theme-updates-form' ).submit( function( e ) {
                e.preventDefault();

                var username = $( '#gp-tf-username', this ).val(),
                    api_key = $( '#gp-tf-apikey', this ).val(),
                    nonce = $( '#ghostpool_theme_updates_nonce', this ).val(),
                    form = $( this ),
                    responseEl = $( '.gp-response-area', this );

                if ( form.hasClass( 'gp-submitting' ) ) {
                    return;
                }

                // bail out
                if ( ! username.length || ! api_key.length || ! nonce.length ) {
                    $( this ).addClass( 'gp-theme-updates-form-error' );
                    return;
                }

                var data = {
                    'action': 'ghostpool_theme_updates_action',
                    'username': username,
                    'api_key': api_key,
                    'ghostpool_theme_updates_nonce': nonce
                };

                // Perform the Ajax call
                $.ajax({
                    url: ajaxurl,
                    data: data,
                    type: 'POST',
                    success: function ( response ) {
                        // If we received an error, display it
                        if ( response.success === false ) {
                            if ( response.data.error ) {
                                responseEl.html( 'ERROR: ' + response.data.error ).removeClass( 'hidden' );
                            }
                        }
                        else if ( response.success === true ) {
                            responseEl.html( response.data.message ).removeClass( 'hidden' );
                        }
                        else {
                            responseEl.html( 'Something went wrong. Please try again later.' ).removeClass( 'hidden' );
                        }
                    },
                    beforeSend : function() {
                        form.addClass( 'gp-submitting' );
                        responseEl.addClass( 'hidden ').html( '' );

                    },
                    complete : function() {
                        form.removeClass( 'gp-submitting' );
                    }
                });
            });
        },


		/*--------------------------------------------------------------
		Theme addons
		--------------------------------------------------------------*/

        init_addons_ajax : function(){
            var fw = this;

            $( document ).on( 'click', '.gp-addon-button', function( e ) {
                e.preventDefault();

                // Perform the ajax call based on action
                var config = {};
                config.button			= $( this );
                config.status_classes	= 'gp-active gp-inactive gp-not-installed';
                config.el_container	    = config.button.closest( '.gp-addon' );
                //config.status_holder	= config.el_container.find( '.gp-addon-status' );
                config.action			= config.button.data( 'action' );
                config.nonce			= config.button.data( 'nonce' );
                config.slug				= config.button.data( 'slug' );

                if ( config.el_container.hasClass( 'gp-addons-disabled' ) ) {
                    return false;
                }

                var data = {
                    security: config.nonce,
                    action: 'ghostpool_do_plugin_action',
                    plugin_action: config.button.data( 'action' ) || false,
                    slug: config.button.data( 'slug' ) || false,
                };

                // Don't allow the user to click the button multiple times
                if ( config.button.hasClass( 'gp-is-active' ) ) { return false; }

                // Add the loading class
                config.button.addClass( 'gp-is-active' );

                fw.perform_ajax_call( data, config );

                return false;
            });
        },

        perform_ajax_call : function( data, config, callback ) {

            $.ajax({
                'type': 'post',
                'dataType': 'json',
                'url': ajaxurl,
                'data': data,
                'success': function( response ) {

                    // If we received an error, display it
                    if ( response.data.error ) {
                        alert( response.data.error );
                    }

                    // Update the plugin status
                    config.el_container.removeClass( config.status_classes );
                    config.el_container.addClass( response.data.status );
                    //config.status_holder.text( response.data.status_text );

                    // Update the plugin
                    config.button.data( 'action', response.data.action );
                    config.button.text( response.data.action_text );

                    if ( typeof callback != 'undefined' ) {
                        callback();
                    }

                    config.button.removeClass( 'gp-is-active' );
                    
                },
                'error' : function( response ) {
                    if ( typeof callback != 'undefined' ) {
                        callback();
                    }
                    alert( 'There was a problem performing the action.' );
                    config.button.removeClass( 'gp-is-active' );
                }
            });
        }
    };


	/*--------------------------------------------------------------
	Run when document ready
	--------------------------------------------------------------*/

    $(document).ready(function() {
        $.themeSetup = new $.themeSetup();
    });

})( jQuery );