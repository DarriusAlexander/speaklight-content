<?php

/**
 * Galaxy funder enqueue scripts
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Enqueuescripts')) {


    class FP_GF_Enqueuescripts {

        public static function init() {
            // wp and admin enqueue scripts
            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts_to_plugin_frontend'));
            add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts_to_plugin_backend'));
            add_action('wp_enqueue_scripts', array(__CLASS__, 'galaxy_funder_enqueues_both_ends'), 999);
            add_action('admin_enqueue_scripts', array(__CLASS__, 'galaxy_funder_enqueues_both_ends'), 999);
        }

        public static function enqueue_scripts_to_plugin_frontend() {
            global $woocommerce;
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-form');



            wp_register_script('galaxyfunder_jquery_validation', plugins_url('gf/assets/js/jquery.validate.js'));
            wp_enqueue_script('galaxyfunder_jquery_validation');


            if ((float) $woocommerce->version >= (float) ('3.0.0')) {

                $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

                wp_register_script('wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array('jquery', 'select2'), WC_VERSION);
                wp_localize_script('wc-enhanced-select', 'wc_enhanced_select_params', array(
                    'i18n_no_matches' => _x('No matches found', 'enhanced select', 'woocommerce'),
                    'i18n_ajax_error' => _x('Loading failed', 'enhanced select', 'woocommerce'),
                    'i18n_input_too_short_1' => _x('Please enter 1 or more characters', 'enhanced select', 'woocommerce'),
                    'i18n_input_too_short_n' => _x('Please enter %qty% or more characters', 'enhanced select', 'woocommerce'),
                    'i18n_input_too_long_1' => _x('Please delete 1 character', 'enhanced select', 'woocommerce'),
                    'i18n_input_too_long_n' => _x('Please delete %qty% characters', 'enhanced select', 'woocommerce'),
                    'i18n_selection_too_long_1' => _x('You can only select 1 item', 'enhanced select', 'woocommerce'),
                    'i18n_selection_too_long_n' => _x('You can only select %qty% items', 'enhanced select', 'woocommerce'),
                    'i18n_load_more' => _x('Loading more results&hellip;', 'enhanced select', 'woocommerce'),
                    'i18n_searching' => _x('Searching&hellip;', 'enhanced select', 'woocommerce'),
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'search_products_nonce' => wp_create_nonce('search-products'),
                    'search_customers_nonce' => wp_create_nonce('search-customers'),
                ));
                wp_enqueue_script('wc-enhanced-select');
            }
            //Form validation js files end
        }

        public static function enqueue_scripts_to_plugin_backend() {
            //js color related js files start
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'crowdfunding_callback') {
                    wp_enqueue_script('jscolor', plugins_url('gf/assets/jscolor/jscolor.js'));
                }
            }
            //js color related js files end
        }

        public static function galaxy_funder_enqueues_both_ends() {
            global $woocommerce;
            //Date picker related js files start
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
            wp_register_script('cfdatepickerscript', plugins_url('gf/assets/js/datepicker.js'));
            wp_enqueue_script('cfdatepickerscript');
            //Date picker related js files end
            //Footable related js files start
            wp_register_script('galaxyfunder_footable', plugins_url('gf/assets/js/footable.js'));
            wp_register_script('galaxyfunder_footable_sort', plugins_url('gf/assets/js/footable.sort.js'));
            wp_register_script('galaxyfunder_footable_paging', plugins_url('gf/assets/js/footable.paginate.js'));
            wp_register_script('galaxyfunder_footable_filter', plugins_url('gf/assets/js/footable.filter.js'));
            wp_register_style('galaxyfunder_footable_css', plugins_url('gf/assets/css/footable.core.css'));

            wp_enqueue_script('galaxyfunder_footable');
            wp_enqueue_script('galaxyfunder_footable_sort');
            wp_enqueue_script('galaxyfunder_footable_paging');
            wp_enqueue_script('galaxyfunder_footable_filter');
            wp_enqueue_style('galaxyfunder_footable_css');
            //Footable related js files end
            //
            //Bootstrap related css files start
            wp_register_style('galaxyfunder_bootstrap_css', plugins_url('gf/assets/css/bootstrap.css'));
            wp_enqueue_style('galaxyfunder_bootstrap_css');
            //Bootstrap related css files end
            //Custom scripts start
            wp_register_style('galaxy_funder_enqueue_styles', plugins_url('gf/assets/css/mystyle.css'));
            wp_enqueue_script('cfcustomscript', plugins_url('gf/assets/js/customscript.js'));
            wp_enqueue_style('galaxy_funder_enqueue_styles');
            //Custom scripts end
            //Chosen related js files start
            wp_register_script('galaxyfunder_chosen_enqueue', plugins_url('gf/assets/js/chosen.jquery.js'));
            wp_register_style('galaxyfunder_chosen_style_enqueue', plugins_url('gf/assets/css/chosen.css'));

//            //Chosen related js files end
            if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                wp_enqueue_script('galaxyfunder_chosen_enqueue');
                wp_enqueue_style('galaxyfunder_chosen_style_enqueue');
            } else {
                $theme = wp_get_theme();
                if (!get_option('gf_enqueue_select2_lib_from_plugin') == '2') {
                    wp_deregister_style('select2');
                    wp_deregister_script('select2');
                    wp_dequeue_style('select2-css');
                    wp_dequeue_script('select2-js');

                    $assets_path = str_replace(array('http:', 'https:'), '', WC()->plugin_url()) . '/assets/';
                    wp_enqueue_script('select2', $assets_path . 'js/select2/select2.js');
                    wp_enqueue_style('select2', $assets_path . 'css/select2.css');
                }
            }
        }

    }

    FP_GF_Enqueuescripts::init();
}