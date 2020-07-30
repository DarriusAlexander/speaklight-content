<?php
if (!class_exists('CFFrontendSubmission')) {

    class CFFrontendSubmission {

        public static function init() {
            /**
             * Adding the setting tab start
             */
            add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'cf_frontend_submission_tab'), 101);
            add_action('woocommerce_cf_settings_tabs_frontend', array(__CLASS__, 'cf_admin_front_end_settings'));
            add_action('woocommerce_update_options_frontend', array(__CLASS__, 'cf_admin_front_end_update_settings'));
            add_action('init', array(__CLASS__, 'cf_frontend_default_values'));
            add_action('admin_init', array(__CLASS__, 'cf_frontend_reset_values'), 1);
            /**
             * Adding the setting tab End
             */
            //Select products and category multi 
            add_action('woocommerce_admin_field_selectedproducts_campaign', array(__CLASS__, 'selected_products_for_crowdfunding'));
            add_action('woocommerce_update_option_selectedproducts_campaign', array(__CLASS__, 'save_selected_products_for_crowdfunding'));
            add_action('admin_head', array(__CLASS__, 'selected_categories_for_crowdfunding'));
        }

        /**
         * Crowdfunding Register Admin Settings Tab
         */
        public static function cf_frontend_submission_tab($settings_tabs) {
            if (!is_array($settings_tabs)) {
                $settings_tabs = (array) $settings_tabs;
            }
            $settings_tabs['frontend'] = __('Frontend Submission', 'galaxyfunder');
            return $settings_tabs;
        }
        
       

        public static function cf_admin_front_end() {
            global $woocommerce;
            $categories = get_terms('product_cat', array('hide_empty' => false));
            $category_id = array();
            $category_name = array();
            $selectedcategories = array();
            if (!is_wp_error($categories)) {
                if (!empty($categories)) {
                    if ($categories != NULL) {
                        foreach ($categories as $value) {
                            if ($value->parent > 0) {
                                $parent_name = get_cat_name($value->parent) . '<br>' . '-' . $value->name;
                            } else {
                                $parent_name = $value->name;
                            }

                            $category_id[] = $value->term_id;
                            $category_name[] = $parent_name;
                        }
                    }
                    $selectedcategories = array_combine((array) $category_id, (array) $category_name);
                }
            }
            return apply_filters('woocommerce_frontend_settings', array(
                array(
                    'name' => __("Use the Shortcode [crowd_fund_form] to display the Front End Submission Form"),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_new_default'
                ),
                array('type' => 'sectionend', 'id' => '_cf_new_default'),
                array(
                    'name' => __('FrontEnd Campaign Submission Form Settings', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_submission'
                ),
                array(
                    'name' => __('Front End Submission Method', 'galaxyfunder'),
                    'desc' => __('This Controls the Campaign should go for Moderation or Live', 'galaxyfunder'),
                    'id' => 'cf_frontend_submission_method',
                    'css' => 'min-width:150px;',
                    'std' => '1', // WooCommerce < 2.0
                    'default' => '1', // WooCommerce >= 2.0
                    'newids' => 'cf_frontend_submission_method',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Goes for Moderation', 'galaxyfunder'),
                        '2' => __('Goes Live', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('URL to Redirect for Guest', 'galaxyfunder'),
                    'desc' => __('Please Enter URL to Redirect if a guest tries this page', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_guest_url',
                    'std' => wp_login_url(),
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_guest_url',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Enable URL Redirection after Campaign is Submitted', 'galaxyfunder'),
                    'desc' => __('Please Select the Option to Enable/Disable URL Redirection after Campaign is Submitted', 'galaxyfunder'),
                    'id' => 'cf_campiagn_success_redirection_option',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_campiagn_success_redirection_option',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Disable', 'galaxyfunder'),
                        '2' => __('Enable', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('URL to Redirect after Campaign is submitted', 'galaxyfunder'),
                    'desc' => __('Please Enter URL to Redirect after the campaign is submitted', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_campiagn_success_redirection_url_content',
                    'std' => '',
                    'type' => 'text',
                    'newids' => 'cf_campiagn_success_redirection_url_content',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display CrowdFunding Type', 'galaxyfunder'),
                    'desc' => __('Please Select the Option to Show or Hide CrowdFunding Type in a Backend', 'galaxyfunder'),
                    'id' => 'cf_show_hide_crowdfunding_type',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_crowdfunding_type',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Crowdfunding Type', 'galaxyfunder'),
                    'desc' => __('Please Select a type of crowdfunding', 'galaxyfunder'),
                    'id' => 'cf_crowdfunding_type_selection',
                    'css' => 'min-width:150px;',
                    'std' => '',
                    'default' => '',
                    'newids' => 'cf_crowdfunding_type_selection',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                        '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Products for crowdfunding Campaign', 'galaxyfunder'),
                    'desc' => __('Please Select whether to display All Products or selected products in frontend Form', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_frontend_product_selection_type',
                    'css' => '',
                    'std' => '1',
                    'type' => 'radio',
                    'options' => array('1' => __('All Products', 'galaxyfunder'), '2' => __('Selected Products', 'galaxyfunder')),
                    'newids' => 'cf_frontend_product_selection_type',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Products for frontend Submission', 'galaxyfunder'),
                    'desc' => __('Please select the products that you wish to be displayed for product purchase in the frontend form', 'galaxyfunder'),
                    'id' => 'cf_frontend_selected_products',
                    'css' => 'min-width:150px;',
                    'newids' => 'cf_frontend_selected_products',
                    'type' => 'selectedproducts_campaign',
                ),
                array(
                    'name' => __('Categories for crowdfunding Campaign', 'galaxyfunder'),
                    'desc' => __('Please Select whether to display All Categories or selected categories in frontend Form', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_frontend_categories_selection_type',
                    'css' => '',
                    'std' => '1',
                    'type' => 'radio',
                    'options' => array('1' => __('All Categories', 'galaxyfunder'), '2' => __('Selected Categories', 'galaxyfunder')),
                    'newids' => 'cf_frontend_categories_selection_type',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Categories For Front End Submission', 'galaxyfunder'),
                    'desc' => __('Please Select the categories that you wish your campaign must be displayed', 'galaxyfunder'),
                    'id' => 'cf_frontend_selected_categories',
                    'css' => 'min-width:250px;',
                    'newids' => 'cf_frontend_selected_categories',
                    'type' => 'multiselect',
                    'std' => '',
                    'options' => $selectedcategories,
                ),
                array(
                    'name' => __('Campaign Purpose', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Purpose Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_purpose_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Campagin Purpose',
                    'type' => 'text',
                    'newids' => 'cf_campaign_purpose_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Title Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_submission_camp_title_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Campaign Title',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_title_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Choose Products', 'galaxyfunder'),
                    'desc' => __('Please Enter Choose Products Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_product_purchase_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Choose Products',
                    'type' => 'text',
                    'newids' => 'cf_campaign_product_purchase_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign End Method Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign End Method Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_end_method_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Campaign End Method',
                    'type' => 'text',
                    'newids' => 'cf_campaign_end_method_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Title input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Title input Paceholder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_submission_camp_title_placeholder',
                    'css' => 'min-width:550px;',
                    'std' => 'Enter the Campaign Title',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_title_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Campaign End Method Field in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select the Option to Show or Hide Campaign End Method in Submission form ', 'galaxyfunder'),
                    'id' => 'cf_show_hide_campaign_end_selection_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_campaign_end_selection_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Default Campaign End Method for Fundraising By CrowdFunding', 'galaxyfunder'),
                    'desc' => __('Please Select the Campaign End Method for Fundraising By CrowdFunding', 'galaxyfunder'),
                    'id' => 'cf_campaign_end_method_for_frbcf',
                    'css' => 'min-width:150px;',
                    'std' => '3',
                    'default' => '3',
                    'newids' => 'cf_campaign_end_method_for_frbcf',
                    'type' => 'select',
                    'options' => array(
                        '3' => __('Target Goal', 'galaxyfunder'),
                        '1' => __('Target Date', 'galaxyfunder'),
                        '4' => __('Target Goal/Target Date', 'galaxyfunder'),
                        '5' => __('Target Quantity', 'galaxyfunder'),
                        '2' => __('Campaign Never Ends', 'galaxyfunder')
                    ),
                ),
                array(
                    'name' => __('Default Campaign End Method for Product Purchase by CrowdFunding', 'galaxyfunder'),
                    'desc' => __('Please Select the Campaign End Method for Product Purchase by CrowdFunding', 'galaxyfunder'),
                    'id' => 'cf_campaign_end_method_for_ppbcf',
                    'css' => 'min-width:150px;',
                    'std' => '3',
                    'default' => '3',
                    'newids' => 'cf_campaign_end_method_for_ppbcf',
                    'type' => 'select',
                    'options' => array(
                        '3' => __('Target Goal', 'galaxyfunder'),
                        '1' => __('Target Date', 'galaxyfunder'),
                        '4' => __('Target Goal/Target Date', 'galaxyfunder'),
                        '2' => __('Campaign Never Ends', 'galaxyfunder')
                    ),
                ),
                array(
                    'name' => __('Campaign Duration Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_submission_camp_duration_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Campaign Duration in Days',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_duration_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Woocommerce Currency Symbol For Single Product Page', 'galaxyfunder'),
                    'desc' => __('Please Enter Woocommerce Currency Symbol For Single Product Page', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_singleproduct_page_currency_symbol',
                    'css' => 'min-width:550px;',
                    'std' => 'woocommerce_currency_symbol',
                    'type' => 'text',
                    'newids' => 'cf_singleproduct_page_currency_symbol',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Duration input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Title Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_submission_camp_duration_placeholder',
                    'css' => 'min-width:550px;',
                    'std' => 'Enter Campaign Duration in Number of Days',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_duration_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Recommended Contribution Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Recommended Contribution Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_recommendedprice_label',
                    'std' => 'Recommended Contribution',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_recommendedprice_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Recommended Contribution input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Recommended Contribution input Placeholder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_recommendedprice_placeholder',
                    'std' => 'Enter Recommended Contribution to show at campaign',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_recommendedprice_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Hide Recommeded Contribution', 'galaxyfunder'),
                    'desc' => __('You can Show or Hide Recommended Contribution', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_recommendedprice_showhide',
                    'std' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'cf_submission_camp_recommendedprice_showhide',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Maximum Contribution Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_maximumprice_label',
                    'std' => 'Maximum Contribution',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_maximumprice_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Maximum Contribution input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Maximum Contribution input Placehoder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_maximumprice_placeholder',
                    'std' => 'Enter Maximum Contribution to show at campaign page',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_maximumprice_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Hide Maximum Contribution', 'galaxyfunder'),
                    'desc' => __('You can Show or Hide Maximum Contribution', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_maximumprice_showhide',
                    'std' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'cf_submission_camp_maximumprice_showhide',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Maximum Contribution Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_minimumprice_label',
                    'std' => 'Minimum Contribution',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_minimumprice_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Minimum Contribution input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Maximum Contribution input Placeholder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_minimumprice_placeholder',
                    'std' => 'Enter Minimum Contribution to show at campaign',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_minimumprice_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Hide Minimum Contribution', 'galaxyfunder'),
                    'desc' => __('You can Show or Hide Minimum Contribution', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_minimumprice_showhide',
                    'std' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'cf_submission_camp_minimumprice_showhide',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Target Goal When Product Purchase by Crowdfunding is chosen', 'galaxyfunder'),
                    'desc' => __('Please Select the Option to Show or Hide Target Goal in Submission form when Product Purchase by Crowdfunding is chosen', 'galaxyfunder'),
                    'id' => 'cf_show_hide_target_product_purchase_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_target_product_purchase_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Target Goal Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Target Goal Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_targetprice_label',
                    'std' => 'Goal',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_targetprice_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('New Target Goal Label', 'galaxyfunder'),
                    'desc' => __('Please Enter New Target Goal Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_targetprice_label_new',
                    'std' => 'New Target Goal',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_targetprice_label_new',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Target Goal input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Target Goal input Placeholder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_targetprice_placeholder',
                    'std' => 'Enter Target Goal to show at campaign',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_targetprice_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Target Quantity Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Target Quantity Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_targetquantity_label',
                    'std' => 'Quantity',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_targetquantity_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Target Quantity input Placeholder', 'galaxyfunder'),
                    'desc' => __('Please Enter Target Quantity input Placeholder for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_targetquantity_placeholder',
                    'std' => 'Enter Target Quantity to show at campaign',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_targetquantity_placeholder',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Product Price Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Product Price Label for campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_productprice_label',
                    'std' => 'Product Price',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_productprice_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Description Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Description Label at campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_description_label',
                    'std' => 'Description',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_description_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Social Promotion label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Social Promotion label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_social_sharing_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Enable Social Promotion for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_social_sharing_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Social Promotion Facebook label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Social Promotion Facebook label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_social_promotion_facebook_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Enable Social Promotion through Facebook for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_social_promotion_facebook_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Social Promotion Twitter label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Social Promotion Twitter label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_social_promotion_twitter_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Enable Social Promotion through Twitter for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_social_promotion_twitter_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Social Promotion Google label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Social Promotion Google label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_social_promotion_google_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Enable Social Promotion through Google for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_social_promotion_google_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Show contributor label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Show contributor label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_show_contributor_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Show Contributor Table for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_show_contributor_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Mark contributor as Anonymous label', 'galaxyfunder'),
                    'desc' => __('Please Enter Campaign Mark contributor as Anonymous label', 'galaxyfunder'),
                    'tip' => '',
                    'id' => 'cf_campaign_mark_contributor_as_anonymous_label',
                    'css' => 'min-width:550px;',
                    'std' => 'Mark Contributors as Anonymous for this Campaign',
                    'type' => 'text',
                    'newids' => 'cf_campaign_mark_contributor_as_anonymous_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Display Add Perk Rule Button in Frontend Submission form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide Add Perk Rule Button in frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_add_perk_button_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_add_perk_button_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Display "Estimated Delivery On" field in Frontend Submission form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide "Estimated Delivery On" field in frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_estimated_del_field_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_estimated_del_field_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                ),
                array(
                    'name' => __('Enable Confirmation Message for Removing Perk Rule', 'galaxyfunder'),
                    'desc' => __('By Enabling this Option ask you to Confirm Before Removing the Perk', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_enable_remove_perk_rule',
                    'std' => 'yes',
                    'type' => 'checkbox',
                    'newids' => 'cf_enable_remove_perk_rule',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Remove Perk Confirmation Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Remove Perk Rule Confirmation Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_remove_perk_confirmation_message',
                    'std' => 'Are you sure want to do this ?',
                    'type' => 'textarea',
                    'newids' => 'cf_custom_remove_perk_confirmation_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Add Perk Rule Button Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Add Perk Rule Button Caption for Front End Campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_add_perk_rule_caption',
                    'std' => 'Add Perk Rule',
                    'type' => 'text',
                    'newids' => 'cf_add_perk_rule_caption',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Remove Perk Rule Button Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Remove Perk Rule Button Caption for Front End Campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px',
                    'id' => 'cf_remove_perk_rule_caption',
                    'std' => 'Remove Perk Rule',
                    'type' => 'text',
                    'newids' => 'cf_remove_perk_rule_caption',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Perk Name Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Perk Name Label for Front End Submission Form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_perk_name_label',
                    'std' => 'Name of Perk',
                    'type' => 'text',
                    'newids' => 'cf_custom_perk_name_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Perk Amount Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Perk Amount Label for Front End Submission Form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_perk_amount_label',
                    'std' => 'Perk Amount',
                    'type' => 'text',
                    'newids' => 'cf_custom_perk_amount_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Perk Description Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Perk Description Label for Front End Submission Form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_perk_description_label',
                    'std' => 'Description',
                    'type' => 'text',
                    'newids' => 'cf_custom_perk_description_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Perk Claim Max Count Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Perk Claim Max Count for Front End Submission Form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_perk_claim_count_label',
                    'std' => 'Perk Claim Max Count',
                    'type' => 'text',
                    'newids' => 'cf_custom_perk_claim_count_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Perk Estimated Delivery Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Perk Delivery Label for Front End', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_custom_perk_delivery_label',
                    'std' => 'Estimated Delivery On',
                    'type' => 'text',
                    'newids' => 'cf_custom_perk_delivery_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Featured Image Label', 'galaxyfunder'),
                    'desc' => __('Please Enter Description Label at campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_featuredimage_label',
                    'std' => 'Featured Image',
                    'type' => 'text',
                    'newids' => 'cf_submission_camp_featuredimage_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('I Agree Label', 'galaxyfunder'),
                    'desc' => __('Please Enter I Agree Label at campaign Submission', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_camp_i_agree_label',
                    'std' => 'I Agree',
                    'type' => 'textarea',
                    'newids' => 'cf_submission_camp_i_agree_label',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign I Agree Error Message', 'galaxyfunder'),
                    'desc' => __('Please Enter I Agree Error Message which will display it in frontend', 'galaxyfunder'),
                    'tip' => '',
                    'id' => '_cf_i_agree_checkbox_error_message',
                    'css' => '',
                    'std' => 'Please Check this before submit',
                    'type' => 'textarea',
                    'newids' => '_cf_i_agree_checkbox_error_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_submission'),
                array(
                    'name' => __('Category Selection Frontend', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_selection_category',
                ),
                array(
                    'name' => __('Front End Submission Empty Checkbox', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Empty Field Error Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_empty_checkbox_error_message',
                    'std' => 'Please Select the CheckBox to Continue',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_empty_checkbox_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide Category Selection in Frontend Form', 'galaxyfunder'),
                    'desc' => __('Select Show/Hide Category Selection Option in Frontend Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_category_selection_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_category_selection_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_selection_category'),
                array(
                    'name' => __('Billing and Shipping Details Settings', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_shipping_details'
                ),
                array(
                    'name' => __('Show/Hide Billing Details in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide Billing Details frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_billing_details_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_billing_details_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Show/Hide Shipping Details in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide Shipping Details frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_shipping_details_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_shipping_details_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_shipping_details'),
                array(
                    'name' => __('PayPal Email Settings', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_paypal_email_settings'
                ),
                array(
                    'name' => __('Show/Hide PayPal Email Address Field in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide PayPal Email Address Field frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_paypal_email_id_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_paypal_email_id_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_paypal_email_settings'),
                array(
                    'name' => __('Social Promotion Settings', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_social_promotion_settings'
                ),
                array(
                    'name' => __('Show/Hide Social Promotion Field in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide Social Promotion Address Field frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_social_promotion_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_social_promotion_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_social_promotion_settings'),
                array(
                    'name' => __('Contributor Table Settings', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_contributor_table_settings'
                ),
                array(
                    'name' => __('Show/Hide Contributor Table Settings Field in Frontend Submission Form', 'galaxyfunder'),
                    'desc' => __('Please Select Whether to show or hide Contributor Table Settings Field frontend Submission Form', 'galaxyfunder'),
                    'id' => 'cf_show_hide_contributor_table_settings_frontend',
                    'css' => 'min-width:150px;',
                    'std' => '1',
                    'default' => '1',
                    'newids' => 'cf_show_hide_contributor_table_settings_frontend',
                    'type' => 'select',
                    'options' => array(
                        '1' => __('Show', 'galaxyfunder'),
                        '2' => __('Hide', 'galaxyfunder'),
                    ),
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_contributor_table_settings'),
                array(
                    'name' => __('FrontEnd Campaign Form Submission Message', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_submission_messages'
                ),
                array(
                    'name' => __('Front End Submission Status Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Status Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_status_message',
                    'std' => 'Submitting',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_status_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Front End Submission Response Success Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Response Success Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_response_message',
                    'std' => 'Campaign Submitted',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_response_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Front End Submission Response Error Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Response Error Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_response_error_message',
                    'std' => 'Something went wrong please try later',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_response_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Front End Submission Number Field Error Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Number Field Error Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_number_field_error_message',
                    'std' => 'Use Number',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_number_field_error_message',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Front End Submission Empty Field Message', 'galaxyfunder'),
                    'desc' => __('Please Enter Front End Submission Empty Field Error Message', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_frontend_submission_empty_field_error_message',
                    'std' => 'Please Check Above Error',
                    'type' => 'text',
                    'newids' => 'cf_frontend_submission_empty_field_error_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_submission_messages'),
                array(
                    'name' => __('FrontEnd Campaign Submission Page', 'galaxyfunder'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => '_cf_campaign_submission_advanced'
                ),
                array(
                    'name' => __('Default CSS (Non Editable)', 'galaxyfunder'),
                    'desc' => __('These are element IDs for the Frontend Campaign Submission form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                    'id' => 'cf_submission_camp_default_css',
                    'std' => '
#cf_campaign_title{
}
#cf_campaign_duration{
}
#cf_campaign_target_value{
}
#cf_campaign_min_price{
}
#cf_campaign_max_price{
}
#cf_campaign_rec_price{
}',
                    'type' => 'textarea',
                    'newids' => 'cf_submission_camp_default_css',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Custom CSS', 'galaxyfunder'),
                    'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                    'id' => 'cf_submission_camp_custom_css',
                    'std' => '
#cf_campaign_title{
}
#cf_campaign_duration{
}
#cf_campaign_target_value{
}
#cf_campaign_min_price{
}
#cf_campaign_max_price{
}
#cf_campaign_rec_price{
}',
                    'type' => 'textarea',
                    'newids' => 'cf_submission_camp_custom_css',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('URL to Redirect for Guest', 'galaxyfunder'),
                    'desc' => __('Please Enter URL to Redirect if a guest tries this page', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_submission_reset',
                    'std' => '',
                    'type' => 'submit',
                    'newids' => 'cf_submission_reset',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Limit Number of Campaigns per User', 'galaxyfunder'),
                    'desc' => __('Please check the checkbox if you wish to limit the number of campaigns created by a user', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_campaign_limit',
                    'std' => 'no',
                    'type' => 'checkbox',
                    'newids' => 'cf_campaign_limit',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Number of campaigns to be allowed per User', 'galaxyfunder'),
                    'desc' => __('Please Enter the Number of campaigns to be allowed per user', 'galaxyfunder'),
                    'tip' => '',
//                'css' => 'min-width:550px;',
                    'id' => 'cf_campaign_limit_value',
                    'std' => '5',
                    'type' => 'number',
                    'newids' => 'cf_campaign_limit_value',
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Campaign Limit exceeded Message', 'galaxyfunder'),
                    'desc' => __('Please Enter a message to display when the campaign limit exceeds', 'galaxyfunder'),
                    'tip' => '',
                    'css' => 'min-width:550px;',
                    'id' => 'cf_campaign_exceeded_message',
                    'std' => 'You cannot create a new campaign ',
                    'type' => 'text',
                    'newids' => 'cf_campaign_exceeded_message',
                    'desc_tip' => true,
                ),
                array('type' => 'sectionend', 'id' => '_cf_campaign_submission_advanced'),
            ));
        }

        /*
         * default values for frontend submission
         */

        public static function cf_frontend_default_values() {
            global $woocommerce;
            foreach (CFFrontendSubmission::cf_admin_front_end() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    if (get_option($setting['newids']) == FALSE) {

                        add_option($setting['newids'], $setting['std']);
                    }
                }
            }
        }

        public static function cf_frontend_reset_values() {
            global $woocommerce;
// var_dump("google google");
            if (isset($_POST['reset'])) {
                if ($_POST['reset_hidden'] == 'frontend') {
                    echo FP_GF_Common_Functions::reset_common_function(CFFrontendSubmission::cf_admin_front_end());
                }
            }

            if (isset($_POST['resetall'])) {
                echo FP_GF_Common_Functions::reset_common_function(CFFrontendSubmission::cf_admin_front_end());
            }
        }

        public static function cf_admin_front_end_settings() {
            woocommerce_admin_fields(CFFrontendSubmission::cf_admin_front_end());
            $cf_campaign_limitcheck = get_option('cf_campaign_limit');
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {

                    if ('<?php echo $cf_campaign_limitcheck; ?>' == 'no') {
                        jQuery('#cf_campaign_limit_value').parent().parent().hide();
                        jQuery('#cf_campaign_exceeded_message').parent().parent().hide();
                    }
                    if (jQuery('#cf_campiagn_success_redirection_option').val() === '1') {
                        jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().hide();
                    } else {
                        jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().show();
                    }
                    jQuery('#cf_campaign_limit').click(function () {
                        jQuery('#cf_campaign_limit_value').parent().parent().toggle();
                        jQuery('#cf_campaign_exceeded_message').parent().parent().toggle();
                    });
                    jQuery('#cf_campiagn_success_redirection_option').change(function () {
                        jQuery('#cf_campiagn_success_redirection_url_content').parent().parent().toggle();
                    });
                });</script>
            <?php
        }

        public static function cf_admin_front_end_update_settings() {
            woocommerce_update_options(CFFrontendSubmission::cf_admin_front_end());
        }

        public static function getcountofactivecampaigns($userid) {
            $mainuserid = $userid == '' ? get_current_user_id() : $userid;
            $dataofgetposts = FP_GF_Common_Functions::common_function_for_get_post($userid);
            $listofactivecampaigns = array();
            if (isset($dataofgetposts)) {
                foreach ($dataofgetposts as $eachposts) {
                    $mainproduct = new WC_Product($eachposts->ID);
                    if ($mainproduct->is_in_stock()) {
                        if (get_post_meta($eachposts->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                            $listofactivecampaigns[] = $eachposts->ID;
                        }
                    }
                }
            }
            return count($listofactivecampaigns);
        }

        public static function selected_products_for_crowdfunding() {
            global $woocommerce;
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="cf_frontend_selected_products"><?php _e('Select Particular Products', 'galaxyfunder'); ?></label>
                </th>
                <?php
                if ((float) $woocommerce->version >= (float) ('3.0.0')) {

                    if (is_admin()) {
                        $width = '50 %';
                    } else {
                        $width = '100 %';
                    }
                    ?>
                <td class="forminp forminp-select">
                <select  class="wc-product-search" multiple="multiple" style="width:<?php echo $width; ?>;" id="cf_frontend_selected_products" name="cf_frontend_selected_products[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>">
                    <?php
                    $selected_product_ids = get_option('cf_frontend_selected_products');
                    if (is_admin() && is_array($selected_product_ids) && !empty($selected_product_ids)) {
                        foreach ($selected_product_ids as $product_id) {
                            if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) != 'yes') {
                            $product = wc_get_product($product_id);
                                if (is_object($product)) {
                                    echo '<option value="' . esc_attr($product_id) . '"' . selected(1, 1) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                                }
                            }
                        }
                    }
                    ?>
                </select>
                </td>
                </tr>
            <?php } else if ((float) $woocommerce->version > (float) ('2.2.0')) {
                ?>
                
                    <td class="forminp forminp-select">
                        <input type="hidden" class="wc-product-search" style="width: 100%;" id="cf_frontend_selected_products" name="cf_frontend_selected_products" data-placeholder="<?php _e('Search for a product&hellip;', 'galaxyfunder'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                $json_ids = array();
                if (get_option('cf_frontend_selected_products') != "") {
                    $list_of_produts = get_option('cf_frontend_selected_products');
                    if (!is_array($list_of_produts)) {
                        $list_of_produts = explode(',', $list_of_produts);
                        $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('cf_frontend_selected_products'))));
                    } else {
                        $product_ids = $list_of_produts;
                    }
                    if ($product_ids != NULL) {
                            foreach ($product_ids as $product_id) {
                                if (isset($product_id)) {
                                    $product = wc_get_product($product_id);
                                    if (is_object($product)) {
                                        $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                    }
                                }
                            } echo esc_attr(json_encode($json_ids));
                        }
                }
                ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />
                    </td>
                </tr>
                <?php
            } else {
                ?>
               
                    <td class="forminp forminp-select">
                        <select multiple name="cf_frontend_selected_products" style='width:550px;' id='cf_frontend_selected_products' class="cf_frontend_selected_products">
                            <?php
                            if ((array) get_option('cf_frontend_selected_products') != "") {
                                $list_of_produts = (array) get_option('cf_frontend_selected_products');
                                foreach ($list_of_produts as $cf_free_id) {
                                    echo '<option value="' . $cf_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $cf_free_id . ' &ndash; ' . get_the_title($cf_free_id);
                                }
                            } else {
                                ?>
                                <option value=""></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <?php
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var product_type = jQuery('input:radio[name=cf_frontend_product_selection_type]:checked').val();
                    if (product_type == '1') {
                        jQuery("#cf_frontend_selected_products").parent().parent().hide();
                    } else {
                        jQuery("#cf_frontend_selected_products").parent().parent().show();
                    }
                    jQuery('input:radio[name=cf_frontend_product_selection_type]').change(function () {
                        jQuery("#cf_frontend_selected_products").parent().parent().toggle();
                    });
                });</script>
            <?php
        }

        public static function selected_categories_for_crowdfunding() {
            if (isset($_GET['tab'])) {
                if ($_GET['tab'] == 'frontend') {
                    global $woocommerce;
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {

                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                jQuery("#cf_frontend_selected_categories").chosen();
                    <?php } else { ?>
                                jQuery("#cf_frontend_selected_categories").select2();
                    <?php } ?>
                        });
                        jQuery(document).ready(function () {

                            var product_type = jQuery('input:radio[name=cf_frontend_categories_selection_type]:checked').val();
                            if (product_type == '1') {

                                jQuery("#cf_frontend_selected_categories").parent().parent().hide();
                            } else {
                                jQuery("#cf_frontend_selected_categories").parent().parent().show();
                            }
                            jQuery('input:radio[name=cf_frontend_categories_selection_type]').change(function () {
                                jQuery("#cf_frontend_selected_categories").parent().parent().toggle();
                            });
                        });

                    </script>
                    <?php
                }
            }
        }

        public static function save_selected_products_for_crowdfunding() {
            update_option('cf_frontend_selected_products', $_POST['cf_frontend_selected_products']);
        }

    }

//Get and Set Product_price
//add_action('wp_ajax_ajax_get_product_price', array('CFFrontendSubmission', 'ajax_get_product_price_function'));

    CFFrontendSubmission::init();
}