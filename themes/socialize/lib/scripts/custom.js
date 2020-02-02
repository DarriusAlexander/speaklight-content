//function vc_prettyPhoto() { }  // Disable Visual Composer prettyPhoto override

jQuery( document ).ready( function( $ ) {

	'use strict';

	/*--------------------------------------------------------------
	Move left sidebar into right sidebar
	--------------------------------------------------------------*/

	function gpMoveSidebars() {
		if ( $( 'body' ).hasClass( 'gp-both-sidebars' ) && $( window ).width() <= 1120 && $( window ).width() >= 1024 ) {
			if ( $( 'body' ).hasClass( 'gp-sticky-sidebars' ) && $( 'div' ).hasClass( 'theiaStickySidebar' ) ) {
				$( '#gp-sidebar-left .theiaStickySidebar > *' ).addClass( 'gp-moved-widget' ).prependTo( '#gp-sidebar-right .theiaStickySidebar' );	
			} else {
				$( '#gp-sidebar-left > *' ).addClass( 'gp-moved-widget' ).prependTo( '#gp-sidebar-right' );	
			}
		} else {
			if ( $( 'body' ).hasClass( 'gp-sticky-sidebars' ) && $( 'div' ).hasClass( 'theiaStickySidebar' ) ) {
				$( '.gp-moved-widget' ).prependTo( '#gp-sidebar-left .theiaStickySidebar' );
			} else {
				$( '.gp-moved-widget' ).prependTo( '#gp-sidebar-left' );
			}
		}
	}
	
	gpMoveSidebars();
	$( window ).resize( gpMoveSidebars );
		
		
	/*--------------------------------------------------------------
	Retina images
	--------------------------------------------------------------*/

	if ( $( 'body' ).hasClass( 'gp-retina' ) ) {
		window.devicePixelRatio >= 2 && $( '.gp-post-thumbnail img' ).each( function() {
			$( this ).attr( { src: $( this ).attr( 'data-rel' ) } );
		});
	}
	
			
	/*--------------------------------------------------------------
	Parallax effect
	--------------------------------------------------------------*/

	if( $( 'div' ).hasClass( 'gp-parallax' ) || $( 'header' ).hasClass( 'gp-parallax' ) ) {
		$( '.gp-parallax' ).css( 'opacity', 0 );		
		$( window ).load( function() {
			$.stellar({
				responsive: true,
				horizontalScrolling: false
			});
			$( '.gp-parallax' ).css( 'opacity', 1 );
		});
	}
				
				
	/*--------------------------------------------------------------
	Blog masonry
	--------------------------------------------------------------*/

	if ( $( '.gp-blog-wrapper' ).hasClass( 'gp-blog-masonry' ) ) {
	
		var container = $( '.gp-blog-masonry .gp-inner-loop' ),
			element = container;

		if ( container.find( 'img' ).length == 0 ) {
			element = $( '<img />' );
		}
			
		imagesLoaded( element, function( instance ) {

			container.isotope({
				itemSelector: 'section',
				percentPosition: true,
				masonry: {
					columnWidth: container.find( 'section' )[0],
					gutter: '.gp-gutter-size'
				}
			});

			container.animate( { 'opacity': 1 }, 1300 );
			$( '.gp-pagination' ).animate( { 'opacity': 1 }, 1300 );

		});
				
	}
	
	
	/*--------------------------------------------------------------
	Portfolio masonry
	--------------------------------------------------------------*/
		
	if ( $( '#gp-portfolio' ).hasClass( 'gp-portfolio-wrapper' ) ) {
	
		var container = $( '#gp-portfolio .gp-inner-loop' ),
			element = container;

		if ( container.find( 'img' ).length == 0 ) {
			element = $( '<img />' );
		}

		if ( container.find( '.gp-portfolio-item' ).length == 1 ) {
			var columnwidth = '.gp-portfolio-item';
		} else {
			var columnwidth = '.gp-portfolio-item:nth-child(3n)';
		}	

		imagesLoaded( element, function( instance ) {

			container.isotope({
				itemSelector: '.gp-portfolio-item',
				percentPosition: true,
				filter: '*',
				masonry: {
					columnWidth: columnwidth,
					gutter: '.gp-gutter-size'
				}
			});

			container.animate( { 'opacity': 1 }, 1300 );
			$( '.gp-pagination' ).animate( { 'opacity': 1 }, 1300 );

		});

		// Add portfolio filters
		$( '#gp-portfolio-filters ul li a' ).click( function() {

			var selector = $( this ).attr( 'data-filter' );
			container.isotope( { filter: selector } );

			$( '#gp-portfolio-filters ul li a' ).removeClass( 'gp-active' );
			$( this ).addClass( 'gp-active' );

			return false;

		});
		
		// Remove portfolio filters not found on current page
		if ( $( 'div' ).hasClass( 'gp-portfolio-filters' ) ) {

			var isotopeCatArr = [];
			var $portfolioCatCount = 0;
			$( '#gp-portfolio-filters ul li' ).each( function( i ) {
				if ( $( this ).find( 'a' ).length > 0 ) {
					isotopeCatArr[$portfolioCatCount] = $( this ).find( 'a' ).attr( 'data-filter' ).substring( 1 );	
					$portfolioCatCount++;
				}
			});

			isotopeCatArr.shift();

			var itemCats = '';

			$( '#gp-portfolio .gp-inner-loop > .gp-portfolio-item' ).each( function( i ) {
				itemCats += $( this ).attr( 'data-portfolio-cat' );
			});
			itemCats = itemCats.split( ' ' );

			itemCats.pop();

			itemCats = $.unique( itemCats );

			var notFoundCats = [];
			$.grep( isotopeCatArr, function( el ) {
				if ( $.inArray(el, itemCats ) == -1 ) {
					notFoundCats.push( el  );
				}
			});

			if ( notFoundCats.length != 0 ) {
				$( '#gp-portfolio-filters ul li' ).each( function() {
					if ( $( this ).find( 'a' ).length > 0 ) {
						if( $.inArray( $( this ).find( 'a' ).attr( 'data-filter' ).substring( 1 ), notFoundCats ) != -1 ) {
							$( this ).hide();
						}
					}
				});
			}

		}

	}
	
	
	/*--------------------------------------------------------------
	Portfolio slider
	--------------------------------------------------------------*/

	$( window ).load( function() {
		$( '.gp-portfolio-slider' ).flexslider({ 
			animation: 'fade',
			slideshowSpeed: 9999999,
			animationSpeed: 600,
			directionNav: true,			
			controlNav: false,			
			pauseOnAction: true, 
			pauseOnHover: false,
			prevText: '',
			nextText: '',
			smoothHeight: true
		});
	});	
	
	
	/*--------------------------------------------------------------
	Gallery category post slider
	--------------------------------------------------------------*/

	$( window ).load( function() {
		$( '.gp-blog-wrapper:not(.gp-blog-masonry) .gp-post-format-gallery-slider, .gp-showcase-wrapper .gp-post-format-gallery-slider' ).flexslider( {
			animation: 'fade',
			slideshowSpeed: 9999999,
			animationSpeed: 600,
			directionNav: true,			
			controlNav: false,			
			pauseOnAction: true, 
			pauseOnHover: false,
			prevText: '',
			nextText: '',
			smoothHeight: true
		});
	});

	$( '.gp-blog-masonry .gp-post-format-gallery-slider' ).flexslider( { 
		animation: 'fade',
		slideshowSpeed: 9999999,
		animationSpeed: 600,
		directionNav: true,			
		controlNav: false,			
		pauseOnAction: true, 
		pauseOnHover: false,
		prevText: '',
		nextText: ''
	});
		

	/*--------------------------------------------------------------
	Gallery single post slider
	--------------------------------------------------------------*/

	$( window ).load( function() {
		$( '.gp-entry-featured .gp-post-format-gallery-slider' ).flexslider( { 
			animation: 'fade',
			slideshowSpeed: 9999999,
			animationSpeed: 600,
			directionNav: true,			
			controlNav: false,			
			pauseOnAction: true, 
			pauseOnHover: false,
			prevText: '',
			nextText: '',
			smoothHeight: true
		});
	});
	
	
	/*--------------------------------------------------------------
	Related items slider
	--------------------------------------------------------------*/

	var $window = $( window ),
		flexslider = { vars:{} };

	function getGridSize() {
		return ( $window.width() <= 567 ) ? 1 : ( $window.width() <= 1023 ) ? 2 : ghostpool_script.related_items_in_view;
	}

	$window.load( function() {
		$( '.gp-related-wrapper' ).flexslider({  
			animation: 'slide',
			animationLoop: false,
			itemWidth: ghostpool_script.related_items_in_view,
			itemMargin: 30,
			slideshowSpeed: 9999999,
			animationSpeed: 600,
			directionNav: true,			
			controlNav: false,			
			pauseOnAction: true, 
			pauseOnHover: false,
			prevText: '',
			nextText: '',
			minItems: getGridSize(),
			maxItems: getGridSize(),
			start: function(slider){
				flexslider = slider;
			}
		});	
	});
			
	$window.resize( function() {
		var gridSize = getGridSize();
		flexslider.vars.minItems = gridSize;
		flexslider.vars.maxItems = gridSize;
	});			

	
	/*--------------------------------------------------------------
	Switch navigation position if near edge
	--------------------------------------------------------------*/

	function gpSwitchNavPosition() {
		$( '#gp-main-nav .menu > li.gp-standard-menu' ).each( function() {
			$( this ).on( 'mouseenter mouseleave', function(e) {
				if ( $( this ).find( 'ul' ).length > 0 ) {
					var menuElement = $( 'ul:first', this ),
						pageWrapper = $( '#gp-main-header .gp-container' ),
						pageWrapperOffset = pageWrapper.offset(),
						menuOffset = menuElement.offset(),
						menuLeftOffset = menuOffset.left - pageWrapperOffset.left,
						pageWrapperWidth = pageWrapper.width();
						if ( $( this ).hasClass( 'gp-dropdowncart-menu' ) ) {							
							var menuWidth = menuElement.width();
						} else {
							var menuWidth = menuElement.width() + 200;
						}
						var isEntirelyVisible = ( menuLeftOffset + menuWidth <= pageWrapperWidth );	
					if ( ! isEntirelyVisible ) {
						$( this ).addClass( 'gp-nav-edge' );
					} else {
						$( this ).removeClass( 'gp-nav-edge' );
					}
				}   
			});
		});	
	}

	gpSwitchNavPosition();
	$( window ).resize( gpSwitchNavPosition );
		
	    
	/*--------------------------------------------------------------
	Mega menus text/image support
	--------------------------------------------------------------*/
		
	if ( $( '.gp-megamenu' ).length > 0 ) {
		
		$( '.gp-megamenu > .sub-menu > li > a, .gp-menu-text > a' ).contents().unwrap().wrap( '<span></span>' );
			
		$( '.gp-nav .gp-megamenu .sub-menu .sub-menu li.gp-menu-image' ).each( function() {
			if ( $( this ).find( 'a' ).length > 0 ) {	
				var src = $( this ).find( 'a' ).attr( 'href' );
				$( '<img class="gp-menu-image" alt="">' ).insertAfter( $( this ).children( ':first' ) );
				$( this ).find( '.gp-menu-image' ).attr( 'src', src );
				$( this ).find( 'a' ).remove();				
			}			
		});
	
		$( '#gp-mobile-nav .gp-menu-image' ).hide();
	
	}
	

	/*--------------------------------------------------------------
	FontAwesome menu icons
	--------------------------------------------------------------*/
		
	$( '.menu li.fa' ).each( function() {	
		var all = $( this ).attr( 'class' ).split(' ');
		for ( var i = 0; i < all.length; ++i ) {
			var cls = all[i];
			if ( cls.indexOf( 'fa' ) == 0 ) {
				$( this ).find( '> a:first-child' ).addClass( cls );
				$( this ).removeClass( cls );
			}
		}
	});
	
					
	/*--------------------------------------------------------------
	Dropdown menu icons
	--------------------------------------------------------------*/
		
	$( '#gp-main-nav .menu > li' ).each( function() {
		if ( $( this ).find( 'ul' ).length > 0 ) {	
			$( '<i class="gp-dropdown-icon gp-primary-dropdown-icon fa fa-angle-down" />' ).appendTo( $( this ).children( ':first' ) );		
		}		
	});
	
	$( '#gp-main-nav .menu > li.gp-standard-menu ul > li' ).each( function() {
		if ( $( this ).find( 'ul' ).length > 0 ) {	
			$( '<i class="gp-dropdown-icon gp-secondary-dropdown-icon fa" />' ).appendTo( $( this ).children( ':first' ) );
		}					
	});
	
							
	/*--------------------------------------------------------------
	Slide in/out header mobile navigation
	--------------------------------------------------------------*/

	function gpHeaderMobileNav() {
		$( '#gp-mobile-nav-button' ).click( function() {
			$( 'body' ).addClass( 'gp-mobile-nav-active' );
		});
		
		$( '#gp-mobile-nav-close-button, #gp-mobile-nav-bg' ).click( function() {
			$( 'body' ).removeClass( 'gp-mobile-nav-active' );
		});		
	}
	
	gpHeaderMobileNav();
	

	/*--------------------------------------------------------------
	Slide up/down header mobile dropdown menus
	--------------------------------------------------------------*/

	$( '#gp-mobile-nav .menu li' ).each( function() {
		if ( $( this ).find( 'ul' ).length > 0 ) {
			$( '<i class="gp-mobile-dropdown-icon" />' ).insertAfter( $( this ).children( ':first' ) );		
		}		
	});
	
	function gpHeaderMobileTopNav() {

		$( '#gp-mobile-nav ul > li' ).each( function() {
			
			var navItem = $( this );
			
			if ( $( navItem ).find( 'ul' ).length > 0 ) {	
		
				$( navItem ).children( '.gp-mobile-dropdown-icon' ).toggle( function() {
					$( navItem ).addClass( 'gp-active' );
					$( navItem ).children( '.sub-menu' ).stop().slideDown()
					$( '#gp-mobile-nav' ).addClass( 'gp-auto-height' );
				}, function() {
					$( navItem ).removeClass( 'gp-active' );
					$( navItem ).children( '.sub-menu' ).stop().slideUp();
				});
		
			}
					
		});
	
	}
	
	gpHeaderMobileTopNav();


	/*--------------------------------------------------------------
	Search box
	--------------------------------------------------------------*/

	$( document ).mouseup(function(e) {		
		var container = $( '#gp-search' );
		if ( ! container.is( e.target ) && container.has( e.target ).length === 0) {
			$( '#gp-search-box' ).hide();
			$( '#gp-search-button' ).removeClass( 'gp-active' );
		}
	});		
	
	$( document ).on( 'click', '#gp-search-button:not(.gp-active)', function() {
		$( this ).addClass( 'gp-active' );
		$( '#gp-search-box' ).show();
		$( '#gp-search-box .gp-search-bar' ).focus();
	});
	
	$( document).on( 'click', '#gp-search-button.gp-active', function() {
		$( this ).removeClass( 'gp-active' );
		$( '#gp-search-box' ).hide();
	});

				
	/*--------------------------------------------------------------
	Smooth scroll
	--------------------------------------------------------------*/

	if ( $( 'body' ).hasClass( 'gp-smooth-scrolling' ) && $( window ).width() > 767 && $( 'body' ).outerHeight( true ) > $( window ).height() ) {
		$( 'html' ).niceScroll({
			cursorcolor: '#424242',
			scrollspeed: 100,
			mousescrollstep: 40,
			cursorwidth: 10,
			cursorborder: '0',
			zindex: 10000,
			cursoropacitymin: 0.3,
			cursoropacitymax: 0.6
		});
	}

	
	/*--------------------------------------------------------------
	Back to top button
	--------------------------------------------------------------*/

	if ( ! $( 'body' ).hasClass( 'gp-no-back-to-top' ) ) {	
		$().UItoTop({ 
			containerID: 'gp-to-top',
			text: '<i class="fa fa-chevron-up"></i>',
			scrollSpeed: 600
		});
	}
		

	/*--------------------------------------------------------------
	prettyPhoto lightbox
	--------------------------------------------------------------*/

	if ( ghostpool_script.lightbox != 'disabled' ) {
		$( 'a.prettyphoto, a[data-rel^="prettyPhoto"]' ).prettyPhoto({
			hook: 'data-rel',
			theme: 'pp_default',
			deeplinking: false,
			social_tools: '',
			default_width: '768'
		});
	}
	

	/*--------------------------------------------------------------
	Share icons panel
	--------------------------------------------------------------*/
		
	$( '.gp-share-button' ).toggle( function() {
		$( '#gp-post-navigation #gp-share-icons' ).stop().slideDown();
		$( this ).addClass( 'gp-active' );
	}, function() {
		$( '#gp-post-navigation #gp-share-icons' ).stop().slideUp();
		$( this ).removeClass( 'gp-active' );
	});
	
	
	/*--------------------------------------------------------------
	Title header video
	--------------------------------------------------------------*/
	
	if ( $( '.gp-page-header' ).hasClass( 'gp-has-video' ) ) {
		headerVideo.init({
			mainContainer: $( '.gp-page-header' ),
			videoContainer: $( '.gp-video-header' ),
			header: $( '.gp-video-media' ),
			videoTrigger: $( '.gp-play-video-button' ),
			closeButton: $( '.gp-close-video-button' ),
			autoPlayVideo: false
		});
	}


	/*--------------------------------------------------------------
	Resize header upon scrolling
	--------------------------------------------------------------*/

	function gpResizeHeader() {
	
		if ( ! $( 'body' ).hasClass( 'gp-relative-header' ) ) {
		
			var mainHeaderHeight = $( '#gp-main-header' ).innerHeight();
			if ( $( '#gp-top-header' ).length > 0 ) {
				var topHeaderHeight = $( '#gp-top-header' ).innerHeight();
			} else {
				var topHeaderHeight = 0;
			}
			
			if ( $( '#gp-top-header' ).length > 0 ) {
				var adminBar = $( '#wpadminbar' ).innerHeight();
			} else {
				var adminBar = 0;
			}
					
			$( '#gp-fixed-header-padding' ).height( mainHeaderHeight + topHeaderHeight );
					
			if ( $( window ).width() <= 600 && $( 'body' ).hasClass( 'gp-fixed-header-all' ) ) {
				if ( $( document ).scrollTop() >= ( topHeaderHeight + 1 + adminBar ) ) {
					$( 'body' ).addClass( 'gp-mobile-scrolling' ).removeClass( 'gp-desktop-scrolling' );
				} else {
					$( 'body' ).removeClass( 'gp-mobile-scrolling' );
				}
			} else if ( $( window ).width() <= 1082 && $( 'body' ).hasClass( 'gp-fixed-header-all' ) ) {
				if ( $( document ).scrollTop() >= topHeaderHeight + 1 ) {
					$( 'body' ).addClass( 'gp-mobile-scrolling' ).removeClass( 'gp-desktop-scrolling' );
				} else {
					$( 'body' ).removeClass( 'gp-mobile-scrolling' );
				}
			} else if ( $( window ).width() > 1082 ) {	
				if ( $( document ).scrollTop() >= topHeaderHeight + 1 ) {
					$( 'body' ).addClass( 'gp-desktop-scrolling' ).removeClass( 'gp-mobile-scrolling' );
				} else {
					$( 'body' ).removeClass( 'gp-desktop-scrolling' );
				}
			} else {
				$( 'body' ).removeClass( 'gp-desktop-scrolling gp-mobile-scrolling' );
			}

		}
		
	}

	gpResizeHeader();
	$( window ).resize( gpResizeHeader );
	$( window ).scroll( gpResizeHeader );		
	

	/*--------------------------------------------------------------
	Close reset success message
	--------------------------------------------------------------*/

	$( '#gp-close-reset-message' ).click( function() {
		$( '#gp-reset-message' ).remove();
	});
	

	/*--------------------------------------------------------------
	Remove "|" from BuddyPress item options
	--------------------------------------------------------------*/

	$( '.item-options' ).contents().filter( function() {
		return this.nodeType == 3;
	}).remove();


	/*--------------------------------------------------------------
	Hide BuddyPress item options if width too small
	--------------------------------------------------------------*/

	function gpBPWidgetOptions() {
		
		$( '.widget.buddypress' ).each( function() {
			
			var widget = $( this ),
				optionsWidth = 325,
				widgettitle = widget.find( '.widgettitle' ).html(),
				textWidth = widget.find( '.gp-widget-title' ).width(),
				containerWidth = widget.find( '.widgettitle' ).width();

			if ( ( containerWidth - optionsWidth ) > textWidth ) {
				widget.find( '.item-options' ).removeClass( 'gp-small-item-options' );
				widget.find( '.gp-item-options-button' ).remove();
			} else {	
				widget.find( '.item-options' ).addClass( 'gp-small-item-options' );
				widget.find( '.item-options' ).append( '<div class="gp-item-options-button"></div>' );
				widget.find( '.item-options' ).show();
			}
			
			widget.find( '.gp-item-options-button' ).toggle( function() {
				widget.find( '.gp-small-item-options' ).addClass( 'gp-active' );
			}, function() {
				widget.find( '.gp-small-item-options' ).removeClass( 'gp-active' );
			});		
						
		});
		
	}
	
	gpBPWidgetOptions();
	$( window ).resize( gpBPWidgetOptions );

	
	/*--------------------------------------------------------------
	BuddyPress tabs for mobile
	--------------------------------------------------------------*/			
						
	$( '.item-list-tabs:not(#subnav)' ).prepend( '<div id="gp-bp-tabs-button"></div>' );
	var bptabs = $( '.item-list-tabs:not(#subnav) > ul' );
	
	function gpBPTabs() {

		if ( $( '.item-list-tabs:not(#subnav)' ).find( 'ul' ).length > 0 ) {	

			if ( $( window ).width() <= 567 && $( 'body' ).hasClass( 'gp-responsive' ) ) {
	
				$( bptabs ).hide();

				$( '#gp-bp-tabs-button' ).toggle( function() {
					$( bptabs ).stop().slideDown();
					$( this ).addClass( 'gp-active' );
				}, function() {
					$( bptabs ).stop().slideUp();
					$( this ).removeClass( 'gp-active' );
				});
		
			} else {
		
				$( bptabs ).css( 'height', 'auto' ).show();
		
			}
		
		}
						
	}
	
	gpBPTabs();
	$( window ).resize( gpBPTabs );


	/*--------------------------------------------------------------
	Login box
	--------------------------------------------------------------*/

	// Submit forms
	var formArray = ['.gp-login-form', '.gp-lost-password-form', '.gp-register-form'];
	
	$.each( formArray, function( index, value ) {
	
		$( value ).submit( function() {
			var form = $( this ); 
			form.find( '.gp-login-results' ).html( '<span class="gp-verify-form">' + $( '.gp-login-results' ).data( 'verify' ) + '</span>' ).fadeIn();
			var input_data = form.serialize();
			$.ajax({
				type: "POST",
				url:  ghostpool_script.url,
				data: input_data,
				success: function( msg ) {
					form.find( '.gp-verify-form' ).remove();
					$( '<span>' ).html( msg ).appendTo( form.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
					/*if ( ! $( '.gp-register-form' ).find( '.gp-login-results > span' ).hasClass( 'error' ) ) {						
						$( '.gp-register-form' ).find( 'input[type="text"]' ).val( '' );
						$( '.gp-register-form' ).find( 'p' ).hide();
						$( '.gp-register-form' ).find( '.wp-submit' ).hide();
					}*/
				},
				error: function( xhr, status, error ) {
				
					// Reset captcha on error
					if ( $( '.gglcptch' ).length > 0 ) {
						grecaptcha.getResponse();
						grecaptcha.reset();
					}
					
					form.find( '.gp-verify-form' ).remove();
					$( '<span>' ).html( xhr.responseText ).appendTo( form.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
					
				}
			});
			return false;
		});
	
	});		
	
	// Close modal window when clicking outside of it		
	$( document ).mouseup(function(e) {
		var container = $( '#gp-login-modal' );
		if ( ! container.is( e.target ) && container.has( e.target ).length === 0) {
			$( '#login' ).hide();
		}
	});
			
	// Close modal window when clicking close button
	$( '#gp-login-close' ).click( function() {		
		$( '#login' ).hide();
		$( '.gp-login-results > span' ).remove();
	});	
	
	// Open login modal window when clicking links		
	$( '.gp-switch-to-login, a[href="#login"]' ).click( function() {
		$( '#login' ).show();
		$( '.gp-login-form-wrapper' ).show();
		$( '.gp-register-form-wrapper, .gp-lost-password-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	});		

	// Open login modal window directly from URL
	if ( /#login/.test( window.location.href ) ) {
		$( '#login' ).show();
		$( '.gp-login-form-wrapper' ).show();
		$( '.gp-register-form-wrapper, .gp-lost-password-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	}	
								
	// Open lost password modal window when clicking link								
	$( '.gp-switch-to-lost-password' ).click( function() {
		$( '.gp-lost-password-form-wrapper' ).show();
		$( '.gp-register-form-wrapper, .gp-login-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	});		

	// Open lost password modal window directly from URL
	if ( /#lost-password/.test( window.location.href ) ) {
		$( '#login' ).show();
		$( '.gp-lost-password-form-wrapper' ).show();
		$( '.gp-register-form-wrapper, .gp-login-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	}
	
	// Open registration modal window when clicking links		
	$( 'a[href="#register"]' ).click( function() {
		$( '#login' ).show();
		$( '.gp-register-form-wrapper, .gp-register-form .login-form > p, .gp-register-form .wp-submit' ).show();
		$( '.gp-register-form .login-form p > input[type="text"]' ).val( '' );
		$( '.gp-login-form-wrapper, .gp-lost-password-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	});

	// Open registration modal window directly from URL
	if ( /#register/.test( window.location.href ) ) {
		$( '#login' ).show();
		$( '.gp-register-form-wrapper, .gp-register-form .login-form > p, .gp-register-form .wp-submit' ).show();
		$( '.gp-register-form .login-form p > input[type="text"]' ).val( '' );
		$( '.gp-login-form-wrapper, .gp-lost-password-form-wrapper, .gp-social-login-form-wrapper' ).hide();
		$( '.gp-login-results > span' ).remove();
	}
				
	// Close reset success message	
	$( '#gp-close-reset-message' ).click( function() {
		$( '#gp-reset-message' ).remove();
	});

});