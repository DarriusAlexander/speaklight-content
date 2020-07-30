<?php
if (!class_exists('CFShortcodeGenerator')) {
class CFShortcodeGenerator {
    
     public static function init() {
        add_filter('widget_text', 'do_shortcode');
        //shortcode tabs settings hooks
        if(isset($_GET['tab'])) {
            if($_GET['tab']=='crowdfunding_shortcode') {
                add_action('woocommerce_update_options_crowdfunding_shortcode', array(__CLASS__, 'crowdfunding_process_shortcode_update_settings'));
                add_action('admin_init', array(__CLASS__, 'crowdfunding_shortcode_default_settings'));
                add_action('woocommerce_cf_settings_tabs_crowdfunding_shortcode', array(__CLASS__, 'crowdfunding_process_shortcode_admin_settings'));

                add_action('admin_init', array(__CLASS__, 'cf_shortcode_reset_values'), 2);
                add_action('admin_head', array(__CLASS__, 'admin_enqueue_script'));
            }
        }
        
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_shortcode_tab'), 1500);
    }

    public static function crowdfunding_admin_shortcode_tab($settings_tabs) {
        if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_shortcode'] = __('Shortcode Generator', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_shortcode_admin_options() {
        if(isset($_GET['tab'])) {
            if($_GET['tab']=='crowdfunding_shortcode') {
        $newarray = array();
        $producttitle=array();
        $output = '';
        $getproducts = FP_GF_Common_Functions::common_function_for_get_post('');
      
        foreach ($getproducts as $product) {
            $product_id=$product->ID;
            if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                $newarray[] = $product_id;
                $producttitle[] = $product->post_title;
            }
        }
        if (is_array($newarray) && (is_array($producttitle))&& (!empty($producttitle))&& (!empty($newarray))) {
            $output = array_combine($newarray, $producttitle);
        }
        return apply_filters('woocommerce_crowdfunding_shortcode_settings', array(
            array(
                'name' => __('Shortcode Label', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_button_text_shortcode'
            ),
            array(
                'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Minimum Contribution Label for Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'crowdfunding_min_price_shop_page_shortcode',
                'css' => 'min-width:550px;',
                'std' => 'Minimum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_min_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_maximum_price_shop_page_shortcode',
                'std' => 'Maximum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_maximum_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Please Enter Goal Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_target_price_shop_page_shortcode',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'crowdfunding_target_price_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_label_shop_page_shortcode',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_label_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Percent Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Percent Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_percent_label_shop_page_shortcode',
                'std' => 'Percent',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_percent_label_shop_page_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Enable Title in Shortcode', 'galaxyfunder'),
                'desc' => __('Enable this Option to show the Title in Shortcode', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_enable_title_for_shortcode',
                'std' => 'yes',
                'type' => 'checkbox',
                'newids' => 'crowdfunding_enable_title_for_shortcode',
            ),
            array(
                'name' => __('Enable Description in Shortcode', 'galaxyfunder'),
                'desc' => __('Enable this Option to show the Description in Shortcode', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_enable_description_for_shortcode',
                'std' => 'yes',
                'type' => 'checkbox',
                'newids' => 'crowdfunding_enable_description_for_shortcode',
            ),
            array(
                'name' => __('Enter Number of words to Trim from  Description', 'galaxyfunder'),
                'desc' => __('Enter Number of words to trim from description of product page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;margin-bottom:80px;',
                'id' => 'crowdfunding_number_of_words_to_trim',
                'std' => '10',
                'type' => 'text',
                'newids' => 'crowdfunding_number_of_words_to_trim',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_button_text_shortcode'),
            array(
                'name' => __('Choose Inbuilt/Custom Design', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_inbuilt_text_shortcode'
            ),
            array(
                'name' => __('Inbuilt Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Inbuilt Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design_shortcode',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Inbuilt Design'),
                'newids' => 'cf_inbuilt_shop_design_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Inbuilt Design', 'galaxyfunder'),
                'desc' => __('This helps to load the inbuilt type', 'galaxyfunder'),
                'id' => 'load_inbuilt_shop_design_shortcode',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_shop_design_shortcode',
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
                'id' => 'shortcode_page_prog_bar_type',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'shortcode_page_prog_bar_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Style 1', 'galaxyfunder'),
                    '2' => __('Style 2', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Amount Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Target Price in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_amount_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_amount_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Percentage Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Raised Percentage in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_raised_percentage_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_percentage_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Target Days Left Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Days Left in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_day_left_show_hide_shortcode',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_day_left_show_hide_shortcode',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Number of Funders Details Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Funders count in a Shortcode Shop Page', 'galaxyfunder'),
                'id' => 'cf_funders_count_show_hide_shortcode',
                'css' => 'min-width:150px;margin-bottom:40px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_funders_count_show_hide_shortcode',
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
                'id' => 'cf_shop_page_contribution_table_default_css_shortcode',
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
#cf_total_price_raise span {font-size:17px;
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
                'newids' => 'cf_shop_page_contribution_table_default_css_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Custom Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_shop_design_shortcode',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('2' => __('Custom Design', 'galaxyfunder')),
                'newids' => 'cf_inbuilt_shop_design_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'galaxyfunder'),
                'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_shop_page_contribution_table_custom_css_shortcode',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_shop_page_contribution_table_custom_css_shortcode',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Campaigns', 'galaxyfunder'),
                'desc' => __('Select your Campaign in the List Box', 'galaxyfunder'),
                'id' => 'load_inbuilt_shortcode_generators',
                'css' => 'min-width:550px;',
                'class' => 'chosen-style',
                'std' => '', // WooCommerce < 2.0
                'default' => '', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_shortcode_generators',
                'type' => 'multiselect',
                'options' => $output,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_inbuilt_text_shortcode'),
            array(
                'name' => __('Shortcode', 'galaxyfunder'),
                'type' => 'title',
                'class' => 'newh1tag',
                'desc' => '<pre id="hiddenpro" style="display:none;"></pre><pre id="productidshortcode"></pre>',
                'id' => '_product_generated_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_generated_shortcode'),
            array(
                'name' => __('Use the Shortcode [galaxyfunder_my_campaign] for displaying the Campaigns created by the User', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_my_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_my_campaign_shortcode'),
            array(
                'name' => __('Use the Shortcode [galaxyfunder_all_campaign_list] for displaying the Campaigns Created by all Members', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_all_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_all_campaign_shortcode'),
             array(
                'name' => __('Use the Shortcode [galaxyfunder_running_campaigns] for displaying  All Running Campaigns', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_all_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_all_running_campaign_shortcode'),
             array(
                'name' => __('Use the Shortcode [galaxyfunder_closed_campaigns] for displaying All Closed Campaigns', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_all_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_all_closed_campaign_shortcode'),
            array(
                'name' => __('Use the Shortcode [gf_funders_table_for_campaign] for displaying the Funders table in Campaign Page', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_list_funders_of_campaign_shortcode'
            ),
            array('type' => 'sectionend', 'id' => '_cf_list_funders_of_campaign_shortcode'),
        ));
    }
    }
}

    public static function crowdfunding_process_shortcode_admin_settings() {
        woocommerce_admin_fields(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
    }

    public static function crowdfunding_process_shortcode_update_settings() {
        woocommerce_update_options(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
    }

    public static function crowdfunding_shortcode_default_settings() {
        global $woocommerce;
        foreach (CFShortcodeGenerator::crowdfunding_shortcode_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_shortcode_reset_values() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
             if($_POST['reset_hidden']=='crowdfunding_shortcode'){
            foreach (CFShortcodeGenerator::crowdfunding_shortcode_admin_options() as $setting)
              echo FP_GF_Common_Functions::reset_common_function(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
             }
        }
        
        if(isset($_POST['resetall'])){
                echo FP_GF_Common_Functions::reset_common_function(CFShortcodeGenerator::crowdfunding_shortcode_admin_options());
        }
    }
    
    

    public static function admin_enqueue_script() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'crowdfunding_shortcode') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                        var selected_style = jQuery('#load_inbuilt_shop_design_shortcode').val();
                        if (selected_style == '1') {
                            jQuery('#cf_day_left_show_hide_shortcode').parent().parent().hide();
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                        }
                        else if (selected_style == '2') {
                            jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                            jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                        } else {
                            jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                        }
                        jQuery('#load_inbuilt_shop_design_shortcode').change(function () {
                            var selected_styles = jQuery('#load_inbuilt_shop_design_shortcode').val();
                            if (selected_styles == '1') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();

                            }
                            if (selected_styles == '2') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                            }
                            if (selected_styles == '3') {
                                jQuery('#cf_day_left_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_funders_count_show_hide_shortcode').parent().parent().hide();
                                jQuery('#cf_raised_amount_show_hide_shortcode').parent().parent().show();
                                jQuery('#cf_raised_percentage_show_hide_shortcode').parent().parent().show();
                            }
                            //alert(jQuery('#load_inbuilt_design').val());
                            // alert("hai");
                        });

                        jQuery(".chosen-style").attr('multiple', '');
                        jQuery('.chosen-style').attr('data-placeholder', 'Search for a Campaign...')
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery(".chosen-style").chosen();


                            var newvalue = jQuery(".chosen-style").chosen().val();
                <?php } else { ?>
                            jQuery('body').trigger('wc-enhanced-select-init');
                            //  jQuery('.chosen-style').select2();
                            var newvalue = jQuery(".chosen-style").select2().val();
                <?php } ?>
                        //console.log(newvalue);
                        if (newvalue !== null) {
                            for (var i = 0; i < newvalue.length; i++) {
                                // alert(newvalue[i]);
                                if ((newvalue[i] !== null)) {
                                    jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                }
                            }
                        }
                <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            jQuery(".chosen-style").chosen().change(function (e, params) {
                                var newvalue = jQuery(".chosen-style").chosen().val();
                                jQuery('.chosen-style').trigger("chosen:updated");

                                if (newvalue !== null) {
                                    for (var i = 0;
                                            i < newvalue.length;
                                            i++) {
                                        if (i === 0) {
                                            jQuery('#productidshortcode').empty();
                                        }
                                        if ((newvalue[i] !== null)) {
                                            jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                        }
                                    }
                                } else {
                                    jQuery('#productidshortcode').empty("");
                                }
                            });
                <?php } else { ?>
                            jQuery(".chosen-style").change(function () {
                                var newvalue = jQuery('#load_inbuilt_shortcode_generators').val();
                                newvalue = jQuery('#hiddenpro').text(newvalue);
                                newvalue = jQuery('#hiddenpro').text();
                                jQuery('#hiddenpro').css('display', 'none');
                                newvalue = newvalue.split(',');
                                if (newvalue !== null) {
                                    for (var i = 0;
                                            i < newvalue.length;
                                            i++) {
                                        if (i === 0) {
                                            jQuery('#productidshortcode').empty();
                                        }
                                        if ((newvalue[i] !== "null")) {
                                            jQuery('#productidshortcode').append('[galaxyfunder_campaign id="' + newvalue[i] + '"]<br>');
                                        }
                                        else {
                                            jQuery('#productidshortcode').empty();
                                        }
                                    }
                                }

                            });

                <?php } ?>
                    });
                </script>
                <?php
            }
        }
    }
}
 CFShortcodeGenerator::init();
}