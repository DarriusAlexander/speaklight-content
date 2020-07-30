<?php
if (!class_exists('CFEmailSettings')) {
class CFEmailSettings {
    
    public static function init() {
        //Available short code
        add_shortcode('cf_site_campaign_completion', array(__CLASS__, 'add_shortcode_campaign_name_for_completion'));
        add_shortcode('cf_site_campaign_name', array(__CLASS__, 'add_shortcode_campaign_name_for_rej_del'));
        add_shortcode('cf_site_title', array(__CLASS__, 'add_shortcode_site_name'));
        add_shortcode('cf_campaign_name', array(__CLASS__, 'add_shortcode_campaign_name'));
        add_shortcode('campaign_name', array(__CLASS__, 'add_shortcode_main_campaign_name'));

        //Email settings hooks
        add_action('woocommerce_update_options_crowdfunding_emails', array(__CLASS__, 'crowdfunding_process_update_settings'));
        add_action('init', array(__CLASS__, 'crowdfunding_mail_default_settings'));
        add_action('woocommerce_cf_settings_tabs_crowdfunding_emails', array(__CLASS__, 'crowdfunding_process_admin_settings'));
        add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_email_tab'), 104);
        add_action('admin_init', array(__CLASS__, 'cf_email_reset_values'), 2);

        //admin head scripts
        add_action('admin_head',array(__CLASS__,'admin_head_scripts_email'));
    }

    public static function crowdfunding_admin_email_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_emails'] = __('Mail', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_mailer_admin_options() {
        global $woocommerce;
        return apply_filters('woocommerce_crowdfunding_email_options', array(
            array(
                'name' => __('Email Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_mailer'
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_mailer'),
            array(
                'name' => __('Campaign Submission Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_submission_template',
            ),
            array(
                'name' => __('Send Email on Campaign Submission', 'galaxyfunder'),
               
                'id' => 'cf_enable_mail_for_campaign_submission',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_submission',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator',
            ),
            array(
               
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin',
            ),
            array(
                
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others',
            ),
            array(
                
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Campaign Submission Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of your Campaign Submission Mail', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_submission_email_subject',
                'css' => 'min-width:550px',
                'std' => 'Campaign Submission for [cf_campaign_name] is submitted',
                'type' => 'text',
                'newids' => 'campaign_submission_email_subject',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_campaign_name]- Shortcode for displaying Campaign Name</p>'
                        . '<p>[cf_site_title]- Shortcode for displaying Campaign Site Title</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Campaign Submission Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Submission', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_submission_email_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi,<br>The Campaign [cf_campaign_name] on [cf_site_title] is Successfully Submitted. Please wait until admin has approved your campaign you will be notified either campaign is approved or rejected.<br> Thanks.',
                'type' => 'textarea',
                'newids' => 'campaign_submission_email_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_mail_settings'),
            array(
                'name' => __('Campaign Approval Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_approved_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Approved', 'galaxyfunder'),
               
                'id' => 'cf_enable_mail_for_campaign_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_approved',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_approved',
            ),
            array(
               
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_approved',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_approved',
            ),
            array(
               
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_approved',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_approved',
            ),
            array(
               
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_approved',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_approved',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Approved Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Approved Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'approved_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Congratulations!!! Your Created Campaign [campaign_name] has been Approved',
                'type' => 'text',
                'newids' => 'approved_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[campaign_name]- Shorcode for displaying Campaign Name</p>'
                        . '<p>[cf_site_title]- Shortcode for displaying Campaign Site Title</p>'
                        . '<p> [cf_site_campaign_url]- Shortcode for displaying Campaign Product Page Url</p>'
                        . '<p> [cf_site_campaign_shipping_address]- Shortcode for displaying Campaign Creator Shipping Address</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Approved Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Approved', 'galaxyfunder'),
                'tip' => '',
                'id' => 'approved_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi,<br> Congratulations!!! The Campaign [campaign_name] on [cf_site_title] is Approved.<br> [cf_site_campaign_url] <br> [cf_site_campaign_shipping_address] Thanks.',
                'type' => 'textarea',
                'newids' => 'approved_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_approved_mail_template'),
            array(
                'name' => __('Campaign Rejection Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_rejected_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Rejected', 'galaxyfunder'),
               
                'id' => 'cf_enable_mail_for_campaign_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_rejected',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_rejected',
            ),
            array(
                
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_rejected',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_rejected',
            ),
            array(
               
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_rejected',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_rejected',
            ),
            array(
                
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_rejected',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => ' ',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_rejected',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_site_campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Rejected Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Rejected Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'rejected_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Your Created Campaign [cf_site_campaign_name] has been rejected',
                'type' => 'text',
                'newids' => 'rejected_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_site_campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Rejected Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_name] Rejection', 'galaxyfunder'),
                'tip' => '',
                'id' => 'rejected_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi, <br> We are Sorry this Campaign [cf_site_campaign_name] could not meet the standards and hence it is rejected',
                'type' => 'textarea',
                'newids' => 'rejected_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_rejected_mail_template'),
            array(
                'name' => __('Campaign Completion Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_completion_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Completion', 'galaxyfunder'),
               
                'id' => 'cf_enable_mail_for_campaign_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_completed',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_completed',
            ),
            array(
                
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_completed',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_completed',
            ),
            array(
                
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_completed',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_completed',
            ),
            array(
               
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_completed',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_completed',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Campaign Completion Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Completion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_completion_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Congratulations!!! Your Created Campaign [campaign_name] has reached the Goal',
                'type' => 'text',
                'newids' => 'campaign_completion_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_site_campaign_completion]- Shortcode for displaying Campaign Name</p>'
                        . '<p> [cf_site_campaign_url]- Shortcode for displaying Campaign Product Page Url</p>'
                        . '<p> [cf_site_campaign_shipping_address]- Shortcode for displaying Campaign Creator Shipping Address</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Campaign Completion Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_completion] Completion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'campaign_completion_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi, <br> Congratulations!!! Your Created Campaign [cf_site_campaign_completion] has reached the goal :) <br> [cf_site_campaign_url] <br> [cf_site_campaign_shipping_address]',
                'type' => 'textarea',
                'newids' => 'campaign_completion_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_completion_mail_template'),
            array(
                'name' => __('Campaign Deletion Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_crowdfunding_deletion_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Deleted', 'galaxyfunder'),
                
                'id' => 'cf_enable_mail_for_campaign_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_deleted',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_deleted',
            ),
            array(
                
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_deleted',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_deleted',
            ),
            array(
               
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_deleted',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_deleted',
            ),
            array(
                
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_deleted',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_deleted',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Campaign Deletion Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Deletion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'deleted_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'We are Sorry Unfortunately your Created Campaign [campaign_name] was Deleted or Removed',
                'type' => 'text',
                'newids' => 'deleted_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Campaign Deletion Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign [cf_site_campaign_name] Deletion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'deleted_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi there, <br> We are Sorry Unfortunately your Approved Campaign was Deleted or Removed <br> Contact Support for More Info',
                'type' => 'textarea',
                'newids' => 'deleted_mail_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_crowdfunding_deletion_mail_template'),
            array(
                'name' => __('Campaign Contribution Mail Template', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_contribution_mail_template',
            ),
            array(
                'name' => __('Send Email on Campaign Order', 'galaxyfunder'),
               
                'id' => 'cf_enable_mail_for_campaign_for_campaign_order',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_enable_mail_for_campaign_for_campaign_order',
            ),
            array(
                'name' => __('Send Email To', 'galaxyfunder'),
                'desc' => __('Creator', 'galaxyfunder'),
                'id' => 'cf_send_email_to_campaign_creator_on_campaign_order',
                'std' => 'yes',
                'default' => 'yes',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_campaign_creator_on_campaign_order',
            ),
            array(
                
                'desc' => __('Admin', 'galaxyfunder'),
                'id' => 'cf_send_email_to_site_admin_on_campaign_order',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_site_admin_on_campaign_order',
            ),
            array(
               
                'desc' => __('Others', 'galaxyfunder'),
                'id' => 'cf_send_email_to_others_on_campaign_order',
                'std' => 'no',
                'default' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_send_email_to_others_on_campaign_order',
            ),
            array(
                
                'desc' => __('Enter Other Emails Each Per Line', 'galaxyfunder'),
                'tip' => '',
                'id' => 'cf_send_email_to_others_mail_on_campaign_order',
                'css' => 'min-width:550px;min-height:300px;',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'cf_send_email_to_others_mail_on_campaign_order',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_site_contributed_campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Contribution Mail Subject', 'galaxyfunder'),
                'desc' => __('Please enter subject of Campaign Deletion Mail Subject', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_subject',
                'css' => 'min-width:550px',
                'std' => 'Hi, Your Campaign [cf_site_contributed_campaign_name] has raised the fund',
                'type' => 'text',
                'newids' => 'contribution_mail_subject',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>[cf_site_contributor_name]- Shortcode for displaying Contributor Name</p>'
                        . '<p>[cf_site_contributor_email]- Shortcode for displaying Contributor Email</p>'
                        . '<p>[cf_site_contribution_amount]- Shortcode for displaying Contribution Amount</p>'
                        . '<p> [cf_site_contributed_campaign_name]- Shortcode for displaying Campaign Name</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Contribution Email Message', 'galaxyfunder'),
                'desc' => __('Enter custom email message for Campaign Deletion', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_message',
                'css' => 'min-width:550px;min-height:300px;margin-bottom:100px;',
                'std' => 'Hi there, <br> Your Created Campaign  [cf_site_contributed_campaign_name]  has raised the Fund. <br> Contributor Name: [cf_site_contributor_name]<br>Contribution Amount: [cf_site_contribution_amount]<br> Contributor E-mail: [cf_site_contributor_email]',
                'type' => 'textarea',
                'newids' => 'contribution_mail_message',
                'desc_tip' => true,
            ),
            array(
                'type' => 'text',
                'css' => 'display:none ; ',
                'id' => 'cf_shortcode_display_contributor_email',
                'newids' => 'cf_shortcode_display_contributor_email',
                'desc' => __('<p>{gfsitelinkwithid}- Shortcode to display Unsubscribe Link</p>', 'galaxyfunder'),
                'std' => ''
            ),
            array(
                'name' => __('Unsubscribe Link Message for Email', 'galaxyfunder'),
                'desc' => __('This message will be displayed a the Unsubscribe message in Galaxy Funder Emails', 'galaxyfunder'),
                'id' => 'gf_unsubscribe_link_for_email',
                'css' => 'min-width:550px;',
                'std' => 'If you want to unsubscribe from your mail,click here...{gfsitelinkwithid}',
                'type' => 'textarea',
                'newids' => 'gf_unsubscribe_link_for_email',
                'class' => 'gf_unsubscribe_link_for_email',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_contribution_mail_template'),
            array(
                'name' => __('Perk Information Order Reciept Customization', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_contribution_mail_reciept',
            ),
            
            array(
                'name' => __('Show/Hide Perk table in Order Reciept', 'galaxyfunder'),
                'desc' => __('Show/Hide Perk table in Order Reciept', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_label_show_hide',
                'std' => '1', 
                'type' => 'select',
                'newids' => 'contribution_mail_perk_label_show_hide',
                'options' => array(
                    '1' => __('Show', 'galaxyfunder'),
                    '2' => __('Hide', 'galaxyfunder'),
                ),
                'desc_tip' => true,
            ),
            
            
            array(
                'name' => __('Perk Name Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Name ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_label',
                'css' => 'min-width:550px',
                'std' => 'Perk Name',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Associated Contribution Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Associated Contribution ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_associated_contribution',
                'css' => 'min-width:550px',
                'std' => 'Perk Associated Contribution',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_associated_contribution',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Products Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Products ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_Perk_Products',
                'css' => 'min-width:550px',
                'std' => 'Perk Products',
                'type' => 'text',
                'newids' => 'contribution_mail_Perk_Products',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Perk Quantity Label', 'galaxyfunder'),
                'desc' => __('Please enter Label For Perk Quantity ', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_perk_quantity',
                'css' => 'min-width:550px',
                'std' => 'Perk Quantity',
                'type' => 'text',
                'newids' => 'contribution_mail_perk_quantity',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message To Show When There Is No Perk Product In Order', 'galaxyfunder'),
                'desc' => __('Please enter Label To Show When There Is No Perk Product In Order', 'galaxyfunder'),
                'tip' => '',
                'id' => 'contribution_mail_Perk_perk_empty',
                'css' => 'min-width:550px',
                'std' => 'No Perk Associated In This Order',
                'type' => 'text',
                'newids' => 'contribution_mail_Perk_perk_empty',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_contribution_mail_reciept'),
        ));
    }

    public static function crowdfunding_process_admin_settings() {
        woocommerce_admin_fields(CFEmailSettings::crowdfunding_mailer_admin_options());
    }

    public static function crowdfunding_process_update_settings() {
        woocommerce_update_options(CFEmailSettings::crowdfunding_mailer_admin_options());
    }

    public static function crowdfunding_mail_default_settings() {
        global $woocommerce;
        foreach (CFEmailSettings::crowdfunding_mailer_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_email_reset_values() {
        global $woocommerce;
        // var_dump("google google");
        if (isset($_POST['reset'])) {
            if($_POST['reset_hidden']=='crowdfunding_emails'){
            echo FP_GF_Common_Functions::reset_common_function(CFEmailSettings::crowdfunding_mailer_admin_options());
            }
        }
        if(isset($_POST['resetall'])){
            echo FP_GF_Common_Functions::reset_common_function(CFEmailSettings::crowdfunding_mailer_admin_options());
        }
    }

    public static function add_shortcode_site_name() {
        return get_option('blogname');
    }

    public static function add_shortcode_campaign_name() {
        return $_POST['crowdfunding_title'];
    }

    public static function add_shortcode_main_campaign_name($atts) {
        $atts = shortcode_atts(
                array(
            'id' => '',
                ), $atts, 'campaign_name');
        $product_id= $atts['id'];
        return get_the_title($product_id);
    }

    public static function add_shortcode_campaign_name_for_rej_del() {
        global $splitids;
        if ($_GET['ids']) {
            $splitids = explode(',', $_GET['ids']);
            $count = count($splitids);
            for ($i = 0; $i < $count; $i++) {
                $oldstatus = get_post_meta($splitids[$i], '_cf_old_status', true);
                $newstatus = get_post_meta($splitids[$i], '_cf_new_status', true);
                if ((($oldstatus == 'draft') && ($newstatus == 'trash'))) {
                    return get_the_title($splitids[$i]);
                }
                if ((($oldstatus == 'publish') && ($newstatus == 'trash'))) {
                    return get_the_title($splitids[$i]);
                }
            }
        }
    }

    public static function add_shortcode_campaign_name_for_completion($atts) {
        $atts = shortcode_atts(
                array(
            'id' => '',
                ), $atts, 'cf_site_campaign_completion');
        $product_id= $atts['id'];
        return get_the_title($product_id);
    }
    
    public static function admin_head_scripts_email(){
        ?>
    <script type="text/javascript">
jQuery(document).ready(function () {
    
     var perk_table_show_hide=jQuery('#contribution_mail_perk_label_show_hide').val();
     
        if(perk_table_show_hide==1){
           jQuery('#contribution_mail_perk_label').parent().parent().show();
           jQuery('#contribution_mail_perk_associated_contribution').parent().parent().show();
           jQuery('#contribution_mail_Perk_Products').parent().parent().show();
           jQuery('#contribution_mail_perk_quantity').parent().parent().show();
           jQuery('#contribution_mail_Perk_perk_empty').parent().parent().show();
        }else{
           jQuery('#contribution_mail_perk_label').parent().parent().hide();
           jQuery('#contribution_mail_perk_associated_contribution').parent().parent().hide();
           jQuery('#contribution_mail_Perk_Products').parent().parent().hide();
           jQuery('#contribution_mail_perk_quantity').parent().parent().hide();
           jQuery('#contribution_mail_Perk_perk_empty').parent().parent().hide();
        }
    
    jQuery('#contribution_mail_perk_label_show_hide').change(function(){
        var perk_table_show_hide=jQuery(this).val();
        if(perk_table_show_hide==1){
            jQuery('#contribution_mail_perk_label').parent().parent().show();
            jQuery('#contribution_mail_perk_associated_contribution').parent().parent().show();
            jQuery('#contribution_mail_Perk_Products').parent().parent().show();
            jQuery('#contribution_mail_perk_quantity').parent().parent().show();
            jQuery('#contribution_mail_Perk_perk_empty').parent().parent().show();
        }else{
            jQuery('#contribution_mail_perk_label').parent().parent().hide();
            jQuery('#contribution_mail_perk_associated_contribution').parent().parent().hide();
            jQuery('#contribution_mail_Perk_Products').parent().parent().hide();
            jQuery('#contribution_mail_perk_quantity').parent().parent().hide();
            jQuery('#contribution_mail_Perk_perk_empty').parent().parent().hide();
        }
    });
});


    </script>    
<?php
    }

}
CFEmailSettings::init();
}