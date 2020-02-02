<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'themify-common','ultra-header','theme-style','themify-media-queries','themify-woocommerce','themify-skin','ultra-header','themify-font-icons-css','magnific' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 21 );

// END ENQUEUE PARENT ACTION
