<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('CFGeneraltab')) {
final class CFGeneraltab {

    public static function init() {

        /**
         * Hook is woocommerce_settings_tabs_array to register the settings tab in admin settings
         */
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_tab'), 100);

        /**
         * Settings Tab for CrowdFunding Registering Admin Settings
         */
        add_action('woocommerce_cf_settings_tabs_crowdfunding', array(__CLASS__, 'crowdfunding_register_admin_settings'));
        /**
         * WooCommerce Update Options for Crowdfunding
         */
        add_action('woocommerce_update_options_crowdfunding', array(__CLASS__, 'crowdfunding_update_settings'));
        /**
         * Init the Default Settings on Page Load for Custom Field in Admin Settings
         */
        add_action('init', array(__CLASS__, 'crowdfunding_default_settings'));

        /**
         * Hook is reset admin fields
         */
        add_action('admin_init', array(__CLASS__, 'reset_changes_crowdfunding'));
    }

    /**
     * Crowdfunding Register Admin Settings Tab
     */
    public static function crowdfunding_admin_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding'] = __('General', 'galaxyfunder');
        return $settings_tabs;
    }

    /**
     * Crowdfunding Add Custom Field to the CrowdFunding Admin Settings
     */
    public static function crowdfunding_admin_fields() {
        global $woocommerce;
        global $wp_roles;
        $all_roles = $wp_roles->role_names;
        foreach ($wp_roles->role_names as $key => $roles) {
            $keys[] = $key;
        }
        if (function_exists('wc_get_order_statuses')) {
            $get_status = wc_get_order_statuses();
            $string_rplaced_keys = str_replace('wc-', '', array_keys(wc_get_order_statuses()));
            $array_values = array_values($get_status);
            $combined_array = array_combine($string_rplaced_keys, $array_values);
        } else {
            $taxanomy = 'shop_order_status';
            $orderstatus = '';
            $orderslugs = '';
            $term_args = array(
                'hide_empty' => false,
                'orderby' => 'date',
            );
            $tax_terms = get_terms($taxanomy, $term_args);
            foreach ($tax_terms as $getterms) {
                $orderstatus[] = @$getterms->name;
                $orderslugs[] = @$getterms->slug;
            }
            $array_values = array_combine((array) $orderslugs, (array) $orderstatus);
            $combined_array = array_combine((array) $orderslugs, (array) $orderstatus);
        }
        return apply_filters('woocommerce_crowdfunding_settings', array(
            array(
                'name' => __('Add to Cart Button Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_add_to_cart_button'
            ),
            array(
                'name' => __('Add to Cart Button Label', 'galaxyfunder'),
                'desc' => __('Please Enter Add to cart Button Label to show', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_add_to_cart_label',
                'css' => 'min-width:550px;
',
                'std' => 'Contribute',
                'type' => 'text',
                'newids' => 'cf_add_to_cart_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redirect after Contribution', 'galaxyfunder'),
                'desc' => __('Please Select the place you want redirect after Contribution', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_add_to_cart_redirection',
                'css' => '',
                'std' => '3',
                'type' => 'radio',
                'options' => array(
                    '1' => __('Cart Page', 'galaxyfunder'),
                    '2' => __('Checkout Page', 'galaxyfunder'),
                    '3' => __('None', 'galaxyfunder'),),
                'newids' => 'cf_add_to_cart_redirection',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_add_to_cart_button'),
            array(
                'name' => __('Campaign Out of Stock Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_out_of_stock'
            ),
            array(
                'name' => __('Out of Stock Label', 'galaxyfunder'),
                'desc' => __('Please Enter Out of Stock Label', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_outofstock_label',
                'css' => 'min-width:550px;margin-bottom:40px;',
                'std' => 'Campaign Closed',
                'type' => 'text',
                'newids' => 'cf_outofstock_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_out_of_stock'),
            array(
                'name' => __('Display Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_display_settings'
            ),
            array(
                'name' => __("Select roles which can enable front end campaign submission form", "galaxyfunder"),
                'desc' => __('Select roles which can able to submit front end campaign submission form', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_campaign_submission_frontend_exclude_role_control',
                'css' => 'min-width:150px;',
                'std' => $keys,
                'type' => 'multiselect',
                'options' => $all_roles,
                'newids' => 'cf_campaign_submission_frontend_exclude_role_control',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show Campaigns in Shop Page', 'galaxyfunder'),
                'desc' => __('This helps to Show/Hide the Campaigns in Shop Page', 'galaxyfunder'),
                'id' => 'cf_campaign_in_shop_page',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_campaign_in_shop_page',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Hide Closed Campaigns', 'galaxyfunder'),
                'desc' => __('If set to hide the closed campaigns will be hidden in shop and category pages ', 'galaxyfunder'),
                'id' => 'cf_hide_closed_campaigns',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'css' => 'min-width:150px;',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'newids' => 'cf_hide_closed_campaigns',
            ),
            array(
                'name' => __('Raise Contributions when order status becomes', 'galaxyfunder'),
                'desc' => __('Please Select the Order status to Raise Contributions', 'galaxyfunder'),
                'id' => 'cf_add_contribution',
                'css' => 'min-width:150px;',
                'std' => array('completed'),
                'type' => 'select',
                'options' => $combined_array,
                'newids' => 'cf_add_contribution',
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_display_settings'),
            array(
                'name' => __('Restriction for Cart', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_restriction_for_cart'
            ),
            array(
                'name' => __('Don\'t Allow Other Products to Cart when Cart Contain CrowdFunding Campaign ', 'galaxyfunder'),
                'desc' => __('This helps to Stop Other Products added to Cart when CrowdFunding Campaign in Cart', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_other_products',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_campaign_restrict_other_products',
                'type' => 'select',
                'options' => array('1' => __('Enable', 'galaxyfunder'), '2' => __('Disable', 'galaxyfunder')),
            ),
            array(
                'name' => __('Cart Error Message', 'galaxyfunder'),
                'desc' => __('This helps to Show your Error Message when add some other products to cart', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Multiple Items are not Allowed when cart contain CrowdFunding Campaign Product',
                'default' => 'Multiple Items are not Allowed when cart contain CrowdFunding Campaign Product',
                'newids' => 'cf_campaign_restrict_error_message',
                'type' => 'textarea',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Woocommerce Coupon Field in Cart/Checkout page', 'galaxyfunder'),
                'desc' => __('This helps to Show/Hide Woocommerce Coupon Field when CrowdFunding Campaign in Cart/Checkout', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_coupon_field',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_campaign_restrict_coupon_field',
                'type' => 'select',
                'options' => array('1' => __('Show', 'galaxyfunder'), '2' => __('Hide', 'galaxyfunder')),
            ),
            array(
                'name' => __('When coupon is applied on a CrowdFunding Campaign update  the Campaign Goal with', 'galaxyfunder'),
                // 'desc' => __('', 'galaxyfunder'),
                // 'desc' => __('This helps to Show/Hide Woocommerce Coupon Field when CrowdFunding Campaign in Cart/Checkout', 'galaxyfunder'),
                'id' => 'cf_campaign_restrict_coupon_discount',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_campaign_restrict_coupon_discount',
                'type' => 'select',
                'options' => array('1' => __('Price after coupon discount', 'galaxyfunder'), '2' => __('User choosen amount', 'galaxyfunder')),
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_restriction_for_cart'),
            array(
                'name' => __('Checkout Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_checkout'
            ),
            array(
                'name' => __('Show/hide Mark as Anonymous Checkbox in Checkout page ', 'galaxyfunder'),
                'desc' => '',
                'id' => 'cf_show_hide_mark_anonymous_checkbox',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_show_hide_mark_anonymous_checkbox',
                'type' => 'select',
                'options' => array('1' => __('Show', 'galaxyfunder'), '2' => __('Hide', 'galaxyfunder')),
            ),
            array(
                'name' => __('Mark as Anonymous text', 'galaxyfunder'),
                'desc' => __('Please enter your custom caption', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_checkout_textbox',
                'css' => 'min-width:350px;margin-bottom:40px;',
                'std' => 'Mark as Anonymous ',
                'default' => 'Mark as Anonymous',
                'newids' => 'cf_checkout_textbox',
                'type' => 'text',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable "Galaxy Funder - PayPal Adaptive Split Payment" only on Checkout when Campaign is in Cart', 'galaxyfunder'),
                'desc' => '',
                'id' => 'cf_enable_paypalasp_when_campaign_is_in_cart',
                'css' => '',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_enable_paypalasp_when_campaign_is_in_cart',
                'type' => 'select',
                'options' => array('1' => __('Disable', 'galaxyfunder'), '2' => __('Enable', 'galaxyfunder')),
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_checkout'),
            array(
                'name' => __('Price Button Settings', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_button_settings'
            ),
            array(
                'name' => __('Button color ', 'galaxyfunder'),
                'desc' => __('Please enter the Button color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'FF8C00',
                'default' => 'FF8C00',
                'newids' => 'cf_button_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Button Text color ', 'galaxyfunder'),
                'desc' => __('Please enter the Button Text color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_text_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => '000000',
                'default' => '000000',
                'newids' => 'cf_button_text_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Button color ', 'galaxyfunder'),
                'desc' => __('Please enter the Selected Button color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_selected_button_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'f00',
                'default' => 'f00',
                'newids' => 'cf_selected_button_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Selected Button Text color ', 'galaxyfunder'),
                'desc' => __('Please enter the Selected Button Text color', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_selected_button_text_color',
                'css' => 'min-width:75px;margin-bottom:40px;',
                'std' => 'fff',
                'default' => 'fff',
                'newids' => 'cf_selected_button_text_color',
                'type' => 'text',
                'class' => 'color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Box Shadow', 'galaxyfunder'),
                'desc' => __('Box Shadow show or hide option', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_button_box_shadow',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('Show', 'galaxyfunder'), '2' => __('Hide', 'galaxyfunder')),
                'newids' => 'cf_button_box_shadow',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_button_settings'),
            array(
                'name' => __('Troubleshoot', 'galaxyfunder'),
                'type' => 'title',
                'id' => '_cf_campaign_troubleshoot'
            ),
            array(
                'name' => __('Load Template', 'galaxyfunder'),
                'desc' => __('Troubleshooting the Problem by change the Option to load Template Files from Various Places', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_load_woocommerce_template',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('From Plugin', 'galaxyfunder'), '2' => __('From Theme', 'galaxyfunder')),
                'newids' => 'cf_load_woocommerce_template',
                'desc_tip' => true,
            ),
            //trouble shoot options added here
            array(
                'name' => __('Hook to destroy session', 'galaxyfunder'),
                'desc' => __('Select Hook to destroy session', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:150px;',
                'id' => 'cf_session_destroy_hook',
                'std' => 'no',
                'type' => 'select',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_session_destroy_hook',
                'options' => array(
                    '1' => __('woocommerce_checkout_update_order_meta', 'galaxyfunder'),
                    '2' => __('woocommerce_thankyou', 'galaxyfunder'),
                ),
                'desc_tip' => false,
            ),
            array('type' => 'sectionend', 'id' => '_cf_campaign_troubleshoot'),
            array(
                'name' => __('Troubleshoot SSL', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => 'cf_product_ssl_troubleshoot'
            ),
            array(
                'name' => __('Load Ajax from', 'galaxyfunder'),
                'desc' => __('Force SSL for Admin Cause Galaxy Funder to Stop Working because of https to http is causing problem', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_load_ajax_from_ssl',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('From Admin URL', 'galaxyfunder'), '2' => __('From Site URL', 'galaxyfunder')),
                'newids' => 'cf_load_ajax_from_ssl',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enqueue select2 Library from Galaxy Funder', 'galaxyfunder'),
                'desc' => __('', 'galaxyfunder'),
                'tip' => '',
                'id' => 'gf_enqueue_select2_lib_from_plugin',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('Enable', 'galaxyfunder'), '2' => __('Disable', 'galaxyfunder')),
                'newids' => 'gf_enqueue_select2_lib_from_plugin',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'cf_product_ssl_troubleshoot'),
        ));
    }

    /**
     * Registering Custom Field Admin Settings of Crowdfunding in woocommerce admin fields funtion
     */
    public static function crowdfunding_register_admin_settings() {
        woocommerce_admin_fields(CFGeneraltab::crowdfunding_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in crowdfunding
     */
    public static function crowdfunding_update_settings() {
        woocommerce_update_options(CFGeneraltab::crowdfunding_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function crowdfunding_default_settings() {
        global $woocommerce;
        foreach (CFGeneraltab::crowdfunding_admin_fields() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }
    
    

    /**
    * Function is reset admin fields
    */
    public static function reset_changes_crowdfunding() {
        global $woocommerce;
        if (!empty($_POST['reset'])) {
            
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-reset_settings'))
                die(__('Action failed. Please refresh the page and retry.', 'galaxyfunder'));
            //Reset
            if (isset($_POST['reset'])) {
                if($_POST['reset_hidden']=='crowdfunding_callback'||$_POST['reset_hidden']=='crowdfunding'){
                    echo FP_GF_Common_Functions::reset_common_function(CFGeneraltab::crowdfunding_admin_fields());
                }
            }
            
            delete_transient('woocommerce_cache_excluded_uris');
            $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
            if (isset($_POST['reset'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
        
        //Reset all
            if(isset($_POST['resetall'])){
                echo FP_GF_Common_Functions::reset_common_function(CFGeneraltab::crowdfunding_admin_fields());
            }
    }

}
    CFGeneraltab::init();
}
