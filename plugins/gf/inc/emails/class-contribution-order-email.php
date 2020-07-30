<?php
if (!class_exists('FP_RAC_Submenu')) {

class CampaignContributionEmail {
    
     public static function init() {
        //add_action('woocommerce_order_status_completed', array('CampaignContributionEmail', 'newwoocommerce'), 10, 1);
        //add_action('admin_head', array('CampaignContributionEmail', 'campaign_contribution_email'));
        //add_action('wp_head', array('CampaignContributionEmail', 'campaign_contribution_email'));
     }
    //Contribution email
    public static function campaign_contribution_email() {
        global $post;
        global $woocommerce;
        $enable = get_option('cf_enable_mail_for_campaign_for_campaign_order');
        $campaign_creator = get_option('cf_send_email_to_campaign_creator_on_campaign_order');
        $siteadmin = get_option('cf_send_email_to_site_admin_on_campaign_order');
        $othersemail = get_option('cf_send_email_to_others_on_campaign_order');
        $othersemaillist = get_option('cf_send_email_to_others_mail_on_campaign_order');
        $subject = do_shortcode(get_option('contribution_mail_subject'));
        $message = do_shortcode(get_option('contribution_mail_message'));
        $products_array=FP_GF_Common_Functions::common_function_for_get_post('');
        foreach ($products_array as $products) {
            $checkvalue = get_post_meta($products->ID, '_crowdfundingcheckboxvalue', true);
            if ($checkvalue == 'yes') {
                $getfundertotal = get_post_meta($products->ID, '_update_total_funders', true);
                add_post_meta($products->ID, '_update_new_total_funders', $getfundertotal);
                //delete_post_meta($products->ID, '_update_new_total_funders');
                $newfundertotal = get_post_meta($products->ID, '_update_new_total_funders', true);
                $checknewpost = add_post_meta($products->ID, '_newfundtotal', 'false');
                //   echo "Get it into the condition" . "<br>";
               
                if ($getfundertotal > $newfundertotal) {
                    echo "Fund has Been Raised";

                    if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
                            FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $post->ID);
                    }
                    fp_gf_update_campaign_metas($products->ID, '_update_new_total_funders', $getfundertotal);
                }
                if ($checknewpost == 'false') {
                    if ($getfundertotal == '1') {
                        if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
                            FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $post->ID);
                        }
                        delete_post_meta($products->ID, '_newfundtotal');
                        add_post_meta($products->ID, '_newfundtotal', 'true');
                    }
                }
            }
        }
    }

}



 CampaignContributionEmail::init();

}
