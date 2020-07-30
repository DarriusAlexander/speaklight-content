<?php

/* /
  Plugin Name: Galaxy Funder
  Plugin URI:
  Description:  Galaxy Funder is a WooCommerce Crowdfunding System.
  Version: 9.6
  Author: Fantastic Plugins
  Author URI:
  / */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('CrowdFunding')) {

    final class CrowdFunding {

        protected static $_instance = null;

        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            $this->cf_do_output_buffer();
            if ($this->check_woocommerce_is_active()) {
                return false;
            }
            $this->cf_translate_file();
            $this->cf_include_files();
        }

        public static function check_woocommerce_is_active() {
            if (is_multisite()) {
                // This Condition is for Multi Site WooCommerce Installation
                if (!is_plugin_active_for_network('woocommerce/woocommerce.php') && (!is_plugin_active('woocommerce/woocommerce.php'))) {
                    if (is_admin()) {
                        $variable = "<div class='error'><p> Galaxy Funder will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                        echo $variable;
                    }
                    return true;
                }
            } else {
                // This Condition is for Single Site WooCommerce Installation
                if (!is_plugin_active('woocommerce/woocommerce.php')) {
                    if (is_admin()) {
                        $variable = "<div class='error'><p> Galaxy Funder will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                        echo $variable;
                    }
                    return true;
                }
            }
        }

        public static function cf_do_output_buffer() {
            ob_start();
        }

        public static function cf_translate_file() {
            load_plugin_textdomain('galaxyfunder', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        public static function cf_include_files() {
            //Enqueue Scripts
            include_once 'inc/class-enqueue-scripts.php';
            //Common Fundtions
            include_once 'inc/fp-gf-common-functions.php';
//            //Shop Related Functions
            include_once 'inc/class-shop-related-functions.php';
//            //Shop Related Functions
            include_once 'inc/class-shortcode-pages.php';
//            //order Related Functions
            include_once 'inc/class-order-related-functions.php';
//            //Mail Related Functions
            include_once 'inc/emails/class-mail-related-functions.php';
//            //Contribution mail
            include_once 'inc/emails/class-contribution-order-email.php';
//            //Perk related functions
            include_once 'inc/class-perk-related-functions.php';
              //completion mail
            include_once 'inc/emails/class-completion-campaign-email.php';
            
            //Frontend Form
            include_once 'inc/entry_forms/class-frontend-form.php';
//            
            /* Crowdfunding settings include file */
            include_once 'inc/admin/class-crowdfunding-submenu.php';
            
            if (!is_admin() || defined('DOING_AJAX')) {
                
                //Cart and checkout Related Functions
                include_once 'inc/class-cart-related-functions.php';
                //Single product page other Functions
                include_once 'inc/class-sigle-product-other-actions.php';
                //Frontend perk Related Functions
                include_once 'inc/class-frontend-perkselectionbox.php';
                //Order Related Functions
                include_once 'inc/class-order-related-functions_frontend.php';
                //Extension Form
                include_once 'inc/class-extension-form.php';
            }
            if (is_admin()) {
                /* Crowdfunding settings include file */
                include_once 'inc/admin/class-crowdfunding-submenu.php';
                //product level entries
                include_once 'inc/entry_forms/class-product-level-entry-from.php';
                //Adding Perk Meta box
                include_once 'inc/entry_forms/class-perk-meta-box.php';
            }
        }
    }
}
CrowdFunding::instance();
