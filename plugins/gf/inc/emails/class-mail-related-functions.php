<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FP_GF_Mail_Related_Functions')) {

    final class FP_GF_Mail_Related_Functions {

        public static function init() {
            //short code for [cf_site_contributed_campaign_name]
            add_shortcode('cf_site_contributed_campaign_name', array(__CLASS__, 'add_shortcode_campaign_order'));
            //send order email to creator
            add_action('woocommerce_order_status_' . FP_GF_Common_Functions::get_order_status_for_contribution(), array(__CLASS__, 'crowdfunding_send_order_email_to_creator'));
            //Mail after order table
            add_action('woocommerce_email_after_order_table', array(__CLASS__, 'add_perk_info_in_mail'), 10, 1);
//             //locate template hooks
            if (get_option('cf_load_woocommerce_template') == '1') {
                add_filter('woocommerce_locate_template', array(__CLASS__, 'crowdfunding_woocommerce_locate_template'), 1, 3);
            }
            //woocommerce paypal args
            add_filter('woocommerce_paypal_args', array(__CLASS__, 'cf_custom_override_paypal_email'), 100, 1);
            /* Campaign Rejection Hook */
            add_action('pending_to_trash', array(__CLASS__, 'do_something_on_campaign_rejection'));
            /* Campaign Deletion (Hard Rejection) */
            add_action('publish_to_trash', array(__CLASS__, 'do_something_on_campaign_deletion'));
            /* Campaign Approval Hook */
            add_action('pending_to_publish', array('FP_GF_Frontend_Form', 'common_function_for_approval'));
        }

        public static function add_shortcode_campaign_order($order_id) {
            global $product_id;
            $order = fp_gf_get_order_object($order_id);
            return get_the_title($product_id);
        }

        public static function perk_table($order_id) {
            $order = fp_gf_get_order_object($order_id);
            $items = $order->get_items();
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                    ob_start();
                    ?>
                    <table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php
                                    echo get_option('contribution_mail_perk_label');
                                    ?>
                                </th>
                                <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_associated_contribution'); ?> </th>
                                <th scope="col" style="text-align:left; border: 1px solid #eee;">

                                    <?php echo get_option('contribution_mail_Perk_Products'); ?></th>
                                <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php echo get_option('contribution_mail_perk_quantity'); ?></th>
                            </tr>
                        </thead>
                        <?php
                        $explode = get_post_meta($order_id, 'getlistofquantities', true);
                        if (!is_array($explode)) {
                            $explode = (array) $explode;
                        }
                        if (is_array($explode)) {
                            foreach ($explode as $exp) {
                                if ($exp != '') {
                                    $iter = explode('_', $exp);
                                    if (is_array($iter) && !empty($iter)) {
                                        $iteration_id = $iter[0];
                                        $quantity = $iter[1];
                                        $getallcampaignperks = get_post_meta($product_id, 'perk', true);
                                        $perkname = $getallcampaignperks[$iteration_id]['name'];
                                        $perkproduct = isset($getallcampaignperks[$iteration_id]['choose_products']) ? $getallcampaignperks[$iteration_id]['choose_products'] : '';
                                        ?>
                                        <tr>
                                            <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $perkname; ?></th>
                                            <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo get_the_title($product_id); ?></th>
                                            <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $perkproduct != '' ? get_the_title($perkproduct) : '---'; ?></th>
                                            <th scope="col" style="text-align:center; border: 1px solid #eee;"><?php echo $quantity != '' ? $quantity : '---'; ?></th>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        } else {
                            ?>
                            <tr>
                                <th colspan="4" style="text-align: center; border: 1px solid #eee;"> <?php echo get_option('contribution_mail_Perk_perk_empty'); ?> </th>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
                    $perk_table = ob_get_clean();
                    return $perk_table;
                }
            }
        }

        public static function add_perk_info_in_mail($order) {
            if (get_option('contribution_mail_perk_label_show_hide') == '1') {
                $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
                $status = $formed_order_object->get_status;
                $w_status = str_replace('wc-', '', $status);
                if (is_array(get_option('cf_add_contribution'))) {
                    foreach (get_option('cf_add_contribution') as $selected_status) {
                        $my_status = $selected_status;
                    }
                } else {
                    $my_status = get_option('cf_add_contribution');
                }
                if ($w_status == $my_status) {
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_id = $item['product_id'];
                        if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) == 'yes') {
                            $order_id = FP_GF_Common_Functions::common_function_to_get_object_id($order);
                            echo FP_GF_Mail_Related_Functions::perk_table($order_id);
                        }
                    }
                }
            }
        }

        public static function fetch_contributor_details($user_id, $replace_amount, $order) {
            $user = get_userdata($user_id);
            if ((float) WC()->version >= (float) ('3.0.0')) {
                $billing_email = $order->get_billing_email();
                $billing_first_name = $order->get_billing_first_name();
                $billing_last_name = $order->get_billing_last_name();
            } else {
                $billing_email = $order->get_billing_email;
                $billing_first_name = $order->billing_first_name;
                $billing_last_name = $order->billing_last_name;
            }
            $contributor_name = isset($user->user_login) ? $user->user_login : $billing_first_name . ' ' . $billing_last_name;
            $contributor_email = isset($user->user_email) ? $user->user_email : $billing_email;
            $find_array = array('[cf_site_contributor_name]', '[cf_site_contributor_email]');
            $replace_array = array($contributor_name, $contributor_email);
            $message = str_replace($find_array, $replace_array, $replace_amount);
            return $message;
        }

        public static function add_hyperlink_for_unsub_link($unsub_url) {
            $new_unsub_url = "<a href= '" . $unsub_url . "'>" . $unsub_url . "</a>";
            return $new_unsub_url;
        }

        public static function crowdfunding_send_order_email_to_creator($order_id) {
            $order = fp_gf_get_order_object($order_id);
            $adminemail = '';
            $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
            $user_id = $formed_order_object->get_user_id;
            $contibute_amount = fp_gf_get_order_currency($order) . $order->get_total();
            global $product_id;

            foreach ($order->get_items() as $item) {
                $product_id = $item['product_id'];
                $checkvalue = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                if ($checkvalue == 'yes') {
                    if (get_option('cf_enable_mail_for_campaign_for_campaign_order') == 'yes') {
                        if (get_option('cf_send_email_to_campaign_creator_on_campaign_order') == 'yes') {
                            $author = get_post_field('post_author', $product_id);
                            $creatoremail = get_the_author_meta('user_email', $author);
                        }
                        if (get_option('cf_send_email_to_site_admin_on_campaign_order') == 'yes') {
                            $adminemail = get_option('admin_email');
                        }
                        $newarray = array($creatoremail, $adminemail);
                        if (get_option('cf_send_email_to_others_on_campaign_order') == 'yes') {
                            $text = trim(get_option('cf_send_email_to_others_mail_on_campaign_order'));
                            $textAr = explode("\n", $text);
                            $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                            foreach ($textAr as $line) {
                                $newarray[] = $line;
                            }
                        }
                        foreach ($newarray as $fieldarray) {
                            if (!is_null($fieldarray) || $fieldarray != '') {
                                global $woocommerce;
                                global $unsubscribe_link2;
                                if (get_user_meta($author, 'gf_email_unsub_value', true) != 'yes') {
                                    $author = get_post_field('post_author', $product_id);
                                    $tos = $fieldarray;
                                    $subject = do_shortcode(get_option('contribution_mail_subject'));
                                    $get_message = get_option('contribution_mail_message');
                                    $replace_amount = str_replace('[cf_site_contribution_amount]', $contibute_amount, $get_message);
                                    $replace_contributor_shortcode = FP_GF_Mail_Related_Functions::fetch_contributor_details($user_id, $replace_amount, $order);
                                    $message = do_shortcode($replace_contributor_shortcode);
                                    $create_wpnonce = wp_create_nonce('gf_unsubscribe_' . $author);
                                    $link_for_unsubscribe = esc_url_raw(add_query_arg(array('userid' => $author, 'unsub' => 'yes', 'nonce' => $create_wpnonce), site_url()));
                                    $updated_link_unsubscribe = FP_GF_Mail_Related_Functions::add_hyperlink_for_unsub_link($link_for_unsubscribe);
                                    $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
                                    $unsubscribe_link2 = str_replace('{gfsitelinkwithid}', $updated_link_unsubscribe, $unsubscribe_link1);
                                    add_filter('woocommerce_email_footer_text', array('FP_GF_Mail_Related_Functions', 'unsubscribe_footer_link'));
                                    ob_start();
                                    wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                    echo $message;
                                    echo "<br>";
                                    echo FP_GF_Mail_Related_Functions::add_perk_info_in_mail($order);
                                    wc_get_template('emails/email-footer.php');
                                    $woo_temp_msg = ob_get_clean();
                                    $mainnames = get_option('woocommerce_email_from_name');
                                    $mainemails = get_option('woocommerce_email_from_address');
                                    // To send HTML mail, the Content-type header must be set
                                    $headerss = 'MIME-Version: 1.0' . "\r\n";
                                    $headerss .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                    // Additional headers
                                    $headerss .= 'From:' . $mainnames . '  <' . $mainemails . '>' . "\r\n";
                                    // Mail it
                                    if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                                        $mail = wp_mail($tos, $subject, $woo_temp_msg, $headerss);
                                    } else {
                                        $mailer = WC()->mailer();
                                        $mailer->send($tos, $subject, $woo_temp_msg, '', '');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public static function crowdfunding_woocommerce_locate_template($template, $template_name, $template_path) {
            global $post;
            if (is_object($post)) {
                $post_id = $post->ID;
                if (isset($post_id)) {
                    $newid = $post_id;
                    $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                    $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/';
                    if ($checkvalue == 'yes') {
                        if (file_exists($plugin_path . $template_name)) {
                            $template = $plugin_path . $template_name;
                            return $template;
                        }
                    }
                }
            }
            return $template;
        }

        public static function cf_custom_override_paypal_email($paypal_args) {
            global $woocommerce;
            $paypal_args['business'] = self::cf_custom_get_email_address();
            return $paypal_args;
        }

        public static function cf_custom_get_email_address() {
            global $woocommerce;
            foreach ($woocommerce->cart->cart_contents as $item) {
                $emailid = get_post_meta($item['product_id'], 'cf_campaigner_paypal_id', true);
                $checkcrowdfunding = get_post_meta($item['product_id'], '_crowdfundingcheckboxvalue', true);
                if ($checkcrowdfunding == 'yes') {
                    if (get_option('cf_enable_paypal_campaign_email_id') == 'yes') {
                        if (!empty($emailid)) {
                            return $emailid;
                        } else {
                            $paypalsettings = get_option('woocommerce_paypal_settings');
                            return $paypalsettings['email'];
                        }
                    } else {
                        $paypalsettings = get_option('woocommerce_paypal_settings');
                        return $paypalsettings['email'];
                    }
                } else {
                    $paypalsettings = get_option('woocommerce_paypal_settings');
                    return $paypalsettings['email'];
                }
            }
        }

        public static function do_something_on_campaign_rejection($post) {
            $productid = $post->ID;
            $enable = get_option('cf_enable_mail_for_campaign_rejected');
            $campaign_creator = get_option('cf_send_email_to_campaign_creator_on_rejected');
            $siteadmin = get_option('cf_send_email_to_site_admin_on_rejected');
            $othersemail = get_option('cf_send_email_to_others_on_rejected');
            $othersemaillist = get_option('cf_send_email_to_others_mail_on_rejected');
            $product_title = get_the_title($productid);

            $subject = str_replace('[cf_site_campaign_name]', $product_title, get_option('rejected_mail_subject'));
            $message = str_replace('[cf_site_campaign_name]', $product_title, get_option('rejected_mail_message'));
            $checkvalue = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingcheckboxvalue');
            if ($checkvalue == 'yes') {
                // Send Mail on Rejection
                self::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $productid);
            }
        }

        public static function do_something_on_campaign_deletion($post) {
            global $post;

            $productid = $post->ID;
            $enable = get_option('cf_enable_mail_for_campaign_deleted');
            $campaign_creator = get_option('cf_send_email_to_campaign_creator_on_deleted');
            $siteadmin = get_option('cf_send_email_to_site_admin_on_deleted');
            $othersemail = get_option('cf_send_email_to_others_on_deleted');
            $othersemaillist = get_option('cf_send_email_to_others_mail_on_deleted');
            $product_title = get_the_title($productid);
            $subject = str_replace('[campaign_name]', $product_title, get_option('deleted_mail_subject'));
            $message = str_replace('[campaign_name]', $product_title, get_option('deleted_mail_message'));
            $checkvalue = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingcheckboxvalue');
            $check_status = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_stock_status');
            if ($checkvalue == 'yes') {
                if ($check_status == 'instock') {
                    // Send Mail on Campaign Deletion
                    self::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $productid);
                }
            }
        }

        public static function unsubscribe_footer_link() {
            global $unsubscribe_link2;
            return $unsubscribe_link2;
        }

        public static function send_mail_function($enable, $campaign_creator, $site_admin, $othersemail, $othersemaillist, $subject, $message, $productid) {
            global $woocommerce;
            global $unsubscribe_link2;
            $adminemail = '';
            if ($enable == 'yes') {
                if ($campaign_creator == 'yes') {
                    $author = get_post_field('post_author', $productid);
                    $creatoremail = get_the_author_meta('user_email', $author);
                }
                if ($site_admin == 'yes') {
                    $adminemail = get_option('admin_email');
                }
                $newarray = array($creatoremail, $adminemail);
                if ($othersemail == 'yes') {
                    $text = trim($othersemaillist);
                    $textAr = explode("\n", $text);
                    $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
                    foreach ($textAr as $line) {
                        $newarray[] = $line;
                    }
                }
                foreach ($newarray as $fieldarray) {
                    if (!is_null($fieldarray) || $fieldarray != '') {
                        $subject = $subject;
                        $author = get_post_field('post_author', $productid);
                        $create_wpnonce = wp_create_nonce('gf_unsubscribe_' . $author);
                        $link_for_unsubscribe = esc_url_raw(add_query_arg(array('userid' => $author, 'unsub' => 'yes', 'nonce' => $create_wpnonce), site_url()));
                        $unsubscribe_link1 = get_option('gf_unsubscribe_link_for_email');
                        $updated_link_unsubscribe = FP_GF_Mail_Related_Functions::add_hyperlink_for_unsub_link($link_for_unsubscribe);
                        $unsubscribe_link2 = str_replace('{gfsitelinkwithid}', $updated_link_unsubscribe, $unsubscribe_link1);
                        add_filter('woocommerce_email_footer_text', array('FP_GF_Mail_Related_Functions', 'unsubscribe_footer_link'));
                        ob_start();
                        wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                        echo $message;
                        wc_get_template('emails/email-footer.php');
                        $temp_message = ob_get_clean();
                        $email = $fieldarray;
                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                        $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                        $headers .= "Reply-To: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                            wp_mail($email, $subject, $temp_message, $headers);
                        } else {
                            $mailer = WC()->mailer();
                            $mailer->send($email, $subject, $temp_message, '', '');
                        }
                    }
                }
            }
            return;
        }

    }

    FP_GF_Mail_Related_Functions::init();
}