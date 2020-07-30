<?php
if (!class_exists('CFSingleProductAdmin')) {
class CFSingleProductAdmin {
    
     public static function init() {
        //Single Product Admin settings
        add_action('init', array(__CLASS__, 'crowdfunding_singleproduct_default_settings'));
        add_action('woocommerce_cf_settings_tabs_crowdfunding_singleproduct', array(__CLASS__, 'crowdfunding_process_singleproduct_admin_settings'));
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_singleproduct_tab'), 100);
        add_action('woocommerce_update_options_crowdfunding_singleproduct', array(__CLASS__, 'crowdfunding_process_singleproduct_update_settings'));
        add_action('admin_init', array(__CLASS__, 'cf_singleproduct_reset_values'), 2);
        add_action('admin_head', array(__CLASS__, 'show_hide_options_for_selected_styles'));
     }

    public static function crowdfunding_admin_singleproduct_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_singleproduct'] = __('Single Product Page', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_singleproduct_admin_options() {
        return apply_filters('woocommerce_crowdfunding_singleproduct_settings', array(
            array(
                'name' => __('[displayperk]- Shortcode for displaying Perk Table', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_perk_shortcode_text'
            ),
            array(
                'name' => __('Contribution Table Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_donation_text'
            ),
            array(
                'name' => __('Contribution Table Show/Hide', 'galaxyfunder'),
                'desc' => __('This Controls the Contribution Table Show or Hide', 'galaxyfunder'),
                'id' => 'cf_display_donation_table',
                'css' => 'min-width:150px;',
                'std' => 'on', // WooCommerce < 2.0
                'default' => 'on', // WooCommerce >= 2.0
                'newids' => 'cf_display_donation_table',
                'type' => 'select',
                'options' => array(
                    'on' => __('Show', 'woocommerce'),
                    'off' => __('Hide', 'woocommerce'),
                ),
            ),
            array(
                'name' => __('Search Box Show/Hide', 'galaxyfunder'),
                'desc' => __('This Controls the Search Box Show or Hide', 'galaxyfunder'),
                'id' => 'cf_display_search_box',
                'css' => 'min-width:150px;',
                'std' => 'on', // WooCommerce < 2.0
                'default' => 'on', // WooCommerce >= 2.0
                'newids' => 'cf_display_search_box',
                'type' => 'select',
                'options' => array(
                    'on' => __('Show', 'woocommerce'),
                    'off' => __('Hide', 'woocommerce'),
                ),
            ),
            array(
                'name' => __('Page Size Option Show/Hide', 'galaxyfunder'),
                'desc' => __('This Controls the Page Size Option Show or Hide', 'galaxyfunder'),
                'id' => 'cf_display_page_size',
                'css' => 'min-width:150px;',
                'std' => 'on', // WooCommerce < 2.0
                'default' => 'on', // WooCommerce >= 2.0
                'newids' => 'cf_display_page_size',
                'type' => 'select',
                'options' => array(
                    'on' => __('Show', 'woocommerce'),
                    'off' => __('Hide', 'woocommerce'),
                ),
            ),
            array(
                'name' => __('S.No Label', 'galaxyfunder'),
                'desc' => __('Change S.No Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_serial_number_label',
                'css' => 'min-width:550px;',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'cf_serial_number_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('S.No Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Serial Number Column in Front End', 'galaxyfunder'),
                'id' => 'cf_serial_number_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_serial_number_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Contributor Image', 'galaxyfunder'),
                'desc' => __('Change Contributor Image in Single Product Page by Your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_contributor_image_label',
                'css' => 'min-width:550px',
                'std' => 'Contributor Image',
                'type' => 'text',
                'newids' => 'cf_contributor_image_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contributor Image Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Contributor Image in Front End', 'galaxyfunder'),
                'id' => 'cf_contributor_image_show_hide',
                'css' => 'min-width:150px',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_contributor_image_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Contributor Image Size ', 'galaxyfunder'),
                'desc_tip' => __('Change Contributor Image size in Single Product Page by Your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_contributor_image__size_label',
                'css' => 'min-width:150px',
                'std' => '50',
                'type' => 'text',
                'newids' => 'cf_contributor_image__size_label',
                'desc' => __('PX','galaxyfunder'),
            ),
            array(
                'name' => __('Contributor Label', 'galaxyfunder'),
                'desc' => __('Change Contributor Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_contributor_label',
                'std' => 'Contributor',
                'type' => 'text',
                'newids' => 'cf_contributor_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contributor Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Contributor Name Column in Front End', 'galaxyfunder'),
                'id' => 'cf_contributor_name_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_contributor_name_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Contributor Email ID Label', 'galaxyfunder'),
                'desc' => __('Change Contributor Email ID Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_contributor_email_label',
                'std' => 'Email ID',
                'type' => 'text',
                'newids' => 'cf_contributor_email_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contributor Email ID Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Contributor Email ID Column in Front End', 'galaxyfunder'),
                'id' => 'cf_contributor_email_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'cf_contributor_email_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Contribution Label', 'galaxyfunder'),
                'desc' => __('Change Contribution Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_donation_label',
                'std' => 'Contribution',
                'type' => 'text',
                'newids' => 'cf_donation_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contribution Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Contribution Column in Front End', 'galaxyfunder'),
                'id' => 'cf_contribution_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_contribution_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Funder\'s Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_funder_label',
                'std' => 'Funders',
                'type' => 'text',
                'newids' => 'cf_funder_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Name Label', 'galaxyfunder'),
                'desc' => __('Change Perk Name Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_perk_name_label',
                'std' => 'Perk Name',
                'type' => 'text',
                'newids' => 'cf_perk_name_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Name Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Perk Name Column in Front End', 'galaxyfunder'),
                'id' => 'cf_perk_name_column_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_perk_name_column_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Perk Amount Label', 'galaxyfunder'),
                'desc' => __('Change Perk Amount Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_perk_amount_label',
                'std' => 'Perk Amount',
                'type' => 'text',
                'newids' => 'cf_perk_amount_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Amount Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Perk Amount Column in Front End', 'galaxyfunder'),
                'id' => 'cf_perk_amount_column_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_perk_amount_column_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Perk Quantity Label', 'galaxyfunder'),
                'desc' => __('Change Perk Quantity Column in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_perk_quantity_label',
                'std' => 'Perk Quantity',
                'type' => 'text',
                'newids' => 'cf_perk_quantity_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Quantity Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Perk Quantity Column in Front End Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_perk_quantity_column_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'newids' => 'cf_perk_quantity_column_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Date Label', 'galaxyfunder'),
                'desc' => __('Change Date Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_date_label',
                'std' => 'Date',
                'type' => 'text',
                'newids' => 'cf_date_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Date Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Date Column in Front End', 'galaxyfunder'),
                'id' => 'cf_date_column_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_date_column_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Order Notes Column Label', 'galaxyfunder'),
                'desc' => __('Change Order Notes Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_order_notes_label',
                'std' => 'Order Notes',
                'type' => 'text',
                'newids' => 'cf_order_notes_label',
                'desc_tip' => true,
            ),
           
            array(
                'name' => __('Order Notes Column Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Order Notes Column in Front End', 'galaxyfunder'),
                'id' => 'cf_order_notes_column_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'cf_order_notes_column_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
             array(
                'name' => __('Label for Predefined Buttons Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Caption for Predefined Buttons', 'galaxyfunder'),
                'id' => 'predefined_button_caption_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'predefined_button_caption_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
              array(
                'name' => __('Predefined Buttons Label', 'galaxyfunder'),
                'desc' => __('Change Predefined Buttons Caption in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'predefined_button_caption',
                'std' => 'Contribute from any of the Predefined Amounts',
                'type' => 'text',
                'newids' => 'predefined_button_caption',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Amount You Wish to Contribute Label Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Label for Amount You Wish to Contribute', 'galaxyfunder'),
                'id' => 'amount_you_wish_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'amount_you_wish_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
              array(
                'name' => __('Predefined Buttons & Editable Texbox Label Line1', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'label_for_button_line1',
                'std' => 'or',
                'type' => 'text',
                'newids' => 'label_for_button_line1',
                'desc_tip' => true,
            ),
            
              array(
                'name' => __('Predefined Buttons & Editable Texbox Label Line2', 'galaxyfunder'),
                'desc' => __('Change the Amount you Wish to Contribute Label in Single Product Page by your Custom Words', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'label_for_button_line2',
                'std' => 'Contributethe Amount You Wish',
                'type' => 'text',
                'newids' => 'label_for_button_line2',
                'desc_tip' => true,
            ),
            
            
            array(
                'name' => __('Contribution Table Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_donation_table_position',
                'options' => array('1' => __('Before Single Product','galaxyfunder'), '2' => __('After Single Product','galaxyfunder'), '3' => __('After Single Product Summary','galaxyfunder')),
                'class' => 'cf_donation_table_position',
                'std' => '3',
                'newids' => 'cf_donation_table_position',
            ),
            array('type' => 'sectionend', 'id' => '_donation_text'),

            array(
                'name' => __('Perk Table Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_perk_table_text'
            ),
            array(
                'name' => __('Perk Table Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Perk Table in Front End', 'galaxyfunder'),
                'id' => 'cf_perk_table_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_perk_table_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Perk Url Type', 'galaxyfunder'),
               
                'id' => 'cf_perk_url_type',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_perk_url_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Perk Name', 'galaxyfunder'),
                    '2' => __('Perk Product', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Perk Product Image Display Selection', 'galaxyfunder'),
                
                'id' => 'cf_perk_url__image_type',
                'std' => '1',
                'newids' => 'cf_perk_url__image_type',
                'class' => 'cf_perk_url__image_type',
                'type' => 'radio',
                'options' => array(
                    '1' => __('Perk Product Name', 'galaxyfunder'),
                    '2' => __('Perk Product Image', 'galaxyfunder'),
                    '3' => __('Perk Product Image and Name', 'galaxyfunder'),
                ),
            ),
            array(
                'name'=>__('Perk Product Image Display Size','galaxyfunder'),
                'desc'=>__('px','galaxyfunder'),
                'desc_tip'=>__('Change Contributor Image size in Single Product Page by Your Custom Words','galaxyfunder'),
                'id'=>'cf_perk_url__image_type_size',
                'std'=>50,
                'newids'=>'cf_perk_url__image_type_size',
                'type'=>'text',
                
            ),
            array(
                'name' => __('Perk Heading Label', 'galaxyfunder'),
                'desc' => __('Change the Head Label in the Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_perk_head_label',
                'std' => 'Which perk would you like?',
                'type' => 'text',
                'newids' => 'cf_perk_head_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('No Perk Label', 'galaxyfunder'),
                'desc' => __('Change the No Perk Label in Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_no_perk_label',
                'std' => 'No perk, I just want to contribute.',
                'type' => 'text',
                'newids' => 'cf_no_perk_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Out of Claimed Label', 'galaxyfunder'),
                'desc' => __('Change the Out of Claimed Label in Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_out_of_claimed_label',
                'std' => 'claimed out of',
                'type' => 'text',
                'newids' => 'cf_out_of_claimed_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Out of Claimed Label for Unlimited Perks', 'galaxyfunder'),
                'desc' => __('Change the Out of Claimed Label for Unlimited Perks in Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_out_of_claimed_unlimited_label',
                'std' => 'Perks claimed',
                'type' => 'text',
                'newids' => 'cf_out_of_claimed_unlimited_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Estimated Delivery Label', 'galaxyfunder'),
                'desc' => __('Change the Estimated Delivery Label in Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_estimated_delivery_label',
                'std' => 'Estimated Delivery:',
                'type' => 'text',
                'newids' => 'cf_estimated_delivery_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Table Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_perk_table_position',
                'options' => array('1' => __('Before Single Product','galaxyfunder'), '2' => __('After Single Product','galaxyfunder'), '3' => __('After Single Product Summary','galaxyfunder')),
                'class' => 'cf_perk_table_position',
                'std' => '3',
                'newids' => 'cf_perk_table_position',
            ),
            array(
                'name' => __('Perk Selection Type', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_perk_selection_type',
                'options' => array('1' => __('Single Selection', 'galaxyfunder'), '2' => __('Multiple Selection', 'galaxyfunder')),
                'class' => 'cf_perk_selection_type',
                'std' => '1',
                'newids' => 'cf_perk_selection_type',
            ),
            array(
                'name' => __('Perk Quantity Selection', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_perk_quantity_selection',
                'options' => array('1' => __('Enable', 'galaxyfunder'), '2' => __('Disable', 'galaxyfunder')),
                'class' => 'cf_perk_quantity_selection',
                'std' => '2',
                'newids' => 'cf_perk_quantity_selection',
            ),
            array(
                'name' => __('Perk Quantity Display Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_perk_quantity_display_selection',
                'options' => array('1' => __('Top Left', 'galaxyfunder'), '2' => __('Top Right', 'galaxyfunder'), '3' => __('Bottom Left', 'galaxyfunder'), '4' => __('Bottom Right', 'galaxyfunder')),
                'class' => 'cf_perk_quantity_display_selection',
                'std' => '1',
                'newids' => 'cf_perk_quantity_display_selection',
            ),
            array('type' => 'sectionend', 'id' => '_cf_perk_table_text'),
            array(
                'name' => __('Author Info Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_authorinfo_settings'
            ),
            array(
                'name' => __('Author Info Heading', 'galaxyfunder'),
                'desc' => __('Customize the Author Info Heading', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_info_heading',
                'std' => 'Campaign Creator',
                'type' => 'text',
                'newids' => 'cf_author_info_heading',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Author Info Table Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Info Entire Table', 'galaxyfunder'),
                'id' => 'cf_author_table_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_author_table_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Author Info Table Position', 'galaxyfunder'),
                'type' => 'radio',
                'desc' => '',
                'id' => 'cf_author_info_table_position',
                'options' => array('1' => __('Before Single Product','galaxyfunder'), '2' => __('After Single Product','galaxyfunder'), '3' => __('After Single Product Summary','galaxyfunder')),
                'class' => 'cf_author_info_table_position',
                'std' => '3',
                'newids' => 'cf_author_info_table_position',
            ),
            array(
                'name' => __('Display Profile when BuddyPress is Active', 'galaxyfunder'),
                'desc' => __('Show or Hide the Profile Link of BuddyPress when it is in active', 'galaxyfunder'),
                'id' => 'cf_check_buddypress_link_is_active',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_check_buddypress_link_is_active',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                )
            ),
            array(
                'name' => __('Avatar Size', 'galaxyfunder'),
                'desc' => __('Customize the Avatar Size by providing common value for width as well as height in pixel', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_avatar_width_height',
                'std' => '150',
                'type' => 'text',
                'newids' => 'cf_avatar_width_height',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Avatar Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Avatar in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_avatar_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_avatar_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Author Name Label', 'galaxyfunder'),
                'desc' => __('Customize the Author Name Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_name_label',
                'std' => 'Name',
                'type' => 'text',
                'newids' => 'cf_author_name_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Author Name Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Name in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_author_name_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_author_name_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Author Nick Name Label', 'galaxyfunder'),
                'desc' => __('Customize the Author Nick Name Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_nick_name_label',
                'std' => 'Nick Name',
                'type' => 'text',
                'newids' => 'cf_author_nick_name_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Author Nick Name Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Name in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_author_nick_name_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_author_nick_name_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Author Email Label', 'galaxyfunder'),
                'desc' => __('Customize the Author Email Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_email_label',
                'std' => 'Email',
                'type' => 'text',
                'newids' => 'cf_author_email_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Author Email Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Email in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_author_email_show_hide',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'cf_author_email_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('About Author/Biography Label', 'galaxyfunder'),
                'desc' => __('Customize the Author Email Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_biography_label',
                'std' => 'Biography',
                'type' => 'text',
                'newids' => 'cf_author_biography_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('About Author/Biography Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Biography in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_author_biography_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_author_biography_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Display Country when UserPro is Active', 'galaxyfunder'),
                'desc' => __('Show or Hide the Country of UserPro when it is in active', 'galaxyfunder'),
                'id' => 'cf_check_userpro_country_is_active',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'cf_check_userpro_country_is_active',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                )
            ),
            array(
                'name' => __('Author Country Label', 'galaxyfunder'),
                'desc' => __('Customize Author Country Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_author_country_label',
                'std' => 'Country',
                'type' => 'text',
                'newids' => 'cf_author_country_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Author Country Show/Hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Author Country in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_author_country_show_hide',
                'css' => 'min-width:150px;margin-bottom:80px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_author_country_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array('type' => 'sectionend', 'id' => '_cf_authorinfo_settings'),
            array(
                'name' => __('Single Product Page Label', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_product_button_text'
            ),
             array(
                'name'=>__('Campaign Day and Time Display Settings','galaxyfunder'),
                'desc'=>__('Please Select You Want to Display Day and Time in Font End Page ','galaxyfunder'),
                'id'=>'cf_campaign_day_time_display',
                'std'=>'1',
                'type'=>'select',
                'options'=>array(
                    '1'=>__('Day Only','galaxyfunder'),
                    '2'=>__('Day With Hour','galaxyfunder'),
                    '3'=>__('Day With Hour and Minutes','galaxyfunder'),
                ),
                'newids'=>'cf_campaign_day_time_display',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Campaign Starts Label', 'galaxyfunder'),
                'desc' => __('Please Enter Campaign Starts Label for Campaign Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_campaign_start_message',
                'css' => 'min-width:550px;',
                'std' => 'Campaign Starts at {from_date}',
                'type' => 'text',
                'newids' => 'cf_campaign_start_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Minimum Contribution Label for Product Page', 'galaxyfunder'),
                'tip' => '',
                'id' => 'crowdfunding_min_price_tab_product',
                'css' => 'min-width:550px;',
                'std' => 'Minimum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_min_price_tab_product',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Maximum Contribution Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_maximum_price_tab_product',
                'std' => 'Maximum Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_maximum_price_tab_product',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Goal Label', 'galaxyfunder'),
                'desc' => __('Please Enter Goal Label for Campaign', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_target_price_tab_product',
                'std' => 'Goal',
                'type' => 'text',
                'newids' => 'crowdfunding_target_price_tab_product',
                'desc_tip' => true,
            ),
            array(
                'name' => __('CrowdFunding Label', 'galaxyfunder'),
                'desc' => __('Please Enter CrowdFunding Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_payyourprice_price_tab_product',
                'std' => 'Contribution',
                'type' => 'text',
                'newids' => 'crowdfunding_payyourprice_price_tab_product',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_label',
                'std' => 'Raised',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Contribution Percent Label', 'galaxyfunder'),
                'desc' => __('Please Enter Total Contribution Percent Label', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'crowdfunding_totalprice_percent_label',
                'std' => 'Percent',
                'type' => 'text',
                'newids' => 'crowdfunding_totalprice_percent_label',
                'desc_tip' => true,
            ),
            /* Contributor Name Field with Show/Hide Option in Galaxy Funder */
            array(
                'name' => __('Contributor Name Label', 'galaxyfunder'),
                'desc' => __("Please Enter Contribution Name Field Label in Single Product Page", 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_contributor_name_caption',
                'std' => 'Contributor Name: ',
                'type' => 'text',
                'newids' => 'cf_contributor_name_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Contributor Name Description', 'galaxyfunder'),
                'desc' => __('Please Enter Description of Contributor Name Field in Single Product Page', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_contributor_name_description',
                'std' => '(First and Last Name will be used if left empty)',
                'type' => 'text',
                'newids' => 'cf_contributor_name_description',
                'desc_tip' => true
            ),
            array(
                'name' => __('Display Contributor Name Field in Frontend', 'galaxyfunder'),
                'desc' => __('Show or Hide Contributor Name Field in Frontend', 'galaxyfunder'),
                'id' => 'cf_check_show_hide_contributor_name',
                'css' => 'min-width:150px;margin-bottom:80px;',
                'std' => '2',
                'default' => '2',
                'newids' => 'cf_check_show_hide_contributor_name',
                'type' => 'select',
                'desc_tip' => true,
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                )
            ),
            /* Contributor Name Field End with Show/Hide Option in Galaxy Funder */
            array(
                'name' => __('Inbuilt Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Inbuilt Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => __('Inbuilt Design','galaxyfunder')),
                'newids' => 'cf_inbuilt_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Select Inbuilt Design', 'galaxyfunder'),
                'desc' => __('This helps to load the inbuilt type', 'galaxyfunder'),
                'id' => 'load_inbuilt_design',
                'css' => 'min-width:150px;',
                'std' => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0
                'newids' => 'load_inbuilt_design',
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
                'id' => 'single_product_prog_bar_type',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'single_product_prog_bar_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Style 1', 'galaxyfunder'),
                    '2' => __('Style 2', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Campaign Amount Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Raised Amount Value in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_raised_amount_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_amount_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Raised Camapaign Percentage Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Raised Percentage in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_raised_percentage_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_raised_percentage_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Target Days Left Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide Days Left in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_day_left_show_hide',
                'css' => 'min-width:150px;',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_day_left_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Number of Funders Details Show/hide', 'galaxyfunder'),
                'desc' => __('Show or Hide the Funders count in a Single Product Page', 'galaxyfunder'),
                'id' => 'cf_funders_count_show_hide',
                'css' => 'min-width:150px;margin-bottom:40px',
                'std' => '1', // WooCommerce < 2.0
                'default' => '1', // WooCommerce >= 2.0
                'newids' => 'cf_funders_count_show_hide',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
            ),
            array(
                'name' => __('Inbuilt CSS (Non Editable)', 'galaxyfunder'),
                'desc' => __('These are element IDs for the Contribution Table', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_single_product_contribution_table_default_css',
                'std' => ' #cf_update_total_funderers {display:none;}
 #cf_target_price_labelers {display:none;}
 #cf_total_raised_in_percentage { display:none; }
 #cf_total_price_raiser {display:none;}
 #cf_days_remainings {display:none;} #cf_days_remaining {display:none;} 
 #cf_target_price_label { display:none; }
  #cf_total_price_raised {display:none;}
  #cf_total_quantity_raised {display:none;}
 #cf_total_price_in_percentage_with_bar {display:none;}
 #cf_total_price_in_percentage {display:none;}  #single_product_contribution_table{
 }
  #cf_min_price_label {
 }
 #cf_max_price_label {
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
 
#cf_total_price_raise span {font-size:30px;
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
 font-size:30px !important;
 margin-bottom:0px;
}',
                'type' => 'textarea',
                'newids' => 'cf_single_product_contribution_table_default_css',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Design', 'galaxyfunder'),
                'desc' => __('Please Select you want to load the Custom Design', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_inbuilt_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('2' => __('Custom Design','galaxyfunder')),
                'newids' => 'cf_inbuilt_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'galaxyfunder'),
                'desc' => __('Customize the following element IDs of Frontend Campaign Submission form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'cf_single_product_contribution_table_custom_css',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_single_product_contribution_table_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_cf_product_button_text'),
            array(
                'name' => __('Social Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => __('Here you can customize social settings', 'galaxyfunder'),
                'id' => '_crowdfunding_social_settings'
            ),
            array(
                'name' => __('Social Buttons Position', 'galaxyfunder'),
                'desc' => __('This helps to Position CrowdFunding Social Buttons in Different Places', 'galaxyfunder'),
                'id' => '_crowdfunding_social_button_position',
                'css' => 'min-width:150px;',
                'std' => '5',
                'default' => '5',
                'desc_tip' => true,
                'newids' => '_crowdfunding_social_button_position',
                'type' => 'select',
                'options' => array(
                    '1' => __('Before Single Product', 'galaxyfunder'),
                    '2' => __('Before Single Product Summary', 'galaxyfunder'),
                    '3' => __('Single Product Summary', 'galaxyfunder'),
                    '4' => __('After Single Product', 'galaxyfunder'),
                    '5' => __('After Single Product Summary', 'galaxyfunder'),
                ),
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_social_settings'),
        ));
    }

    public static function crowdfunding_process_singleproduct_admin_settings() {
        woocommerce_admin_fields(CFSingleProductAdmin::crowdfunding_singleproduct_admin_options());
    }

    public static function crowdfunding_process_singleproduct_update_settings() {
        woocommerce_update_options(CFSingleProductAdmin::crowdfunding_singleproduct_admin_options());
    }

    public static function crowdfunding_singleproduct_default_settings() {
        global $woocommerce;
        foreach (CFSingleProductAdmin::crowdfunding_singleproduct_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_singleproduct_reset_values() {
        global $woocommerce;
            if (isset($_POST['reset'])) {
                if($_POST['reset_hidden']=='crowdfunding_singleproduct'){
                    echo FP_GF_Common_Functions::reset_common_function(CFSingleProductAdmin::crowdfunding_singleproduct_admin_options());
                } 
            }
            
            if(isset($_POST['resetall'])){
                echo FP_GF_Common_Functions::reset_common_function(CFSingleProductAdmin::crowdfunding_singleproduct_admin_options());
            }    
            
        }
    


    public static function show_hide_options_for_selected_styles() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var selected_style = jQuery('#load_inbuilt_design').val();
                if (selected_style == '1') {
                    jQuery('#cf_day_left_show_hide').parent().parent().hide();
                    jQuery('#cf_funders_count_show_hide').parent().parent().hide();
                     jQuery('#single_product_prog_bar_type').parent().parent().show();
                }
                else if (selected_style == '2') {
                    jQuery('#cf_day_left_show_hide').parent().parent().show();
                    jQuery('#cf_funders_count_show_hide').parent().parent().show();
                    jQuery('#cf_raised_amount_show_hide').parent().parent().show();
                    jQuery('#cf_raised_percentage_show_hide').parent().parent().show();
                     jQuery('#single_product_prog_bar_type').parent().parent().show();
                } else {
                    jQuery('#cf_raised_percentage_show_hide').parent().parent().hide();
                     jQuery('#single_product_prog_bar_type').parent().parent().hide();
                }
                jQuery('#load_inbuilt_design').change(function () {
                    var selected_styles = jQuery('#load_inbuilt_design').val();
                    if (selected_styles == '1') {
                        jQuery('#cf_day_left_show_hide').parent().parent().hide();
                        jQuery('#cf_funders_count_show_hide').parent().parent().hide();
                        jQuery('#cf_raised_amount_show_hide').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide').parent().parent().show();
                        jQuery('#single_product_prog_bar_type').parent().parent().show();

                    }
                    if (selected_styles == '2') {
                        jQuery('#cf_day_left_show_hide').parent().parent().show();
                        jQuery('#cf_funders_count_show_hide').parent().parent().show();
                        jQuery('#cf_raised_amount_show_hide').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide').parent().parent().show();
                        jQuery('#single_product_prog_bar_type').parent().parent().show();
                    }
                    if (selected_styles == '3') {
                        jQuery('#cf_day_left_show_hide').parent().parent().show();
                        jQuery('#cf_funders_count_show_hide').parent().parent().hide();
                        jQuery('#cf_raised_amount_show_hide').parent().parent().show();
                        jQuery('#cf_raised_percentage_show_hide').parent().parent().show();
                        jQuery('#single_product_prog_bar_type').parent().parent().hide();
                    }
                });

                var selected_perkurlvalue = jQuery('#cf_perk_url_type').val();
                //alert(selected_perkurlvalue);
                if (selected_perkurlvalue == '2') {                    
                    jQuery('input:radio[name=cf_perk_url__image_type]').parent().parent().parent().parent().parent().parent().show();
                    jQuery('#cf_perk_url__image_type_size').parent().parent().show();
                }else {
                    jQuery('input:radio[name=cf_perk_url__image_type]').parent().parent().parent().parent().parent().parent().hide();
                    jQuery('#cf_perk_url__image_type_size').parent().parent().hide();
                }
                jQuery('#cf_perk_url_type').change(function () {

                    var selected_perkurlvalue = jQuery('#cf_perk_url_type').val();
                    if (selected_perkurlvalue == '2') {
                        jQuery('input:radio[name=cf_perk_url__image_type]').parent().parent().parent().parent().parent().parent().show();
                        jQuery('#cf_perk_url__image_type_size').parent().parent().show();
                    }else {
                        jQuery('input:radio[name=cf_perk_url__image_type]').parent().parent().parent().parent().parent().parent().hide();
                        jQuery('#cf_perk_url__image_type_size').parent().parent().hide();
                    }
                });
            });
        </script>
        <?php
    }
    
   
}

CFSingleProductAdmin::init();
}

