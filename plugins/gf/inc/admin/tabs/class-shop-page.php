<?php
if (!class_exists('CFShopPageAdmin')) {
class CFShopPageAdmin {
    
       public static function init() {
        //Admin Settings
        add_action('woocommerce_update_options_crowdfunding_shoppage', array(__CLASS__, 'crowdfunding_process_shoppage_update_settings'));
        add_action('init', array(__CLASS__, 'crowdfunding_shoppage_default_settings'));
        add_action('woocommerce_cf_settings_tabs_crowdfunding_shoppage', array(__CLASS__, 'crowdfunding_process_shoppage_admin_settings'));
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_shoppage_tab'), 100);
        add_action('admin_init', array(__CLASS__, 'cf_shoppage_reset_values'), 2);
        //Shop Styles Jquery
        add_action('admin_head', array(__CLASS__, 'show_hide_options_for_selected_styles_shop'));
    }

    public static function crowdfunding_admin_shoppage_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_shoppage'] = __('Shop Page', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_shoppage_admin_options() {
        return apply_filters('woocommerce_crowdfunding_shoppage_settings', array(
            array(
                'name' => __('Shop Page Label', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_button_text'
            ),
            array(
                'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Minimum Contribution Label for Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'crowdfunding_min_price_shop_page',
                'css' => 'min-width:550px;',
                'std' => 'Minimum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_min_price_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_maximum_price_shop_page',
                'std' => 'Maximum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_maximum_price_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Please Enter Goal Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_target_price_shop_page',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'crowdfunding_target_price_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_label_shop_page',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_label_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Percent Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Percent Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_percent_label_shop_page',
                'std' => 'Percent',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_percent_label_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Funder\'s Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_funder_label_shop',
                'std' => 'Funders',
                'type' => 'text',
                'newids' => 'cf_funder_label_shop',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show Description on shop page', 'galaxyfunder'),
                'desc' => __('Please Select You want to display the Description on shop page', 'galaxyfunder'),
                'id' => 'crowdfunding_description_on_shop_page',
                'std' => 'none',
                'type' => 'select',
                'options' => array(
                    'none' => __('None', 'galaxyfunder'),
                    'above_stylebar' => __('Above Style Bar', 'galaxyfunder'),
                    'below_stylebar' => __('Below Style Bar', 'galaxyfunder'),
                ),
                'newids' => 'crowdfunding_description_on_shop_page',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Product Description Type', 'galaxyfunder'),
                'desc' => __('Please Select You Want to Display the Which Description on Shop Page', 'galaxyfunder'),
                'id' => 'crowdfunding_description_type',
                'std' => '1',
                'type' => 'radio',
                'options' => array(
                    '1' => __('Product Main Description', 'galaxyfunder'),
                    '2' => __('Product Short Description', 'galaxyfunder'),
                ),
                'newids' => 'crowdfunding_description_type',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Description Words Count', 'galaxyfunder'),
                'desc' => __('Please Enter The Words Count to Display in Description', 'galaxyfunder'),
                'id' => 'crowdfunding_description_words_count',
                'std' => '10',
                'type' => 'text',
                'newids' => 'crowdfunding_description_words_count',
                'css' => 'min-width:100px;',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_button_text'),
            array(
                'name' => __('Choose Inbuilt/Custom Design', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_inbuilt_text'
            ),
            array(
                'name' => __('Inbuilt Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Inbuilt Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Inbuilt Design'),
                'newids' => 'cf_inbuilt_shop_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Inbuilt Design', 'galaxyfunder'),
                'desc' => __('This helps to load the inbuilt type', 'galaxyfunder'),
                'id' => 'load_inbuilt_shop_design',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_shop_design',
                'type' => 'select',
                'options' => array(
                    '1' => __('Minimal Style', 'galaxyfunder'),
                    '2' => __('IGG Style', 'galaxyfunder'),
                    '3' => __('KS Style', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Select Progress Bar Style', 'galaxyfunder'),
                'desc' => __('This helps to select the Progress bar Style', 'galaxyfunder'),
                'id' => 'shop_page_prog_bar_type',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'shop_page_prog_bar_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Style 1', 'galaxyfunder'),
                    '2' => __('Style 2', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Amount Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Target Price in a Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_amount_show_hide_shop',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_amount_show_hide_shop',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Percentage Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Raised Percentage in a Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_percentage_show_hide_shop',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_percentage_show_hide_shop',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Target Days Left Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Days Left in a Shop Page', 'galaxyfunder'),
                'id' => 'cf_day_left_show_hide_shop',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_day_left_show_hide_shop',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Number of Funders Details Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Funders count in a Shop Page', 'galaxyfunder'),
                'id' => 'cf_funders_count_show_hide_shop',
                'css' => 'min-width:150px;margin-bottom:40px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_funders_count_show_hide_shop',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Inbuilt CSS (Non Editable)', 'galaxyfunder'),
                'desc' => __('These are element IDs in the Shop Page ', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_shop_page_contribution_table_default_css',
                'std' => '#cf_min_price_label { display:none;
 }
 #cf_total_raised_in_percentage { display:none; }
 #cf_total_price_raiser {display:none;}
 #cf_days_remainings {display:none;}
 #cf_max_price_label { display:none; }
 #cf_target_price_label { display:none; }
 #cf_total_price_raised {display:none;}
 #cf_total_quantity_raised {display:none;}
 #cf_total_price_in_percentage_with_bar {display:none;}
 #cf_total_price_in_percenter_with_bar {display:none;}
 #cf_total_price_in_percentage {display:none;}  #single_product_contribution_table{
 }
#cf_serial_number_label{
 }
#cf_contributor_label{
 }
#cf_contributor_email_label{
 }
#cf_contribution_label{
 }
#cf_date_label{
 }
#serial_id{
 }
#cf_billing_first_name{
 }
 #cf_billing_email{
 }
 #cf_order_total{
 }
#cf_target_price_labels{ margin-bottom:0px;
 }
#cf_total_price_raise{ float:left;
 }
#cf_total_price_raise span {
 }
#cf_total_price_in_percent{
 }
#cf_total_price_in_percent_with_bar{width: 100%;
 height:12px;
 background-color: #ffffff;
 border-radius:10px;
 border:1px solid #000000;
 clear:both;
 }
 #cf_percent_bar{ height:10px;
 border-radius:10px;
 background-color: green;
 }
 #cf_total_price_in_percenter_with_bar{width: 100%;
 height:12px;
 background-color: #ffffff;
 border-radius:10px;
 border:1px solid #000000;
 clear:both;
 }
 #cf_percenter_bar{ height:10px;
 border-radius:10px;
 background-color: green;
 }
 #cf_price_new_date_remain small { font-style:italic;  }
 #cf_price_new_date_remain { margin-bottom:0px;
 float:left; }
 #singleproductinputfieldcrowdfunding{color:green;
 }
 #cf_target_price_labels {font-style:italic;
 font-size:20px;
 }
 #cf_update_total_funders {margin-bottom:0px; float:right; }
 #cf_total_raised_percentage {float:right;
 font-size:16px !important;
 margin-bottom:0px;
}
',
                'type' => 'textarea',
                'newids' => 'cf_shop_page_contribution_table_default_css',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Custom Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('2' => __('Custom Design', 'galaxyfunder')),
                'newids' => 'cf_inbuilt_shop_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'galaxyfunder'),
                'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_shop_page_contribution_table_custom_css',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_shop_page_contribution_table_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_inbuilt_text'),
        ));
    }

    public static function crowdfunding_process_shoppage_admin_settings() {
        woocommerce_admin_fields(CFShopPageAdmin::crowdfunding_shoppage_admin_options());
    }

    public static function crowdfunding_process_shoppage_update_settings() {
        woocommerce_update_options(CFShopPageAdmin::crowdfunding_shoppage_admin_options());
    }

    public static function crowdfunding_shoppage_default_settings() {
        global $woocommerce;
        foreach (CFShopPageAdmin::crowdfunding_shoppage_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_shoppage_reset_values() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
            if($_POST['reset_hidden']=='crowdfunding_shoppage'){
                echo FP_GF_Common_Functions::reset_common_function(CFShopPageAdmin::crowdfunding_shoppage_admin_options());
            } 
        }
             
        if(isset($_POST['resetall'])){
            echo FP_GF_Common_Functions::reset_common_function(CFShopPageAdmin::crowdfunding_shoppage_admin_options());
        }
    }
 

    public static function show_hide_options_for_selected_styles_shop() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var selected_style = jQuery('#load_inbuilt_shop_design').val();
                if (selected_style == '1') {
                    jQuery('#cf_day_left_show_hide_shop').parent().parent().hide();
                    jQuery('#cf_funders_count_show_hide_shop').parent().parent().hide();
                }
                else if (selected_style == '2') {
                    jQuery('#cf_day_left_show_hide_shop').parent().parent().show();
                    jQuery('#cf_funders_count_show_hide_shop').parent().parent().show();
                    jQuery('#cf_raised_amount_show_hide_shop').parent().parent().show();
                    jQuery('#cf_raised_percentage_show_hide_shop').parent().parent().show();
                } else {
                    jQuery('#cf_funders_count_show_hide_shop').parent().parent().hide();
                }
                jQuery('#load_inbuilt_shop_design').change(function () {
                    var selected_styles = jQuery('#load_inbuilt_shop_design').val();
                    if (selected_styles == '1') {
                        jQuery('#cf_day_left_show_hide_shop').parent().parent().hide();
                        jQuery('#cf_funders_count_show_hide_shop').parent().parent().hide();
                        jQuery('#cf_raised_amount_show_hide_shop').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide_shop').parent().parent().show();
                    }
                    if (selected_styles == '2') {
                        jQuery('#cf_day_left_show_hide_shop').parent().parent().show();
                        jQuery('#cf_funders_count_show_hide_shop').parent().parent().show();
                        jQuery('#cf_raised_amount_show_hide_shop').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide_shop').parent().parent().show();
                    }
                    if (selected_styles == '3') {
                        jQuery('#cf_day_left_show_hide_shop').parent().parent().show();
                        jQuery('#cf_funders_count_show_hide_shop').parent().parent().hide();
                        jQuery('#cf_raised_amount_show_hide_shop').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide_shop').parent().parent().show();
                    }
                });
                var select_shop_description_style = jQuery('#crowdfunding_description_on_shop_page').val();
                if (select_shop_description_style == 'above_stylebar' || select_shop_description_style == 'below_stylebar') {
                    jQuery('#crowdfunding_description_words_count').parent().parent().parent().show();
                    jQuery('label[for=crowdfunding_description_type]').parent().parent().show();
                }
                else {
                    jQuery('#crowdfunding_description_words_count').parent().parent().hide();
                    jQuery('label[for=crowdfunding_description_type]').parent().parent().hide();
                }
                jQuery('#crowdfunding_description_on_shop_page').change(function () {
                    var select_shop_description_style = jQuery('#crowdfunding_description_on_shop_page').val();
                    if (select_shop_description_style == 'above_stylebar' || select_shop_description_style == 'below_stylebar') {
                        jQuery('#crowdfunding_description_words_count').parent().parent().show();
                        jQuery('label[for=crowdfunding_description_type]').parent().parent().show();
                    }
                    else {
                        jQuery('#crowdfunding_description_words_count').parent().parent().hide();
                        jQuery('label[for=crowdfunding_description_type]').parent().parent().hide();
                    }
                });
            });
        </script>
        <?php

    }

}

 CFShopPageAdmin::init();
}