<?php
/*
 * FrontEnd Form Related Functionality
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Frontend_Form')) {

    /**
     * Front end  Class.
     */
    class FP_GF_Frontend_Form {

        public static function init() {
            //shortcode for form
            add_shortcode('crowd_fund_form', array(__CLASS__, 'add_frontend_form'));
            //perkform frontend
            add_action('wp_ajax_cf_process_form', array(__CLASS__, 'perform_frontend_form'));
            //FrontEnd Form ajax functions
            add_action('wp_ajax_ajax_get_product_price', array(__CLASS__, 'ajax_get_product_price_function'));
            //ajax product search
            add_action('wp_ajax_ajax_product_search', array(__CLASS__, 'ajax_product_search_function'));

            add_action('wp_ajax_ajax_product_search_no_filter', array(__CLASS__, 'ajax_product_search_no_filter_function'));


            //email subscribe meta
            add_action('wp_head', array(__CLASS__, 'update_gf_email_unsubscribe_meta'));
        }

        //Update the Unsubscribe user meta for User
        public static function update_gf_email_unsubscribe_meta() {
            if (isset($_GET['userid']) && isset($_REQUEST['nonce'])) {
                $user_id = $_GET['userid'];
                $unsub = $_GET['unsub'];
                if (($user_id) && ($unsub == 'yes')) {
                    update_user_meta($user_id, 'gf_email_unsub_value', 'yes');
                    wp_safe_redirect(site_url());
                    exit();
                }
            }
        }

        public static function ajax_product_search_no_filter_function() {

            global $woocommerce;
            $json_ids = array();
//            $selected_products_check = get_option('cf_frontend_product_selection_type');
            $selected_products_array = get_option('cf_frontend_selected_products');

            if ((float) $woocommerce->version < (float) ('3.0.0')) {
                $selected_products_array = explode(',', $selected_products_array);
            }
            $args = array(
                'offset' => 0,
                'orderby' => 'post_date',
                'post_type' => 'product',
                's' => $_REQUEST['term'],
            );
            $full_products_array = get_posts($args);
            foreach ($full_products_array as $full_products) {
                $product_id = $full_products->ID;
                $product = FP_GF_Common_Functions::get_woocommerce_product_object($product_id);
                if ($product->is_type('simple')) {
                    $product_name = $product->get_formatted_name();
                    $json_ids[$product_id] = $product_name;
                } elseif ($product->is_type('variable')) {
                    $product_class_array = $product->get_available_variations();
                    foreach ($product_class_array as $product_class_variation) {
                        $product_name = get_the_title($product_id);
                        $variation_id = $product_class_variation['variation_id'];
                        $product_details = FP_GF_Common_Functions::get_woocommerce_product_object($variation_id);
                        $product_name = $product_details->get_formatted_name();
                        $json_ids[$variation_id] = $product_name;
                    }
                }
            }
            wp_send_json($json_ids);
            exit();
        }

        //Sumo product details in a AJAX callback
        public static function ajax_product_search_function() {

            global $woocommerce;
            $json_ids = array();
            $selected_products_check = get_option('cf_frontend_product_selection_type');
            $selected_products_array = get_option('cf_frontend_selected_products');

            if ((float) $woocommerce->version < (float) ('3.0.0')) {
                $selected_products_array = explode(',', $selected_products_array);
            }
            $args = array(
                'offset' => 0,
                'orderby' => 'post_date',
                'post_type' => 'product',
                's' => $_REQUEST['term'],
            );
            $full_products_array = get_posts($args);
            foreach ($full_products_array as $full_products) {
                $product_id = $full_products->ID;
                $product = FP_GF_Common_Functions::get_woocommerce_product_object($product_id);
                if ($selected_products_check == '2') {
                    if ($product->is_type('simple')) {
                        if (in_array($product_id, $selected_products_array)) {
                            if (get_post_meta($product_id, '_crowdfundingcheckboxvalue', true) != 'yes') {
                                $product_name = $product->get_formatted_name();
                                $json_ids[$product_id] = $product_name;
                            }
                        }
                    } elseif ($product->is_type('variable')) {
                        $product_class_array = $product->get_available_variations();
                        foreach ($product_class_array as $product_class_variation) {
                            $variation_id = $product_class_variation['variation_id'];
                            if (in_array($variation_id, $selected_products_array)) {
                                $product_details = FP_GF_Common_Functions::get_woocommerce_product_object($variation_id);
                                $product_name = $product_details->get_formatted_name();
                                $json_ids[$variation_id] = $product_name;
                            }
                        }
                    }
                } else {
                    if ($product->is_type('simple')) {
                        $product_name = $product->get_formatted_name();
                        $json_ids[$product_id] = $product_name;
                    } elseif ($product->is_type('variable')) {
                        $product_class_array = $product->get_available_variations();
                        foreach ($product_class_array as $product_class_variation) {
                            $variation_id = $product_class_variation['variation_id'];
                            $product_details = FP_GF_Common_Functions::get_woocommerce_product_object($variation_id);
                            $product_name = $product_details->get_formatted_name();
                            $json_ids[$variation_id] = $product_name;
                        }
                    }
                }
            }
            wp_send_json($json_ids);
            exit();
        }

        public static function add_frontend_form() {
            ob_start();
            global $woocommerce;
            if (is_user_logged_in()) {
                // For Member do this stuff
                $userid = get_current_user_id();
                $ship_first_name = get_user_meta($userid, 'shipping_first_name', true);
                $ship_last_name = get_user_meta($userid, 'shipping_last_name', true);
                $ship_company = get_user_meta($userid, 'shipping_company', true);
                $ship_address1 = get_user_meta($userid, 'shipping_address_1', true);
                $ship_address2 = get_user_meta($userid, 'shipping_address_2', true);
                $ship_city = get_user_meta($userid, 'shipping_city', true);
                $ship_country = get_user_meta($userid, 'shipping_country', true);
                $ship_postcode = get_user_meta($userid, 'shipping_postcode', true);
                $ship_state = get_user_meta($userid, 'shipping_state', true);

                /* Billing Information for the Corresponding USER/AUTHOR */
                $bill_first_name = get_user_meta($userid, 'billing_first_name', true);
                $bill_last_name = get_user_meta($userid, 'billing_last_name', true);
                $bill_company = get_user_meta($userid, 'billing_company', true);
                $bill_address1 = get_user_meta($userid, 'billing_address_1', true);
                $bill_address2 = get_user_meta($userid, 'billing_address_2', true);
                $bill_city = get_user_meta($userid, 'billing_city', true);
                $bill_country = get_user_meta($userid, 'billing_country', true);
                $bill_postcode = get_user_meta($userid, 'billing_postcode', true);
                $bill_state = get_user_meta($userid, 'billing_state', true);
                $bill_email = get_user_meta($userid, 'billing_email', true);
                $bill_phone = get_user_meta($userid, 'billing_phone', true);
                $country_obj = new WC_Countries();

                $allowed_roles = get_option('cf_campaign_submission_frontend_exclude_role_control');
                $current_role = (wp_get_current_user()->roles);
                if (in_array($current_role[0], $allowed_roles)) {
                    ?>
                    <style type="text/css">
                        .newError {
                            color:#ff0000;
                        }
                        @media only screen and (max-width: 767px){
                            .cf_frontend_campaign_form_table{
                                width:90% !important;
                            }
                            .cf_frontend_campaign_form_table th{
                                width:90% !important;
                                float:left;
                            }
                            .cf_frontend_campaign_form_table td{
                                width:90% !important;
                                float:left;
                            }
                        }
                    </style>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery(".cf_frontend_campaign_form").validate({errorClass: 'newError'});
                            var options = {
                                beforeSubmit: showRequest, // pre-submit callback
                                success: showResponse, // post-submit callback
                                url: "<?php echo admin_url('admin-ajax.php'); ?>", // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                            };
                            // bind form using 'ajaxForm'
                            jQuery('.cf_frontend_campaign_form').ajaxForm(options);
                            function showRequest(formData, jqForm, options) {
                                jQuery('#cf_submit').attr("disabled", "disabled");
                            }
                            function showResponse(responseText, statusText, xhr, $form) {

                    <?php
                    if (get_option('cf_campiagn_success_redirection_option') == '1') {
                        ?>
                                    var campaign_success_redirect_url = '';
                    <?php } else {
                        ?>
                                    var campaign_success_redirect_url = "<?php echo get_option("cf_campiagn_success_redirection_url_content") ?>";
                    <?php } ?>
                                console.log(responseText);
                                console.log(statusText);
                                //do extra stuff after submit
                                var newresponse = responseText.replace(/\s/g, '');
                                if (newresponse === 'success') {
                                    jQuery('.cf_frontend_campaign_form')[0].reset();
                                    if (campaign_success_redirect_url != "") {
                                        window.location = campaign_success_redirect_url;
                                    } else {
                                        jQuery('.cf_frontend_campaign_form').delay(400).fadeOut(function () {
                                            jQuery('.cf_frontend_message').delay(1000).css('display', 'block').html('<?php echo get_option('cf_frontend_submission_response_message'); ?>');
                                        });
                                    }
                                }
                            }
                        });
                    </script>
                    <?php
                    if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                        //wp_enqueue_script('galaxyfunder_chosen_enqueue');
                        //wp_enqueue_style('galaxyfunder_chosen_style_enqueue');
                    } else {
                        $assets_path = str_replace(array('http:', 'https:'), '', WC()->plugin_url()) . '/assets/';
                        wp_enqueue_script('select2');
                        wp_enqueue_style('select2', $assets_path . 'css/select2.css');
                    }
                    ?>
                    <div class="cf_frontend_message">
                    </div>
                    <form method="post" class="cf_frontend_campaign_form" name="cf_frontend_campaign_form" action="#" enctype="multipart/form-data">
                        <table class="cf_frontend_campaign_form_table">
                            <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                                <tr>
                                    <th>
                                        <label>
                                            <?php echo get_option('cf_campaign_purpose_label'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <select name="crowdfunding_options" style="width:100%;" id="crowdfunding_options" class='crowdfunding_options'>
                                            <option value="1"><?php echo __('Fundraising by Crowdfunding', 'galaxyfunder'); ?></option>
                                            <option value="2"><?php echo __('Product Purchase by Crowdfunding', 'galaxyfunder'); ?></option>
                                        </select>
                                    </td>
                                <?php } ?>
                            </tr>

                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_campaign_product_purchase_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <?php echo FP_GF_Common_Functions::common_functionps('', 'frontend') ?>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php _e('Use Selected Product Featured Image', 'galaxyfunder'); ?></label></th><td><input type="checkbox" name="use_selected_product_image" id="use_selected_product_image" value="1"/><label><?php _e('   ' . '  (Works only When one Product is Chosen)', 'galaxyfunder'); ?></label></td>
                            </tr>
                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_title_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" placeholder="<?php echo get_option('cf_submission_camp_title_placeholder'); ?>" data-rule-required="true" data-msg-required="<?php echo get_option('_cf_empty_campaign_title'); ?>" style='width:100%;' name="cf_campaign_title" class="cf_campaign_title" id="cf_campaign_title" value=""/>
                                </td>
                            </tr>
                            <tr><?php if (get_option('cf_show_hide_campaign_end_selection_frontend') == '1') { ?>
                                    <th> 
                                        <label>
                                            <?php echo get_option('cf_campaign_end_method_label'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <select name="_target_end_selection1"  style="width:100%;" id="_target_end_selection1" class='_target_end_selection1'>
                                            <option value="3"><?php echo __('Target Goal', 'galaxyfunder'); ?></option>
                                            <option value="1"><?php echo __('Target Date', 'galaxyfunder'); ?></option>
                                            <option value='2'><?php echo __('Target Goal/Target Date', 'galaxyfunder'); ?></option>
                                            <option value='4'><?php echo __('Campaign Never Ends', 'galaxyfunder'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_targetquantity_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type='number' placeholder="<?php echo get_option('cf_submission_camp_targetquantity_placeholder'); ?>"  style='width:100%;' data-rule-required='true' data-msg-required ="<?php echo 'Target Quantity'; ?>" name='cf_campaign_targetquantity_value' class='cf_campaign_targetquantity_value' id='cf_campaign_targetquantity_value' value=''/>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_productprice_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type='number' placeholder="<?php echo 'Product Price'; ?>"  style='width:100%;' data-rule-required='true' data-msg-required ="<?php echo 'Product Price'; ?>" name='cf_campaign_productprice_value' class='cf_campaign_productprice_value' id='cf_campaign_productprice_value' value=''/>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_targetprice_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type='number' placeholder="<?php echo get_option('cf_submission_camp_targetprice_placeholder'); ?>"  style='width:100%;' data-rule-required='true' data-msg-required ="<?php echo get_option('_cf_empty_campaign_target_goal'); ?>" name='cf_campaign_target_value' class='cf_campaign_target_value' id='cf_campaign_target_value' value=''/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_duration_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="number" placeholder="<?php echo get_option('cf_submission_camp_duration_placeholder'); ?>" style="width:100%" data-rule-required="true" data-msg-required="<?php echo get_option('_cf_empty_campaign_target_days'); ?>" name="crowdfunding_duration" class="cf_campaign_duration" value=""/>
                                </td>
                            </tr>
                            <?php if (get_option('cf_submission_camp_minimumprice_showhide') == 'no') { ?>
                                <tr>
                                    <th>
                                        <label>
                                            <?php echo get_option('cf_submission_camp_minimumprice_label'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type='number' placeholder ="<?php echo get_option('cf_submission_camp_minimumprice_placeholder'); ?>" style='width:100%;' name='cf_campaign_min_price' class='cf_campaign_min_price' id='cf_campaign_min_price' value=''/>
                                    </td>
                                </tr>
                            <?php }if (get_option('cf_submission_camp_recommendedprice_showhide') == 'no') { ?>
                                <tr>
                                    <th>
                                        <label>
                                            <?php echo get_option('cf_submission_camp_recommendedprice_label'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type='number' placeholder="<?php echo get_option('cf_submission_camp_recommendedprice_placeholder'); ?>" style='width:100%;' name='cf_campaign_rec_price' class='cf_campaign_rec_price' id='cf_campaign_rec_price' value=''/>
                                    </td>
                                </tr>   
                            <?php } if (get_option('cf_submission_camp_maximumprice_showhide') == 'no') { ?>
                                <tr>
                                    <th>
                                        <label>
                                            <?php echo get_option('cf_submission_camp_maximumprice_label'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <input type='number' placeholder="<?php echo get_option('cf_submission_camp_maximumprice_placeholder'); ?>" style='width:100%;' name='cf_campaign_max_price' class='cf_campaign_max_price' id='cf_campaign_max_price' value=''/>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>
                                    <label>
                                        <?php echo get_option('cf_submission_camp_description_label'); ?>
                                    </label>
                                </th>
                                <td>
                                    <textarea name="campaign_description" data-rule-required="true" data-msg-required="<?php echo get_option('cf_empty_campaign_description'); ?>" style='width:100%;' data-rule-required="true" rows="10" cols="45" class="campaign_description" id="campaign_description"></textarea>
                                </td>
                            </tr>
                            <?php if (get_option('cf_show_hide_social_promotion_frontend') == '1') { ?>
                                <tr>
                                    <th> 
                                        <label><?php echo get_option('cf_campaign_social_sharing_label'); ?></label>
                                    </th>
                                    <td> 
                                        <input type="checkbox" name="cf_newcampaign_social_sharing" id="cf_newcampaign_social_sharing" class="cf_newcampaign_social_sharing" value="yes"/>
                                    </td>
                                </tr>
                                <tr class="_social_promotion_facebook" style="display: none">
                                    <th>
                                        <label><?php echo get_option('cf_campaign_social_promotion_facebook_label'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="cf_newcampaign_social_sharing_facebook" id="cf_newcampaign_social_sharing_facebook" class="cf_newcampaign_social_sharing_facebook" value="yes"/>
                                    </td>
                                </tr>
                                <tr class="_social_promotion_twitter" style="display: none">
                                    <th>
                                        <label><?php echo get_option('cf_campaign_social_promotion_twitter_label'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="cf_newcampaign_social_sharing_twitter" id="cf_newcampaign_social_sharing_twitter" class="cf_newcampaign_social_sharing_twitter" value="yes"/>
                                    </td>
                                </tr>
                                <tr class="_social_promotion_google" style="display: none">
                                    <th>
                                        <label><?php echo get_option('cf_campaign_social_promotion_google_label'); ?></label>
                                    </th> 
                                    <td>
                                        <input type="checkbox" name="cf_newcampaign_social_sharing_google" id="cf_newcampaign_social_sharing_google" class="cf_newcampaign_social_sharing_google" value="yes"/>
                                    </td>
                                </tr>
                            <?php } if (get_option('cf_show_hide_contributor_table_settings_frontend') == '1') { ?>
                                <tr>
                                    <th>
                                        <label><?php echo get_option('cf_campaign_show_contributor_label'); ?></label>
                                    </th> 
                                    <td>
                                        <input type="checkbox" name="cf_newcampaign_show_hide_contributors" id="cf_newcampaign_show_hide_contributors" class="cf_newcampaign_show_hide_contributors" value="yes"/>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class ="mark_contributor_as_anonymous" style="display: none">
                                <th>
                                    <label><?php echo get_option('cf_campaign_mark_contributor_as_anonymous_label'); ?></label>
                                </th> 
                                <td>
                                    <input type="checkbox" name="cf_newcampaign_mark_contributors_anonymous" id="cf_newcampaign_mark_contributors_anonymous" class="cf_newcampaign_mark_contributors_anonymous" value="yes"/>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label><?php echo get_option('cf_submission_camp_featuredimage_label'); ?></label>
                                </th>
                                <td>
                                    <input type="file" name="cf_featured_image">
                                </td>
                            </tr>
                            <?php if (get_option('cf_show_hide_add_perk_button_frontend') == '1') { ?>
                                <tr>
                                    <th>
                                        <label>

                                        </label>
                                    </th>
                                    <td>

                                        <div id="meta_inner">
                                            <?php
                                            $i = 0;

                                            wp_nonce_field(plugin_basename(__FILE__), 'ufperkrulenonce');
                                            ?>
                                            <span id="ufhere"></span>

                                            <button class="ufadd button-primary" style="margin-top:10px;margin-bottom:10px;"><?php _e(get_option('cf_add_perk_rule_caption'), 'galaxyfunder'); ?></button>
                                            <script>
                                                jQuery(document).ready(function () {
                                                    jQuery('.cf_newcampaign_social_sharing').removeAttr('checked');
                                                    jQuery('.cf_newcampaign_show_hide_contributors').removeAttr('checked');
                                                    jQuery(".ufadd").click(function () {
                                                        var countperk = Math.round(new Date().getTime() + (Math.random() * 100));

                                                        jQuery('#ufhere').append('<div class="panel woocommerce_options_panel" style="display: block;"><div style=" border:1px solid black;padding:10px;" class="options_group"><p class="form-field"><label><?php echo get_option('cf_custom_perk_name_label'); ?></label><input type="text" name="perk[' + countperk + '][name]" class="short" value=""/></p>\n\
                            \n\<p class="form-field"><label><?php echo get_option('cf_custom_perk_amount_label'); ?> </label><input type="text" id="perkamount' + countperk + '" name="perk[' + countperk + '][amount]" class="short" value=""/></p>\n\
                            <p class="form-field"><label><?php echo get_option('cf_custom_perk_description_label'); ?> </label><textarea rows="3" cols="14" style="height:110px;width:360px;" name="perk[' + countperk + '][description]" class="short" value=""></textarea></p>\n\
                                <p class="form-field"><label><?php echo get_option('cf_custom_perk_claim_count_label'); ?> </label><select name="perk[' + countperk + '][limitperk]" id="perk_limitation' + countperk + '" class="cf_limit_perk_count"><option value ="cf_limited" >Limited</option><option value ="cf_unlimited">Unlimited</option></select></p>\n\
                        <p class="form-field"><label><?php echo get_option('cf_custom_perk_claim_count_label'); ?> </label><input type ="text" id="perk_claimcount' + countperk + '" name="perk[' + countperk + '][claimcount]" class="short test"  value=""/></p>\n\
                        <?php if (get_option('cf_show_hide_estimated_del_field_frontend') == '1') { ?><p class="form-field"><label><?php echo get_option('cf_custom_perk_delivery_label'); ?> </label><input type="text" name="perk[' + countperk + '][deliverydate]" id="perkid' + countperk + '" class="short" value=""/></p><?php } ?><button class="ufremove button-primary"><?php echo get_option('cf_remove_perk_rule_caption'); ?></button></div></div>');
                                                        if (jQuery('#perk_limitation' + countperk).val() == 'cf_unlimited') {
                                                            jQuery('#perk_claimcount' + countperk).parent().hide();
                                                        } else {
                                                            jQuery('#perk_claimcount' + countperk).parent().show();
                                                        }
                                                        jQuery(document).on('change', '#perk_limitation' + countperk, function () {
                                                            if (jQuery(this).val() == 'cf_unlimited') {
                                                                jQuery('#perk_claimcount' + countperk).parent().hide();
                                                            } else {
                                                                jQuery('#perk_claimcount' + countperk).parent().show();
                                                            }
                                                        });
                                                        jQuery('#perkid' + countperk).datepicker({
                                                            changeMonth: true,
                                                        });
                                                        return false;
                                                    });

                                                    jQuery('#cf_newcampaign_social_sharing').click(function () {
                                                        jQuery('._social_promotion_facebook').toggle();
                                                        jQuery('._social_promotion_twitter').toggle();
                                                        jQuery('._social_promotion_google').toggle();
                                                    });

                                                    jQuery('#cf_newcampaign_show_hide_contributors').click(function () {
                                                        jQuery('.mark_contributor_as_anonymous').toggle();
                                                    });
                                                    jQuery(document).on('click', '.ufremove', function () {
                        <?php if (get_option('cf_enable_remove_perk_rule') == 'yes') { ?>
                                                            var didConfirm = confirm("<?php echo get_option('cf_custom_remove_perk_confirmation_message'); ?>");
                                                            if (didConfirm === true) {
                                                                jQuery(this).parent().remove();
                                                                return false;
                                                            }
                        <?php } else { ?>
                                                            jQuery(this).parent().remove();
                                                            return false;
                        <?php } ?>
                                                        return false;
                                                    });
                                                });</script>
                                        </div>
                                    </td>
                                </tr>
                            <?php } if (get_option('cf_show_hide_billing_details_frontend') == '1') {
                                ?>
                                <tr class="cf_address"><td><h5><?php _e('Billing Information', 'galaxyfunder'); ?></h5></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing First Name', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_first_name" id="billing_first_name" value="<?php echo $bill_first_name; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Last Name', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_last_name" id="billing_last_name" value="<?php echo $bill_last_name; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Company', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_company" id="billing_company" value="<?php echo $bill_company; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Address 1', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_address_1" id="billing_address_1" value="<?php echo $bill_address1; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Address 2', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_address_2" id="billing_address_2" value="<?php echo $bill_address2; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing City', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_city" id="billing_city" value="<?php echo $bill_city; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Postcode', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_postcode" id="billing_postcode" value="<?php echo $bill_postcode; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Country', 'galaxyfunder'); ?></label></th><td><select id="cf_billing_country" name="cf_billing_country"><?php echo $country_obj->country_dropdown_options($bill_country, '*'); ?></select></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing State', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_state" id="billing_state" value="<?php echo $bill_state; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Email', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_email" id="billing_email" value="<?php echo $bill_email; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Billing Phone', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="billing_phone" id="billing_phone" value="<?php echo $bill_phone; ?>"/></td></tr>
                            <?php } if (get_option('cf_show_hide_shipping_details_frontend') == '1') { ?>
                                <tr class="cf_address"><td><h5><?php _e('Shipping Information', 'galaxyfunder'); ?></h5></td></tr><?php
                                if (get_option('cf_show_hide_billing_details_frontend') == '1') {
                                    ?>
                                    <tr class="cf_address"><th><label><?php _e('Same as Billing Information', 'galaxyfunder'); ?></label></th><td><input type="checkbox" name="same_as_billing" id="same_as_billing" value="1"/></td></tr>
                                <?php } ?>
                                <tr class="cf_address"><th><label><?php _e('Shipping First Name', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_first_name" id="shipping_first_name" value="<?php echo $ship_first_name; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Last Name', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_last_name" id="shipping_last_name" value="<?php echo $ship_last_name; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Company', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_company" id="shipping_company" value="<?php echo $ship_company; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Address 1', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_address_1" id="shipping_address_1" value="<?php echo $ship_address1; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Address 2', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_address_2" id="shipping_address_2" value="<?php echo $ship_address2; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping City', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="cf_shipping_city" id="cf_shipping_city" value="<?php echo $ship_city; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Postcode', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo $ship_postcode; ?>"/></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping Country', 'galaxyfunder'); ?></label></th><td><select id="cf_shipping_country" name="cf_shipping_country"><?php echo $country_obj->country_dropdown_options($ship_country, '*'); ?></select></td></tr>
                                <tr class="cf_address"><th><label><?php _e('Shipping State', 'galaxyfunder'); ?></label></th><td><input style="width:100%;" type="text" name="shipping_state" id="shipping_state" value="<?php echo $ship_state; ?>"/></td></tr>
                                <tr><?php
                                }
                                if (get_option('cf_show_hide_category_selection_frontend') == '1') {
                                    if (get_option('cf_frontend_categories_selection_type') == '1') {
                                        $terms = get_terms('product_cat', array('hide_empty' => false));
                                        if ($terms) {
                                            ?>
                                        <tr class="cf_newcampaign_select_category">
                                            <th>
                                                <label>
                                                    <?php _e('Choose Category', 'galaxyfunder'); ?>
                                                </label>
                                            </th>
                                            <td>
                                                <select style="width:300px;" name="cf_newcampaign_choose_category[]" id="cf_newcampaign_choose_category" class="cf_newcampaign_choose_category" multiple="multiple">
                                                    <?php
                                                    $selected_categories_type = get_option('cf_frontend_categories_selection_type');
                                                    if ($selected_categories_type == '1') {
                                                        $selected_categories = FP_GF_Common_Functions::cf_all_categories();
                                                    } else {
                                                        $selected_categories = get_option('cf_frontend_selected_categories');
                                                    }


                                                    foreach ($selected_categories as $each_category) {
                                                        $category_object = get_term($each_category, 'product_cat');
                                                        if ($category_object->parent > 0) {
                                                            ?>
                                                            <option value="<?php echo $category_object->term_id; ?>"><?php echo get_cat_name($category_object->parent) . '<br>' . '-' . $category_object->name; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $category_object->term_id; ?>"><?php echo $category_object->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="cf_newcampaign_select_category">
                                        <th>
                                            <label>
                                                <?php _e('Choose Category', 'galaxyfunder'); ?>
                                            </label>
                                        </th>
                                        <td>
                                            <select style="width:300px;" name="cf_newcampaign_choose_category[]" id="cf_newcampaign_choose_category" class="cf_newcampaign_choose_category" multiple="multiple">
                                                <?php
                                                $selected_categories = get_option('cf_frontend_selected_categories');

                                                foreach ($selected_categories as $each_category) {
                                                    $category_object = get_term($each_category, 'product_cat');
                                                    if ($category_object->parent > 0) {
                                                        ?>
                                                        <option value="<?php echo $category_object->term_id; ?>"><?php echo get_cat_name($category_object->parent) . '<br>' . '-' . $category_object->name; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $category_object->term_id; ?>"><?php echo $category_object->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            if (get_option('cf_show_paypal_email_id_frontend') == '1') {
                                ?>
                                <tr>
                                    <th><label><?php _e('Enter your PayPal ID', 'galaxyfunder'); ?></label></th>
                                    <td><input type="text" name="cf_campaigner_paypal_id" id="cf_campaigner_paypal_id" value=""/></td>
                                </tr>
                            <?php } ?>
                            <th><label><?php echo get_option("cf_submission_camp_i_agree_label"); ?></label></th>
                            <td><input type="checkbox" name="iagree" value="1" data-rule-required="true" data-msg-required="<?php echo '<br>' . get_option('_cf_i_agree_checkbox_error_message') ?>"></td>
                            </tr>

                        </table>
                        <input type="hidden" name="action" id="action" value="cf_process_form"/>
                        <input type="hidden" name="cf_user_id" id="cf_user_id" value="<?php echo get_current_user_id(); ?>"/>
                        <input type="submit" name="cf_submit" class="cf_submit" id="cf_submit" value="<?php _e('Submit Campaign', 'galaxyfunder'); ?>"/>
                    </form>
                    <script type='text/javascript'>
                        jQuery(document).ready(function () {

                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                //jQuery('#_cf_product_selection').chosen();
                                jQuery('#cf_billing_country').chosen();
                                jQuery('#cf_shipping_country').chosen();
                                jQuery('#crowdfunding_options').chosen();
                                jQuery('._target_end_selection1').chosen();
                    <?php } else { ?>
                                //jQuery('#_cf_product_selection').select2();
                                jQuery('#cf_billing_country').select2();
                                jQuery('#cf_shipping_country').select2();
                                jQuery('#crowdfunding_options').select2();
                                jQuery('._target_end_selection1').select2();
                        <?php
                    }
                    if (get_option('cf_show_hide_crowdfunding_type') == '2') {
                        if (get_option('cf_crowdfunding_type_selection') == '2') {
                            ?>
                                    jQuery('.cf_address').show();
                                    jQuery('#_cf_product_selection').parent().parent().show();
                                    jQuery('#use_selected_product_image').parent().parent().show();
                        <?php } else { ?>
                                    jQuery('.cf_address').hide();
                                    jQuery('#_cf_product_selection').parent().parent().hide();
                                    jQuery('#use_selected_product_image').parent().parent().hide();
                            <?php
                        }
                    } else {
                        ?>  //  On Page Load need to check what option it was
                                var onloadvalue = jQuery('#crowdfunding_options').val();
                                jQuery('#_target_end_selection1').hide();
                                if (onloadvalue === '1') {
                                    //target qty code
                                    jQuery('#_target_end_selection1').html('');
                                    var append_options = '<option value="3"><?php echo __('Target Goal', 'galaxyfunder') ?></option><option value="1"><?php echo __('Target Date', 'galaxyfunder') ?></option><option value="4"><?php echo __('Target Goal/Target Date', 'galaxyfunder') ?></option><option value="5"><?php echo __('Target Quantity', 'galaxyfunder') ?></option><option value="2"><?php echo __('Campaign Never Ends', 'galaxyfunder') ?></option>';
                                    jQuery('#_target_end_selection1').append(append_options);
                                    //target qty code end    
                                    jQuery('.cf_address').hide();
                                    jQuery('#_cf_product_selection').parent().parent().hide();
                                    jQuery('#use_selected_product_image').parent().parent().hide();
                                } else {
                                    //target qty code
                                    jQuery('#_target_end_selection1').html('');
                                    var append_options = '<option value="3"><?php echo __('Target Goal', 'galaxyfunder') ?></option><option value="1"><?php echo __('Target Date', 'galaxyfunder') ?></option><option value="4"><?php echo __('Target Goal/Target Date', 'galaxyfunder') ?></option><option value="2"><?php echo __('Campaign Never Ends', 'galaxyfunder') ?></option>';
                                    jQuery('#_target_end_selection1').append(append_options);
                                    //target qty code end        
                                    jQuery('.cf_address').show();
                                    jQuery('#_cf_product_selection').parent().parent().show();
                                    jQuery('#use_selected_product_image').parent().parent().show();
                        <?php if (get_option("cf_show_hide_target_product_purchase_frontend") == '1') { ?>
                                        jQuery('#cf_campaign_target_value').parent().parent().show();
                        <?php } else { ?>
                                        jQuery('#cf_campaign_target_value').parent().parent().hide();
                        <?php } ?>
                                }
                                jQuery('#crowdfunding_options').change(function () {
                                    var current_purpose = jQuery(this).val();
                                    if (current_purpose === '1') {
                                        //target qty code
                        <?php if (get_option('cf_show_hide_campaign_end_selection_frontend') != '2') { ?>
                                            jQuery('#_target_end_selection1').html('');
                                            var append_options = '<option value="3"><?php echo __('Target Goal', 'galaxyfunder') ?></option><option value="1"><?php echo __('Target Date', 'galaxyfunder') ?></option><option value="4"><?php echo __('Target Goal/Target Date', 'galaxyfunder') ?></option><option value="5"><?php echo __('Target Quantity', 'galaxyfunder') ?></option><option value="2"><?php echo __('Campaign Never Ends', 'galaxyfunder') ?></option>';
                                            jQuery('#_target_end_selection1').append(append_options);
                        <?php } else {
                            ?>
                                            jQuery('#_target_end_selection1').closest('tr').hide();
                                            var campaign_end_method = "<?php echo get_option('cf_campaign_end_method_for_frbcf') ?>";
                                            if (campaign_end_method == '3' || campaign_end_method == '2') {
                                                jQuery('.cf_campaign_target_value').closest('tr').show();
                                                jQuery('.cf_campaign_duration').closest('tr').hide();
                                                jQuery('.cf_campaign_targetquantity_value').closest('tr').hide();
                                            } else if (campaign_end_method == '1' || campaign_end_method == '4') {
                                                jQuery('.cf_campaign_target_value').closest('tr').show();
                                                jQuery('.cf_campaign_duration').closest('tr').show();
                                                jQuery('.cf_campaign_targetquantity_value').closest('tr').hide();
                                            } else {
                                                jQuery('.cf_campaign_target_value').closest('tr').hide();
                                                jQuery('.cf_campaign_duration').closest('tr').hide();
                                                jQuery('.cf_campaign_targetquantity_value').closest('tr').show();
                                            }
                        <?php }
                        ?>
                                        //target qty code end
                                        jQuery('.cf_address').hide();
                                        jQuery('#_cf_product_selection').parent().parent().hide();
                                        jQuery('#use_selected_product_image').parent().parent().hide();
                                    } else {
                                        //target qty code
                        <?php if (get_option('cf_show_hide_campaign_end_selection_frontend') != '2') { ?>
                                            jQuery('#_target_end_selection1').html('');
                                            var append_options = '<option value="3"><?php echo __('Target Goal', 'galaxyfunder') ?></option><option value="1"><?php echo __('Target Date', 'galaxyfunder') ?></option><option value="4"><?php echo __('Target Goal/Target Date', 'galaxyfunder') ?></option><option value="2"><?php echo __('Campaign Never Ends', 'galaxyfunder') ?></option>';
                                            jQuery('#_target_end_selection1').append(append_options);
                        <?php } else {
                            ?>
                                            jQuery('#_target_end_selection1').closest('tr').hide();
                                            var campaign_end_method = "<?php echo get_option('cf_campaign_end_method_for_ppbcf') ?>";
                                            if (campaign_end_method == '3' || campaign_end_method == '2') {
                                                jQuery('.cf_campaign_target_value').closest('tr').show();
                                                jQuery('.cf_campaign_duration').closest('tr').hide();
                                                jQuery('.cf_campaign_targetquantity_value').closest('tr').hide();
                                            } else if (campaign_end_method == '1' || campaign_end_method == '4') {
                                                jQuery('.cf_campaign_target_value').closest('tr').show();
                                                jQuery('.cf_campaign_duration').closest('tr').show();
                                                jQuery('.cf_campaign_targetquantity_value').closest('tr').hide();
                                            }
                        <?php }
                        ?>
                                        jQuery('.cf_address').show();
                                        jQuery('#_cf_product_selection').parent().parent().show();
                                        jQuery('#use_selected_product_image').parent().parent().show();
                        <?php if (get_option("cf_show_hide_target_product_purchase_frontend") == '1') { ?>
                                            jQuery('#cf_campaign_target_value').parent().parent().show();
                        <?php } else { ?>
                                            jQuery('#cf_campaign_target_value').parent().parent().hide();
                        <?php } ?>
                                    }
                                });
                    <?php }
                    ?>
                    <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                                var value = jQuery('#crowdfunding_options').val();
                    <?php } else {
                        ?>
                                var value = "<?php echo get_option('cf_crowdfunding_type_selection') ?>";
                    <?php }
                    ?>
                    <?php if (get_option('cf_show_hide_campaign_end_selection_frontend') == '1') { ?>
                                var campaign_onload_end_method = jQuery('#_target_end_selection1').val();
                    <?php } else {
                        ?>
                                if (value != '2') {
                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_frbcf'); ?>";
                                } else {
                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_ppbcf'); ?>";
                                }
                    <?php }
                    ?>
                            function_name(campaign_onload_end_method, value);
//                            jQuery('#crowdfunding_options').change(function () {
//                                if (this.value != '2') {
//                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_frbcf'); ?>";
//                                } else {
//                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_ppbcf'); ?>";
//                                }
//                                function_name(campaign_onload_end_method, this.value);
//                            });

                            function function_name(campaign_onload_end_method, value) {
                                if (campaign_onload_end_method === '3' || campaign_onload_end_method === '2') {
                                    // For Target Goal Target Date is not required
                                    jQuery('.cf_campaign_duration').closest('tr').hide();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').closest('tr').show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').closest('tr').hide();
                                        }
                    <?php } else { ?>
                                        jQuery('.cf_campaign_target_value').closest('tr').show();
                    <?php } ?>
                                } else if (campaign_onload_end_method === '4') {
                                    //For Target Date Target Goal is not required
                                    jQuery('.cf_campaign_duration').closest('tr').show();
                                    jQuery('.cf_campaign_target_value').closest('tr').hide();

                                } else if (campaign_onload_end_method === '1') {
                                    //Both Target Goal as well as Target Date is required
                                    jQuery('.cf_campaign_duration').closest('tr').show();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').closest('tr').show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').closest('tr').hide();
                                        }
                    <?php } else { ?>
                                        jQuery('.cf_campaign_target_value').closest('tr').show();
                    <?php } ?>
                                } else {
                                    // Target Date is not required
                                    jQuery('.cf_campaign_duration').closest('tr').hide();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').closest('tr').show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').closest('tr').hide();
                                        }
                    <?php } else { ?>
                                        jQuery('.cf_campaign_target_value').closest('tr').show();
                    <?php } ?>
                                }
                            }

                            jQuery('#_target_end_selection1').change(function () {
                                var value = jQuery('#crowdfunding_options').val();
                                var campaign_onload_end_method = jQuery('#_target_end_selection1').val();
                                if (campaign_onload_end_method === '3') {
                                    // For Target Goal Target Date is not required
                                    jQuery('.cf_campaign_duration').parent().parent().hide();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').parent().parent().show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').parent().parent().hide();
                                        }
                    <?php } else { ?>
                                        // jQuery('.cf_campaign_target_value').parent().parent().show();
                    <?php } ?>
                                } else if (campaign_onload_end_method === '1' || campaign_onload_end_method === '4') {
                                    //For Target Date Target Goal is not required
                                    jQuery('.cf_campaign_duration').parent().parent().show();
                                    jQuery('.cf_campaign_target_value').parent().parent().hide();

                                } else if (campaign_onload_end_method === '2') {
                                    //Both Target Goal as well as Target Date is required
                                    jQuery('.cf_campaign_duration').parent().parent().show();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').parent().parent().show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').parent().parent().hide();
                                        }
                    <?php } else { ?>
                                        jQuery('.cf_campaign_target_value').parent().parent().show();
                    <?php } ?>
                                } else {
                                    // Target Date is not required
                                    jQuery('.cf_campaign_duration').parent().parent().hide();
                    <?php if (get_option('cf_show_hide_target_product_purchase_frontend') == '2') { ?>
                                        if (value != '2') {
                                            jQuery('.cf_campaign_target_value').parent().parent().show();
                                        } else {
                                            jQuery('.cf_campaign_target_value').parent().parent().hide();
                                        }
                    <?php } else { ?>
                                        jQuery('.cf_campaign_target_value').parent().parent().show();
                    <?php } ?>

                                }
                            });
                    <?php if (get_option('cf_show_hide_campaign_end_selection_frontend') == '1') { ?>
                                var campaign_onload_end_method = jQuery('#_target_end_selection1').val();
                    <?php } else {
                        ?>
                        <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                                    var crowdfundtype = jQuery('#crowdfunding_options').val();
                        <?php } else {
                            ?>
                                    var crowdfundtype = "<?php echo get_option('cf_crowdfunding_type_selection') ?>";
                        <?php }
                        ?>
                                if (crowdfundtype != '2') {
                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_frbcf'); ?>";
                                } else {
                                    var campaign_onload_end_method = "<?php echo get_option('cf_campaign_end_method_for_ppbcf'); ?>";
                                }
                    <?php }
                    ?>
                            if (campaign_onload_end_method == 5) {
                                jQuery('#cf_campaign_target_value').parent().parent().hide();
                                jQuery('#cf_campaign_rec_price').parent().parent().hide();
                                jQuery('#cf_campaign_max_price').parent().parent().hide();
                                jQuery('#cf_campaign_min_price').parent().parent().hide();
                                jQuery('#cf_campaign_targetquantity_value').parent().parent().show();
                                jQuery('#cf_campaign_productprice_value').parent().parent().show();

                            } else {
                                jQuery('#cf_campaign_target_value').parent().parent().show();
                                jQuery('#cf_campaign_rec_price').parent().parent().show();
                                jQuery('#cf_campaign_max_price').parent().parent().show();
                                jQuery('#cf_campaign_min_price').parent().parent().show();
                                jQuery('#cf_campaign_targetquantity_value').parent().parent().hide();
                                jQuery('#cf_campaign_productprice_value').parent().parent().hide();


                            }
                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                jQuery('.cf_newcampaign_choose_category').chosen();
                    <?php } else { ?>
                                jQuery('.cf_newcampaign_choose_category').select2();
                    <?php } ?>


                            //target qty code 
                            jQuery('#_target_end_selection1').change(function () {
                                var campaign_onload_end_method = jQuery('#_target_end_selection1').val();
                                if (campaign_onload_end_method == 5) {
                                    jQuery('#cf_campaign_target_value').parent().parent().hide();
                                    jQuery('#cf_campaign_rec_price').parent().parent().hide();
                                    jQuery('#cf_campaign_max_price').parent().parent().hide();
                                    jQuery('#cf_campaign_min_price').parent().parent().hide();
                                    jQuery('#cf_campaign_targetquantity_value').parent().parent().show();
                                    jQuery('#cf_campaign_productprice_value').parent().parent().show();

                                } else {
                                    jQuery('#cf_campaign_target_value').parent().parent().show();
                                    jQuery('#cf_campaign_rec_price').parent().parent().show();
                                    jQuery('#cf_campaign_max_price').parent().parent().show();
                                    jQuery('#cf_campaign_min_price').parent().parent().show();
                                    jQuery('#cf_campaign_targetquantity_value').parent().parent().hide();
                                    jQuery('#cf_campaign_productprice_value').parent().parent().hide();
                                }
                            });


                            jQuery('#_cf_product_selection').change(function () {
                                var thisvalue = 0;
                                jQuery('#_cf_product_selection > option:selected').each(function () {
                                    var value = jQuery(this).attr('data-price');
                                    thisvalue = parseFloat(thisvalue) + parseFloat(value);
                                    thisvalue = thisvalue.toFixed(2);
                                });
                                jQuery("#cf_campaign_target_value").val(thisvalue);
                                jQuery('#cf_campaign_target_value').attr('readonly', true);
                            });
                            jQuery('#_cf_product_selection').hide();

                        });
                    </script>
                    <style type='text/css'>
                        .chosen-container-multi .chosen-choices li.search-field input{
                            height:25px !important;
                        }
                    </style>
                    <?php
                } else {
                    ?> <h4><?php echo __('You can not submit campaign', 'galaxyfunder') ?></h4><?php
                }
            } else {
                // For Guest Show Any Message
                $url_to_redirect = get_option("cf_submission_camp_guest_url");
                $newurl_to_redirect = esc_url_raw(add_query_arg('redirect_to', get_permalink(), $url_to_redirect));
                header('Location:' . $newurl_to_redirect);
            }
            $content = ob_get_clean();
            $cf_active_products = FP_GF_Common_Functions::getcountofactivecampaigns(get_current_user_id());
            $cf_campaign_limit = get_option('cf_campaign_limit_value');
            $cf_campaign_exceeded_message = get_option('cf_campaign_exceeded_message');
            $cf_campaign_limitcheck_front = get_option('cf_campaign_limit');
            if ($cf_campaign_limitcheck_front == 'yes') {
                if ($cf_active_products >= $cf_campaign_limit) {
                    echo $cf_campaign_exceeded_message;
                } else {
                    return $content;
                }
            } else {
                return $content;
            }
        }

        public static function perform_frontend_form() {
            $campaign_purpose = '';
            $campaign_title = '';
            $campaign_end_method = '';
            $charge_amount_from_pledgers = '';
            $campaign_product_selection = '';
            $campaign_target_goal = '';
            $campaign_target_days = '';
            $campaign_minimum_price = '';
            $campaign_recommended_price = '';
            $campaign_maximum_price = '';
            $campaign_perk_rule = '';
            $campaign_description = '';
            $campaign_social_promotion = '';
            $campaign_social_promotion_facebook = '';
            $campaign_social_promotion_twitter = '';
            $campaign_social_promotion_google = '';
            $campaign_show_contributor_table = '';
            $campaign_mark_contributor_as_anonymous = '';
            $tmp_file = '';
            $uploadfile = '';
            $campaign_use_selected_product_featured_image = '';
            $galaxy_target_qty = '';

            if (get_option('cf_show_hide_crowdfunding_type') == '1') {
                $campaign_purpose = $_POST['crowdfunding_options'];
            } else {
                $campaign_purpose = get_option('cf_crowdfunding_type_selection');
            }
            if (isset($_POST['_cf_product_selection'])) {
                $campaign_product_selection = $_POST['_cf_product_selection'];
            }
            if (isset($_POST['use_selected_product_image'])) {
                $campaign_use_selected_product_featured_image = $_POST['use_selected_product_image'];
            }

            if (isset($_POST['cf_campaign_title'])) {
                $campaign_title = $_POST['cf_campaign_title'];
            }
            if (get_option('cf_show_hide_campaign_end_selection_frontend') != '2') {
                if (isset($_POST['_target_end_selection1'])) {
                    $campaign_end_method = $_POST['_target_end_selection1'];
                }
            } else {
                if ($campaign_purpose == '1') {
                    $campaign_end_method = get_option('cf_campaign_end_method_for_frbcf');
                } else {
                    $campaign_end_method = get_option('cf_campaign_end_method_for_ppbcf');
                }
            }
//        if (isset($_POST['uf_frontend_charge_from_pledgers'])) {
//            $charge_amount_from_pledgers = $_POST['uf_frontend_charge_from_pledgers'];
//        }
            if (isset($_POST['cf_campaign_target_value'])) {
                $campaign_target_goal = $_POST['cf_campaign_target_value'];
            }
            if (isset($_POST['crowdfunding_duration'])) {
                $campaign_target_days = $_POST['crowdfunding_duration'];
            }
            if (isset($_POST['cf_campaign_min_price'])) {
                $campaign_minimum_price = $_POST['cf_campaign_min_price'];
            }
            if (isset($_POST['cf_campaign_rec_price'])) {
                $campaign_recommended_price = $_POST['cf_campaign_rec_price'];
            }
            if (isset($_POST['cf_campaign_max_price'])) {
                $campaign_maximum_price = $_POST['cf_campaign_max_price'];
            }
            if (isset($_POST['campaign_description'])) {
                $campaign_description = $_POST['campaign_description'];
            }
            if (isset($_POST['cf_newcampaign_social_sharing'])) {
                $campaign_social_promotion = $_POST['cf_newcampaign_social_sharing'];
            }
            if (isset($_POST['cf_newcampaign_social_sharing_facebook'])) {
                $campaign_social_promotion_facebook = $_POST['cf_newcampaign_social_sharing_facebook'];
            }
            if (isset($_POST['cf_newcampaign_social_sharing_twitter'])) {
                $campaign_social_promotion_twitter = $_POST['cf_newcampaign_social_sharing_twitter'];
            }
            if (isset($_POST['cf_newcampaign_social_sharing_google'])) {
                $campaign_social_promotion_google = $_POST['cf_newcampaign_social_sharing_google'];
            }
            if (isset($_POST['cf_newcampaign_show_hide_contributors'])) {
                $campaign_show_contributor_table = $_POST['cf_newcampaign_show_hide_contributors'];
            }
            if (isset($_POST['cf_newcampaign_mark_contributors_anonymous'])) {
                $campaign_mark_contributor_as_anonymous = $_POST['cf_newcampaign_mark_contributors_anonymous'];
            }

            if (isset($_POST['cf_campaign_targetquantity_value'])) {
                $galaxy_target_qty = $_POST['cf_campaign_targetquantity_value'];
            }

            if (isset($_POST['cf_campaign_productprice_value'])) {
                $galaxy_target_qty_prod_price = $_POST['cf_campaign_productprice_value'];
            }
            if (!isset($_POST['ufperkrulenonce']))
                return;
            if (!wp_verify_nonce($_POST['ufperkrulenonce'], plugin_basename(__FILE__)))
                return;

            if (isset($_POST['perk'])) {
                $campaign_perk_rule = $_POST['perk'];
            }
            if (isset($_POST['cf_campaigner_paypal_id'])) {
                $campaigner_paypal_email = $_POST['cf_campaigner_paypal_id'];
            }


            if (get_option('cf_frontend_submission_method') == '1') {
                $arg = array('post_type' => 'product', 'post_content' => $campaign_description, 'post_title' => $campaign_title, 'post_author' => $_POST['cf_user_id'], 'post_status' => 'pending');
            } else {
                $arg = array('post_type' => 'product', 'post_content' => $campaign_description, 'post_title' => $campaign_title, 'post_author' => $_POST['cf_user_id'], 'post_status' => 'publish');
            }
            $campaign_id = wp_insert_post($arg);

            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingcheckboxvalue', 'yes');
            fp_gf_update_campaign_metas($campaign_id, '_crowdfunding_options', $campaign_purpose);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundinggetdescription', $campaign_description);
            fp_gf_update_campaign_metas($campaign_id, '_cf_product_selection', $campaign_product_selection);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundinggetminimumprice', $campaign_minimum_price);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundinggetrecommendedprice', $campaign_recommended_price);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundinggetmaximumprice', $campaign_maximum_price);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundinggettargetprice', $campaign_target_goal);
            fp_gf_update_campaign_metas($campaign_id, '_target_end_selection', $campaign_end_method);
            // fp_gf_update_campaign_metas($campaign_id, '_universe_funder_charge_from_pledgers', $charge_amount_from_pledgers);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingtodateindays', $campaign_target_days);

            //-----galaxy metas---//
            fp_gf_update_campaign_metas($campaign_id, 'cf_campaigner_paypal_id', $campaigner_paypal_email);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingsocialsharing', $campaign_social_promotion);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingsocialsharing_facebook', $campaign_social_promotion_facebook);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingsocialsharing_twitter', $campaign_social_promotion_twitter);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingsocialsharing_google', $campaign_social_promotion_google);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfunding_showhide_contributor', $campaign_show_contributor_table);
            fp_gf_update_campaign_metas($campaign_id, '_crowdfunding_contributor_anonymous', $campaign_mark_contributor_as_anonymous);

            if ($galaxy_target_qty_prod_price != '') {
                fp_gf_update_campaign_metas($campaign_id, '_regular_price', $galaxy_target_qty_prod_price);
            } else {
                fp_gf_update_campaign_metas($campaign_id, '_regular_price', '1');
            }

            fp_gf_update_campaign_metas($campaign_id, '_price', 1);
            fp_gf_update_campaign_metas($campaign_id, '_stock_status', 'instock');

            //target_qty
            fp_gf_update_campaign_metas($campaign_id, '_crowdfundingquantity', $galaxy_target_qty);

            if (isset($_POST['cf_newcampaign_choose_category'])) {
                wp_set_post_terms($campaign_id, $_POST['cf_newcampaign_choose_category'], 'product_cat');
            }


            if (get_option('cf_frontend_submission_method') == '2') {
                $getdate = FP_GF_Common_Functions::date_with_format();
                $gethour = date("h");
                $getminutes = date("i");
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingfromdatepicker', $getdate);
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingfromhourdatepicker', $gethour);
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingfromminutesdatepicker', $getminutes);
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingtodateindays', $campaign_target_days);

                $todatenew = date(FP_GF_Common_Functions::fp_gf_date_format(), strtotime($getdate . ' + ' . $campaign_target_days . 'days'));
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingtodatepicker', $todatenew);
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingtohourdatepicker', $gethour);
                fp_gf_update_campaign_metas($campaign_id, '_crowdfundingtominutesdatepicker', $getminutes);
            } else {
                fp_gf_update_campaign_metas($campaign_id, 'user_created_moderation_campaign', 'yes');
            }

            fp_gf_update_campaign_metas($campaign_id, '_visibility', 'visible');
            wp_set_object_terms($campaign_id, 'simple', 'product_type');

            if ($campaign_use_selected_product_featured_image == '1') {
                //if (count($_POST['cf_product_selection']) == 1) {
                fp_gf_update_campaign_metas($campaign_id, '_use_selected_product_image', 'yes');
                $feat_image = get_post_thumbnail_id($_POST['_cf_product_selection'][0]);
                set_post_thumbnail($campaign_id, $feat_image);
                // }
            } else {
                $uploaddir = wp_upload_dir();
                if (isset($_FILES['cf_featured_image'])) {
                    $tmp_file = $_FILES['cf_featured_image']["tmp_name"];
                }
                if (isset($_FILES['cf_featured_image'])) {
                    $uploadfile = $uploaddir['path'] . '/' . $_FILES['cf_featured_image']['name'];
                }
                move_uploaded_file($tmp_file, $uploadfile);
                $wp_filetype = wp_check_filetype(basename($uploadfile), null);
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploadfile)),
                    'post_status' => 'inherit',
                );
                $attach_id = wp_insert_attachment($attachment, $uploadfile); // adding the image to th media
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploadfile);
                $update = wp_update_attachment_metadata($attach_id, $attach_data); // Updated the image details
                set_post_thumbnail($campaign_id, $attach_id);
            }
            /*
             * Update user Meta Information for Billing Information and this meta information is needed on creating custom order
             *
             */
            $user_ID = get_current_user_id();
            if (isset($_POST['billing_first_name'])) {
                update_user_meta($user_ID, 'billing_first_name', $_POST['billing_first_name']);
            }
            if (isset($_POST['billing_last_name'])) {
                update_user_meta($user_ID, 'billing_last_name', $_POST['billing_last_name']);
            }
            if (isset($_POST['billing_company'])) {
                update_user_meta($user_ID, 'billing_company', $_POST['billing_company']);
            }
            if (isset($_POST['billing_address_1'])) {
                update_user_meta($user_ID, 'billing_address_1', $_POST['billing_address_1']);
            }
            if (isset($_POST['billing_address_2'])) {
                update_user_meta($user_ID, 'billing_address_2', $_POST['billing_address_2']);
            }
            if (isset($_POST['billing_city'])) {
                update_user_meta($user_ID, 'billing_city', $_POST['billing_city']);
            }
            if (isset($_POST['billing_postcode'])) {
                update_user_meta($user_ID, 'billing_postcode', $_POST['billing_postcode']);
            }
            if (isset($_POST['billing_country'])) {
                update_user_meta($user_ID, 'billing_country', $_POST['billing_country']);
            }
            if (isset($_POST['billing_state'])) {
                update_user_meta($user_ID, 'billing_state', $_POST['billing_state']);
            }
            if (isset($_POST['billing_email'])) {
                update_user_meta($user_ID, 'billing_email', $_POST['billing_email']);
            }
            if (isset($_POST['billing_phone'])) {
                update_user_meta($user_ID, 'billing_phone', $_POST['billing_phone']);
            }

            if (isset($_POST['same_as_billing'])) {
                if ($_POST['same_as_billing'] == '1') {
                    update_user_meta($user_ID, 'shipping_first_name', $_POST['billing_first_name']);
                    update_user_meta($user_ID, 'shipping_last_name', $_POST['billing_last_name']);
                    update_user_meta($user_ID, 'shipping_company', $_POST['billing_company']);
                    update_user_meta($user_ID, 'shipping_address_1', $_POST['billing_address_1']);
                    update_user_meta($user_ID, 'shipping_address_2', $_POST['billing_address_2']);
                    update_user_meta($user_ID, 'shipping_city', $_POST['billing_city']);
                    update_user_meta($user_ID, 'shipping_postcode', $_POST['billing_postcode']);
                    update_user_meta($user_ID, 'shipping_country', $_POST['billing_country']);
                    update_user_meta($user_ID, 'shipping_state', $_POST['billing_state']);
                } else {
                    update_user_meta($user_ID, 'shipping_first_name', $_POST['shipping_first_name']);
                    update_user_meta($user_ID, 'shipping_last_name', $_POST['shipping_last_name']);
                    update_user_meta($user_ID, 'shipping_company', $_POST['shipping_company']);
                    update_user_meta($user_ID, 'shipping_address_1', $_POST['shipping_address_1']);
                    update_user_meta($user_ID, 'shipping_address_2', $_POST['shipping_address_2']);
                    update_user_meta($user_ID, 'shipping_city', $_POST['shipping_city']);
                    update_user_meta($user_ID, 'shipping_postcode', $_POST['shipping_postcode']);
                    update_user_meta($user_ID, 'shipping_country', $_POST['shipping_country']);
                    update_user_meta($user_ID, 'shipping_state', $_POST['shipping_state']);
                }
            }
//            if (get_option('cf_frontend_submission_method') == '2') {
//                add_post_meta($campaign_id, '_crowdfundingfromdatepicker', date('m/d/Y'));
//                $todatenew = date('m/d/Y', strtotime(date('m/d/Y') . ' + ' . $campaign_target_days . ' days'));
//                add_post_meta($campaign_id, '_crowdfundingtodatepicker', $todatenew);
//            }
            fp_gf_update_campaign_metas($campaign_id, 'perk', $campaign_perk_rule);
            if (get_option('cf_frontend_submission_method') == '1') {
                self::do_something_on_campaign_submission($campaign_id);
            } else {
                self::do_something_on_instant_live_approval($campaign_id);
            }
            echo "success";
            exit();
        }

        public static function do_something_on_instant_live_approval($productid) {
            $product = new WC_Product($productid);
            self::common_function_for_approval($product);
        }

        public static function common_function_for_approval($productid) {
            if (is_object($productid)) {
                $productid = $productid->ID;
            }
            $enable = get_option('cf_enable_mail_for_campaign_approved');
            $campaign_creator = get_option('cf_send_email_to_campaign_creator_on_approved');
            $siteadmin = get_option('cf_send_email_to_site_admin_on_approved');
            $othersemail = get_option('cf_send_email_to_others_on_approved');
            $othersemaillist = get_option('cf_send_email_to_others_mail_on_approved');
            $subject = get_option('approved_mail_subject');
            $message = get_option('approved_mail_message');

            $findarray = array('[campaign_name]', '[cf_site_title]', '[cf_site_campaign_url]', '[cf_site_campaign_shipping_address]');
            $replacearray = self::get_values_for_shortcode($productid);
            $subject = str_replace($findarray, $replacearray, $subject);
            $message = str_replace($findarray, $replacearray, $message);

            $checkvalue = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingcheckboxvalue');
            if ($checkvalue == 'yes') {
                // Send Mail on Campaign Approval
                FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $productid);
                $getdate = FP_GF_Common_Functions::date_with_format();
                $gethour = date("h");
                $getminutes = date("i");
                $campaign_target_days = get_post_meta($productid, '_crowdfundingtodateindays', true);
                fp_gf_update_campaign_metas($productid, '_crowdfundingfromdatepicker', $getdate);
                fp_gf_update_campaign_metas($productid, '_crowdfundingfromhourdatepicker', $gethour);
                fp_gf_update_campaign_metas($productid, '_crowdfundingfromminutesdatepicker', $getminutes);
                fp_gf_update_campaign_metas($productid, '_crowdfundingtodateindays', $campaign_target_days);
                $todatenew = date(FP_GF_Common_Functions::fp_gf_date_format(), strtotime($getdate . ' + ' . $campaign_target_days . 'days'));
                fp_gf_update_campaign_metas($productid, '_crowdfundingtodatepicker', $todatenew);
                fp_gf_update_campaign_metas($productid, '_crowdfundingtohourdatepicker', $gethour);
                fp_gf_update_campaign_metas($productid, '_crowdfundingtominutesdatepicker', $getminutes);
            }
        }

        public static function get_values_for_shortcode($productid) {
            ob_start();
            $userid = get_post_field('post_author', $productid);
            /* Shipping Information for the Corresponding USER/AUTHOR */
            $ship_first_name = get_user_meta($userid, 'shipping_first_name', true);
            $ship_last_name = get_user_meta($userid, 'shipping_last_name', true);
            $ship_company = get_user_meta($userid, 'shipping_company', true);
            $ship_address1 = get_user_meta($userid, 'shipping_address_1', true);
            $ship_address2 = get_user_meta($userid, 'shipping_address_2', true);
            $ship_city = get_user_meta($userid, 'shipping_city', true);
            $ship_country = get_user_meta($userid, 'shipping_country', true);
            $ship_postcode = get_user_meta($userid, 'shipping_postcode', true);
            $ship_state = get_user_meta($userid, 'shipping_state', true);
            ?>
            <table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <th scope="col" ><?php _e('Shipping Address', 'galaxyfunder'); ?></th>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_company; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_first_name . ' ' . $ship_last_name; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_address1; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_address2; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_city . '-' . $ship_postcode; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_state; ?></td>
                    </tr>
                    <tr>
                        <td scope="col"><?php echo $ship_country; ?></td>
                    </tr>
                </tbody>
            </table>

            <?php
            $shipping_address = ob_get_clean();
            $campaign_name = get_the_title($productid);
            $campaign_page_url = get_permalink($productid);
            $campaign_site = get_site_url();
            $values = array($campaign_name, $campaign_site, $campaign_page_url, $shipping_address);
            return $values;
        }

        /* Do Something upon Campaign Frontend Submission */

        public static function do_something_on_campaign_submission($productid) {
            $enable = get_option('cf_enable_mail_for_campaign_submission');
            $campaign_creator = get_option('cf_send_email_to_campaign_creator');
            $siteadmin = get_option('cf_send_email_to_site_admin');
            $othersemail = get_option('cf_send_email_to_others');
            $othersemaillist = get_option('cf_send_email_to_others_mail');
            $subject = get_option('campaign_submission_email_subject');
            $message = get_option('campaign_submission_email_message');

            $findarray = array('[cf_campaign_name]', '[cf_site_title]', '[cf_approved_campaign_link]');
            $replacearray = array(get_the_title($productid), get_site_url(), get_permalink($productid));
            $subject = str_replace($findarray, $replacearray, $subject);
            $message = str_replace($findarray, $replacearray, $message);


            $checkvalue = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingcheckboxvalue');
            if ($checkvalue == 'yes') {
                // Send Mail
                FP_GF_Mail_Related_Functions::send_mail_function($enable, $campaign_creator, $siteadmin, $othersemail, $othersemaillist, $subject, $message, $productid);
                $campaign_target_days = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingtodateindays');
                add_post_meta($productid, '_crowdfundingfromdatepicker', date('m/d/Y'));
                $todatenew = date('m/d/Y', strtotime(date('m/d/Y') . ' + ' . $campaign_target_days . ' days'));
                add_post_meta($productid, '_crowdfundingtodatepicker', $todatenew);
            }
        }

        public static function ajax_get_product_price_function() {
            global $woocommerce;
            if (isset($_POST['product_id_array'])) {
                $selected_product_array = $_POST['product_id_array'];
                $added_product_price = 0;
                if (!is_array($selected_product_array)) {
                    $selected_product_array = (array) $selected_product_array;
                }
                foreach ($selected_product_array as $exploded_value) {
                    $exploded_product_price = get_post_meta($exploded_value, '_regular_price', true);
                    $added_product_price+=$exploded_product_price;
                }
                echo $added_product_price;
            }
            exit();
        }

    }

    FP_GF_Frontend_Form::init();
}

 







