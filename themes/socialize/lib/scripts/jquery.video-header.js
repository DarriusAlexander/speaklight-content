var headerVideo = ( function ( $, document ) {
    
    var settings = {
		mainContainer: $( '.gp-page-header' ),
		videoContainer: $( '.gp-video-header' ),
		header: $( '.gp-video-media' ),
		videoTrigger: $( '.gp-play-video-button' ),
		closeButton: $( '.gp-close-video-button' ),
		autoPlayVideo: false
    }

    var init = function( options ) {
        settings = $.extend( settings, options );
        getVideoDetails();
        setFluidVideoContainer();
        setFluidMainContainer();
        bindClickAction();
        
        if ( videoDetails.teaser ) {
            appendTeaserVideo();
        }

        if ( settings.autoPlayVideo ) {
            appendFrame();
        }
    }

    var getVideoDetails = function() {
        videoDetails = {
            id: settings.header.attr( 'data-video-src' ),
            teaser: settings.header.attr( 'data-teaser-source' ),
            provider: settings.header.attr( 'data-provider').toLowerCase(),
            videoHeight: settings.header.attr( 'data-video-height' ),
            videoWidth: settings.header.attr( 'data-video-width' )
        }
        return videoDetails;
    };

    var setFluidVideoContainer = function () {
		
		if ( $( 'body' ).hasClass( 'gp-large-page-header' ) ) {
		
			$( window ).resize(function(){
				var winWidth = $( '.gp-page-header.gp-has-video' ).width(),
					winHeight = $( '.gp-page-header .gp-container' ).outerHeight();
				settings.videoContainer.width( winWidth ).height( winHeight );
			}).trigger('resize');
		
		} else {

			$( window ).resize(function(){
				var winWidth = $( '#gp-page-wrapper' ).width(),
					winHeight = $( '.gp-page-header .gp-container' ).outerHeight();
				settings.videoContainer.width( winWidth ).height( winHeight );
			}).trigger( 'resize' );
					
		}
		
    };

    var setFluidMainContainer = function () {

		if ( $( 'body' ).hasClass( 'gp-large-page-header' ) ) {
			$( window ).resize(function(){
				var winWidth = $( '.gp-page-header.gp-has-video' ).width(),
					winHeight = $( '.gp-page-header .gp-container' ).outerHeight();
				settings.mainContainer.width(winWidth).height(winHeight);
			}).trigger( 'resize' );
		} else {
			$( window ).resize(function(){
				var winWidth = $( '#gp-page-wrapper' ).width(),
					winHeight = $( '.gp-page-header .gp-container' ).outerHeight();
				settings.mainContainer.width( winWidth ).height( winHeight );
			}).trigger( 'resize' );		
		}
		
    };
    
    var bindClickAction = function() {
        settings.videoTrigger.on( 'click', function(e) {
            e.preventDefault();
            appendFrame();
            settings.closeButton.show();
        });
    };

    var appendTeaserVideo = function() {
        if ( Modernizr.video && ! isMobile() ) {
            var source = videoDetails.teaser,
                html = '<video autoplay="true" loop="loop" muted class="gp-teaser-video"><source src="' + source + '.ogv" type="video/ogg"><source src="' + source + '.mp4" type="video/mp4"></video>';
            settings.videoContainer.append( html );
        }
    };
    
    var createFrame = function() {
        if ( videoDetails.provider === 'youtube' ) {
            var html = '<iframe src="//www.youtube.com/embed/' + videoDetails.id + '?rel=0&amp;hd=1&autohide=1&showinfo=0&autoplay=1&enablejsapi=1&origin=*" frameborder="0" class="gp-full-video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if ( videoDetails.provider === 'vimeo' ) {
            var html = '<iframe src="//player.vimeo.com/video/' + videoDetails.id + '?title=0&amp;byline=0&amp;portrait=0&amp;color=3d96d2&autoplay=1" frameborder="0" class="gp-full-video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if(videoDetails.provider === 'html5') {
            var html = '<video autoplay="true" loop="loop" class="gp-full-video"><source src="'+videoDetails.id+'.ogv" type="video/ogg"><source src="' + videoDetails.id + '.mp4" type="video/mp4"></video>';
        }
        return html;
    };

    var appendFrame = function() {
        settings.header.hide();
        settings.videoContainer.append( createFrame() );
        removePlayButton();
        showCloseButton();
        $('.gp-teaser-video').hide();
    };

    var removeFrame = function() {
        settings.videoContainer.find( '.gp-full-video' ).remove();
        showPlayButton();
        if ( $( '#gp-page-wrapper' ).width() > 900 ) {
        	$( '.gp-teaser-video' ).show();
        }
		$( window ).resize(function(){
			var winHeight = $( '.gp-page-header .gp-container' ).outerHeight();
			settings.mainContainer.height( winHeight );
		}).trigger( 'resize' );        
    };

    var removePlayButton = function () {
        if(settings.videoTrigger) {
            settings.videoTrigger.fadeOut( 'slow' );
        }
    };

    var showPlayButton = function () {
        if ( settings.videoTrigger ) {
            settings.videoTrigger.fadeIn( 'slow' );
        }
    };
    
    var showCloseButton = function () {
		settings.closeButton.click( function() {
			settings.closeButton.hide();
			removeFrame();
		});
	};
	    
    var isMobile = function () {
        if ( $( '#gp-page-wrapper' ).width() < 900 && Modernizr.touch ) {
            return true;
        } else {
            return false;
        }
    }

    return {
        init: init
    };
    
})( jQuery, document );