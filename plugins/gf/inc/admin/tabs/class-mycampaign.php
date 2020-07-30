<?php

//error_reporting(0);
if (!class_exists('FPCrowdFundingMycampaign')) {
class FPCrowdFundingMycampaign {
    
    public static function init() {
        /**
         * Adding the setting tab my account
         */
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'cf_my_account_tab'), 103);
        add_action('woocommerce_cf_settings_tabs_my_account', array(__CLASS__, 'cf_admin_my_campaign_settings'));
        add_action('woocommerce_update_options_my_account', array(__CLASS__, 'cf_admin_my_campaign_update_settings'));
        add_action('init', array(__CLASS__, 'cf_default_my_account_page'));
//        add_action('admin_enqueue_scripts', array('CrowdFunding', 'crowdfunding_enqueue_scripts'));
        add_action('admin_init', array(__CLASS__, 'cf_shoppage_reset_values_tab'), 2);
    }
    

//adding tab
    public static function cf_my_account_tab($settings_tab) {
         if(!is_array($settings_tab)){
            $settings_tab=(array)$settings_tab;
        }
        $settings_tab['my_account'] = __('My Account Page', 'galaxyfunder');
        return $settings_tab;
    }

    public static function cf_admin_my_campaign_options() {
        global $woocommerce;
        return apply_filters('woocommerce_my_account_settings', array(
            array(
                'name' => __("Use the Shortcode [crowd_fund_extension] to display the Campaign Extension Form"),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_new_extn_shortcode'
            ),
            array(
                'name' => __("Use the Shortcode [cf_mycampaign_table] to display the Campaign table"),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_contributor_shortcode'
            ),
            array(
                'name' => __('My Account Page Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_my_campaign'
            ),
            array(
                'name' => __('My Campaign Table Show/Hide', 'galaxyfunder'),
                'desc' => __('This Controls the My Campaign Table Show or Hide', 'galaxyfunder'),
                'id' => 'cf_display_mycampaign_table',
                'css' => 'min-width:150px;',
                'std' => 'on', // WooCommerce < 2.0
                'default' => 'on', // WooCommerce >= 2.0
                'newids' => 'cf_display_mycampaign_table',
                'type' => 'select',
                'options' => array(
                    'on' => __('Show', 'galaxyfunder'),
                    'off' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Title of My Campaign', 'galaxyfunder'),
                'desc' => __('Change My Campaign Title in My Account Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_mycampaign_title',
                'css' => 'min-width:550px;',
                'std' => 'My Campaigns',
                'type' => 'text',
                'newids' => 'cf_mycampaign_title',
                'desc_tip' => true,
            ),
            array(
                'name' => __('S.No Label', 'galaxyfunder'),
                'desc' => __('Change S.No Caption in Single in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_mycampaign_serial_number_label',
                'css' => 'min-width:550px;',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'cf_mycampaign_serial_number_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Label', 'galaxyfunder'),
                'desc' => __('Change Campaign Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_campaign_label',
                'std' => 'Campaign',
                'type' => 'text',
                'newids' => 'cf_mycampaign_campaign_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Date Label', 'galaxyfunder'),
                'desc' => __('Change Date Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_date_label',
                'std' => 'Date',
                'type' => 'text',
                'newids' => 'cf_mycampaign_date_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Change Goal Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_goal_label',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'cf_mycampaign_goal_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Fund Raised Label', 'galaxyfunder'),
                'desc' => __('Change Fund Raised Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_raised_label',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'cf_mycampaign_raised_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Fund Raised Percentage Label', 'galaxyfunder'),
                'desc' => __('Change Fund Raised Percentage Caption in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_raised_percent_label',
                'std' => 'Raised %',
                'type' => 'text',
                'newids' => 'cf_mycampaign_raised_percent_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Extension Label', 'galaxyfunder'),
              
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_label',
                'std' => 'Extend Campaign',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Extension Link Label', 'galaxyfunder'),
               
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_link_label',
                'std' => 'Contribution Extension',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_link_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Page Id of Contribution Extension Link ', 'galaxyfunder'),
               
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_extension_pageid',
                'std' => '',
                'type' => 'text',
                'newids' => 'cf_mycampaign_extension_pageid',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Funders Label', 'galaxyfunder'),
                'desc' => __('Change Funders in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_funders_label',
                'std' => 'Funders',
                'type' => 'text',
                'newids' => 'cf_mycampaign_funders_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Status Label', 'galaxyfunder'),
                'desc' => __('Change Funders in My Campaign table by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_mycampaign_status_label',
                'std' => 'Status',
                'type' => 'text',
                'newids' => 'cf_mycampaign_status_label',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Customize Target End Type in My Account Page', 'galaxyfunder'),
                'desc' => __('Customize Target End Type in My Account Page', 'galaxyfunder'),
                'tip' => '',
                'desc_tip' => true,
                'css' => '',
                'id' => '_cf_customize_target_type_my_account',
                'type' => 'text',
                'newids' => '_cf_customize_target_type_my_account',
                'std' => 'Target Type',
            ),
            array(
                'name' => __('Campaign Table Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_mycampaign_table_position',
                'options' => array('1' => __('Start of My Account', 'galaxyfunder'), '2' => __('End of My Account', 'galaxyfunder')),
                'class' => 'cf_mycampaign_table_position',
                'std' => '2',
                'newids' => 'cf_mycampaign_table_position',
            ),
            array(
                'name' => __('Show/Hide Your Subscribe Link', 'galaxyfunder'),
                'desc' => __('Show/Hide Your Subscribe Link if you want to display it in my account page', 'galaxyfunder'),
                'id' => 'gf_show_hide_your_subscribe_link',
                'newids' => 'gf_show_hide_your_subscribe_link',
                'class' => 'gf_show_hide_your_subscribe_link',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Subscribe Link Message', 'galaxyfunder'),
                'desc' => __('This Message will be displayed on the option to Unsubscribe from Galaxy Funder Emails', 'galaxyfunder'),
                'id' => 'gf_unsubscribe_message_myaccount_page',
                'css' => 'min-width:550px;',
                'std' => 'Unsubscribe here To Stop Receiving Email',
                'type' => 'textarea',
                'newids' => 'gf_unsubscribe_message_myaccount_page',
                'class' => 'gf_unsubscribe_message_myaccount_page',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_my_campaign'),
        ));
    }

    public static function cf_admin_my_campaign_settings() {
        woocommerce_admin_fields(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
    }

    public static function cf_admin_my_campaign_update_settings() {
        woocommerce_update_options(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
    }

    public static function cf_default_my_account_page() {
        global $woocommerce;
        foreach (FPCrowdFundingMycampaign::cf_admin_my_campaign_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    
    
    public static function cf_shoppage_reset_values_tab() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
            if($_POST['reset_hidden']=='my_account'){
                echo FP_GF_Common_Functions::reset_common_function(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
            }
        }
        
        if(isset($_POST['resetall'])){
            echo FP_GF_Common_Functions::reset_common_function(FPCrowdFundingMycampaign::cf_admin_my_campaign_options());
        }
        
        
        
    }


}
 FPCrowdFundingMycampaign::init();
}