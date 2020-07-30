<?php
if (!class_exists('CFErrorMessage')) {
class CFErrorMessage {
    
      public static function init() {
        //Error message Settings Array
        add_action('woocommerce_update_options_crowdfunding_errormessage', array(__CLASS__, 'crowdfunding_process_error_update_settings'));
        add_action('init', array(__CLASS__, 'crowdfunding_error_default_settings'));
        add_action('woocommerce_cf_settings_tabs_crowdfunding_errormessage', array(__CLASS__, 'crowdfunding_process_error_admin_settings'));
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_error_tab'), 1500);
        add_action('admin_init', array(__CLASS__, 'cf_error_reset_values'), 2);
    }

    public static function crowdfunding_admin_error_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_errormessage'] = __('Error Message', 'galaxyfunder');
        return $settings_tabs;
        
    }

    public static function crowdfunding_error_admin_options() {
        return apply_filters('woocommerce_crowdfunding_error_settings', array(
            array(
                'name' => __('Error Message Settings', 'galaxyfunder'),
                'type' => 'title',
//                'desc' => 'Shortcode Available (Supported for Simple Products)<br> <pre> [cf_min_price] => Minimum Contribution </pre><pre> [cf_max_price] => Maximum Contribution </pre>',
                'id' => '_cf_product_error_settings'
            ),
            array(
                'name' => __('Minimum Contribution Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter Minimum Contribution Error Message', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_min_price_error_msg',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter Minimum Contribution',
                'type' => 'text',
                'newids' => 'cf_min_price_error_msg',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Error Message', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_max_price_error_msg',
                'std' => 'Contribution should not be more than Maximum Contribution',
                'type' => 'text',
                'newids' => 'cf_max_price_error_msg',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Input Contribution Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter your Input Error Message', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_input_price_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter Only Numbers',
                'type' => 'text',
                'newids' => 'cf_input_price_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contribution Field Empty Error Message', 'galaxyfunder'),
                'desc' => __('Please Enter Contribution Field Empty Error Message for Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => '_cf_empty_contribution_error_msg',
                'css' => '',
                'std' => 'Contribution Field cannot be Empty',
                'type' => 'textarea',
                'newids' => '_cf_empty_contribution_error_msg',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Display Error Message on', 'galaxyfunder'),
                'desc' => __('This Controls where the Error Message should be Displayed', 'galaxyfunder'),
                'id' => 'display_select_box_crowdfunding',
                'css' => 'min-width:150px;',
                'std' => 'bottom', // WooCommerce < 2.0
                'default' => 'bottom', // WooCommerce >= 2.0
                'newids' => 'display_select_box_crowdfunding',
                'type' => 'select',
                'options' => array(
                    'top' => __('Above Contribution Text Box', 'galaxyfunder'),
                    'bottom' => __('Below Contribution Text Box', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Perk Error Message', 'galaxyfunder'),
                'desc' => __('This Controls where the Error Message of Perk Should be Displayed', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_perk_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Contribution Amount Should be equal or greater than Perk Value', 'galaxyfunder',
                'type' => 'text',
                'newids' => 'cf_perk_error_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_error_settings'),
        ));
    }

    public static function crowdfunding_process_error_admin_settings() {
        woocommerce_admin_fields(CFErrorMessage::crowdfunding_error_admin_options());
    }

    public static function crowdfunding_process_error_update_settings() {
        woocommerce_update_options(CFErrorMessage::crowdfunding_error_admin_options());
    }

    public static function crowdfunding_error_default_settings() {
        global $woocommerce;
        foreach (CFErrorMessage::crowdfunding_error_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_error_reset_values() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
            if($_POST['reset_hidden']=='crowdfunding_errormessage'){
                echo FP_GF_Common_Functions::reset_common_function(CFErrorMessage::crowdfunding_error_admin_options());
            }
            
            if(isset($_POST['resetall'])){
                echo FP_GF_Common_Functions::reset_common_function(CFErrorMessage::crowdfunding_error_admin_options());
            }
            
            
            
        }
    }

}

 CFErrorMessage::init();
}
