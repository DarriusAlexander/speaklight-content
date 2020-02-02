<?php

// INCLUDE THIS BEFORE you load your ReduxFramework object config file.


// You may replace $redux_opt_name with a string if you wish. If you do so, change loader.php
// as well as all the instances below.
$redux_opt_name = 'socialize';

if ( ! function_exists( 'ghostpool_add_metaboxes' ) ) :

    function ghostpool_add_metaboxes( $metaboxes ) {

    $metaboxes = array();
             
                
	/*--------------------------------------------------------------
	Post Options
	--------------------------------------------------------------*/	

	// Audio Post Format Options
    $audio_format_options = array();
    $audio_format_options[] = array(
		'fields' => array(
						        
			array(
				'id'        => 'audio_mp3_url',
				'type'      => 'media',
				'title'     => esc_html__( 'MP3 Audio File', 'socialize' ),
				'mode'      => false,
				'desc'      => esc_html__( 'Upload a MP3 audio file.', 'socialize' ),
			),

			array(
				'id'        => 'audio_ogg_url',
				'type'      => 'media',
				'title'     => esc_html__( 'OGG Audio File', 'socialize' ),
				'mode'      => false,
				'desc'      => esc_html__( 'Upload a OGG audio file.', 'socialize' ),
			),
					
		),
	);	
    $metaboxes[] = array(
        'id' => 'audio-format-options',
        'title' => esc_html__( 'Audio Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( 'audio' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $audio_format_options,
    );
    	
    	
	// Gallery Post Format Options
    $gallery_format_options = array();
    $gallery_format_options[] = array(
        'fields' => array(
						        
			array(
				'id'        => 'gallery_slider',
				'type'      => 'gallery',
				'title'     => esc_html__( 'Gallery Slider', 'socialize' ),
				 'subtitle'  => esc_html__( 'Create a new gallery by selecting an existing one or uploading new images using the WordPress native uploader.', 'socialize' ),
				 'desc'  => esc_html__( 'Add a gallery slider.', 'socialize' ),
			),
 
		),
	);		
    $metaboxes[] = array(
        'id' => 'gallery-format-options',
        'title' => esc_html__( 'Gallery Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( 'gallery' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $gallery_format_options,
    );
    
    
    // Link Format Options
    $link_format_options = array();
    $link_format_options[] = array(
        'fields' => array(
						        
			array(
				'id'       => 'link',
				'type'     => 'text',
				'title'    => esc_html__( 'Link', 'socialize' ),
				'desc'     => esc_html__( 'The link which your post goes to.', 'socialize' ),
				'validate' => 'url',
			),
			
			array( 
				'id' => 'link_target',
				'title' => esc_html__( 'Link Target', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The target for the link.', 'socialize' ),
				'options' => array(
					'_blank' => esc_html__( 'New Window', 'socialize' ),
					'_self' => esc_html__( 'Same Window', 'socialize' ),
				),
				'default' => '_blank',
			),
					 
		),
	);		
    $metaboxes[] = array(
        'id' => 'link-format-options',
        'title' => esc_html__( 'Link Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( 'link' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $link_format_options,
    );
    
    
    // Quote Format Options
    $quote_format_options = array();
    $quote_format_options[] = array(
        'fields' => array(
						        
			array(
				'id'       => 'quote_source',
				'type'     => 'text',
				'title'    => esc_html__( 'Quote Source', 'socialize' ),
				'desc'     => esc_html__( 'The source of the quote.', 'socialize' ),
			),
					 
		),
	);
    $metaboxes[] = array(
        'id' => 'quote-format-options',
        'title' => esc_html__( 'Quote Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( 'quote' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $quote_format_options,
    );
    
            
    // Video Format Options
    $video_format_options = array();
    $video_format_options[] = array(
        'fields' => array(
			
			array(
				'id'        => 'video_embed_url',
				'type'      => 'text',
				'title'     => esc_html__( 'Video URL', 'socialize' ),
				'desc'      => esc_html__( 'Video URL uploaded to one of the major video sites e.g. YouTube, Vimeo, blip.tv, etc.', 'socialize' ),
				'validate'  => 'url',
				'default' => '',
			),
			        
			array(
				'id'        => 'video_m4v_url',
				'type'      => 'media',
				'title'     => esc_html__( 'M4V Video', 'socialize' ),
				'desc'      => esc_html__( 'Upload a M4V video.', 'socialize' ),
				'mode'      => false,
				'default' => '',
			),

			array(
				'id'        => 'video_mp4_url',
				'type'      => 'media',
				'title'     => esc_html__( 'MP4 Video', 'socialize' ),
				'desc'      => esc_html__( 'Upload a MP4 video.', 'socialize' ),
				'mode'      => false,
				'default' => '',
			),

			array(
				'id'        => 'video_webm_url',
				'type'      => 'media',
				'title'     => esc_html__( 'WebM Video', 'socialize' ),
				'desc'      => esc_html__( 'Upload a WebM video.', 'socialize' ),
				'mode'      => false,
				'default' => '',
			),
			
			array(
				'id'        => 'video_ogv_url',
				'type'      => 'media',
				'title'     => esc_html__( 'OGV Video', 'socialize' ),
				'desc'      => esc_html__( 'Upload a OGV video.', 'socialize' ),
				'mode'      => false,
				'default' => '',
			),

			array(
				'id'       => 'video_description',
				'type' => 'textarea',
				'title'    => esc_html__( 'Video Description', 'socialize' ),
				'desc'     => esc_html__( 'A description which is added next to your video.', 'socialize' ),
			),
					
		),
	);	
    $metaboxes[] = array(
        'id' => 'video-format-options',
        'title' => esc_html__( 'Video Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( 'video' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $video_format_options,
    ); 
       
       	
    // Main Post Options
	$post_options = array();
    $post_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),		
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(

			array( 
				'id' => 'post_page_header',
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
				'id' => 'post_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',		
				'mode'      => false,
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'post_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),	
								
			array(
				'id' => 'post_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'post_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
			
			array( 
				'id' => 'post_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
						        
			array( 
				'id' => 'post_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
									
			array( 
				'id' => 'post_subtitle',
				'title' => esc_html__( 'Post Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
				'default' => '',
			),
		
			array( 
				'id' => 'post_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'post_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'post_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),		

		),
	);

	$post_options[] = array(
		'title' => esc_html__( 'Image', 'socialize' ),
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-picture',
		'fields' => array(  
	
			array(  
				'id' => 'post_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'type' => 'button_set',
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'post_image',
				'type' => 'dimensions',
				'units' => false,
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image.', 'socialize' ),
				'default'           => array(
					'width'     => '', 
					'height'    => '',
				),
			),

			array(
				'id' => 'post_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'post_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'Choose how the image aligns with the content.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'default',
			),
		
		),
	);	
    $metaboxes[] = array(
        'id' => 'post-options',
        'title' => esc_html__( 'Post Options', 'socialize' ),
        'post_types' => array( 'post' ),
        'post_format' => array( '0', 'audio', 'gallery', 'quote', 'video' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $post_options
    ); 
        
 
	/*--------------------------------------------------------------
	Page Options
	--------------------------------------------------------------*/	

	$page_options = array();
    $page_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),		
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(

			array( 
				'id' => 'page_page_header',
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
				'id' => 'page_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',		
				'mode'      => false,
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
				
			array(
				'id' => 'page_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
								
			array(
				'id' => 'page_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'page_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'page_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
						        
			array( 
				'id' => 'page_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
									
			array( 
				'id' => 'page_subtitle',
				'title' => esc_html__( 'Page Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
				'default' => '',
			),
											
			array( 
				'id' => 'page_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'page_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'page_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),
						
		),		
	);	

    $page_options[] = array(
		'title' => esc_html__( 'Image', 'socialize' ),
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-picture',
		'fields' => array(
		
			array(  
				'id' => 'page_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Shows the featured image on the page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'page_image',
				'type' => 'dimensions',
				'units' => false,
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image.', 'socialize' ),
				'default'           => array(
					'width'     => '', 
					'height'    => '',
				),
			),

			array(
				'id' => 'page_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'page_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'Choose how the image aligns with the content.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'default',
			),	
			
		),
	);	
    $metaboxes[] = array(
        'id' => 'page-options',
        'title' => esc_html__( 'Page Options', 'socialize' ),
        'post_types' => array( 'page' ),
        'page_template' => array( 'default' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $page_options,
    ); 


	/*--------------------------------------------------------------
	Blog Page Template Options
	--------------------------------------------------------------*/	

    $blog_template_options = array();
    $blog_template_options[] = array(
		'title' => esc_html__( 'Blog', 'socialize' ),
		'icon' => 'el-icon-folder',
		'fields' => array(
			        
			array(
				'id'       => 'blog_template_cats',
				'type'     => 'select',
				'multi' => true,
				'title'    => esc_html__( 'Categories', 'socialize' ),
				'data' => 'terms',
				'args' => array( 'taxonomies' => 'category', 'hide_empty' => false ),
				'desc' => esc_html__( 'Select the categories you want to display.', 'socialize' ),
				'default' => '',
			),
			
			array( 
				'id' => 'blog_template_post_types',
				'title' => esc_html__( 'Post Types', 'socialize' ),
				'desc' => esc_html__( 'Select the post types you want to display.', 'socialize' ),
				'type' => 'select',
				'multi' => true,				
				'options' => array(
					'post' => esc_html__( 'Post', 'socialize' ),
					'page' => esc_html__( 'Page', 'socialize' ),
				),
				'default' => array( 'post' ),
			),
													
			array( 
				'id' => 'blog_template_format',
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
				'id' => 'blog_template_orderby',
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
				'id' => 'blog_template_date_posted',
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
				'id' => 'blog_template_date_modified',
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
				'id' => 'blog_template_filter',
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
				'id'        => 'blog_template_filter_options',
				'type'      => 'checkbox',
				'required'  => array( 'blog_template_filter', '=', 'enabled' ),
				'title'     => esc_html__( 'Filter Options', 'socialize' ),
				'desc' => esc_html__( 'Choose what options to display in the dropdown filter menu.', 'socialize' ), 
				'options'   => array(
					'cats' => esc_html__( 'Categories', 'socialize' ),
					'date' => esc_html__( 'Date', 'socialize' ),
					'title' => esc_html__( 'Title', 'socialize' ),
					'comment_count' => esc_html__( 'Comment Count', 'socialize' ),
					'views' => esc_html__( 'Views', 'socialize' ),
					'date_posted' => esc_html__( 'Date Posted', 'socialize' ),
					'date_modified' => esc_html__( 'Date Modified', 'socialize' ),
				),
				'default'   => array(
					'cats' => 0,
					'date' => '1',
					'title' => '1',
					'comment_count' => '1',
					'views' => '1',
					'date_posted' => '1',
					'date_modified' => '0',
				)
			),

			array(
				'id'       => 'blog_template_filter_cats_id',
				'type'     => 'select',
				'required'  => array( 'blog_template_filter', '=', 'enabled' ),
				'title'    => esc_html__( 'Filter Category', 'socialize' ),
				'data' => 'terms',
				'args' => array( 'taxonomies' => 'category', 'hide_empty' => false ),
				'desc' => esc_html__( 'Select the category you want to filter by, leave blank to display all categories.', 'socialize' ),
				'subtitle' => esc_html__( 'The sub categories of this category will also be displayed.', 'socialize' ),
				'default' => '',
			),
			                    										
			array(
				'id'       => 'blog_template_per_page',
				'type'     => 'spinner',
				'title'    => esc_html__( 'Items Per Page', 'socialize' ),
				'desc' => esc_html__( 'The number of items on each page.', 'socialize' ),
				'min' => 1,
				'max' => 999999,
				'default' => 12,
			),
												
			array( 
				'id' => 'blog_template_content_display',
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
				'id' => 'blog_template_excerpt_length',
				'title' => esc_html__( 'Excerpt Length', 'socialize' ),
				'required'  => array( 'blog_template_content_display', '=', 'excerpt' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of characters in excerpts.', 'socialize' ),
				'min' => 0,
				'max' => 999999,
				'default' => '800',
			),

			array(
				'id'        => 'blog_template_meta',
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
				),
			),
									
			array(  
				'id' => 'blog_template_read_more_link',
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
	);
	
    $blog_template_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(
		
			array( 
				'id' => 'blog_template_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'Display the title header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-fullwidth-page-header',
			),
										
			array(
				'id'        => 'blog_template_page_header_bg',
				'type'      => 'media',
				'mode'      => false,	
				'required' => array( 'blog_template_page_header', '!=', 'gp-standard-page-header' ),
				'title'     => esc_html__( 'Page Header Image Background', 'socialize' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),

			array(
				'id' => 'blog_template_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',	
				'required' => array( 'blog_template_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
						
			array(
				'id' => 'blog_template_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),	
				'required' => array( 'blog_template_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'blog_template_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),
				'required' => array( 'blog_template_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'blog_template_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
						
			array( 
				'id' => 'blog_template_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'blog_template_subtitle',
				'title' => esc_html__( 'Page Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
				'default' => '',
			),
					
			array( 
				'id' => 'blog_template_layout',
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
				'id'      => 'blog_template_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'blog_template_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
		
		),			
	);		
	
    $blog_template_options[] = array(
		'title' => esc_html__( 'Image', 'socialize' ),
		'icon' => 'el-icon-picture',
		'fields' => array(	
			
			array(  
				'id' => 'blog_template_featured_image',
				'title' => esc_html__( 'Featured Image', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the featured images..', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),

			array(
				'id' => 'blog_template_image',
				'type' => 'dimensions',
				'required'  => array( 'blog_template_featured_image', '!=', 'disabled' ),
				'units' => false,
				'title' => esc_html__( 'Image Dimensions', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured images.', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'default' => array(
					'width'     => 1050, 
					'height'    => 600,
				),
			),

			array(
				'id' => 'blog_template_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'required'  => array( 'blog_template_featured_image', '!=', 'disabled' ),
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => true,
			),

			array(
				'id' => 'blog_template_image_alignment',
				'title' => esc_html__( 'Image Alignment', 'socialize' ),
				'type' => 'select',
				'required'  => array( 'blog_template_featured_image', '!=', 'disabled' ),
				'desc' => esc_html__( 'Choose how the image aligns with the content.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-image-wrap-left' => esc_html__( 'Left Wrap', 'socialize' ),
					'gp-image-wrap-right' => esc_html__( 'Right Wrap', 'socialize' ),
					'gp-image-above' => esc_html__( 'Above Content', 'socialize' ),
					'gp-image-align-left' => esc_html__( 'Left Align', 'socialize' ),
					'gp-image-align-right' => esc_html__( 'Right Align', 'socialize' ),
				),
				'default' => 'gp-image-above',
			),

		),		
	);
    $metaboxes[] = array(
        'id' => 'blog-template-options',
        'title' => esc_html__( 'Blog Options', 'socialize' ),
        'post_types' => array( 'page' ),
        'page_template' => array( 'blog-template.php' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $blog_template_options,
    );
    

	/*--------------------------------------------------------------
	Custom Homepage Options
	--------------------------------------------------------------*/	

	$homepage_options = array();
    $homepage_options[] = array(
		'fields' => array(

			array( 
				'id' => 'homepage_page_header',
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
				'id' => 'homepage_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',		
				'mode'      => false,	
				'required' => array( 'homepage_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
				
			array(
				'id' => 'homepage_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',	
				'required' => array( 'page_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
								
			array(
				'id' => 'homepage_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),	
				'required' => array( 'homepage_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'homepage_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),	
				'required' => array( 'homepage_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'homepage_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'disabled',
			),
						        
			array( 
				'id' => 'homepage_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
									
			array( 
				'id' => 'homepage_subtitle',
				'title' => esc_html__( 'Page Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
				'default' => '',
			),
											
			array( 
				'id' => 'homepage_layout',
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
				'default' => 'gp-both-sidebars',
			),
			
			array(
				'id'      => 'homepage_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-homepage-left-sidebar',
			),

			array(
				'id'      => 'homepage_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-homepage-right-sidebar',
			),
			
			array(
				'id'       => 'homepage_content_header',
				'type'     => 'editor',
				'title'    => esc_html__( 'Content Header', 'socialize' ),
				'desc' => esc_html__( 'Add content directly above the page content and sidebar.', 'socialize' ),
				'default' => '[slider cats="" format="gp-slider-two-cols" per_page="3" slider_speed="0"]',
			),

			array(
				'id' => 'homepage_content_header_format',
				'title' => esc_html__( 'Content Header Format', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Choose whether the content area stretches across the entire page.', 'socialize' ),
				'options' => array(
					'fixed' => esc_html__( 'Fixed', 'socialize' ),
					'fullwidth' => esc_html__( 'Full Width', 'socialize' ),
				),
				'default' => 'fixed',
			),
						
		),		
	);	
	
    $metaboxes[] = array(
        'id' => 'homepage-options',
        'title' => esc_html__( 'Homepage Options', 'socialize' ),
        'post_types' => array( 'page' ),
        'page_template' => array( 'homepage-template.php' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $homepage_options,
    ); 
    
        	
	/*--------------------------------------------------------------
	Portfolio Page Template Options
	--------------------------------------------------------------*/	

    $portfolio_template_options = array();
    $portfolio_template_options[] = array(
		'title' => esc_html__( 'Portfolio', 'socialize' ),
		'icon' => 'el-icon-photo-alt',
		'fields' => array(	
			        
			array(
				'id'       => 'portfolio_template_cats',
				'type'     => 'select',
				'multi' => true,
				'title'    => esc_html__( 'Portfolio Categories', 'socialize' ),
				'data' => 'terms',
				'args' => array( 'taxonomies' => 'gp_portfolios', 'hide_empty' => false ),
				'desc' => esc_html__( 'Select the portfolio categories you want to display.', 'socialize' ),
				'default' => '',
			),	
	
			array( 
				'id' => 'portfolio_template_format',
				'title' => esc_html__( 'Format', 'socialize' ),					
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
				'id' => 'portfolio_template_orderby',
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
				'id' => 'portfolio_template_date_posted',
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
				'id' => 'portfolio_template_date_modified',
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
				'id' => 'portfolio_template_filter',
				'title' => esc_html__( 'Portfolio Filter', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Add category filter links to the page.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),					

			array( 
				'id' => 'portfolio_template_per_page',
				'title' => esc_html__( 'Items Per Page', 'socialize' ),
				'type' => 'spinner',
				'desc' => esc_html__( 'The number of items on each page.', 'socialize' ),
				'min' => 0,
				'max' => 999999,
				'default' => 12,
			),
				
		)
	);
	
    $portfolio_template_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),	
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),	
		'icon' => 'el-icon-cogs',
		'fields' => array(
				
			array( 
				'id' => 'portfolio_template_page_header',
				'title' => esc_html__( 'Page Header', 'socialize' ),
				'type' => 'select',
				'desc' => esc_html__( 'Display the title header on the page.', 'socialize' ),
				'options' => array(
					'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ),
					'gp-large-page-header' => esc_html__( 'Large', 'socialize' ),
					'gp-fullwidth-page-header' => esc_html__( 'Full Width', 'socialize' ),
					'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
				),
				'default' => 'gp-standard-page-header',
			),
							
			array(
				'id' => 'portfolio_template_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),	
				'required' => array( 'portfolio_template_page_header', '!=', 'gp-standard-page-header' ),
				'type'      => 'media',	
				'mode'      => false,		
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'portfolio_template_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',	
				'required' => array( 'portfolio_template_page_header', '!=', 'gp-standard-page-header' ),
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'portfolio_template_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),	
				'required' => array( 'portfolio_template_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'portfolio_template_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),	
				'required' => array( 'portfolio_template_page_header', '!=', 'gp-standard-page-header' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'portfolio_template_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
						
			array( 
				'id' => 'portfolio_template_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'portfolio_template_subtitle',
				'title' => esc_html__( 'Page Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
				'default' => '',
			),
											
			array( 
				'id' => 'portfolio_template_layout',
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
				'id'      => 'portfolio_template_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-left-sidebar',
			),

			array(
				'id'      => 'portfolio_template_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars',
				'default' => 'gp-right-sidebar',
			),
			 
		),	
	);
    $metaboxes[] = array(
        'id' => 'portfolio-template-options',
        'title' => esc_html__( 'Portfolio Options', 'socialize' ),
        'post_types' => array( 'page' ),
        'page_template' => array( 'portfolio-template.php' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $portfolio_template_options,
    );
    
    
	/*--------------------------------------------------------------
	Link Page Template Options
	--------------------------------------------------------------*/	

    $link_template_options = array();
    $link_template_options[] = array(
        'fields' => array(
        
			array( 
				'id' => 'link_template_link',
				'title' => esc_html__( 'Link', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'The link which your page goes to.', 'socialize' ),
				'default' => '',
				'validate' => 'url',
			),

			array( 
				'id' => 'link_template_link_target',
				'title' => esc_html__( 'Link Target', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The target for the link.', 'socialize' ),
				'options' => array(
					'_blank' => esc_html__( 'New Window', 'socialize' ),
					'_self' => esc_html__( 'Same Window', 'socialize' ),
				),
				'default' => '_self',
			),
															 
		),
	);	
    $metaboxes[] = array(
        'id' => 'link-options',
        'title' => esc_html__( 'Link Options', 'socialize' ),
        'post_types' => array( 'page' ),
        'page_template' => array( 'link-template.php' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $link_template_options,
    );
  
               
	/*--------------------------------------------------------------
	Portfolio Item Options
	--------------------------------------------------------------*/	

    $portfolio_item_options = array();
    $portfolio_item_options[] = array(
		'title' => esc_html__( 'Portfolio', 'socialize' ),	
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),	
		'icon' => 'el-icon-photo-alt',
        'fields' => array(

			array(
				'id'        => 'portfolio_item_type',
				'type'      => 'radio',
				'title'     => esc_html__( 'Image/Slider Type', 'socialize' ),
				'desc' => esc_html__( 'The type of image or slider on the page.', 'socialize' ),
				'options'   => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-left-image' => 'Left Featured Image',
					'gp-fullwidth-image' => 'Full Width Featured Image',
					'gp-left-slider' => 'Left Slider',
					'gp-fullwidth-slider' => 'Full Width Slider',
					'none' => 'None',
				), 
				'default'   => 'default',
			),   

			array(
				'id'        => 'portfolio_item_gallery_slider',
				'type'      => 'gallery',
				'title'     => esc_html__( 'Gallery Slider', 'socialize' ),
				'subtitle'  => esc_html__( 'Create a new gallery by selecting an existing one or uploading new images using the WordPress native uploader.', 'socialize' ),
				'desc'  => esc_html__( 'Add a gallery slider.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'portfolio_item_image',
				'type' => 'dimensions',
				'units' => false,
				'title' => esc_html__( 'Image/Slider Dimensions', 'socialize' ),
				'subtitle' => esc_html__( 'Set height to 0 to have a proportionate height.', 'socialize' ),
				'desc' => esc_html__( 'The width and height of the featured image or slider.', 'socialize' ),
				'default'           => array(
					'width'     => '', 
					'height'    => '',
				),
			),
			
			array(
				'id' => 'portfolio_item_hard_crop',
				'title' => esc_html__( 'Hard Crop', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'default',
			),

			array(
				'id' => 'portfolio_item_image_size',
				'title' => esc_html__( 'Image Size', 'socialize' ),
				'subtitle' => esc_html__( 'Only for use with the Masonry portfolio type.', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Size of the image when displayed on a masonry portfolio page.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'gp-regular' => esc_html__( 'Regular', 'socialize' ),
					'gp-narrow' => esc_html__( 'Narrow', 'socialize' ),
					'gp-tall' => esc_html__( 'Tall', 'socialize' ),
				),
				'default' => 'default',
			),
		
			array( 	
				'id' => 'portfolio_item_link',
				'title' => esc_html__( 'Button Link', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'The link for the button.', 'socialize' ),
				'validate' => 'url',
				'default' => '',
			), 
								
			array( 	
				'id' => 'portfolio_item_link_text',
				'title' => esc_html__( 'Button Text', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'The text for the button.', 'socialize' ),
				'default' => '',
			), 

			array( 
				'id' => 'portfolio_item_link_target',
				'title' => esc_html__( 'Button Link Target', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The target for the button link.', 'socialize' ),
				'options' => array(
					'default' => esc_html__( 'Default', 'socialize' ),
					'_blank' => esc_html__( 'New Window', 'socialize' ),
					'_self' => esc_html__( 'Same Window', 'socialize' ),
				),
				'default' => 'default',
			),
			
		),
	);		
	
    $portfolio_item_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),	
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),	
		'icon' => 'el-icon-cogs',
        'fields' => array(
        
			array( 
				'id' => 'portfolio_item_page_header',
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
				'id' => 'portfolio_item_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',
				'mode'      => false,
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'portfolio_item_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id' => 'portfolio_item_page_header_teaser_video_bg', 
				'title' => esc_html__( 'Title Header Teaser Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports HTML5 video only. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',
				'desc' => esc_html__( 'Video URL to the teaser video that is displayed in the title header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'portfolio_item_page_header_full_video_bg', 
				'title' => esc_html__( 'Title Header Full Video Background', 'socialize' ),
				'subtitle' => esc_html__( 'Supports YouTube, Vimeo and HTML5 video. For multiple HTML5 formats, each video should have exactly the same filename but remove the extension (e.g. .mp4) from the filename in the text box.', 'socialize' ),
				'type'      => 'text',	
				'validate'  => 'url',	
				'desc' => esc_html__( 'Video URL to the full video that is displayed when the play button is clicked.', 'socialize' ),
				'default' => '',
			),
						
			array( 
				'id' => 'portfolio_item_title',
				'title' => esc_html__( 'Page Title', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'Display the page title.', 'socialize' ),
				'options' => array(
					'enabled' => esc_html__( 'Enabled', 'socialize' ),
					'disabled' => esc_html__( 'Disabled', 'socialize' ),
				),
				'default' => 'enabled',
			),
						 
			array( 
				'id' => 'portfolio_item_custom_title',
				'title' => esc_html__( 'Custom Title', 'socialize' ),
				'type' => 'text',
				'desc' => esc_html__( 'A custom title that overwrites the default title.', 'socialize' ),
				'default' => '',
			),
			
			array( 
				'id' => 'portfolio_item_subtitle',
				'title' => esc_html__( 'Page Subtitle', 'socialize' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Add a subtitle below the title header.', 'socialize' ),
			),
											
			array( 
				'id' => 'portfolio_item_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'portfolio_item_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'portfolio_item_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),
					 
		),
	);
    $metaboxes[] = array(
        'id' => 'portfolio-item-options',
        'title' => esc_html__( 'Portfolio Item Options', 'socialize' ),
        'post_types' => array( 'gp_portfolio_item' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $portfolio_item_options,
    );
    
    
	/*--------------------------------------------------------------
	bbPress Options
	--------------------------------------------------------------*/	

	$bbpress_options = array();
    $bbpress_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),		
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(

			array( 
				'id' => 'bbpress_page_header',
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
				'id' => 'bbpress_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',		
				'mode'      => false,
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),	

			array(
				'id' => 'bbpress_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
		
			array( 
				'id' => 'bbpress_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'bbpress_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'bbpress_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),			

		),
	);
    $metaboxes[] = array(
        'id' => 'bbpress-options',
        'title' => esc_html__( 'bbPress Options', 'socialize' ),
        'post_types' => array( 'forum', 'topic' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $bbpress_options,
    );
    
	
	/*--------------------------------------------------------------
	Events Post Options
	--------------------------------------------------------------*/	

	$events_post_options = array();
    $events_post_options[] = array(
		'title' => esc_html__( 'General', 'socialize' ),		
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
		'icon' => 'el-icon-cogs',
		'fields' => array(

			array( 
				'id' => 'events_post_page_header',
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
				'id' => 'events_post_page_header_bg', 
				'title' => esc_html__( 'Page Header Image Background', 'socialize' ),
				'type'      => 'media',		
				'mode'      => false,
				'desc' => esc_html__( 'The background of the page header.', 'socialize' ),
				'default' => '',
			),	
			
			array(
				'id' => 'events_post_page_header_text', 
				'title' => esc_html__( 'Page Header Text', 'socialize' ),
				'type'      => 'text',
				'desc' => esc_html__( 'The text in the page header.', 'socialize' ),
				'default' => '',
			),
			
			array( 
				'id' => 'events_post_layout',
				'title' => esc_html__( 'Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'events_post_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'events_post_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),		

		),
	);
    $metaboxes[] = array(
        'id' => 'events-post_options',
        'title' => esc_html__( 'Events Post Options', 'socialize' ),
        'post_types' => array( 'tribe_events' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $events_post_options,
    );
    
    
	/*--------------------------------------------------------------
	Product Options
	--------------------------------------------------------------*/	

    $product_options = array();
    $product_options[] = array(
		'desc' => esc_html__( 'By default most of these options are set from the Theme Options page to change all pages at once, but you can overwrite these options here so this page has different settings.', 'socialize' ),
        'fields' => array( 
		
			array( 
				'id' => 'product_layout',
				'title' => esc_html__( 'Product Page Layout', 'socialize' ),					
				'type' => 'image_select',
				'desc' => esc_html__( 'The layout of the page.', 'socialize' ),
				'options' => array(
					'default' => array( 'title' => esc_html__( 'Default', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/default.png' ),
					'gp-left-sidebar' => array('title' => esc_html__( 'Left Sidebar', 'socialize' ),   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
					'gp-right-sidebar' => array('title' => esc_html__( 'Right Sidebar', 'socialize' ),  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
					'gp-both-sidebars' => array( 'title' => esc_html__( 'Both Sidebars', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/both-sidebars.png' ),
					'gp-no-sidebar' => array('title' => esc_html__( 'No Sidebar', 'socialize' ), 'img' => get_template_directory_uri() . '/lib/framework/images/no-sidebar.png'),
					'gp-fullwidth' => array('title' => esc_html__( 'Full Width', 'socialize' ), 'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
				),	
				'default' => 'default',
			),
			
			array(
				'id'      => 'product_left_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Left Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),

			array(
				'id'      => 'product_right_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Right Sidebar', 'socialize' ),
				'desc' => esc_html__( 'The sidebar to display.', 'socialize' ),
				'data'    => 'custom_sidebars_default',
				'default' => 'default',
			),
					 
		),
	);
    $metaboxes[] = array(
        'id' => 'product-options',
        'title' => esc_html__( 'Product Options', 'socialize' ),
        'post_types' => array( 'product' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $product_options,
    );
    
    
	/*--------------------------------------------------------------
	Slide Options
	--------------------------------------------------------------*/	

    $slide_options = array();
    $slide_options[] = array(
        'fields' => array( 

			array(
				'id'       => 'slide_caption_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Caption Title', 'socialize' ),
				'desc' => esc_html__( 'The caption title for the slide.', 'socialize' ),
				'default' => '',
			),
			
			array(
				'id'       => 'slide_caption_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Caption Text', 'socialize' ),
				'desc' => esc_html__( 'The caption text for the slide.', 'socialize' ),
				'default' => '',
			),	
					
			array(
				'id'       => 'slide_link',
				'type'     => 'text',
				'title'    => esc_html__( 'Link', 'socialize' ),
				'desc'     => esc_html__( 'The link which your post goes to.', 'socialize' ),
				'validate' => 'url',
				'default' => '',
			),
			
			array( 
				'id' => 'slide_link_target',
				'title' => esc_html__( 'Link Target', 'socialize' ),
				'type' => 'button_set',
				'desc' => esc_html__( 'The target for the link.', 'socialize' ),
				'options' => array(
					'_self' => esc_html__( 'Same Window', 'socialize' ),
					'_blank' => esc_html__( 'New Window', 'socialize' ),
				),
				'default' => '_self',
			),			
					 
		),		
	);
    $metaboxes[] = array(
        'id' => 'slide-options',
        'title' => esc_html__( 'Slide Options', 'socialize' ),
        'post_types' => array( 'gp_slide' ),
        'position' => 'normal',
        'priority' => 'high',
        'sections' => $slide_options,
    );
        
    // Kind of overkill, but ahh well.  ;)
    $metaboxes = apply_filters( 'gp_redux_metabox_options', $metaboxes, $post_options, $audio_format_options, $gallery_format_options, $link_format_options, $quote_format_options, $video_format_options, $blog_template_options, $homepage_options, $portfolio_template_options, $link_template_options, $portfolio_item_options, $bbpress_options, $events_post_options, $product_options, $slide_options );

    return $metaboxes;
  }
  add_action( 'redux/metaboxes/'.$redux_opt_name.'/boxes', 'ghostpool_add_metaboxes' );
endif;

// The loader will load all of the extensions automatically based on your $redux_opt_name
require_once( dirname(__FILE__). '/loader.php' );