<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "socialize";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Theme Options', 'socialize' ),
        'page_title'           => esc_html__( 'Theme Options', 'socialize' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => 'AIzaSyDipV4M7FL2ylBHtJ5OvW1CSBWTyKKrP6E',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-admin-generic',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

	// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
	$args['admin_bar_links'][] = array(
		'id'    => 'gp-help',
		'href'   => 'http://ghostpool.com/help/socialize/help.html',
		'title' => esc_html__( 'Help File', 'socialize' ),
	);

	$args['admin_bar_links'][] = array(
		'id'    => 'gp-changelog',
		'href'   => 'http://ghostpool.com/help/socialize/changelog.html',
		'title' => esc_html__( 'Changelog', 'socialize' ),
	);

	$args['admin_bar_links'][] = array(
		'id'    => 'gp-support',
		'href'   => 'http://ghostpool.ticksy.com',
		'title' => esc_html__( 'Support', 'socialize' ),
	);

	// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
	$args['share_icons'][] = array(
		'url'   => 'http://twitter.com/ghostpool',
		'title' => esc_html__( 'Follow us on Twitter', 'socialize' ),
		'icon'  => 'el el-icon-twitter'
	);

    // Panel Intro text -> before the form
   /* if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( esc_html__( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'socialize' ), $v );
    } else {
        $args['intro_text'] = esc_html__( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'socialize' );
    }*/

    // Add content after the form.
    //$args['footer_text'] = esc_html__( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'socialize' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

	 $tabs = array(
		array(
			'id'        => 'help-tab',
			'title'     => esc_html__( 'Help File', 'socialize' ),
			'content'   => '<p>' . esc_html__( 'The help file explains how to install, set up and use the main features of the theme. The help file comes with the full theme download or you can view the latest version online.', 'socialize' ) . '</p><p><a href="http://ghostpool.com/help/socialize/help.html" target="_blank">' . esc_html__( 'View Help File', 'socialize' ) . '</a></p>',
		),
		array(
			'id'        => 'changelog-tab',
			'title'     => esc_html__( 'Changelog', 'socialize' ),
			'content'   => '<p>' . esc_html__( 'The changelog is a record of changes made to theme including bug fixes, new features and tweaks. The changelog comes with the full theme download or you can view the latest version online.', 'socialize' ) . '</p><p><a href="http://ghostpool.com/help/socialize/changelog.html" target="_blank">' . esc_html__( 'View Changelog', 'socialize' ) . '</a></p>',
		),
		array(
			'id'        => 'support-tab',
			'title'     => esc_html__( 'Support', 'socialize' ),
			'content'   => wp_kses( __( '<p>If you have any questions about how to use the theme or want to report a bug then we can help you out on our ticket support site. However support does not include any services that modify or extend the theme beyond the original features, style and functionality advertised on the item page. For a more detailed explanation of what support does and does not cover check out Envato\'s support definition and guidelines for buyers <a href="http://themeforest.net/page/item_support_policy" target="_blank">here</a>.</p><p><a href="http://ghostpool.ticksy.com" target="_blank">Submit Support Ticket</a></p>', 'socialize' ), array( 'a' => array( 'href' => array(), 'target' => array() ), 'p' => array() ) ),
		),
		array(
			'id'        => 'developer-tab',
			'title'     => esc_html__( 'Premium Services (Customisations)', 'socialize' ),
			'content'   => wp_kses( __( '<p>Anything that modifies or extends the theme beyond the original features, style and functionality as advertised on the item page is classed as a customisation. Customisations are not covered by support so you will need to hire someone to do this work for you. We refer customers to our own developer OurWebMedia who will be able to give you a quote for this work.</p><p><a href="http://www.ourwebmedia.com/ghostpool.php?aff=002" target="_blank">Get A Quote</a></p>', 'socialize' ), array( 'a' => array( 'href' => array(), 'target' => array() ), 'p' => array() ) ),
		)
	);
	Redux::setHelpTab( $opt_name, $tabs );
        
    // Set the help sidebar
    $content = sprintf( wp_kses( __( '<p>If you need any help using the theme then take a look at the tabs to the left.</p>', 'socialize' ), array( 'p' => array() ) ) );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
	Redux::setSection( $opt_name, array(
		'id' => 'general',
		'title' => esc_html__('General', 'socialize' ),
		'desc' => esc_html__( 'General theme options.', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(

			array(  
				'id' => 'theme_layout',
				'title' => esc_html__( 'Theme Layout', 'socialize' ),
				'desc' => esc_html__( 'Choose whether the theme layout is wide or boxed.', 'socialize' ),
				'type' => 'button_set',
				'options'   => array(
					'gp-wide-layout' => esc_html__( 'Wide', 'socialize' ),
					'gp-boxed-layout' => esc_html__( 'Boxed', 'socialize' ),
				), 
				'default'   => 'gp-wide-layout'						
			),
					
			array(  
				'id' => 'retina',
				'title' => esc_html__( 'Retina Images', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Crop images at double the size on retina displays (newer iPhones/iPads, Macbook Pro etc.).', 'socialize' ),
				'options' => array(
					'gp-retina' => esc_html__( 'Enabled', 'socialize' ),
					'gp-no-retina' => esc_html__( 'Disabled', 'socialize' )
				),
				'default' => 'gp-retina',
			),
			
			array(  
				'id' => 'smooth_scrolling',
				'title' => esc_html__( 'Smooth Scrolling', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Scroll down the page smoothly without incremental stops.', 'socialize' ),
				'options' => array(
					'gp-smooth-scrolling' => esc_html__( 'Enabled', 'socialize' ),
					'gp-normal-scrolling' => esc_html__( 'Disabled', 'socialize' )
				),
				'default' => 'gp-normal-scrolling',
			),
			
			array(  
				'id' => 'back_to_top',
				'title' => esc_html__( 'Back To Top Button', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'Add a button to the bottom right corner of the page that takes you back to the top of the page.', 'socialize' ),
				'options' => array(
					'gp-back-to-top-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-back-to-top-desktop' => esc_html__( 'Only show on desktop devices', 'socialize' ),
					'gp-no-back-to-top' => esc_html__( 'Disabled', 'socialize' )
				),
				'default' => 'gp-back-to-top-desktop',
			),
			 
			array(  
				'id' => 'ajax',
				'title' => esc_html__( 'Ajax', 'socialize' ),
				'desc' => esc_html__( 'Load and filter content dynamically using ajax.', 'socialize' ),
				'type' => 'button_set',
				'options'   => array(
					'gp-ajax-loop' => esc_html__( 'Enabled', 'socialize' ),
					'gp-standard-loop' => esc_html__( 'Disabled', 'socialize' ),
				), 
				'default'   => 'gp-ajax-loop'						
			),

			array(
				'id'        => 'lightbox',
				'type'      => 'radio',
				'title'     => esc_html__( 'Lightbox', 'socialize' ),
				'subtitle' => esc_html__( 'Make sure the images open the media file and not the attachment page.', 'socialize' ),
				'desc' => esc_html__( 'Choose how images open in the lightbox (pop-up window).', 'socialize' ), 
				'options'   => array(
					'group_images' => esc_html__( 'All images on page show as gallery within lightbox window', 'socialize' ),
					'separate_images' => esc_html__( 'Images are not grouped', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'group_images',
			),
			
			array(  
				'id' => 'popup_box',
				'title' => esc_html__( 'Login/Register Popup Windows', 'socialize' ),
				'desc' => esc_html__( 'Choose whether to use the login/register popup windows or standard WordPress login.', 'socialize' ),
				'subtitle' => wp_kses( __( 'To create login, register, logout and profile links <a href="http://ghostpool.com/help/socialize/help.html#4223" target="_blank">click here</a>.', 'socialize' ), array( 'a' => array( 'href' => array() ) ) ),
				'type' => 'button_set',
				'options'   => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				), 
				'default'   => 'enabled'						
			),

			array(  
				'id' => 'registration_gdpr',
				'title' => esc_html__( 'Registration Privacy Policy Checkbox (GDPR)', 'socialize' ),
				'desc' => esc_html__( 'Add a privacy policy checkbox to the theme\'s registration form (this does not add a checkbox to the BuddyPress or other plugin registration pages).', 'socialize' ),
				'type' => 'button_set',
				'options'   => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				), 
				'default'   => 'disabled',
			),
			
			array(  
				'id' => 'registration_gdpr_text',
				'title' => esc_html__( 'Registration Privacy Policy Text', 'socialize' ),
				'desc' => esc_html__( 'Add your own privacy policy text next to the checkbox.', 'socialize' ),
				'subtitle' => esc_html__( 'To add a link within your text use HTML tags e.g. "This is my text and this is a <a href="http://domain.com/privacy-policy">link</a>."', 'socialize' ),
				'type' => 'textarea',
				'required' => array( 'registration_gdpr', '=', 'enabled' ),
			),
				
			array( 
				'id' => 'js_code',
				'type' => 'ace_editor',
				'title' => esc_html__('JS Code', 'socialize' ),
				'subtitle' => esc_html__('Paste your JS code here.', 'socialize' ),
				'desc' => esc_html__( 'Scripts that need to be embedded into the theme (e.g. Google Analytics).', 'socialize' ),
				'mode' => 'javascript',
				'theme' => 'chrome',
				'default' => '',				
			 ),
							
		),
	
	) );
		
	Redux::setSection( $opt_name, array(
		'id' => 'header',
		'title' => esc_html__( 'Header', 'socialize' ),
		'desc' => esc_html__( 'Options for the header.', 'socialize' ),
		'icon' => 'el-icon-website',
		'fields' => array(								 

			array( 
				'id' => 'header_layout',
				'title' => esc_html__( 'Layout', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The layout for the header.', 'socialize' ),
				'options' => array( 
					'gp-header-standard' => esc_html__( 'Standard Header', 'socialize' ),
					'gp-header-centered' => esc_html__( 'Centered Header', 'socialize' ),
				),
				'default' => 'gp-header-standard',
			),
								 
			array(  
				'id' => 'fixed_header',
				'title' => esc_html__( 'Fixed Header', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The header stays at the top of the screen as you scroll down the page.', 'socialize' ),
				'options' => array(
					'gp-fixed-header-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-fixed-header-desktop' => esc_html__( 'Only show on desktop devices', 'socialize' ),
					'gp-relative-header' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'gp-fixed-header-desktop',
			),
			
			array(
				'id' => 'desktop_header_height',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__( 'Desktop Header Height', 'socialize' ),
				'desc' => esc_html__( 'The height of the header on larger devices.', 'socialize' ),
				'width' => false,
				'mode' => array( 
					'width' => false,
					'height' => 'height',
				),
				'default'           => array(
					'height'    => 50,
				)
			),

			array(
				'id' => 'desktop_scrolling_header_height',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Desktop Scrolling Header Height', 'socialize' ),
				'desc' => esc_html__( 'The height of the header on larger devices when scrolling.', 'socialize' ),
				'width' => false,
				'mode' => array( 
					'width' => false,
					'height' => 'height',
				),
				'default'           => array(
					'height'    => 50,
				)
			),
			
			array(
				'id' => 'mobile_header_height',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Mobile Header Height', 'socialize' ),
				'desc' => esc_html__( 'The height of the header on mobile and smaller tablet devices.', 'socialize' ),
				'width' => false,
				'mode' => array( 
					'width' => false,
					'height' => 'height',
				),
				'default'           => array(
					'height'    => 50,
				)
			),
				
			array( 
				'id' => 'desktop_logo',
				'title' => esc_html__( 'Desktop Logo', 'socialize' ),						
				'type' => 'media',
				'desc' => esc_html__( 'The image that is displayed in the header on larger devices.', 'socialize' ),
				'default'  => array(
					'url' => get_template_directory_uri() . '/lib/images/logo-desktop.png',
				),
			 ),
			 
			array(
				'id' => 'desktop_logo_dimensions',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Desktop Logo Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the logo on larger devices.', 'socialize' ),
				'subtitle' => esc_html__('Set to half the original logo dimensions for retina displays.', 'socialize' ),
				'default'           => array(
					'width'     => 132, 
					'height'    => 22,
				)
			),

			array( 
				'id' => 'desktop_scrolling_logo',
				'required' => array( 'fixed_header', '!=', 'gp-relative-header' ),
				'title' => esc_html__( 'Desktop Scrolling Logo', 'socialize' ),			
				'type' => 'media',
				'desc' => esc_html__( 'The image that is displayed in the header on larger devices when scrolling.', 'socialize' ),
				'default'  => array(
					'url' => get_template_directory_uri() . '/lib/images/logo-desktop.png',
				),
			 ),
			 
			array(
				'id' => 'desktop_scrolling_logo_dimensions',
				'required' => array( 'fixed_header', '!=', 'gp-relative-header' ),
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Desktop Scrolling Logo Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the logo on larger devices.', 'socialize' ),
				'subtitle' => esc_html__('Set to half the original logo dimensions for retina displays.', 'socialize' ),
				'default'           => array(
					'width'     => 132, 
					'height'    => 22,
				)
			),
			
			array( 
				'id' => 'mobile_logo',
				'title' => esc_html__( 'Mobile Logo', 'socialize' ),						
				'type' => 'media',
				'desc' => esc_html__( 'The image that is displayed in the header on mobile and smaller tablet devices.', 'socialize' ),
				'default'  => array(
					'url' => get_template_directory_uri() . '/lib/images/logo-mobile.png',
				),
			 ),

			array(
				'id' => 'mobile_logo_dimensions',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Mobile Logo Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the logo on mobile and smaller tablet devices.', 'socialize' ),
				'subtitle' => esc_html__('Set to half the original logo dimensions for retina displays.', 'socialize' ),
				'default'           => array(
					'width'     => 26, 
					'height'    => 22,
				)
			),
						 
			array(  
				'id' => 'cart_button',
				'title' => esc_html__( 'Cart Button', 'socialize' ),
				'desc' => esc_html__( 'Add a cart button to the header.', 'socialize' ),
				'type' => 'radio',
				'options' => array(
					'gp-cart-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-cart-desktop' => esc_html__( 'Only hide on mobile devices', 'socialize' ),
					'gp-cart-mobile' => esc_html__( 'Only show on mobile devices', 'socialize' ),
					'gp-cart-disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'gp-cart-all',
			),
			
			array(
				'id' => 'search_button',  
				'title' => esc_html__( 'Search Button', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'Add a search button to the header.', 'socialize' ),
				'options' => array(
					'gp-search-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-search-desktop' => esc_html__( 'Only hide on mobile devices', 'socialize' ),
					'gp-search-mobile' => esc_html__( 'Only show on mobile devices', 'socialize' ),
					'gp-search-disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'gp-search-all',
			 ),
			
			array(  
				'id' => 'profile_button',
				'title' => esc_html__( 'Profile Button', 'socialize' ),
				'desc' => esc_html__( 'Add a profile button to the header.', 'socialize' ),
				'type' => 'radio',
				'options' => array(
					'gp-profile-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-profile-desktop' => esc_html__( 'Only hide on mobile devices', 'socialize' ),
					'gp-profile-mobile' => esc_html__( 'Only show on mobile devices', 'socialize' ),
					'gp-profile-disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'gp-profile-mobile',
			),
			
			array(  
				'id' => 'small_header',
				'title' => esc_html__( 'Small Header', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'Display a small header above the main header.', 'socialize' ),	
				'options' => array(
					'gp-small-header-all' => esc_html__( 'Show on all devices', 'socialize' ),
					'gp-small-header-desktop' => esc_html__( 'Only hide on mobile devices', 'socialize' ),
					'gp-small-header-mobile' => esc_html__( 'Only show on mobile devices', 'socialize' ),
					'gp-no-small-header' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'gp-small-header-desktop',
			),
			
			array( 
				'id' => 'header_ad',
				'title' => esc_html__( 'Advertisement', 'socialize' ),
				'desc' => esc_html__( 'Add your advertisement code to display just below the header.', 'socialize' ),
				'type' => 'textarea',
				'default' => '<div class="gp-leader" style="width: 100%; background: #e0e0e0; color: #c7c7c7; padding: 20px 15px; text-align: center; text-transform: uppercase; font-size: 20px; font-weight: 500; letter-spacing: 1px;">Responsive Ad Area</div>',
			),	
			
			array(
				'id'        => 'header_ad_exclude',
				'type' => 'button_set',
				'title'     => esc_html__( 'Exclude On Non-Content Pages', 'socialize' ),
				'desc' => esc_html__( 'Hide your header ad content on non-content pages e.g. 404 and attachment pages.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
																								
		),
			
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'footer',
		'title' => esc_html__('Footer', 'socialize' ),
		'desc' => esc_html__('Options for the footer.', 'socialize' ),
		'icon' => 'el-icon-photo',
		'fields' => array(

			array( 
				'id' => 'footer_image',
				'title' => esc_html__( 'Footer Image', 'socialize' ),						
				'type' => 'media',
				'desc' => esc_html__( 'The image that is displayed just above the footer.', 'socialize' ),
				'default'  => array(
					'url' => get_template_directory_uri() . '/lib/images/footer-speech-bubbles.png',
				),
			 ),
			 		
			array(
				'id' => 'footer_image_dimensions',
				'type' => 'dimensions',
				'units' => 'px',
				'title' => esc_html__('Footer Image Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the footer image.', 'socialize' ),
				'subtitle' => esc_html__('Set to half the original footer image dimensions for retina displays.', 'socialize' ),
				'default'           => array(
					'width'     => 99, 
					'height'    => 22,				
				)
			),
									
			array(
				'id' => 'footer_image_spacing',
				'type' => 'spacing',
				'output' => array( '#footer-image img' ),
				'mode' => 'margin',
				'units' => 'px',
				'title' => esc_html__('Footer Image Spacing', 'socialize' ),
				'desc' => esc_html__('The spacing around the footer image.', 'socialize' ),
				'default'       => array(
					'margin-top'    => '20px', 
					'margin-right'  => '0', 
					'margin-bottom' => '0', 
					'margin-left'   => '0',
				)
			),
									
			array( 
				'id' => 'copyright_text',
				'title' => esc_html__( 'Copyright Text', 'socialize' ),
				'desc' => esc_html__( 'Add copyright text to the footer.', 'socialize' ),
				'type' => 'textarea',
			),
		
			array( 
				'id' => 'footer_ad',
				'title' => esc_html__( 'Advertisement', 'socialize' ),
				'desc' => esc_html__( 'Add your advertisement code to display just above the footer.', 'socialize' ),
				'type' => 'textarea',
				'default' => '<div class="gp-leader" style="width: 100%; background: #e0e0e0; color: #c7c7c7; padding: 20px 15px; text-align: center; text-transform: uppercase; font-size: 20px; font-weight: 500; letter-spacing: 1px;">' . esc_html__( 'Responsive Ad Area', 'socialize' ) . '</div>'
			),	
			
			array(
				'id'        => 'footer_ad_exclude',
				'type' => 'button_set',
				'title'     => esc_html__( 'Exclude On Non-Content Pages', 'socialize' ),
				'desc' => esc_html__( 'Hide your footer ad content on non-content pages e.g. 404 and attachment pages.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
							
		),
		
	) );	

	Redux::setSection( $opt_name, array(
		'id' => 'posts',
		'title' => esc_html__('Posts', 'socialize' ),
		'desc' => esc_html__('Global options for all posts (some options can overridden on individual posts).', 'socialize' ),
		'icon' => 'el-icon-pencil',
		'fields' => array(
			
			array( 
				'id' => 'post_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'Choose the type of page header you want to display.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'post_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'post_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => esc_html__( 'Blog', 'socialize' ),
			),	
											
			array( 
				'id' => 'post_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-right-sidebar',
			),
			
			array(
				'id'      => 'post_left_sidebar',
				'type'    => 'select',
				'required' => array( 'post_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'post_right_sidebar',
				'type'    => 'select',
				'required' => array( 'post_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

			array(  
				'id' => 'post_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'desc' => esc_html__( 'Display a featured image on the page.', 'socialize' ),
				'type' => 'button_set',
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'post_image',
				'type' => 'dimensions',
				'required'  => array( 'post_featured_image', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image.', 'socialize' ),
				'default'           => array(
					'width'     => 1050, 
					'height'    => 600,
				),
			),

			array(
				'id' => 'post_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'post_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'post_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'required'  => array( 'post_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Choose how the image aligns with the content.', 'socialize' ),
				'options' => array(
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'gp-image-above',
			),	
				
			array(
				'id'        => 'post_meta',
				'type'      => 'checkbox',
				'title'     => esc_html__( 'Post Meta', 'socialize' ),
				'desc' => esc_html__( 'Add post meta data to the page.', 'socialize' ),
				'options'   => array(
					'author' => esc_html__( 'Author Name', 'socialize' ),
					'date' => esc_html__( 'Post Date', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'cats' => esc_html__( 'Categories', 'socialize' ),
					'tags' => esc_html__( 'Post Tags', 'socialize' ),
					'post_nav' => esc_html__( 'Post Navigation', 'socialize' ),
					'top_share_icons' => esc_html__( 'Top Share Icons', 'socialize' ),
					'bottom_share_icons' => esc_html__( 'Bottom Share Icons', 'socialize' ),
				),
				'default'   => array(
					'author' => '1',
					'date' => '1',
					'comment_count' => '1',
					'views' => '1',
					'cats' => '1',
					'tags' => '1',
					'post_nav' => '1',
					'top_share_icons' => '1',
					'bottom_share_icons' => '1',
				)
			),
										   
			array(  
				'id' => 'post_author_info',
				'title' => esc_html__( 'Author Info Panel', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add an author info panel to the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(  
				'id' => 'post_related_items',
				'title' => esc_html__( 'Related Items', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add a related items section to the page.', 'socialize' ), 
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
				
			array( 
				'id' => 'post_related_items_per_page',
				'title' => esc_html__( 'Number Of Related Items', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of related items to display.', 'socialize' ),
				'min' => 1,
				'max' => 999,
				'required'  => array( 'post_related_items', '=', 'enabled' ),
				'default' => 9,
			),
				
			array( 
				'id' => 'post_related_items_in_view',
				'title' => esc_html__( 'Number Of Related Items In View', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of related items in view.', 'socialize' ),
				'min' => 1,
				'max' => 10,
				'required'  => array( 'post_related_items', '=', 'enabled' ),
				'default' => 3,
			),
			
			array(
				'id' => 'post_related_items_image',
				'type' => 'dimensions',
				'required'  => array( 'post_related_items', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Related Items Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the related images.', 'socialize' ),
				'default'           => array(
					'width'     => 327, 
					'height'    => 219,
				),
			),	

		),			
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'post-categories',
		'title' => esc_html__('Post Categories', 'socialize' ),
		'desc' => esc_html__( 'Global options for all post categories (some options can be overridden on individual post categories or by using the Blog page template).', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-folder-open',
		'fields' => array(	

			array( 
				'id' => 'cat_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'cat_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'cat_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
										
			array( 
				'id' => 'cat_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-right-sidebar',
			),
			
			array(
				'id'      => 'cat_left_sidebar',
				'type'    => 'select',
				'required' => array( 'cat_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'cat_right_sidebar',
				'type'    => 'select',
				'required' => array( 'cat_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			
			array( 
				'id' => 'cat_format',
				'title' => esc_html__( 'Format', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The format to display the items in.', 'socialize' ),
				'options' => array(
					'gp-blog-large' => esc_html__( 'Large', 'socialize' ),
					'gp-blog-standard' => esc_html__( 'Standard', 'socialize' ),
					'gp-blog-columns-1' => esc_html__( '1 Column', 'socialize' ),
					'gp-blog-columns-2' => esc_html__( '2 Columns', 'socialize' ),
					'gp-blog-columns-3' => esc_html__( '3 Columns', 'socialize' ),
					'gp-blog-columns-4' => esc_html__( '4 Columns', 'socialize' ),
					'gp-blog-columns-5' => esc_html__( '5 Columns', 'socialize' ),
					'gp-blog-columns-6' => esc_html__( '6 Columns', 'socialize' ),
					'gp-blog-masonry' => esc_html__( 'Masonry', 'socialize' ),
				),
				'default' => 'gp-blog-large',
			),

			array(  
				'id' => 'cat_orderby',
				'title' => esc_html__( 'Order By', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The criteria which the items are ordered by.', 'socialize' ),
				'options' => array(
					'newest' => esc_html__( 'Newest', 'socialize' ),
					'oldest' => esc_html__( 'Oldest', 'socialize' ),
					'title_az' => esc_html__( 'Title (A-Z)', 'socialize' ),
					'title_za' => esc_html__( 'Title (Z-A)', 'socialize' ),
					'comment_count' => esc_html__( 'Most Comments', 'socialize' ),
					'views' => esc_html__( 'Most Views', 'socialize' ),
					'menu_order' => esc_html__( 'Menu Order', 'socialize' ),
					'rand' => esc_html__( 'Random', 'socialize' ),
				),
				'default' => 'newest',
			),	
	
			array(  
				'id' => 'cat_date_posted',
				'title' => esc_html__( 'Date Posted', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were posted.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),

			array(  
				'id' => 'cat_date_modified',
				'title' => esc_html__( 'Date Modified', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were modified.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),

			array(  
				'id' => 'cat_filter',
				'title' => esc_html__( 'Filter', 'socialize' ),
				'desc' => esc_html__( 'Add a dropdown filter menu to the page.', 'socialize' ),
				'type' => 'button_set',
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
											
			array(
				'id'        => 'cat_filter_options',
				'type'      => 'checkbox',
				'required'  => array( 'cat_filter', '=', 'enabled' ),
				'title'     => esc_html__( 'Filter Options', 'socialize' ),
				'desc' => esc_html__( 'Choose what options to display in the dropdown filter menu.', 'socialize' ), 
				'options'   => array(
					'date' => esc_html__( 'Date', 'socialize' ),
					'title' => esc_html__( 'Title', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'date_posted' => esc_html__( 'Date Posted', 'socialize' ),
					'date_modified' => esc_html__( 'Date Modified', 'socialize' ),
				),
				'default'   => array(
					'date' => '1',
					'title' => '1',
					'comment_count' => '1',
					'views' => '1',
					'date_posted' => '1',
					'date_modified' => '0',
				),
			),
			
			array(
				'id'       => 'cat_per_page',
				'type'     => 'spinner',
				'title'    => esc_html__( 'Items Per Page', 'socialize' ),
				'desc' => esc_html__( 'The number of items on each page.', 'socialize' ),
				'min' => 1,
				'max' => 999999,
				'default' => 12,
			),
																
			array(  
				'id' => 'cat_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'desc' => esc_html__( 'Display the featured image on the page.', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the featured images.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'cat_image',
				'type' => 'dimensions',
				'required'  => array( 'cat_featured_image', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured images.', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'default'           => array(
					'width'     => 1050, 
					'height'    => 600,
				),
			),

			array(
				'id' => 'cat_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'cat_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'cat_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'required'  => array( 'cat_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Choose how the images align with the content.', 'socialize' ),
				'options' => array(
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'gp-image-above',
			),
	
			array( 
				'id' => 'cat_content_display',
				'title' => esc_html__( 'Content Display', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The amount of content displayed.', 'socialize' ),
				'options' => array(
					'excerpt' => esc_html__( 'Excerpt', 'socialize' ),
					'full_content' => esc_html__( 'Full Content', 'socialize' ),
				),
				'default' => 'excerpt',
			),
		
			array( 
				'id' => 'cat_excerpt_length',
				'title' => esc_html__( 'Excerpt Length', 'socialize' ),
				'required'  => array( 'cat_content_display', '=', 'excerpt' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of characters in excerpts.', 'socialize' ),
				'min' => 0,
				'max' => 999999,
				'default' => '800',
			),

			array(
				'id'        => 'cat_meta',
				'type'      => 'checkbox',
				'title'     => esc_html__( 'Post Meta', 'socialize' ),
				'desc' => esc_html__( 'Select the meta data you want to display.', 'socialize' ), 
				'options'   => array(
					'author' => esc_html__( 'Author Name', 'socialize' ),
					'date' => esc_html__( 'Post Date', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'cats' => esc_html__( 'Categories', 'socialize' ),
					'tags' => esc_html__( 'Post Tags', 'socialize' ),
				),
				'default'   => array(
					'author' => '1',
					'date' => '1', 
					'comment_count' => '1',
					'views' => '1',
					'cats' => '1',
					'tags' => '0',
				)
			),

			array(
				'id'       => 'cat_exclude_cats',
				'type'     => 'select',
				'multi' => true,
				'title'    => esc_html__( 'Exclude Post Categories', 'socialize' ),
				'data' => 'terms',
				'args' => array( 'taxonomies' => 'category', 'hide_empty' => false ),
				'desc' => esc_html__( 'Select the post categories you want to exclude from the post meta.', 'socialize' ),
				'default' => '',
			),
							  
			array(  
				'id' => 'cat_read_more_link',
				'title' => esc_html__( 'Read More Link', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add a read more link below the content.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),

		),						   

	) );


	Redux::setSection( $opt_name, array(
		'id' => 'search-author-results',
		'title' => esc_html__( 'Search/Author Results', 'socialize' ),
		'desc' => esc_html__( 'Global options for search and author results.', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-search',
		'fields' => array(	

			array( 
				'id' => 'search_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'search_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'search_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),

			array(
				'id' => 'search_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'search_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
															
			array( 
				'id' => 'search_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-right-sidebar',
			),
			
			array(
				'id'      => 'search_left_sidebar',
				'type'    => 'select',
				'required' => array( 'search_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'search_right_sidebar',
				'type'    => 'select',
				'required' => array( 'search_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			
			array( 
				'id' => 'search_format',
				'title' => esc_html__( 'Format', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The format to display the items in.', 'socialize' ),
				'options' => array(
					'gp-blog-large' => esc_html__( 'Large', 'socialize' ),
					'gp-blog-standard' => esc_html__( 'Standard', 'socialize' ),
					'gp-blog-columns-1' => esc_html__( '1 Column', 'socialize' ),
					'gp-blog-columns-2' => esc_html__( '2 Columns', 'socialize' ),
					'gp-blog-columns-3' => esc_html__( '3 Columns', 'socialize' ),
					'gp-blog-columns-4' => esc_html__( '4 Columns', 'socialize' ),
					'gp-blog-columns-5' => esc_html__( '5 Columns', 'socialize' ),
					'gp-blog-columns-6' => esc_html__( '6 Columns', 'socialize' ),
					'gp-blog-masonry' => esc_html__( 'Masonry', 'socialize' ),
				),
				'default' => 'gp-blog-standard',
			),

			array(  
				'id' => 'search_orderby',
				'title' => esc_html__( 'Order By', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The criteria which the items are ordered by (author pages only).', 'socialize' ),
				'options' => array(
					'newest' => esc_html__( 'Newest', 'socialize' ),
					'oldest' => esc_html__( 'Oldest', 'socialize' ),
					'title_az' => esc_html__( 'Title (A-Z)', 'socialize' ),
					'title_za' => esc_html__( 'Title (Z-A)', 'socialize' ),
					'comment_count' => esc_html__( 'Most Comments', 'socialize' ),
					'views' => esc_html__( 'Most Views', 'socialize' ),
					'menu_order' => esc_html__( 'Menu Order', 'socialize' ),
					'rand' => esc_html__( 'Random', 'socialize' ),
				),
				'default' => 'newest',
			),

			array(  
				'id' => 'search_date_posted',
				'title' => esc_html__( 'Date Posted', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were posted.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),

			array(  
				'id' => 'search_date_modified',
				'title' => esc_html__( 'Date Modified', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were modified.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),

			array(  
				'id' => 'search_filter',
				'title' => esc_html__( 'Filter', 'socialize' ),
				'desc' => esc_html__( 'Add a dropdown filter menu to the page.', 'socialize' ),
				'type' => 'button_set',
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
											
			array(
				'id'        => 'search_filter_options',
				'type'      => 'checkbox',
				'required'  => array( 'search_filter', '=', 'enabled' ),
				'title'     => esc_html__( 'Filter Options', 'socialize' ),
				'desc' => esc_html__( 'Choose what options to display in the dropdown filter menu.', 'socialize' ), 
				'options'   => array(
					'date' => esc_html__( 'Date', 'socialize' ),
					'title' => esc_html__( 'Title', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'date_posted' => esc_html__( 'Date Posted', 'socialize' ),
					'date_modified' => esc_html__( 'Date Modified', 'socialize' ),
				),
				'default'   => array(
					'date' => '1',
					'title' => '1',
					'comment_count' => '1',
					'views' => '1',
					'date_posted' => '1',
					'date_modified' => '0',
				)
			),
			
			array(
				'id'       => 'search_per_page',
				'type'     => 'spinner',
				'title'    => esc_html__( 'Items Per Page', 'socialize' ),
				'desc' => esc_html__( 'The number of items on each page.', 'socialize' ),
				'min' => 1,
				'max' => 999999,
				'default' => 12,
			),
																
			array(  
				'id' => 'search_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'desc' => esc_html__( 'Display the featured image on the page.', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the featured images.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'search_image',
				'type' => 'dimensions',
				'required'  => array( 'search_featured_image', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured images.', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'default'           => array(
					'width'     => 175, 
					'height'    => 175,
				),
			),

			array(
				'id' => 'search_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'search_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'search_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'required'  => array( 'search_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Choose how the images align with the content.', 'socialize' ),
				'options' => array(
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'gp-image-align-left',
			),
	
			array( 
				'id' => 'search_content_display',
				'title' => esc_html__( 'Content Display', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The amount of content displayed.', 'socialize' ),
				'options' => array(
					'excerpt' => esc_html__( 'Excerpt', 'socialize' ),
					'full_content' => esc_html__( 'Full Content', 'socialize' ),
				),
				'default' => 'excerpt',
			),
		
			array( 
				'id' => 'search_excerpt_length',
				'title' => esc_html__( 'Excerpt Length', 'socialize' ),
				'required'  => array( 'search_content_display', '=', 'excerpt' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of characters in excerpts.', 'socialize' ),
				'min' => 0,
				'max' => 999999,
				'default' => '400',
			),

			array(
				'id'        => 'search_meta',
				'type'      => 'checkbox',
				'title'     => esc_html__( 'Post Meta', 'socialize' ),
				'desc' => esc_html__( 'Select the meta data you want to display.', 'socialize' ), 
				'options'   => array(
					'author' => esc_html__( 'Author Name', 'socialize' ),
					'date' => esc_html__( 'Post Date', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'cats' => esc_html__( 'Categories', 'socialize' ),
					'tags' => esc_html__( 'Post Tags', 'socialize' ),
				),
				'default'   => array(
					'author' => '1',
					'date' => '1', 
					'comment_count' => '1',
					'views' => '1',
					'cats' => '0',
					'tags' => '0',
				)
			),
							   
			array(  
				'id' => 'search_read_more_link',
				'title' => esc_html__( 'Read More Link', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add a read more link below the content.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),

		),						   

	) );

										
	Redux::setSection( $opt_name, array(
		'id' => 'pages',
		'title' => esc_html__('Pages', 'socialize' ),
		'desc' => esc_html__('Global options for all pages (some options can be overridden on individual pages).', 'socialize' ),
		'icon' => 'el-icon-file',
		'fields' => array(

			array( 
				'id' => 'page_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'page_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'page_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
										
			array( 
				'id' => 'page_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-right-sidebar',
			),

			array(
				'id'      => 'page_left_sidebar',
				'type'    => 'select',
				'required' => array( 'page_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'page_right_sidebar',
				'type'    => 'select',
				'required' => array( 'page_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			
			array(  
				'id' => 'page_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'desc' => esc_html__( 'Display the featured image on the page.', 'socialize' ),
				'type' => 'button_set',
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'page_image',
				'type' => 'dimensions',
				'required'  => array( 'page_featured_image', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image.', 'socialize' ),
				'default'           => array(
					'width'     => 1050, 
					'height'    => 600,
				),
			),

			array(
				'id' => 'page_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'page_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'page_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'required'  => array( 'page_featured_image', '=', 'enabled' ),
				'desc' => esc_html__( 'Choose how the image aligns with the content.', 'socialize' ),
				'options' => array(
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'gp-image-above',
			),

			array(  
				'id' => 'page_author_info',
				'title' => esc_html__( 'Author Info Panel', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add an author info panel to the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),

		),

	) );


	Redux::setSection( $opt_name, array(
		'id' => 'portfolios',
		'title' => esc_html__('Portfolios', 'socialize' ),
		'desc' => esc_html__('Global options for all portfolio items (some options can be overridden on individual portfolio items).', 'socialize' ),
		'icon' => 'el-icon-photo-alt',
		'fields' => array(							

			array(
				'id'        => 'portfolio_item_slug',
				'type'      => 'text',
				'title'     => esc_html__( 'Slug', 'socialize' ),
				'subtitle'  => esc_html__( 'After changing the slug, go to', 'socialize' ) . ' <a href="'.admin_url( 'options-permalink.php' ).'">' . esc_html__( 'Settings -> Permalinks' ,'socialize' ) . '</a> ' . esc_html__( 'and click Save Changes.', 'socialize' ),
				'desc' => esc_html__( 'Custom slug used in the URL for portfolio categories e.g. ', 'socialize' ) . 'http://domain.com/<strong>portfolios</strong>/item-name.',
				'validate'  => 'str_replace',
				'str'       => array(
					'search'        => ' ', 
					'replacement'   => '-'
				),
				'default'   => 'portfolio'
			),

			array( 
				'id' => 'portfolio_item_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'portfolio_item_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'portfolio_item_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
											
			array( 
				'id' => 'portfolio_item_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
			
			array(
				'id'      => 'portfolio_item_left_sidebar',
				'type'    => 'select',
				'required' => array( 'portfolio_item_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'portfolio_item_right_sidebar',
				'type'    => 'select',
				'required' => array( 'portfolio_item_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			
			array(
				'id'        => 'portfolio_item_type',
				'type'      => 'radio',
				'title'     => esc_html__( 'Image/Slider Type', 'socialize' ),
				'desc' => esc_html__( 'The type of image or slider on the page.', 'socialize' ),
				'options'   => array(
					'left-image' => 'Left Featured Image',
					'fullwidth-image' => 'Full Width Featured Image',
					'left-slider' => 'Left Slider',
					'fullwidth-slider' => 'Full Width Slider',
					'none' => 'None',
				), 
				'default'   => 'left-image',
			),   

			array(
				'id' => 'portfolio_item_image',
				'type' => 'dimensions',
				'required'  => array( 'portfolio_item_type', '!=', 'none' ),
				'units' => 'px',
				'title' => esc_html__( 'Image/Slider Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image or slider.', 'socialize' ),
				'default'           => array(
					'width'     => 1220, 
					'height'    => 0,
				),
			),
			
			array(
				'id' => 'portfolio_item_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'portfolio_item_type', '!=', 'none' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'portfolio_item_image_size',
				'title' => esc_html__( 'Image Size', 'socialize' ),
				'subtitle' => esc_html__( 'Only for use with the Masonry portfolio type.', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Size of the image when displayed on a masonry portfolio page.', 'socialize' ),
				'options' => array(
					'gp-regular' => esc_html__( 'Regular', 'socialize' ),
					'gp-narrow' => esc_html__( 'Narrow', 'socialize' ),
					'gp-tall' => esc_html__( 'Tall', 'socialize' ),
				),
				'default' => 'gp-regular',
			),
						
			array( 	
				'id' => 'portfolio_item_link_text',
				'title' => esc_html__( 'Button Text', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'The text for the button.', 'socialize' ),
				'default' => 'Website',
			), 

			array( 
				'id' => 'portfolio_item_link_target',
				'title' => esc_html__( 'Button Link Target', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The target for the button link.', 'socialize' ),
				'options' => array(
					'_blank' => esc_html__( 'New Window', 'socialize' ),
					'_self' => esc_html__( 'Same Window', 'socialize' ),
				),
				'default' => '_blank',
			),

			array(
				'id'        => 'portfolio_item_meta',
				'type'      => 'checkbox',
				'title'     => esc_html__( 'Post Meta', 'socialize' ),
				'desc' => esc_html__( 'Add post meta data to the page.', 'socialize' ),
				'options'   => array(
					'author' => esc_html__( 'Author Name', 'socialize' ),
					'date' => esc_html__( 'Post Date', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'cats' => esc_html__( 'Categories', 'socialize' ),
					'tags' => esc_html__( 'Post Tags', 'socialize' ),
					'post_nav' => esc_html__( 'Post Navigation', 'socialize' ),
					'top_share_icons' => esc_html__( 'Top Share Icons', 'socialize' ),
					'bottom_share_icons' => esc_html__( 'Bottom Share Icons', 'socialize' ),
				),
				'default'   => array(
					'author' => '1',
					'date' => '1',
					'comment_count' => '1',
					'views' => '1',
					'cats' => '1',
					'tags' => '1',
					'share_icons' => '1',
					'post_nav' => '1',
					'top_share_icons' => '1',
					'bottom_share_icons' => '1',
				)
			),

			array(  
				'id' => 'portfolio_item_author_info',
				'title' => esc_html__( 'Author Info Panel', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add an author info panel to the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),
								
			array(  
				'id' => 'portfolio_item_related_items',
				'title' => esc_html__( 'Related Items', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add a related items section to the page.', 'socialize' ), 
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
				
			array( 
				'id' => 'portfolio_item_related_items_per_page',
				'title' => esc_html__( 'Number Of Related Items', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of related items to display.', 'socialize' ),
				'required'  => array( 'portfolio_item_related_items', '=', 'enabled' ),
				'min' => 1,
				'max' => 999999,
				'default' => 12,
			),
				
			array( 
				'id' => 'portfolio_item_related_items_in_view',
				'title' => esc_html__( 'Number Of Related Items In View', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of related items in view.', 'socialize' ),
				'min' => 1,
				'max' => 10,
				'required'  => array( 'portfolio_item_related_items', '=', 'enabled' ),
				'default' => 4,
			),

			array(
				'id' => 'portfolio_item_related_items_image',
				'type' => 'dimensions',
				'required'  => array( 'portfolio_item_related_items', '=', 'enabled' ),
				'units' => 'px',
				'title' => esc_html__( 'Related Items Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the related images.', 'socialize' ),
				'default'           => array(
					'width'     => 263, 
					'height'    => 176,
				),
			),    
				
		),
			
	) );	
				
	Redux::setSection( $opt_name, array(
		'id' => 'portfolio-categories',
		'title' => esc_html__('Portfolio Categories', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-th',
		'desc' => esc_html__('Global options for all portfolio categories (some options can be overridden on individual portfolio categories or by using the Portfolio page template).', 'socialize' ),
		'fields' => array(
		
			array(
				'id'        => 'portfolio_cat_slug',
				'type'      => 'text',
				'title'     => esc_html__( 'Slug', 'socialize' ),
				'subtitle'  => esc_html__( 'After changing the slug, go to', 'socialize' ) . ' <a href="'.admin_url( 'options-permalink.php' ).'">' . esc_html__( 'Settings -> Permalinks' ,'socialize' ) . '</a> ' . esc_html__( 'and click Save Changes.', 'socialize' ),
				'desc' => esc_html__( 'Custom slug used in the URL for portfolio categories e.g. ', 'socialize' ) . 'http://domain.com/<strong>portfolios</strong>/category-name.',
				'validate'  => 'str_replace',
				'str'       => array(
					'search'        => ' ', 
					'replacement'   => '-',
				),
				'default'   => 'portfolios',
			),

			array(
				'id'        => 'portfolio_cat_prefix_slug',
				'type'      => 'text',
				'title'     => esc_html__( 'Prefix Category Slugs', 'socialize' ),
				'subtitle'  => esc_html__( 'Leave blank to remove the prefix from category slugs.', 'socialize' ),
				'desc' => esc_html__( 'Prefix portfolio category slugs to avoid conflicts with post categories e.g. ', 'socialize' ) . 'http://domain.com/portfolios/<strong>portfolio</strong>-category-name.',
				'validate'  => 'str_replace',
				'str'       => array(
					'search'        => ' ', 
					'replacement'   => '-'
				),
				'default'   => ''
			),
								
			array( 
				'id' => 'portfolio_cat_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'portfolio_cat_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'portfolio_cat_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
															
			array( 
				'id' => 'portfolio_cat_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
			
			array(
				'id'      => 'portfolio_cat_left_sidebar',
				'type'    => 'select',
				'required' => array( 'portfolio_cat_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'portfolio_cat_right_sidebar',
				'type'    => 'select',
				'required' => array( 'portfolio_cat_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			
			array( 
				'id' => 'portfolio_cat_format',
				'title' => esc_html__( 'Portfolio Format', 'socialize' ),					
				'type' => 'select',
				'desc' => esc_html__( 'The format to display the items in.', 'socialize' ),
				'options' => array(
					'gp-portfolio-columns-2' => esc_html__( '2 Columns', 'socialize' ),
					'gp-portfolio-columns-3' => esc_html__( '3 Columns', 'socialize' ),
					'gp-portfolio-columns-4' => esc_html__( '4 Columns', 'socialize' ),
					'gp-portfolio-columns-5' => esc_html__( '5 Columns', 'socialize' ),
					'gp-portfolio-columns-6' => esc_html__( '6 Columns', 'socialize' ),
					'gp-portfolio-masonry' => esc_html__( 'Masonry', 'socialize' ),
				),	
				'default' => 'gp-portfolio-columns-2',
			),

			array(  
				'id' => 'portfolio_cat_orderby',
				'title' => esc_html__( 'Order By', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The criteria which the items are ordered by.', 'socialize' ),
				'options' => array(
					'newest' => esc_html__( 'Newest', 'socialize' ),
					'oldest' => esc_html__( 'Oldest', 'socialize' ),
					'title_az' => esc_html__( 'Title (A-Z)', 'socialize' ),
					'title_za' => esc_html__( 'Title (Z-A)', 'socialize' ),
					'comment_count' => esc_html__( 'Most Comments', 'socialize' ),
					'views' => esc_html__( 'Most Views', 'socialize' ),
					'menu_order' => esc_html__( 'Menu Order', 'socialize' ),
					'rand' => esc_html__( 'Random', 'socialize' ),
				),
				'default' => 'newest',
			),
			
			array(  
				'id' => 'portfolio_cat_date_posted',
				'title' => esc_html__( 'Date Posted', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were posted.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),

			array(  
				'id' => 'portfolio_cat_date_modified',
				'title' => esc_html__( 'Date Modified', 'socialize' ),
				'type' => 'radio',
				'desc' => esc_html__( 'The date the items were modified.', 'socialize' ),
				'options' => array(
					'all' => esc_html__( 'Any date', 'socialize' ),
					'year' => esc_html__( 'In the last year', 'socialize' ),
					'month' => esc_html__( 'In the last month', 'socialize' ),
					'week' => esc_html__( 'In the last week', 'socialize' ),
					'day' => esc_html__( 'In the last day', 'socialize' ),
				),
				'default' => 'all',
			),
										
			array(  
				'id' => 'portfolio_cat_filter',
				'title' => esc_html__( 'Filter', 'socialize' ),
				'desc' => esc_html__( 'Add a dropdown filter menu to the page.', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add category filter links to the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),					

			array( 
				'id' => 'portfolio_cat_per_page',
				'title' => esc_html__( 'Items Per Page', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of items on each page.', 'socialize' ),
				'min' => 1,
				'max' => 999999,
				'default' => 12,
			),
			
		),
	
	) );
	
	
	Redux::setSection( $opt_name, array(
		'id' => 'woocommerce',
		'title' => esc_html__( 'WooCommerce', 'socialize' ),
		'desc' => esc_html__( 'Global options for WooCommerce pages (can be overridden on individual shop page).', 'socialize' ),
		'icon' => 'el-icon-shopping-cart',
		'fields' => array(
		
			array( 
				'id' => 'shop_page_header',
				'title' => esc_html__( 'Shop Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-fullwidth-page-header',
			),

			array(
				'id' => 'shop_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'shop_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => esc_html__( 'Shop', 'socialize' ),
			),	
													
			array( 
				'id' => 'shop_layout',
				'title' => esc_html__( 'Shop Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),

			array(
				'id'      => 'shop_left_sidebar',
				'type'    => 'select',
				'required' => array( 'shop_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'shop_right_sidebar',
				'type'    => 'select',
				'required' => array( 'shop_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
						
		),
	
	) );
	
	Redux::setSection( $opt_name, array(
		'id' => 'products',
		'title' => esc_html__( 'Products', 'socialize' ),
		'desc' => esc_html__( 'Global options for all products (some options can be overridden on individual products).', 'socialize' ),
		'icon' => 'el-icon-shopping-cart',
		'subsection' => true,
		'fields' => array(
					
			array( 
				'id' => 'product_layout',
				'title' => esc_html__( 'Product Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
			
			array(
				'id'      => 'product_left_sidebar',
				'type'    => 'select',
				'required' => array( 'product_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'product_right_sidebar',
				'type'    => 'select',
				'required' => array( 'product_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

		),
	
	) );
	
	
	/*Redux::setSection( $opt_name, array(
		'id' => 'buddypress',
		'title' => esc_html__( 'BuddyPress', 'socialize' ),
		'desc' => esc_html__( 'Global options for all BuddyPress pages (some options can be overridden on individual pages).', 'socialize' ),
		'icon' => 'el-icon-comment',
		'fields' => array(
		
			array( 
				'id' => 'bp_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'bp_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'bp_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),

			array(
				'id' => 'bp_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'bp_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
						
			array( 
				'id' => 'bp_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
		
			array(
				'id'      => 'bp_left_sidebar',
				'type'    => 'select',
				'required' => array( 'bp_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'bp_right_sidebar',
				'type'    => 'select',
				'required' => array( 'bp_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

		),	
	
	) );*/


	Redux::setSection( $opt_name, array(
		'id' => 'buddypress_options',
		'title' => esc_html__( 'BuddyPress', 'socialize' ),
		'desc' => esc_html__( 'Global options for all BuddyPress pages (some options can be overridden on individual pages).', 'socialize' ),
		'icon' => 'el-icon-user',
		'fields' => array(),
	) );	

	Redux::setSection( $opt_name, array(
		'id' => 'bp_general_options',
		'title' => esc_html__( 'General', 'socialize' ),
		'desc' => esc_html__( 'Global options for all BuddyPress pages (some options can be overridden for different BuddyPress sections below).', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-user',
		'fields' => array(

			array( 
				'id' => 'bp_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'bp_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'bp_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),

			array(
				'id' => 'bp_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'bp_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
		
			array( 
				'id' => 'bp_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'gp-no-sidebar',
			),

			array(
				'id'      => 'bp_left_sidebar',
				'type'    => 'select',
				'required' => array( 'bp_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'bp_right_sidebar',
				'type'    => 'select',
				'required' => array( 'bp_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
	
		),
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'bp_activity_options',
		'title' => esc_html__( 'Activity Page', 'socialize' ),
		'desc' => esc_html__( 'Global options for the BuddyPress activity page.', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-user',
		'fields' => array(

			array( 
				'id' => 'bp_activity_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'bp_activity_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
										
			array( 
				'id' => 'bp_activity_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'default',
			),

			array(
				'id'       => 'bp_activity_left_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc'     => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

			array(
				'id'       => 'bp_activity_right_sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc'     => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

		),
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'bp_members_options',
		'title' => esc_html__( 'Members Page', 'socialize' ),
		'desc' => esc_html__( 'Global options for the BuddyPress members page.', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-user',
		'fields' => array(	

			array( 
				'id' => 'bp_members_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'bp_members_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
				
			array( 
				'id' => 'bp_members_layout',
				'title' => esc_html__( 'Directory Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'default',
			),

			array( 
				'id' => 'bp_profile_layout',
				'title' => esc_html__( 'Profile Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'bp_members_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

			array(
				'id'      => 'bp_members_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

		),
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'bp_groups_options',
		'title' => esc_html__( 'Groups Page', 'socialize' ),
		'desc' => esc_html__( 'Global options for the BuddyPress group page.', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-user',
		'fields' => array(		

			array( 
				'id' => 'bp_groups_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'bp_groups_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
				
			array( 
				'id' => 'bp_groups_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'default',
			),

			array(
				'id'      => 'bp_groups_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

			array(
				'id'      => 'bp_groups_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

		),
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'bp_register_options',
		'title' => esc_html__( 'Registration Page', 'socialize' ),
		'desc' => esc_html__( 'Global options for the BuddyPress registration page.', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-user',
		'fields' => array(

			array( 
				'id' => 'bp_register_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'bp_register_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
										
			array( 
				'id' => 'bp_register_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array( 'title' => esc_html__( 'Left Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cl.png' ),
					'gp-right-sidebar' => array( 'title' => esc_html__( 'Right Sidebar', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/2cr.png' ),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array( 'title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png' ),
					'gp-fullwidth' => array( 'title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png' ),
				),	
				'default' => 'default',
			),

			array(
				'id'      => 'bp_register_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

			array(
				'id'      => 'bp_register_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'     => 'custom_sidebars_default',
				'default'  => 'default',
			),

		)
	) );
	
		
	Redux::setSection( $opt_name, array(
		'id' => 'bbpress',
		'title' => esc_html__( 'bbPress', 'socialize' ),
		'desc' => esc_html__( 'Global options for all bbPress pages (some options can be overridden on individual forums and topics).', 'socialize' ),
		'icon' => 'el-icon-comment-alt',
		'fields' => array(
			
			array( 
				'id' => 'bbpress_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'bbpress_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'bbpress_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),

			array(
				'id' => 'bbpress_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'bbpress_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
						
			array(						
				'id' => 'bbpress_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
			
			array(
				'id'      => 'bbpress_left_sidebar',
				'type'    => 'select',
				'required' => array( 'bbpress_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'bbpress_right_sidebar',
				'type'    => 'select',
				'required' => array( 'bbpress_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

		),	
	
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'events',
		'title' => esc_html__( 'Events', 'socialize' ),
		'desc' => esc_html__( 'Global options for the event page .', 'socialize' ),
		'icon' => 'el-icon-calendar',
		'fields' => array(
			
			array( 
				'id' => 'events_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'events_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'events_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'events_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'events_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => esc_html__( 'Events', 'socialize' ),
			),				
			
			array(						
				'id' => 'events_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-no-sidebar',
			),
			
			array(
				'id'      => 'events_left_sidebar',
				'type'    => 'select',
				'required' => array( 'events_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'events_right_sidebar',
				'type'    => 'select',
				'required' => array( 'events_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

		),	
	
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'events-posts',
		'title' => esc_html__( 'Events Posts', 'socialize' ),
		'desc' => esc_html__( 'Global options for all event posts (some options can be overridden on individual events posts).', 'socialize' ),
		'icon' => 'el-icon-calendar',
		'subsection' => true,
		'fields' => array(
			
			array( 
				'id' => 'events_post_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'The page header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),

			array(
				'id' => 'events_post_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'required' => array( 'events_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
			
			array(
				'id' => 'events_post_page_header_bg', 
				'title' => esc_html__( 'Page Header Background', 'socialize' ),
				'type'      => 'media',			
				'required' => array( 'events_post_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(						
				'id' => 'events_post_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'gp-right-sidebar',
			),
			
			array(
				'id'      => 'events_post_left_sidebar',
				'type'    => 'select',
				'required' => array( 'events_post_layout', '=', array( 'gp-left-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'events_post_right_sidebar',
				'type'    => 'select',
				'required' => array( 'events_post_layout', '=', array( 'gp-right-sidebar', 'gp-both-sidebars' ) ),
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),

		),	
	
	) );	
			
	Redux::setSection( $opt_name, array(
		'id' => 'styling',
		'title'     => esc_html__( 'Styling', 'socialize' ),
		'desc' => esc_html__( 'Style your theme.', 'socialize' ),
		'icon' => 'el-icon-brush',
		'fields'    => array(
			
			array( 
				'title' => esc_html__( 'Custom Stylesheet', 'socialize' ),
				'subtitle' => wp_kses( __( 'Use relative URL to your custom stylesheet e.g. <strong>lib/css/custom-style.css</strong>', 'socialize' ), array( 'strong' => array() ) ),
				'desc' => esc_html__( 'Load a custom stylesheet to add your own CSS code.', 'socialize' ),
				'id' => 'custom_stylesheet',
				'type' => 'text',
				'default' => '',
			),
			
			array(
				'id'        => 'custom_css',
				'type'      => 'ace_editor',
				'title'     => esc_html__( 'CSS Code', 'socialize' ),
				'subtitle'  => esc_html__( 'Add your CSS code here - this CSS will not be lost if you update the theme.', 'socialize' ),
				'mode'      => 'css',
				'theme'     => 'monokai',
				'options'   => array( 'minLines' => 50 ),
				'default' => '',
			),
				
		),		
	) );
				
	Redux::setSection( $opt_name, array(
		'id' => 'styling-general',
		'title'     => esc_html__( 'General', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-cogs',
		'fields'    => array(
	
			array(
				'id'        => 'page_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Page Background', 'socialize' ),
				'desc'  => esc_html__( 'The overall page background.', 'socialize' ),
				'output'    => array( 'body' ),
				'preview' => false,
				'default'   => array(
					'background-color' => '#f1f1f1',
				),
			),
			
			array(
				'id'        => 'primary_bg',
				'type'      => 'color',
				'title'     => esc_html__( 'Primary Background', 'socialize' ),
				'desc'  => esc_html__( 'The primary background (includes the area surrounding main content and sidebars).', 'socialize' ),
				'output'    => array( 
					'background-color' => 'body:not(.gp-full-page-page-header) #gp-page-wrapper, body:not(.gp-full-page-page-header) #gp-small-header .gp-container, .gp-homepage .widgettitle.gp-fancy-title:before',
					'border-left-color' => 'body:not(.gp-full-page-page-header) #gp-small-header .gp-left-triangle',
					'border-bottom-color' => 'body:not(.gp-full-page-page-header) #gp-small-header .gp-right-triangle',
				),
				'default'   => '#f1f1f1',
			),

			array(
				'id'        => 'content_bg',
				'type'      => 'color',
				'title'     => esc_html__( 'Content Background', 'socialize' ),
				'desc'  => esc_html__( 'The main content column background.', 'socialize' ),
				'output'    => array(
					'background-color' => '#gp-content',
				),
				'preview' => false,
				'default'   =>  '#fff',
			),
						
			array(
				'id'        => 'general_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'General Typography', 'socialize' ),
				'desc'  => esc_html__( 'The general typography.', 'socialize' ),
				'output'    => array( 'body' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '16px',
					'line-height' => '28px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
					'color'       => '#000',
				),
			),
																															
			array(
				'id'        => 'general_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'General Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The general link colors.', 'socialize' ),
				'output'    => array( 'a' ),
				'default'   => array(
					'regular'  => '#e93100',
					'hover'    => '#000',
					'active'   => false,
				),
			),

			array(
				'id'        => 'h1_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H1 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H1 typography.', 'socialize' ),
				'output'    => array( 'h1' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'color' => false,
				'default'   => array(
					'font-size'   => '48px',
					'line-height' => '56px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'h2_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H2 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H2 typography.', 'socialize' ),
				'output'    => array( 'h2' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '36px',
					'line-height' => '44px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'h3_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H3 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H3 typography.', 'socialize' ),
				'output'    => array( 'h3', '#tab-description h2', '.woocommerce #comments h2', '.woocommerce #reviews h3', '.woocommerce .related h2', '.woocommerce-checkout .woocommerce h2', '.woocommerce-checkout .woocommerce h3' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '28px',
					'line-height' => '36px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'h4_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H4 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H4 typography.', 'socialize' ),
				'output'    => array( 'h4' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '20px',
					'line-height' => '30px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'h5_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H5 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H5 typography.', 'socialize' ),
				'output'    => array( 'h5' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '18px',
					'line-height' => '26px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'h6_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'H6 Typography', 'socialize' ),
				'desc'  => esc_html__( 'The H6 typography.', 'socialize' ),
				'output'    => array( 'h6' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '16px',
					'line-height' => '24px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),
													
			array(
				'id'        => 'light_divider',
				'type'      => 'border',
				'title'     => esc_html__( 'Light Divider Color', 'socialize' ),
				'desc'  => esc_html__( 'The divider color over light backgrounds.', 'socialize' ),
				'output'    => array( '.gp-entry-meta .gp-post-meta-row-1', '.gp-homepage #gp-content .gp-vc-element-3', '.gp-homepage #gp-content .gp-vc-element-4', '.gp-homepage #gp-content .gp-vc-element-5', '.gp-homepage #gp-content .gp-vc-element-6', '#comments .commentlist li .comment_container', '.gp-loop-divider:before', '.gp-recent-comments ul li', '.gp-search' ),   
				'left' => false,
				'right' => false,    
				'default'   => array(
					'border-color' => '#e0e0e0',
					'border-width' => '1px',
					'border-style' => 'solid',
				),
			),

			array(
				'id'        => 'dark_divider',
				'type'      => 'border',
				'title'     => esc_html__( 'Dark Divider Color', 'socialize' ),
				'desc'  => esc_html__( 'The divider color over dark backgrounds.', 'socialize' ),
				'output'    => array( '.gp-entry-video-wrapper .gp-entry-meta .gp-post-meta-row-1', '.gp-footer-widget .gp-recent-comments ul li' ),   
				'left' => false,
				'right' => false,    
				'default'   => array(
					'border-color' => '#50504B',
					'border-width' => '1px',
					'border-style' => 'solid',
				),
			),
																																			
		),
	) );
	
	Redux::setSection( $opt_name, array(
		'id' => 'styling-main-header',
		'title'     => esc_html__( 'Main Header', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-website',
		'fields'    => array(                                          
						
			array(
				'id'        => 'main_header_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Header Background', 'socialize' ),
				'desc'  => esc_html__( 'The main header background.', 'socialize' ),
				'output'    => array( '#gp-main-header' ),
				'default'   => array(
					'background-color' => '#e93100',
				),
			),

			array(
				'id'        => 'main_header_scrolling_bg',
				'type'      => 'color_rgba',
				'title'     => esc_html__( 'Scrolling Header Background', 'socialize' ),
				'desc'  => esc_html__( 'The main header background when scrolling.', 'socialize' ),
				'output' => array( 'background-color' => '.gp-desktop-scrolling #gp-main-header' ),
				'default'   => array(
					'color' => '#e93100',
					'alpha' => '1',
				),
			),
															   
			array(
				'id'        => 'primary_nav_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Primary Navigation Typography', 'socialize' ),
				'desc'  => esc_html__( 'The main header primary navigation typography.', 'socialize' ),
				'output'    => array( '#gp-primary-main-nav .menu > li', '#gp-primary-main-nav .menu > li > a' ),
				'google'    => true,
				'text-align' => false,
				'text-transform' => true,
				'font-backup' => true,
				'default'   => array(
					'font-size'     => '16px',
					'line-height' 	=> '16px', 
					'font-family'   => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight'   => '300',
					'subsets'       => 'latin',
					'color'			=> '#ababab',
					'text-transform' => 'none',
				),
			),

			array(
				'id'        => 'primary_nav_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Primary Navigation Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The main header primary navigation link colors.', 'socialize' ),
				'output'    => array( '#gp-primary-main-nav .menu > li:hover > a', '#gp-primary-main-nav .menu > li > a' ),
				'default'   => array(
					'regular'   => '#fff',
					'hover'     => '#fff',
					'active' 	=> false,
				),
			),

			array(
				'id'        => 'primary_nav_link_border_hover',
				'type'      => 'border',
				'title'     => esc_html__( 'Primary Navigation Link Border Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The main header primary navigation link border hover color.', 'socialize' ),
				'output'    => array( '#gp-primary-main-nav .menu > li > a:hover', '#gp-primary-main-nav .menu > li:hover > a' ),
				'left' => false, 
				'bottom' => false,  
				'right' => false,    
				'default'   => array(
					'border-color' => '#fff',
					'border-top' => '3px',
					'border-style' => 'solid',
				),
			),

			array(
				'id'        => 'secondary_nav_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Secondary Navigation Typography', 'socialize' ),
				'desc'  => esc_html__( 'The main header secondary navigation typography.', 'socialize' ),
				'output'    => array( '#gp-secondary-main-nav .menu > li', '#gp-secondary-main-nav .menu > li > a' ),
				'google'    => true,
				'text-align' => false,
				'text-transform' => true,
				'font-backup' => true,
				'default'   => array(
					'font-size'     => '14px',
					'line-height' 	=> '14px', 
					'font-family'   => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight'   => '400',
					'subsets'       => 'latin',
					'color' => '#fff',
					'text-transform' => 'none',
				),
			),

			array(
				'id'        => 'secondary_nav_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Secondary Navigation Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The main header secondary navigation link colors.', 'socialize' ),
				'output'    => array( '#gp-secondary-main-nav .menu > li:hover > a, #gp-secondary-main-nav .menu > li > a' ),
				'default'   => array(
					'regular'         => '#fff',
					'hover'     => '#fff',
					'active' => false,
				),
			),

			array(
				'id'        => 'secondary_nav_link_border_hover',
				'type'      => 'border',
				'title'     => esc_html__( 'Secondary Navigation Link Border Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The main header secondary navigation link border hover color.', 'socialize' ),
				'output'    => array( '#gp-secondary-main-nav .menu > li > a:hover', '#gp-secondary-main-nav .menu > li:hover > a' ),
				'left' => false, 
				'bottom' => false,  
				'right' => false,    
				'default'   => array(
					'border-color' => '#fff',
					'border-top' => '3px',
					'border-style' => 'solid',
				),
			),

			array(
				'id'        => 'search_button_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Search Button Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The search button link colors.', 'socialize' ),
				'output'    => array( '#gp-search-button', '#gp-cart-button' ),
				'default'   => array(
					'regular'         => '#fff',
					'hover'     => '#fff',
					'active' => false,
				),
			),
									
			array(
				'id'        => 'dropdown_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Dropdown Menu Background', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu background.', 'socialize' ),
				'output'    => array( '.gp-nav .sub-menu', '.gp-nav .menu li .gp-menu-tabs li:hover', '.gp-nav .menu li .gp-menu-tabs li.gp-selected', '#gp-search-box' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#fff',
				),
			),
							   
			array(
				'id'        => 'dropdown_bg_hover',
				'type'      => 'background',
				'title'     => esc_html__( 'Dropdown Menu Background Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu background hover color.', 'socialize' ),
				'output'    => array( '.gp-nav .menu > .gp-standard-menu > .sub-menu > li a:hover', '.gp-nav .menu > .gp-standard-menu > .sub-menu > li:hover > a' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
			
			array(
				'id'        => 'dropdown_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Dropdown Menu Typography', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu typography.', 'socialize' ),
				'output'    => array( '.gp-nav .gp-standard-menu .sub-menu li', '.gp-nav .gp-standard-menu .sub-menu li a', '.gp-nav li.gp-megamenu .sub-menu li', '.gp-nav .gp-megamenu .sub-menu li a' ),
				'google'    => true,
				'text-align' => false,
				'line-height' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'     => '14px',
					'font-family'   => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight'   => '300',
					'subsets'       => 'latin',
					'color' 		=> '#000',
				),
			),

			array(
				'id'        => 'dropdown_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Dropdown Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu link colors.', 'socialize' ),
				'output'    => array( '.gp-nav .menu > .gp-standard-menu > .sub-menu > li a' ),
				'default'   => array(
					'regular'   => '#000',
					'hover'     => '#fff',
					'active' 	=> false,
				),
			),
			
			array(
				'id'        => 'dropdown_link_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Dropdown Links Border', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu link border.', 'socialize' ),
				'output'    => array( '.gp-nav .sub-menu li', '#gp-dropdowncart .total', '#gp-dropdowncart .buttons' ),
				'left' => false,
				'right' => false,
				'bottom' => false,
				'default'   => array(
					'border-width' => '1px',
					'border-color' => '#e0e0e0',
					'border-style' => 'solid',
				),
			),

			array(
				'id'        => 'megamenu_header',
				'type'      => 'color',                        
				'title'     => esc_html__( 'Mega Menu Header Color', 'socialize' ),
				'desc'  => esc_html__( 'The mega menu header color.', 'socialize' ),
				'output'    => array( '.gp-nav .gp-megamenu > .sub-menu > li > a', '.gp-nav .gp-megamenu > .sub-menu > li > span' ),
				'transparent' => false,
				'default'  => '#000',
			),

			array(
				'id'        => 'megamenu_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Mega Menu Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown mega menu link colors.', 'socialize' ),
				'output'    => array( '.gp-nav li.gp-megamenu a' ),
				'default'   => array(
					'regular'         => '#000',
					'hover'     => '#e93100',
					'active' => false,
				),
			),
																																	 
			array(
				'id'        => 'primary_dropdown_icon',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Primary Dropdown Icon Color', 'socialize' ),
				'desc'  => esc_html__( 'The primary dropdown icon color.', 'socialize' ),
				'output'    => array( '.gp-primary-dropdown-icon' ),
				'default'   => array(
					'regular'         => '#fff',
					'hover'     => '#fff',
					'active' => false,
				),
			),

			array(
				'id'        => 'secondary_dropdown_icon',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Secondary Dropdown Icon Color', 'socialize' ),
				'desc'  => esc_html__( 'The secondary dropdown icon color.', 'socialize' ),
				'output'    => array( '.gp-secondary-dropdown-icon' ),
				'default'   => array(
					'regular'         => '#000',
					'hover'     => '#fff',
					'active' => false,
				),
			),
			
			array(
				'id'        => 'main_header_menu_tabs',
				'type'      => 'background',
				'title'     => esc_html__( 'Menu Tabs Background', 'socialize' ),
				'desc'  => esc_html__( 'The menu tabs background.', 'socialize' ),
				'output'    => array( '.gp-menu-tabs' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),

			array(
				'id'        => 'menu_tabs_link',
				'type'      => 'color',
				'title'     => esc_html__( 'Menu Tabs Link Color', 'socialize' ),
				'desc'  => esc_html__( 'The menu tabs link color.', 'socialize' ),
				'output'    => array( '.gp-nav .menu li .gp-menu-tabs li' ),
				'default'    => '#fff',
			),

			 array(
				'id'        => 'menu_tabs_link_hover',
				'type'      => 'color',
				'title'     => esc_html__( 'Menu Tabs Link Hover/Selected Color', 'socialize' ),
				'desc'  => esc_html__( 'The menu tabs link hover/selected color.', 'socialize' ),
				'output'    => array( '.gp-nav .menu li .gp-menu-tabs li:hover', '.gp-nav .menu li .gp-menu-tabs li.gp-selected' ),
				'default'    => '#e93100',
			),
				   
		)
	) );
								
	Redux::setSection( $opt_name, array(
		'id' => 'styling-small-header',
		'title'     => esc_html__( 'Small Header', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-website',
		'fields'    => array(
						
			array(
				'id'        => 'small_header_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Background', 'socialize' ),
				'desc'  => esc_html__( 'The small header background.', 'socialize' ),
				'output'    => array( '#gp-small-header #gp-top-nav' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'transparent' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#fff',
				),
			),

			array(
				'id'        => 'small_header_nav_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Navigation Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The small header navigation link colors.', 'socialize' ),
				'output'    => array( '#gp-small-header .menu > li', '#gp-small-header .menu > li > a' ),
				'default'   => array(
					'regular'   => '#000',
					'hover'     => '#999',
					'active' 	=> false,
				),
			),
								
		),
	) );
	
	Redux::setSection( $opt_name, array(
		'id' => 'styling-mobile-navigation',
		'title'     => esc_html__( 'Mobile Navigation', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-lines',
		'fields'    => array(                                          
																									 
			array(
				'id'        => 'mobile_nav_button',
				'type'      => 'color',
				'title'     => esc_html__( 'Mobile Buttons', 'socialize' ),
				'desc'  => esc_html__( 'The mobile navigation buttons color.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav-button', '#gp-profile-button', '#gp-mobile-nav-close-button' ),
				'transparent' => false,
				'default'   => '#fff',
			),
														
			array(
				'id'        => 'mobile_nav_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Container Background', 'socialize' ),
				'desc'  => esc_html__( 'The mobile navigation background.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav' ),
			   'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#fff',
				),
			),

			array(
				'id'        => 'mobile_close_button_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Close Button Background', 'socialize' ),
				'desc'  => esc_html__( 'The close button background.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav-close-button' ),
			   'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#00BEEE',
				),
			),

			array(
				'id'        => 'mobile_nav_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu text color.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav li' ),
				'default' => '#000',
			),

			array(
				'id'        => 'mobile_nav_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu link colors.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav .menu li span', '#gp-mobile-nav .menu li a' ),
				'default'   => array(
					'regular'         => '#000',
					'hover'     => '#000',
					'active' => false,
				),
			),

			array(
				'id'        => 'mobile_nav_bg_hover',
				'type'      => 'background',
				'title'     => esc_html__( 'Link Background Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu background hover color.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav li a:hover' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#f1f1f1',
				),
			),
			
			array(
				'id'        => 'mobile_nav_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Link Border', 'socialize' ),
				'desc'  => esc_html__( 'The dropdown menu border.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav li' ),
				'left' => false,
				'right' => false,
				'bottom' => false,
				'default'   => array(
					'border-width' => '1px',
					'border-color' => '#e0e0e0',
					'border-style' => 'solid',
				),
			),
			
			array(
				'id'        => 'mobile_nav_megamenu_header',
				'type'      => 'color',                        
				'title'     => esc_html__( 'Mega Menu Header Color', 'socialize' ),
				'desc'  => esc_html__( 'The mega menu header color.', 'socialize' ),
				'output'    => array( '#gp-mobile-nav .gp-megamenu > .sub-menu > li > a' ),
				'transparent' => false,
				'default'  => '#000',
			),
						 
		)
	) );    

	Redux::setSection( $opt_name, array(
		'id' => 'styling-page-header',
		'title'     => esc_html__( 'Page Header', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-website',
		'fields'    => array( 
																	   
			array(
				'id'        => 'page_header_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Page Header Background', 'socialize' ),
				'desc'  => esc_html__( 'The page header background (can be overriden on individual posts/pages).', 'socialize' ),
				'output'    => array( '.gp-large-page-header .gp-page-header', '.gp-fullwidth-page-header .gp-page-header', '#gp-full-page-bg' ),
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
					'background-repeat' => 'no-repeat',
					'background-size' => 'cover',
					'background-attachment' => 'scroll',
					'background-position' => 'center center'
				),
			),
			
			array(
				'id'        => 'page_header_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Page Header Typography', 'socialize' ),
				'desc'  => esc_html__( 'The page header typography.', 'socialize' ),
				'output'    => array( '.gp-page-header h1', '.gp-page-header h2' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '80px',
					'line-height' => '90px',
					'color' => '#fff',                           
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '600',
					'subsets'     => 'latin',
				),
			),
															
			array( 
				'id' => 'page_header_height',
				'title' => esc_html__( 'Page Header Height', 'socialize' ),
				'output'    => array( '.gp-page-header .gp-container' ),
				'units' => 'px',
				'type' => 'dimensions',
				'desc' => esc_html__( 'The height of the page header.', 'socialize' ),
				'width' => false,
				'mode' => array( 
					'width' => false,
					'height' => 'height',
				),
				'default' => array(
					'height'     => 250, 
				)					
			 ),

			array( 
				'id' => 'page_header_parallax',
				'title' => esc_html__( 'Page Header Parallax Effect', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The page header background image moves as you scroll up and down the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),
													
		)
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'styling-posts-pages',
		'title'     => esc_html__( 'Posts/Pages', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-pencil',
		'fields'    => array(      
		                                    
			array(
				'id'        => 'post_title_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Title Typography', 'socialize' ),
				'desc'  => esc_html__( 'The title typography.', 'socialize' ),
				'output'    => array( '.gp-entry-title', '.woocommerce .page-title', '.woocommerce div.product .entry-title.product_title' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '40px',
					'line-height' => '46px',
					'color' => '#000',                           
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'post_subtitle_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Subtitle Typography', 'socialize' ),
				'desc'  => esc_html__( 'The subtitle typography.', 'socialize' ),
				'output'    => array( '.gp-subtitle' ),
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '20px',
					'line-height' => '32px',
					'color' => '#888',                           
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
				),
			),

			array(
				'id'        => 'post_links_color',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Post Navigation/Share Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The post navigation/share link colors.', 'socialize' ),
				'output'    => array( '#gp-post-links a', '.gp-pagination-arrows a.prev', '.gp-pagination-arrows a.next', '.gp-carousel-wrapper .flex-direction-nav a' ),
				'transparent' => false,
				'default'   => array(
					'regular' => '#aaa',
					'hover'   => '#333',
					'active'  => false,
				),
			),
						
			array(
				'id'        => 'post_meta_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Meta Color', 'socialize' ),
				'desc'  => esc_html__( 'The post meta color.', 'socialize' ),
				'output'    => array( '.gp-entry-meta', '.gp-meta-comments a' ),
				'transparent' => false,
				'default'  => '#aaa',
			),
											
			array(
				'id'        => 'post_cats_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Categories Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The post categories text color.', 'socialize' ),
				'output'    => array( '.gp-entry-cats', '.gp-entry-cats a', '.gp-entry-cats a:hover' ),
				'transparent' => false,
				'default' => '#fff',
			),

			array(
				'id'        => 'post_cats_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Post Categories Background', 'socialize' ),
				'desc'  => esc_html__( 'The post categories background.', 'socialize' ),
				'output'    => array( '.gp-entry-cats' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#e93100',
				),
			),
								
			array(
				'id'        => 'post_tags_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Tags Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The post tags text color.', 'socialize' ),
				'output'    => array( '.gp-entry-tags a', '.gp-entry-tags a:hover' ),
				'transparent' => false,
				'default' => '#fff',
			),
									
			array(
				'id'        => 'post_tags_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Post Tags Background', 'socialize' ),
				'desc'  => esc_html__( 'The post tags background.', 'socialize' ),
				'output'    => array( '.gp-entry-tags a' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
																															 
			array(
				'id'        => 'author_info_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Author Info Panel Background', 'socialize' ),
				'desc'  => esc_html__( 'The author info panel background.', 'socialize' ),
				'output'    => array( '.gp-author-info', '#gp-post-navigation #gp-share-icons' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),			

			array(
				'id'        => 'author_info_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Author Info Panel Typography', 'socialize' ),
				'desc'  => esc_html__( 'The author info panel typography.', 'socialize' ),
				'output'    => array( '.gp-author-info', '.gp-author-info a', '#gp-post-navigation #gp-share-icons h3' ),
				'google'    => true,
				'text-align' => false,
				'font-family' => false,
				'font-weight' => false,
				'font-style' => false,
				'subsets'     => false,
				'default'   => array(
					'font-size'   => '14px',
					'line-height' => '24px',
					'color' 	  => '#fff',
					'font-weight' => '300',
				),
			),

			array(
				'id'        => 'author_info_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Author Info Panel Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The author info panel link colors.', 'socialize' ),
				'output'    => array( '.gp-author-info a' ),
				'default'   => array(
					'regular' => '#b1b1b1',
					'hover'   => '#fff',
					'active'  => false,
				),
			),

			array(
				'id'        => 'video_post_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Video Post Background', 'socialize' ),
				'desc'  => esc_html__( 'The video post background.', 'socialize' ),
				'output'    => array( '.gp-entry-video-wrapper' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
																				 
			array(
				'id'        => 'video_post_title_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Video Post Title Color', 'socialize' ),
				'desc'  => esc_html__( 'The video post title color.', 'socialize' ),
				'output'    => array( '.gp-entry-video-wrapper .gp-entry-title' ),
				'default' => '#fff',
			),
																					 
			array(
				'id'        => 'video_post_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Video Post Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The video post text color.', 'socialize' ),
				'output'    => array( '.gp-entry-video-wrapper .gp-video-description' ),
				'default' => '#777',
			),
																								 
			array(
				'id'        => 'blockquote_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Blockquote Background', 'socialize' ),
				'desc'  => esc_html__( 'The blockquote background.', 'socialize' ),
				'output'    => array( 'blockquote' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#191919',
				),
			),
			
			array(
				'id'        => 'blockquote_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Blockquote Typography', 'socialize' ),
				'desc'  => esc_html__( 'The blockquote typography.', 'socialize' ),
				'output'    => array( 'blockquote', 'blockquote a', 'blockquote a:hover' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '24px',
					'line-height' => '38px',
					'font-family' => 'Roboto Slab',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
					'color'       => '#fff',
				),
			),
												
		)
	) );
				  
	Redux::setSection( $opt_name, array(
		'id' => 'styling-categories',
		'title'     => esc_html__( 'Categories', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-folder-open',
		'fields'    => array(

			array(
				'id'        => 'cat_standard_post_title_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Standard Post Title Typography', 'socialize' ),
				'desc'  => esc_html__( 'The standard post title typography.', 'socialize' ),
				'output'    => array( '.gp-loop-title' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-family' => false,
				'font-style' => false,
				'subsets'     => false,
				'default'   => array(
					'font-size'   => '19px',
					'line-height' => '22px',
					'font-weight' => '400',
				),
			),
			
			array(
				'id'        => 'cat_large_post_title_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Large Post Title Typography', 'socialize' ),
				'desc'  => esc_html__( 'The large post title typography.', 'socialize' ),
				'output'    => array( '.gp-blog-large .gp-loop-title' ),
				'google'    => true,
				'text-align' => false,
				'color' => false,
				'font-family' => false,
				'font-style' => false,
				'subsets'     => false,
				'default'   => array(
					'font-size'   => '40px',
					'line-height' => '46px',
					'font-weight' => '200',
				),
			),
		
			array(
				'id'        => 'cat_post_title_link_color',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Post Title Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The post title link colors.', 'socialize' ),
				'output'    => array( '.gp-loop-title a', '.bboss_search_item .entry-title a' ),
				'default'   => array(
					'regular'       => '#000',
					'hover'       => '#e93100',
					'active'       => false,
				),
			),

			array(
				'id'        => 'cat_post_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The post text color.', 'socialize' ),
				'output'    => array( '.gp-loop-text', '.gp-login-wrapper p' ),
				'transparent' => false,
				'default' => '#8c8c8c',
			),

			array(
				'id'        => 'cat_meta_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Meta Color', 'socialize' ),
				'desc'  => esc_html__( 'The post meta color.', 'socialize' ),
				'output'    => array( '.gp-loop-meta', '.gp-loop-meta a', '#gp-breadcrumbs', '#gp-breadcrumbs a', '.woocommerce .woocommerce-breadcrumb a', '.woocommerce .woocommerce-breadcrumb', '.comment-text time', 'div.bbp-breadcrumb', 'div.bbp-breadcrumb a', '.gp-statistics-wrapper .gp-stat-title', '.widget_display_replies ul li div', '.widget_display_topics ul li div' ),
				'transparent' => false,
				'default'  => '#aaa',
			),
								
			array(
				'id'        => 'cat_cats_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Categories Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The post categories text color.', 'socialize' ),
				'output'    => array( '.gp-loop-cats', '.gp-loop-cats a', '.gp-loop-cats a:hover' ),
				'transparent' => false,
				'default' => '#aaa',
			),
													
			array(
				'id'        => 'cat_post_tags_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Post Tags Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The post tags text color.', 'socialize' ),
				'output'    => array( '.gp-loop-tags', '.gp-loop-tags a', '.gp-loop-tags a:hover' ),
				'transparent' => false,
				'default' => '#e93100',
			),

			array(
				'id'        => 'cat_masonry_post_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Masonry Background', 'socialize' ),
				'desc'  => esc_html__( 'The masonry background.', 'socialize' ),
				'output'    => array( '.gp-blog-masonry section', 'section.sticky' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#f8f8f8',
				),
			),
				
			array(
				'id'        => 'pagination_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Pagination Background', 'socialize' ),
				'desc'  => esc_html__( 'The pagination background.', 'socialize' ),
				'output'    => array( 'ul.page-numbers .page-numbers' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#e93100',
				),
			),

			array(
				'id'        => 'pagination_bg_hover',
				'type'      => 'background',
				'title'     => esc_html__( 'Pagination Hover/Selected Background', 'socialize' ),
				'desc'  => esc_html__( 'The pagination hover/selected background.', 'socialize' ),
				'output'    => array( 'ul.page-numbers .page-numbers:hover', 'ul.page-numbers .page-numbers.current', 'ul.page-numbers > span.page-numbers' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),

			array(
				'id'        => 'pagination_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Pagination Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The pagination text color.', 'socialize' ),
				'output'    => array( 'ul.page-numbers .page-numbers' ),
				'transparent' => false,
				'default' => '#fff',
			),
																
		),
	
	) );      
	
	Redux::setSection( $opt_name, array(
		'id' => 'styling-widgets-elements',
		'title'     => esc_html__( 'Widgets/Elements', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-cog',
		'fields'    => array(
								
			array(
				'id'        => 'widget_title_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Widget Title Typography', 'socialize' ),
				'desc'  => esc_html__( 'The widget title typograpghy.', 'socialize' ),
				'output'    => array( '.widgettitle' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '14px',
					'line-height' => '18px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '500',
					'subsets'     => 'latin',
					'color'       => '#000',
				),
			), 

			array(
				'id'        => 'widget_title_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Widget Title Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The widget title link colors.', 'socialize' ),
				'output'    => array( '.widgettitle a' ),
				'default'   => array(
					'regular' => '#e93100',
					'hover'   => '#000',
					'active'  => false,
				),
			),
			
			 array(
				'id'        => 'widget_title_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Widget Title Border', 'socialize' ),
				'desc'  => esc_html__( 'The widget title border color.', 'socialize' ),
				'output'    => array( '.gp-sidebar .widgettitle', '.widgettitle.gp-standard-title' ),
				'top' => false,
				'left' => false,
				'right' => false,
				'default'   => array(
					'border-color' => '#E93100',
					'border-width' => '3px',
					'border-style' => 'solid',
				),
			),
			
			 array(
				'id'        => 'widget_bg',
				'type'      => 'color',
				'title'     => esc_html__( 'Widget Background', 'socialize' ),
				'desc'  => esc_html__( 'The widget background.', 'socialize' ),
				'output'    => array(
					'background-color' => '.widget, .gp-vc-element, .widgettitle.gp-fancy-title .gp-widget-title, .widgettitle.gp-fancy-title .gp-element-icon, .widget .woocommerce',
					'border-left-color' => '.widgettitle.gp-fancy-title .gp-triangle',
				),	
				'default' => '#fff',
			),
																									
		),
	
	) );      
		
			
	Redux::setSection( $opt_name, array(
		'id' => 'styling-fields-buttons',
		'title'     => esc_html__( 'Fields & Buttons', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-check',
		'fields'    => array(
						
			array(
				'id'        => 'input_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Input Background', 'socialize' ),
				'desc'  => esc_html__( 'The input background.', 'socialize' ),
				'output'    => array( 'input', 'textarea', '.gp-search-bar', '.gp-theme #buddypress .dir-search input[type=search]', '.gp-theme #buddypress .dir-search input[type=text]', '.gp-theme #buddypress .groups-members-search input[type=search]', '.gp-theme #buddypress .standard-form input[type=color]', '.gp-theme #buddypress .standard-form input[type=date]', '.gp-theme #buddypress .standard-form input[type=datetime-local]', '.gp-theme #buddypress .standard-form input[type=datetime]', '.gp-theme #buddypress .standard-form input[type=email]', '.gp-theme #buddypress .standard-form input[type=month]', '.gp-theme #buddypress .standard-form input[type=number]', '.gp-theme #buddypress .standard-form input[type=password]', '.gp-theme #buddypress .standard-form input[type=range]', '.gp-theme #buddypress .standard-form input[type=search]', '.gp-theme #buddypress .standard-form input[type=tel]', '.gp-theme #buddypress .standard-form input[type=text]', '.gp-theme #buddypress .standard-form input[type=time]', '.gp-theme #buddypress .standard-form input[type=url]', '.gp-theme #buddypress .standard-form input[type=week]', '.gp-theme #buddypress .standard-form textarea', '.gp-theme #buddypress div.activity-comments form .ac-textarea', '.gp-theme #buddypress form#whats-new-form textarea' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#fafafa',
				),
			),
			
			array(
				'id'        => 'input_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Input Border', 'socialize' ),
				'desc'  => esc_html__( 'The input border.', 'socialize' ),
				'output'    => array( 'input', 'textarea', '.gp-search-bar', '.gp-login-wrapper .gp-login-icon', '.gp-login-wrapper .gp-password-icon', '.gp-login-wrapper .gp-email-icon', '.gp-theme #buddypress .dir-search input[type=search]', '.gp-theme #buddypress .dir-search input[type=text]', '.gp-theme #buddypress .groups-members-search input[type=search]', '.gp-theme #buddypress .standard-form input[type=color]', '.gp-theme #buddypress .standard-form input[type=date]', '.gp-theme #buddypress .standard-form input[type=datetime-local]', '.gp-theme #buddypress .standard-form input[type=datetime]', '.gp-theme #buddypress .standard-form input[type=email]', '.gp-theme #buddypress .standard-form input[type=month]', '.gp-theme #buddypress .standard-form input[type=number]', '.gp-theme #buddypress .standard-form input[type=password]', '.gp-theme #buddypress .standard-form input[type=range]', '.gp-theme #buddypress .standard-form input[type=search]', '.gp-theme #buddypress .standard-form input[type=tel]', '.gp-theme #buddypress .standard-form input[type=text]', '.gp-theme #buddypress .standard-form input[type=time]', '.gp-theme #buddypress .standard-form input[type=url]', '.gp-theme #buddypress .standard-form input[type=week]', '.gp-theme #buddypress .standard-form textarea', '.gp-theme #buddypress div.activity-comments form .ac-textarea', '.bb-global-search-ac.ui-autocomplete' ),      
				'default'   => array(
					'border-color' => '#ddd',
					'border-width' => '1px',
					'border-style' => 'solid',
				),
			),
								
			array(
				'id'        => 'input_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Input Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The input text color.', 'socialize' ),
				'output'    => array( 'input', 'textarea', '.gp-search-bar', '.gp-theme #buddypress .dir-search input[type=search]', '.gp-theme #buddypress .dir-search input[type=text]', '.gp-theme #buddypress .groups-members-search input[type=search]', '.gp-theme #buddypress .groups-members-search input[type=text]', '.gp-theme #buddypress .standard-form input[type=color]', '.gp-theme #buddypress .standard-form input[type=date]', '.gp-theme #buddypress .standard-form input[type=datetime-local]', '.gp-theme #buddypress .standard-form input[type=datetime]', '.gp-theme #buddypress .standard-form input[type=email]', '.gp-theme #buddypress .standard-form input[type=month]', '.gp-theme #buddypress .standard-form input[type=number]', '.gp-theme #buddypress .standard-form input[type=password]', '.gp-theme #buddypress .standard-form input[type=range]', '.gp-theme #buddypress .standard-form input[type=search]', '.gp-theme #buddypress .standard-form input[type=tel]', '.gp-theme #buddypress .standard-form input[type=text]', '.gp-theme #buddypress .standard-form input[type=time]', '.gp-theme #buddypress .standard-form input[type=url]', '.gp-theme #buddypress .standard-form input[type=week]', '.gp-theme #buddypress .standard-form textarea', '.gp-theme #buddypress div.activity-comments form .ac-textarea', '.gp-theme #buddypress form#whats-new-form textarea,' ),
				'transparent' => false,
				'default' => '#000',
			),

						
			array(
				'id'        => 'select_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Dropdown Menu Background', 'socialize' ),
				'desc'  => esc_html__( 'The select background.', 'socialize' ),
				'output'    => array( 'select', '.gp-theme #buddypress .standard-form select', '.gp-theme #buddypress form#whats-new-form #whats-new-options select', '#buddypress .standard-form select:focus' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
								
			array(
				'id'        => 'select_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Dropdown Menu Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The select text color.', 'socialize' ),
				'output'    => array( 'select', '.gp-theme #buddypress .standard-form select', '.gp-theme #buddypress form#whats-new-form #whats-new-options select' ),
				'transparent' => false,
				'default' => '#fff',
			),
			
			array(
				'id'        => 'button_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Button Background', 'socialize' ),
				'desc'  => esc_html__( 'The button background.', 'socialize' ),
				'output'    => array( 'input[type="button"]', 'input[type="submit"]', 'input[type="reset"]', 'button', '.button', '.gp-theme #buddypress .comment-reply-link', '.gp-notification-counter', '#gp-cart-counter', '.gp-theme #buddypress a.button', '.gp-theme #buddypress button', '.gp-theme #buddypress div.generic-button a', '.gp-theme #buddypress input[type=button]', '.gp-theme #buddypress input[type=reset]', '.gp-theme #buddypress input[type=submit]', '.gp-theme #buddypress ul.button-nav li a', 'a.bp-title-button', '.gp-theme #buddypress .activity-list #reply-title small a span', '.gp-theme #buddypress .activity-list a.bp-primary-action span', '.tribe-events-calendar thead th', '#tribe-events .tribe-events-button', '#tribe-events .tribe-events-button:hover', '#tribe_events_filters_wrapper input[type=submit]', '.tribe-events-button', '.tribe-events-button.tribe-active:hover',  '.tribe-events-button.tribe-inactive', '.tribe-events-button:hover', '.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-]', '.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-] > a', '#tribe-bar-form .tribe-bar-submit input[type=submit]', '.woocommerce #respond input#submit.alt', '.woocommerce a.button.alt', '.woocommerce button.button.alt', '.woocommerce input.button.alt', '#gp-dropdowncart .woocommerce a.button' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#000',
				),
			),

			 array(
				'id'        => 'button_bg_hover',
				'type'      => 'background',
				'title'     => esc_html__( 'Button Background Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The button background hover color.', 'socialize' ),
				'output'    => array( 'input[type="button"]:hover', 'input[type="submit"]:hover', 'input[type="reset"]:hover', 'button:hover', '.button:hover', '.gp-theme #buddypress .comment-reply-link:hover', '.gp-theme #buddypress a.button:hover', '.gp-theme #buddypress button:hover', '.gp-theme #buddypress div.generic-button a:hover', '.gp-theme #buddypress input[type=button]:hover', '.gp-theme #buddypress input[type=reset]:hover', '.gp-theme #buddypress input[type=submit]:hover', '.gp-theme #buddypress ul.button-nav li a:hover', 'a.bp-title-button:hover', '.gp-theme #buddypress .activity-list #reply-title small a:hover span', '.gp-theme #buddypress .activity-list a.bp-primary-action:hover span', '.woocommerce #respond input#submit.alt:hover', '.woocommerce a.button.alt:hover', '.woocommerce button.button.alt:hover', '.woocommerce input.button.alt:hover', '#gp-dropdowncart .woocommerce a.button:hover' ),                        
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
								
			array(
				'id'        => 'button_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Button Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The button text color.', 'socialize' ),
				'output'    => array( 'input[type="button"]', 'input[type="submit"]', 'input[type="reset"]', 'button', '.button', 'a.gp-notification-counter', 'a.gp-notification-counter:hover', '#gp-cart-counter:hover', '.gp-theme #buddypress .comment-reply-link', '.gp-theme #buddypress a.button', '.gp-theme #buddypress button', '.gp-theme #buddypress div.generic-button a', '.gp-theme #buddypress input[type=button]', '.gp-theme #buddypress input[type=reset]', '.gp-theme #buddypress input[type=submit]', '.gp-theme #buddypress ul.button-nav li a', 'a.bp-title-button', '.gp-theme #buddypress .activity-list #reply-title small a span', '.gp-theme #buddypress .activity-list a.bp-primary-action span', '#gp-dropdowncart .woocommerce a.button' ),
				'transparent' => false,
				'default' => '#fff',
			),
			
			array(
				'id'        => 'button_text_hover_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Button Text Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The button text hover color.', 'socialize' ),
				'output'    => array( 'input[type="button"]:hover', 'input[type="submit"]:hover', 'input[type="reset"]:hover', 'button', '.button:hover', '.gp-theme #buddypress .comment-reply-link:hover', '.gp-theme #buddypress a.button:hover', '.gp-theme #buddypress button:hover', '.gp-theme #buddypress div.generic-button a:hover', '.gp-theme #buddypress input[type=button]:hover', '.gp-theme #buddypress input[type=reset]:hover', '.gp-theme #buddypress input[type=submit]:hover', '.gp-theme #buddypress ul.button-nav li a:hover', 'a.bp-title-button:hover', '.gp-theme #buddypress .activity-list #reply-title small a span', '.gp-theme #buddypress .activity-list a.bp-primary-action span', '#gp-dropdowncart .woocommerce a.button:hover' ),
				'transparent' => false,
				'default'  => '#fff', 
			),	
		)
	) );

	Redux::setSection( $opt_name, array(
		'id' => 'styling-footer',
		'title'     => esc_html__( 'Footer', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-photo',
		'fields'    => array(
											
			array(
				'id'        => 'footer_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Footer Background', 'socialize' ),
				'desc'  => esc_html__( 'The footer background.', 'socialize' ),
				'output'    => array( '#gp-footer' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
												  
			array(
				'id'        => 'footer_widget_title_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Footer Widget Title Typography', 'socialize' ),
				'desc'  => esc_html__( 'The footer widget title typography.', 'socialize' ),
				'output'    => array( '.gp-footer-widget .widgettitle' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '16px',
					'line-height' => '20px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
					'color'       => '#fff',
				),
			),
								
			array(
				'id'        => 'footer_widget_typography',
				'type'      => 'typography',
				'title'     => esc_html__( 'Footer Widget Typography', 'socialize' ),
				'desc'  => esc_html__( 'The footer widget typography.', 'socialize' ),
				'output'    => array( '.gp-footer-widget' ),
				'google'    => true,
				'text-align' => false,
				'font-backup' => true,
				'default'   => array(
					'font-size'   => '18px',
					'line-height' => '28px',
					'font-family' => 'Roboto',
					'font-backup' => 'Arial, Helvetica, sans-serif',
					'font-weight' => '300',
					'subsets'     => 'latin',
					'color'       => '#777',
				),
			),

			array(
				'id'        => 'footer_widget_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Footer Widget Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The footer widget link colors.', 'socialize' ),
				'output'    => array( '.gp-footer-widget a' ),
				'default'   => array(
					'regular' => '#fff',
					'hover'   => '#b1b1b1',
					'active'  => false,
				),
			),
			
			array(
				'id'        => 'copyright_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Copyright Background', 'socialize' ),
				'desc'  => esc_html__( 'The copyright background.', 'socialize' ),
				'output'    => array( '#gp-copyright' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),

			array(
				'id'        => 'copyright_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Copyright Border', 'socialize' ),
				'desc'  => esc_html__( 'The copyright border.', 'socialize' ),
				'output'    => array( '#gp-copyright' ),   
				'left' => false,
				'right' => false,    
				'bottom' => false,    
				'default'   => array(
					'border-color' => '#444',
					'border-top' => '1px',
					'border-style' => 'solid',
				),
			),
						
			array(
				'id'        => 'copyright_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Copyright Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The copyright text color.', 'socialize' ),
				'output'    => array( '#gp-copyright' ),
				'transparent' => false,
				'default' => '#777',
			),

			array(
				'id'        => 'copyright_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Copyright Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The copyright link colors.', 'socialize' ),
				'output'    => array( '#gp-copyright a' ),
				'default'   => array(
					'regular' => '#999',
					'hover'   => '#e0e0e0',
					'active'  => false,
				),
			),
																																	
			array(
				'id'        => 'back_to_top_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Back To Top Background', 'socialize' ),
				'desc'  => esc_html__( 'The back to top button background.', 'socialize' ),
				'output'    => array( '#gp-to-top' ),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),
								
			array(
				'id'        => 'back_to_top_icon_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Back To Top Icon Color', 'socialize' ),
				'desc'  => esc_html__( 'The back to top icon color.', 'socialize' ),
				'output'    => array( '#gp-to-top' ),
				'transparent' => false,
				'default'   => '#fff',
			),
																																	 
		)
	) );                


	Redux::setSection( $opt_name, array(
		'id' => 'styling-woocommerce',
		'title'     => esc_html__( 'WooCommerce', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-shopping-cart',
		'fields'    => array(

			array(
				'id'        => 'product_price_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Price Color', 'socialize' ),
				'desc'  => esc_html__( 'The price color.', 'socialize' ),
				'output'    => array( '.woocommerce ul.products li.product .price', '.woocommerce div.product p.price', '.woocommerce div.product span.price' ),
				'transparent' => false,
				'default'   => '#00bee9',
			),
			
			array(
				'id'        => 'product_sale_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Sale Sign Background', 'socialize' ),
				'desc'  => esc_html__( 'The sale sign background.', 'socialize' ),
				'output'    => array( '.woocommerce span.onsale' ), 	
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#e93100',
				),
			),
																				 
		)
	) );                
	
	Redux::setSection( $opt_name, array(
		'id' => 'styling-buddypress',
		'title'     => esc_html__( 'BuddyPress', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-comment-alt',
		'fields'    => array(

			array(
				'id'        => 'bp_list_title_color',
				'type'      => 'color',
				'title'     => esc_html__( 'List Title Color', 'socialize' ),
				'desc'  => esc_html__( 'The list title color.', 'socialize' ),
				'output'    => array(
					'#buddypress .activity-list .activity-content .activity-header', 
					'#buddypress .activity-list .activity-content .comment-header', 
					'#buddypress .activity-list .activity-header a',
					'#buddypress .activity-list div.activity-comments div.acomment-meta',
					'#buddypress .activity-list .acomment-meta a',
					'.widget.buddypress .item-title a',
					'.widget.buddypress div.item-options.gp-small-item-options:before',
					'.widget.buddypress div.item-options a',
					'#buddypress ul.item-list li div.item-title a',
					'#buddypress ul.item-list li h4 > a',
					'#buddypress ul.item-list li h5 > a',
					'#buddypress div#item-header div#item-meta',
				),
				'default'   => '#000',
			),

			array(
				'id'        => 'bp_list_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'List Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The list text color.', 'socialize' ),
				'output'    => array(
					'#buddypress .activity-list .activity-content .activity-inner',
				),
				'default'   => '#aaa',
			),
			
			array(
				'id'        => 'bp_list_meta_color',
				'type'      => 'color',
				'title'     => esc_html__( 'List Meta Color', 'socialize' ),
				'desc'  => esc_html__( 'The list meta color.', 'socialize' ),
				'output'    => array( 
					'#buddypress .activity-list a.activity-time-since', 
					'.widget_display_replies ul li a + div', 
					'.widget_display_topics ul li a + div',
					'#buddypress .activity-list .acomment-meta a.activity-time-since',
					'#buddypress .activity-list div.activity-comments div.acomment-content',
					'.widget.buddypress div.item-meta',
					'#buddypress span.activity',
					'#buddypress ul.item-list li div.meta',
				),
				'default'   => '#aaa',
			),

			array(
				'id'        => 'bp_list_meta_button_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'List Meta Button Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The list meta button link colors.', 'socialize' ),
				'output'    => array( 
					'.gp-theme #buddypress .activity-list div.activity-meta a.button',
					'.gp-theme #buddypress .activity .acomment-options a',
					'.gp-theme #buddypress .activity-list li.load-more a',
					'.gp-theme #buddypress .activity-list li.load-newest a',
					'.widget.buddypress div.item-options a.selected',
				),
				'default'   => array(
					'regular'  => '#e93100',
					'hover'    => '#000',
					'active'   => false,
				),
			),
			
			array(
				'id'        => 'bp_list_divider',
				'type'      => 'border',
				'title'     => esc_html__( 'List Divider Color', 'socialize' ),
				'desc'  => esc_html__( 'The list divider color.', 'socialize' ),
				'output'    => array(
					'.gp-theme #buddypress ul.item-list li',
					'.gp-theme #buddypress div.activity-comments ul li:first-child',
					'.widget.buddypress #friends-list li',
					'.widget.buddypress #groups-list li',
					'.widget.buddypress #members-list li',
				),   
				'left' => false,
				'right' => false,    
				'default'   => array(
					'border-color' => '#e0e0e0',
					'border-width' => '1px',
					'border-style' => 'solid',
				),
			),

			
			array(
				'id'        => 'bp_primary_options_tab',
				'type'      => 'color',
				'title'     => esc_html__( 'Primary Options Tab Background', 'socialize' ),
				'desc'  => esc_html__( 'The primary options tab background.', 'socialize' ),
				'output'    => array(
					'background-color' => '.gp-theme #buddypress div.item-list-tabs',
					'color' => '.gp-theme #buddypress div.item-list-tabs ul li a span,.gp-theme #buddypress div.item-list-tabs ul li a:hover span,.gp-theme #buddypress div.item-list-tabs ul li.current a span,.gp-theme #buddypress div.item-list-tabs ul li.selected a span'
				),   	
				'default'   => '#353535',
			),

			array(
				'id'        => 'bp_primary_option_tab_link',
				'type'      => 'color',
				'title'     => esc_html__( 'Primary Options Tab Link Color', 'socialize' ),
				'desc'  => esc_html__( 'The primary options tab link color.', 'socialize' ),
				'output'    => array( 
					'background-color' => '.gp-theme #buddypress div.item-list-tabs ul li a span',
					'color' => '.gp-theme #buddypress div.item-list-tabs ul li a, .gp-theme #buddypress #gp-bp-tabs-button, .gp-theme #buddypress div.item-list-tabs ul li span',
				),
				'transparent' => false,
				'default' => '#fff',
			),

			array(
				'id'        => 'bp_primary_option_tab_link_hover',
				'type'      => 'color',
				'title'     => esc_html__( 'Primary Options Tab Link Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The primary options tab link hover colors.', 'socialize' ),
				'output'    => array( 
					'color' => '.gp-theme #buddypress div.item-list-tabs ul li.current a, .gp-theme #buddypress div.item-list-tabs ul li.selected a,.gp-theme #buddypress div.item-list-tabs ul li a:hover', 
					'background' => '.gp-theme #buddypress div.item-list-tabs ul li a:hover span,.gp-theme #buddypress div.item-list-tabs ul li.current a span,.gp-theme #buddypress div.item-list-tabs ul li.selected a span',
				),
				'transparent' => false,
				'default'   => '#b1b1b1',
			),

			array(
				'id'        => 'bp_secondary_options_tab',
				'type'      => 'color',
				'title'     => esc_html__( 'Secondary Options Tab Background', 'socialize' ),
				'desc'  => esc_html__( 'The secondary options tab background.', 'socialize' ),
				'output'    => array(
					'background-color' => '.gp-theme #buddypress div.item-list-tabs#subnav ul,  .widget.buddypress div.item-options.gp-small-item-options > a',
					'color' => '.gp-theme #buddypress div.item-list-tabs#subnav ul li a span,.gp-theme #buddypress div.item-list-tabs#subnav ul li a:hover span,.gp-theme #buddypress div.item-list-tabs#subnav ul li.current a span,.gp-theme #buddypress div.item-list-tabs#subnav ul li.selected a span'
				),   	
				'default'   => '#f8f8f8',
			),

			array(
				'id'        => 'bp_secondary_options_tab_link',
				'type'      => 'color',
				'title'     => esc_html__( 'Secondary Options Tab Link Color', 'socialize' ),
				'desc'  => esc_html__( 'The secondary options tab link color.', 'socialize' ),
				'output'    => array(
					'background-color' => '.gp-theme #buddypress div.item-list-tabs#subnav ul li a span',
					'color' => '.gp-theme #buddypress div.item-list-tabs#subnav ul li a',
				),   	
				'transparent' => false,
				'default'   => '#000',
			),

			array(
				'id'        => 'bp_secondary_options_tab_link_hover',
				'type'      => 'color',
				'title'     => esc_html__( 'Secondary Options Tab Link Hover Color', 'socialize' ),
				'desc'  => esc_html__( 'The secondary options tab link hover color.', 'socialize' ),
				'output'    => array(
					'color' => '.gp-theme #buddypress div.item-list-tabs#subnav ul li.current a, .gp-theme #buddypress div.item-list-tabs#subnav ul li.selected a, .gp-theme #buddypress div.item-list-tabs#subnav ul li a:hover',
					'background' => '.gp-theme #buddypress div.item-list-tabs#subnav ul li a:hover span,.gp-theme #buddypress div.item-list-tabs#subnav ul li.current a span,.gp-theme #buddypress div.item-list-tabs#subnav ul li.selected a span',
				),   	
				'transparent' => false,
				'default'   => '#e93100',
			),
																																	 
		)
	) );                

	Redux::setSection( $opt_name, array(
		'id' => 'styling-bbpress',
		'title'     => esc_html__( 'bbPress', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-comment-alt',
		'fields'    => array(

			array(
				'id'        => 'bbpress_forum_cat_header_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Forum Category Header Background', 'socialize' ),
				'desc'  => esc_html__( 'The forum category header background.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .gp-forum-home.bbp-forums .bbp-has-subforums .bbp-forum-info > .bbp-forum-title',
					'#bbpress-forums .bbp-topics .bbp-header',
					'#bbpress-forums .bbp-replies .bbp-header',
					'#bbpress-forums .bbp-search-results .bbp-header',
				),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#353535',
				),
			),

			array(
				'id'        => 'bbpress_forum_cat_header_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Forum Category Header Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The forum category header text color.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .gp-forum-home.bbp-forums .bbp-has-subforums .bbp-forum-info > .bbp-forum-title',
					'#bbpress-forums .bbp-topics .bbp-header',
					'#bbpress-forums .bbp-replies .bbp-header',
					'#bbpress-forums .bbp-search-results .bbp-header',
				),
				'default'   => '#fff',
			),

			array(
				'id'        => 'bbpress_forum_cat_header_link',
				'type'      => 'link_color',
				'title'     => esc_html__( 'Forum Category Header Link Colors', 'socialize' ),
				'desc'  => esc_html__( 'The forum category header link colors.', 'socialize' ),
				'output'    => array( '#bbpress-forums .bbp-header div.bbp-reply-content a' ),
				'default'   => array(
					'regular'  => '#ddd',
					'hover'    => '#fff',
					'active'   => false,
				),
			),
									
			array(
				'id'        => 'bbpress_forum_row_bg_1',
				'type'      => 'background',
				'title'     => esc_html__( 'Forum Row Background 1', 'socialize' ),
				'desc'  => esc_html__( 'The forum row background.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .bbp-forums-list li.odd-forum-row',
					'#bbpress-forums div.odd',
					'#bbpress-forums ul.odd',
				),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#f8f8f8',
				),
			),

			array(
				'id'        => 'bbpress_forum_row_bg_2',
				'type'      => 'background',
				'title'     => esc_html__( 'Forum Row Background 2', 'socialize' ),
				'desc'  => esc_html__( 'The forum row background.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .bbp-forums-list li.even-forum-row',
					'#bbpress-forums div.even',
					'#bbpress-forums ul.even',
				),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#fff',
				),
			),
													
			array(
				'id'        => 'bbpress_forum_border',
				'type'      => 'border',
				'title'     => esc_html__( 'Forum Border Color', 'socialize' ),
				'desc'  => esc_html__( 'The forum border color.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .gp-forum-home.bbp-forums .bbp-forum-info > .bbp-forum-title',
					'#bbpress-forums div.bbp-forum-header',
					'#bbpress-forums div.bbp-topic-header',
					'#bbpress-forums div.bbp-reply-header',
					'#bbpress-forums .bbp-forums-list',
					'#bbpress-forums li.bbp-body',
				),    
				'default'   => array(
					'border-color' => '#ddd',
					'border-width' => '1px',
					'border-style' => 'solid',
				),
			),
										
			array(
				'id'        => 'bbpress_forum_title_link',
				'type'      => 'color',
				'title'     => esc_html__( 'Forum Title Link Color', 'socialize' ),
				'desc'  => esc_html__( 'The forum title link color.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums .bbp-forums-list .bbp-forum .bbp-forum-link',
					'body.forum #bbpress-forums .bbp-forums .bbp-forum-info > .bbp-forum-title',
					'#bbpress-forums .bbp-topics .bbp-topic-permalink',
					'#bbpress-forums .gp-forum-home.bbp-forums .bbp-forum-info > .bbp-forum-title',
				),
				'default'   => '#000',
			),

			array(
				'id'        => 'bbpress_forum_role_bg',
				'type'      => 'background',
				'title'     => esc_html__( 'Forum Role Background', 'socialize' ),
				'desc'  => esc_html__( 'The forum role background.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums div.bbp-forum-author .bbp-author-role', 
					'#bbpress-forums div.bbp-topic-author .bbp-author-role', 
					'#bbpress-forums div.bbp-reply-author .bbp-author-role',
				),
				'background-repeat' => false,
				'background-attachment' => false,
				'background-position' => false,
				'background-image' => false,
				'background-size' => false,
				'preview' => false,
				'default'   => array(
					'background-color' => '#e93100',
				),
			),
										
			array(
				'id'        => 'bbpress_forum_role_text_color',
				'type'      => 'color',
				'title'     => esc_html__( 'Forum Role Text Color', 'socialize' ),
				'desc'  => esc_html__( 'The forum role text color.', 'socialize' ),
				'output'    => array( 
					'#bbpress-forums div.bbp-forum-author .bbp-author-role', 
					'#bbpress-forums div.bbp-topic-author .bbp-author-role', 
					'#bbpress-forums div.bbp-reply-author .bbp-author-role',
				),
				'default'   => '#fff',
			),
																																																																					 
		)
	) );       
			
	/*Redux::setSection( $opt_name, array(
		'id' => 'styling-theme-widths',
		'title'     => esc_html__( 'Theme Widths', 'socialize' ),
		'subsection' => true,
		'icon' => 'el-icon-resize-horizontal',
		'fields'    => array(
										
			$fields = array(
			   'id' => 'section-start-desktop',
			   'type' => 'section',
			   'title' => esc_html__('Larger Desktop (above 1200px)', 'socialize' ),
			   'indent' => true,
		   ),

				array(
					'id' => 'desktop_container',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Container Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 1260, 
					)
				),                     			                                

				array(
					'id' => 'desktop_content',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Content Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 790, 
					)
				),
			
				array(
					'id' => 'desktop_sidebar',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Sidebar Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 380, 
					)
				),

			array(
				'id'     => 'section-end-desktop',
				'type'   => 'section',
				'indent' => false,
			),
		
			array(
			   'id' => 'section-start-sm-desktop',
			   'type' => 'section',
			   'title' => esc_html__('Smaller Desktop (1200px - 1082px)', 'socialize' ),
				'indent' => true,
		   ),

				array(
					'id' => 'sm_desktop_container',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Container Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 1083, 
					)
				),                     			                                

				array(
					'id' => 'sm_desktop_content',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Content Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 663, 
					)
				),
			
				array(
					'id' => 'sm_desktop_sidebar',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Sidebar Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 330, 
					)
				),

			array(
				'id'     => 'section-end-sm-desktop',
				'type'   => 'section',
				'indent' => false,
			),
											
			array(
			   'id' => 'section-start-tablet',
			   'type' => 'section',
			   'title' => esc_html__('Tablet (Landscape)', 'socialize' ),
				'indent' => true,
		   ),

				array(
					'id' => 'tablet_container',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Container Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 1024, 
					)
				),                     			                                

				array(
					'id' => 'tablet_content',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Content Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 604, 
					)
				),
			
				array(
					'id' => 'tablet_sidebar',
					'type' => 'dimensions',
					'units' => 'px',
					'title' => esc_html__('Sidebar Width', 'socialize' ),
					'height' => false,
					'default' => array(
						'width'     => 330, 
					)
				),

			array(
				'id'     => 'section-end-tablet',
				'type'   => 'section',
				'indent' => false,
			),
																																															 
		)
	) );*/
  

	Redux::setSection( $opt_name, array(
		'id' => 'theme_update',
		'title' => esc_html__( 'Theme Update', 'socialize' ),
		'desc' => esc_html__( 'To automatically update the theme you need to enter your ThemeForest (Envato) username and API key.', 'socialize' ) . '<br/>' . esc_html__( 'Please make a backup of the current theme files before you update the theme.', 'socialize' ),
		'icon' => 'el-icon-key',
		'fields' => array(

			array(
				'id'       => 'tf_username',
				'type'     => 'text',
				'title'    => esc_html__( 'ThemeForest Username', 'socialize' ),
				'default'  => ''
			),
			
			array(
				'id'       => 'tf_apikey',
				'type'     => 'text',
				'title'    => esc_html__( 'ThemeForest API Key', 'socialize' ),
				'desc' => '<a href="https://ticksy_attachments.s3.amazonaws.com/9076411986.jpg" class="thickbox" target="_blank">' . esc_html__( 'See how to get your API Key', 'socialize' ) . '</a>',
				'default'  => ''
			),
			
		)
	) );
    /*
     * <--- END SECTIONS
     */