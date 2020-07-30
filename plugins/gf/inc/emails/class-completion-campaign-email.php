<?php

if (!class_exists('CampaignCompletionEmail')) {

    class CampaignCompletionEmail {

        public static function init() {
            //Completion Mail
            add_action('woocommerce_order_status_' . FP_GF_Common_Functions::get_order_status_for_contribution(), array(__CLASS__, 'campaign_completion_email'));

        }

        public static function campaign_completion_email($order_id) {

            $order = fp_gf_get_order_object($order_id);
            foreach ($order->get_items() as $eachitem) {
                $product_id = $eachitem['product_id'];

                $posttype = get_post_type($product_id);
                if ($posttype == 'product') {
                    $enable = get_option('cf_enable_mail_for_campaign_completed');
                    $campaign_creator = get_option('cf_send_email_to_campaign_creator_on_completed');
                    $siteadmin = get_option('cf_send_email_to_site_admin_on_completed');
                    $othersemail = get_option('cf_send_email_to_others_on_completed');
                    $othersemaillist = get_option('cf_send_email_to_others_mail_on_completed');
                    $message_details = get_option('campaign_completion_mail_message');
                    $find_array = array('[cf_site_campaign_url]');
                    $replace_array = array(get_permalink($product_id));
                    $url_shortcode_replace = str_replace($find_array, $replace_array, $message_details);
                    $find_array = array('[cf_site_campaign_shipping_address]');
                    $replace_array = FP_GF_Common_Functions::get_values_for_shortcode($product_id);
                    $shipping_shortcode_replace = str_replace($find_array, $replace_array, $url_shortcode_replace);
                    $subject = get_option('campaign_completion_mail_subject');

                    $embed_product_id_with_subject = str_replace('[campaign_name]', '[campaign_name id = ' . $product_id . ']', $subject);
                    $subject = do_shortcode($embed_product_id_with_subject);

                    $embed_product_id_with_message = str_replace('[cf_site_campaign_completion]', '[cf_site_campaign_completion id = ' . $product_id . ']', $shipping_shortcode_replace);
                    $message = do_shortcode($embed_product_id_with_message);
                    $checkvalue = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                    $targetendselection = get_post_meta($product_id, '_target_end_selection', true);
                    echo $targetendselection;
                    if ($checkvalue == 'yes') {
                        if ($targetendselection == '1') {
                            $checkstatus = get_post_meta($product_id, '_stock_status', true);
                            if ($checkstatus == 'instock') {
                                $gettargetdate = get_post_meta($product_id, '_crowdfundingtodatepicker', true);
                                $gettargethour = get_post_meta($product_id, '_crowdfundingtohourdatepicker', true);
                                $gettargetminutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker', true);
                                if ($gettargetdate != '') {
                                    if ($gettargethour != '' || $gettargetminutes != '') {
                                        $time = $gettargethour . ':' . $gettargetminutes . ':' . '00';
                                        $datestr = $gettargetdate . $time; //Your date
                                    } else {
                                        $datestr = $gettargetdate . "23:59:59";
                                    }//Your date
                                    $local_current_time = strtotime(FP_GF_Common_Functions::date_time_with_format());
                                    $date = strtotime($datestr); //Converted to a PHP date (a second count)
                                    if ($date < $local_current_time) {

                                        if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {
                                            if (get_post_meta($product_id, '_crowdfunding_options', 'true') == '2') {
                                                $crowdtargetprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                                                $crowdtargetprice = fp_wpml_multi_currency($crowdtargetprice1);
                                                $crowdtotalprice1 = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                                $crowdtotalprice = fp_wpml_multi_currency($crowdtotalprice1);
                                                $checkstatus = get_post_meta($product_id, '_stock_status', true);
                                                if ($crowdtotalprice >= $crowdtargetprice) {
                                                    include('create-custom-order.php');
                                                }
                                            }
                                            FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $product_id);
                                        }
                                        fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                                    }
                                }
                            }
                        }if ($targetendselection == '3') {
                            $crowdtargetprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                            $crowdtargetprice = fp_wpml_multi_currency($crowdtargetprice1);
                            $crowdtotalprice1 = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                            $crowdtotalprice = fp_wpml_multi_currency($crowdtotalprice1);
                            $checkstatus = get_post_meta($product_id, '_stock_status', true);
                            if ($crowdtotalprice >= $crowdtargetprice) {
                                if ($checkstatus == 'instock') {
                                    if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {

                                        if (get_post_meta($product_id, '_crowdfunding_options', 'true') == '2') {
                                            $crowdtargetprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                                            $crowdtargetprice = fp_wpml_multi_currency($crowdtargetprice1);
                                            $crowdtotalprice1 = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                            $crowdtotalprice = fp_wpml_multi_currency($crowdtotalprice1);
                                            $checkstatus = get_post_meta($product_id, '_stock_status', true);
                                            if ($crowdtotalprice >= $crowdtargetprice) {
                                                $newproducttype[1][] = $product_id;
                                                include('create-custom-order.php');
                                            }
                                        }
                                        FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $product_id);
                                    }
                                    fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                                }
                            }
                        }if ($targetendselection == '2') {
                            $crowdtargetprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                            $crowdtargetprice = fp_wpml_multi_currency($crowdtargetprice1);
                            $crowdtotalprice1 = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                            $crowdtotalprice = fp_wpml_multi_currency($crowdtotalprice1);
                            $checkstatus = get_post_meta($product_id, '_stock_status', true);
                            $gettargetdate = get_post_meta($product_id, '_crowdfundingtodatepicker', true);
                            $gettargethour = get_post_meta($product_id, '_crowdfundingtohourdatepicker', true);
                            $gettargetminutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker', true);
                            if ($gettargetdate != '') {
                                if ($gettargethour != '' || $gettargetminutes != '') {
                                    $time = $gettargethour . ':' . $gettargetminutes . ':' . '00';
                                    $datestr = $gettargetdate . $time; //Your date
                                } else {
                                    $datestr = $gettargetdate . "23:59:59";
                                }//Your date
                                $date = strtotime($datestr); //Converted to a PHP date (a second count)
                                $local_current_time = strtotime(FP_GF_Common_Functions::date_time_with_format());
                                if ($crowdtotalprice >= $crowdtargetprice || $date < $local_current_time) {
                                    if ($checkstatus == 'instock') {
                                        $gettargetdate = get_post_meta($product_id, '_crowdfundingtodatepicker', true);
                                        if (get_option('cf_enable_mail_for_campaign_completed') == 'yes') {
                                            if (get_post_meta($product_id, '_crowdfunding_options', 'true') == '2') {
                                                $crowdtargetprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                                                $crowdtargetprice = fp_wpml_multi_currency($crowdtargetprice1);
                                                $crowdtotalprice1 = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                                $crowdtotalprice = fp_wpml_multi_currency($crowdtotalprice1);
                                                $checkstatus = get_post_meta($product_id, '_stock_status', true);
                                                if ($crowdtotalprice >= $crowdtargetprice) {
                                                    $newproducttype[1][] = $product_id;
                                                    include('create-custom-order.php');
                                                }
                                            }
                                            FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $product_id);
                                        }
                                        fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    CampaignCompletionEmail::init();
}