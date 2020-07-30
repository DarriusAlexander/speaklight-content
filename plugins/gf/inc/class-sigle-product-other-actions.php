<?php
/*
 * Shop Related Functionality
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Single_Product_Page_Other_Actions')) {

    /**
     * Single Product page Class.
     */
    class FP_GF_Single_Product_Page_Other_Actions {

        public static function init() {

            //Social Promotion
            if (get_option('_crowdfunding_social_button_position') == '1') {
                add_action('woocommerce_before_single_product', array(__CLASS__, 'crowdfunding_social_promotion'));
            } elseif (get_option('_crowdfunding_social_button_position') == '2') {
                add_action('woocommerce_before_single_product_summary', array(__CLASS__, 'crowdfunding_social_promotion'));
            } elseif (get_option('_crowdfunding_social_button_position') == '3') {
                add_action('woocommerce_single_product_summary', array(__CLASS__, 'crowdfunding_social_promotion'));
            } elseif (get_option('_crowdfunding_social_button_position') == '4') {
                add_action('woocommerce_after_single_product', array(__CLASS__, 'crowdfunding_social_promotion'));
            } else {
                add_action('woocommerce_after_single_product_summary', array(__CLASS__, 'crowdfunding_social_promotion'));
            }

            //Author table function hooks
            if (get_option('cf_author_info_table_position') == '1') {
                add_action('woocommerce_before_single_product_summary', array(__CLASS__, 'get_author_information'));
            } elseif (get_option('cf_author_info_table_position') == '2') {
                add_action('woocommerce_after_single_product', array(__CLASS__, 'get_author_information'));
            } else {
                add_action('woocommerce_after_single_product_summary', array(__CLASS__, 'get_author_information'));
            }

            //Crowd funding table    
            if (get_option('cf_donation_table_position') == '1') {
                add_action('woocommerce_before_single_product_summary', array(__CLASS__, 'crowdfunding_table'));
            } elseif (get_option('cf_donation_table_position') == '2') {
                add_action('woocommerce_after_single_product', array(__CLASS__, 'crowdfunding_table'));
            } else {
                add_action('woocommerce_after_single_product_summary', array(__CLASS__, 'crowdfunding_table'));
            }
            //Crowd funding contribution table short codes
            add_shortcode('gf_funders_table_for_campaign', array(__CLASS__, 'crowdfunding_table_shortcode'));


            //My account my campaigns Table
            if (get_option('cf_mycampaign_table_position') === '2') {
                add_action('woocommerce_after_my_account', array(__CLASS__, 'cf_my_account_campaign'));
            } else if (get_option('cf_mycampaign_table_position') === '1') {
                add_action('woocommerce_before_my_account', array(__CLASS__, 'cf_my_account_campaign'));
            }
            //My account my campaigns Table shortcode
            add_shortcode('cf_mycampaign_table', array(__CLASS__, 'cf_my_account_campaign_shortcode'));
        }

        public static function crowdfunding_social_promotion() {
            global $woocommerce;
            global $post;

            $enablesharing = get_post_meta($post->ID, '_crowdfundingsocialsharing', true);
            if ($enablesharing == 'yes') {
                ?>
                <div id="fb-root"></div>
                <script type="text/javascript">
                    window.fbAsyncInit = function () {
                        FB.init({
                            appId: "",
                            xfbml: true,
                            version: 'v2.0'
                        });
                    };
                    (function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) {
                            return;
                        }
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "https://connect.facebook.net/en_US/sdk.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                    console.log('script loaded');</script>
                <script>
                    !function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "https://platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>
                <script>
                    var originalCallback = function (o) {
                        console.log(o);
                        console.log('original callback - ' + o.state);
                        var state = o.state;
                        return false;
                    };</script>
                <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
                </script>
                <style>
                    .share_wrapper1{
                        margin-top: -12px;
                        background-color:#3b5998;
                        /*padding:2px;*/
                        color:#fff;
                        cursor:pointer;
                        font-size:12px;
                        font-weight:bold;
                        border: 1px solid transparent;
                        border-radius: 2px ;
                        width:59px;
                        height:23px;
                    }

                    .fb_share_img{
                        margin-top: -3px;
                        margin-left: 3px;
                        margin-right: 3px;
                    }
                </style>
                <table style="display:inline;">
                    <tr>
                        <?php
                        $enablesharing_facebook = get_post_meta($post->ID, '_crowdfundingsocialsharing_facebook', true);
                        if ($enablesharing_facebook == 'yes') {
                            ?>
                            <td> <div class="fb-like" data-href="<?php echo get_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></td>

                        <?php } ?>
                        <?php
                        $enablesharing_twitter = get_post_meta($post->ID, '_crowdfundingsocialsharing_twitter', true);
                        if ($enablesharing_twitter == 'yes') {
                            ?>
                            <td><a href="https://twitter.com/share" class="twitter-share-button" data-href="<?php echo get_permalink(); ?>" data-lang="en">Tweet</a></td>
                        <?php } ?>
                        <?php
                        $enablesharing_google = get_post_meta($post->ID, '_crowdfundingsocialsharing_google', true);
                        if ($enablesharing_google == 'yes') {
                            ?>
                            <td> <g:plusone annotation="bubble" class="google-plus-one" href='<?php echo get_permalink(); ?>'></g:plusone></td>
                <?php } ?>
                </tr>
                </table>
                <?php
            }
        }

        public static function get_author_information() {
            global $post;
            $author_id = $post->post_author;
            //get_avatar($author_id, 150);
            $user_email = get_the_author_meta('user_email', $author_id);
            $firstname = get_the_author_meta('first_name', $author_id);
            $lastname = get_the_author_meta('last_name', $author_id);
            $nickname = get_the_author_meta('user_nicename', $author_id);
            $biography = get_the_author_meta('description', $author_id);
            $news = get_the_author_meta('country', $author_id);
            $getpostmeta = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);
            if ($getpostmeta == 'yes') {
                if (get_option('cf_author_table_show_hide') == '1') {
                    ?>
                    <h3><?php echo get_option('cf_author_info_heading'); ?></h3>
                    <table>
                        <tr><td><?php
                                if (get_option('cf_avatar_show_hide') == '1') {
                                    echo get_avatar($author_id, get_option('cf_avatar_width_height'));
                                }
                                ?></td><td>
                                <?php
                                if (get_option('cf_author_name_show_hide') == '1') {
                                    if ($firstname != '') {
                                        if (function_exists('bp_core_get_user_domain')) {
                                            if (get_option('cf_check_buddypress_link_is_active') == '1') {
                                                echo get_option('cf_author_name_label');
                                                ?>: <a href="<?php echo bp_core_get_user_domain($author_id); ?>"> <?php echo $firstname . " " . $lastname; ?></a><br><?php
                                                } else {
                                                    echo get_option('cf_author_name_label');
                                                    ?>: <?php echo $firstname . " " . $lastname; ?><br>
                                                <?php
                                            }
                                        } else {
                                            echo get_option('cf_author_name_label');
                                            ?>: <?php echo $firstname . " " . $lastname; ?><br>
                                            <?php
                                        }
                                    } else {
                                        if ($nickname != '') {
                                            if (function_exists('bp_core_get_user_domain')) {
                                                if (get_option('cf_check_buddypress_link_is_active') == '1') {
                                                    echo get_option('cf_author_name_label');
                                                    ?>: <a href="<?php echo bp_core_get_user_domain($author_id); ?>"><?php echo $nickname; ?></a><br> <?php
                                                } else {
                                                    echo get_option('cf_author_name_label');
                                                    ?>: <?php echo $nickname; ?><br>
                                                    <?php
                                                }
                                            } else {
                                                echo get_option('cf_author_name_label');
                                                ?>: <?php echo $nickname; ?><br>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                <?php
                                if (get_option('cf_author_nick_name_show_hide') == '1') {
                                    if ($nickname != '') {
                                        ?>
                                        <?php echo get_option('cf_author_nick_name_label'); ?>: <?php echo $nickname; ?><br>
                                        <?php
                                    }
                                }
                                ?>

                                <?php
                                if (get_option('cf_author_email_show_hide') == '1') {
                                    if ($user_email != '') {
                                        ?>
                                        <?php echo get_option('cf_author_email_label'); ?>: <?php echo $user_email; ?><br>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                if (get_option('cf_author_biography_show_hide') == '1') {
                                    if ($biography != '') {
                                        ?>
                                        <?php echo get_option('cf_author_biography_label'); ?>: <?php echo $biography; ?><br>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                if (function_exists('userpro_get_badge')) {
                                    if (get_option('cf_check_userpro_country_is_active') == '1') {
                                        if (get_user_meta($author_id, 'country', true) != '') {
                                            if (get_option('cf_author_country_show_hide') == '1') {
                                                echo get_option('cf_author_country_label');
                                                ?>: <?php
                                                echo userpro_get_badge('country_big', $author_id);
                                                echo get_user_meta($author_id, 'country', true);
                                            }
                                        }
                                    }
                                }
                                ?>
                            </td></tr>
                    </table>
                    <?php
                }
            }
        }

        public static function common_function_for_crowdfunding_table() {
            global $post;
            $i = 0;

            $gf_search_show_hide = get_option('cf_display_search_box');
            $gf_page_size_show_hide = get_option('cf_display_page_size');
            $showhide_serialnumber = get_option('cf_serial_number_show_hide');
            $showhide_contributor_image = get_option('cf_contributor_image_show_hide');
            $showhide_contributor_image_size = get_option('cf_contributor_image__size_label');
            $showhide_contributorname = get_option('cf_contributor_name_show_hide');
            $showhide_contributoremail = get_option('cf_contributor_email_show_hide');
            $showhide_contribution = get_option('cf_contribution_show_hide');
            $showhide_date = get_option('cf_date_column_show_hide');
            $showhide_perkname = get_option('cf_perk_name_column_show_hide');
            $showhide_perkamount = get_option('cf_perk_amount_column_show_hide');
            $showhide_order_notes = get_option('cf_order_notes_column_show_hide');
            $order_notes_label = get_option('cf_order_notes_label');

            if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {

                if (get_post_meta($post->ID, '_crowdfunding_showhide_contributor', true) == 'yes') {
                    $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($post->ID, 'orderids', true)));

                    if (is_array($listofcontributedorderids)) {
                        foreach ($listofcontributedorderids as $orderid) {
                            $cf_order_notes = self::cf_order_notes_common_fn($orderid);
                            $order = fp_gf_get_order_object($orderid);
                            $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
                            $billing_first_name = $formed_order_object->get_billing_firstname;
                            $billing_last_name = $formed_order_object->get_billing_lastname;
                            $billing_email = $formed_order_object->get_billing_email;
                            $get_order_date = $formed_order_object->get_order_date;
                            if (is_object($order)) {
                                foreach ($order->get_items() as $item) {
                                    $products = array();
                                    $product_id = isset($item['product_id']) ? $item['product_id'] : '';
                                    $products[] = $product_id;
                                    if (in_array($post->ID, $products)) {
                                        if ($i == 0) {
                                            if ($gf_search_show_hide == 'on' && $gf_page_size_show_hide == 'off') {
                                                echo '<p class = "single_product_contribution_table" style="display:inline-table;"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . '</p>';
                                            }
                                            if ($gf_page_size_show_hide == 'on' && $gf_search_show_hide == 'off') {
                                                echo '<p style="display:inline-table;margin-left:220px;"> ' . __('Page Size:', 'galaxyfunder') . '
                                                <select id="change-page-size" style="display:inline-table">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                </select></p>';
                                            }
                                            if ($gf_search_show_hide == 'on' && $gf_page_size_show_hide == 'on') {
                                                echo '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                                                <select id="change-page-size" style="display:inline-table">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                </select></p>';
                                            }
                                            ?>
                                            <script type="text/javascript">
                                                jQuery(function () {
                                                    jQuery('.single_product_contribution_table').footable();
                                                    jQuery('.single_product_contribution_table').footable().bind('footable_filtering', function (e) {
                                                        var selected = jQuery('.filter-status').find(':selected').text();
                                                        if (selected && selected.length > 0) {
                                                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                                                            e.clear = !e.filter;
                                                        }
                                                    });
                                                    jQuery('#change-page-size').change(function (e) {
                                                        e.preventDefault();
                                                        var pageSize = jQuery(this).val();
                                                        jQuery('.footable').data('page-size', pageSize);
                                                        jQuery('.footable').trigger('footable_initialized');
                                                    });
                                                });</script>
                                            <table class = "single_product_contribution_table  demo shop_table my_account_orders table-bordered" data-page-navigation=".pagination" id="single_product_contribution_table" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
                                                <thead>
                                                    <tr>
                                                        <?php if ($showhide_serialnumber == '1') { ?>
                                                            <th class="cf_serial_number_label" id="cf_serial_number_label" data-toggle="true" data-sort-initial = "true"><?php echo get_option('cf_serial_number_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributor_image == '1') { ?>
                                                            <th class="cf_contributor_image_label" id="cf_contributor_image_label"><?php echo get_option('cf_contributor_image_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributorname == '1') { ?>
                                                            <th class="cf_contributor_label" id="cf_contributor_label"><?php echo get_option('cf_contributor_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contributoremail == '1') { ?>
                                                            <th class="cf_contributor_email_label" id="cf_contributor_email_label"><?php echo get_option('cf_contributor_email_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_contribution == '1') { ?>
                                                            <th class="cf_contribution_label" id="cf_contribution_label" data-hide="phone"><?php echo get_option('cf_donation_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_perkname == '1') { ?>
                                                            <th class="cf_contribution_perk_name" id="cf_contribution_perk_name" data-hide="phone"><?php echo get_option('cf_perk_name_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_perkamount == '1') { ?>
                                                            <th class="cf_contribution_perk_amount" id="cf_contribution_perk_amount" data-hide="phone"><?php echo get_option('cf_perk_amount_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                            <th class="cf_perkquantity" id="cf_perk_label" data-hide="phone,tablet"><?php echo get_option('cf_perk_quantity_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_date == '1') { ?>
                                                            <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo get_option('cf_date_label'); ?></th>
                                                        <?php } ?>
                                                        <?php if ($showhide_order_notes == '1') { ?>
                                                            <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo $order_notes_label; ?></th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                }
                                                $i++;
                                                ?>
                                                <tr>
                                                    <?php if ($showhide_serialnumber == '1') { ?>
                                                        <td class='serial_id' data-value="<?php echo $i; ?>" id='serial_id'>
                                                            <?php echo $i; ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($showhide_contributor_image == '1') { ?>

                                                        <td class="cf_billing_name_image" id="cf_billing_name_image"> <?php
                                                            if (get_post_meta($orderid, 'My Checkbox', true) == '1' || get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true) == 'yes') {

                                                                $avatar = get_avatar('', $showhide_contributor_image_size, 'mystery');
                                                                echo $avatar;
                                                            } else {

                                                                echo get_avatar($billing_email, $showhide_contributor_image_size);
                                                            }
                                                            ?> 
                                                        </td>
                                                    <?php } ?>

                                                    <?php if ($showhide_contributorname == '1') { ?>
                                                        <td class='cf_billing_first_name' id='cf_billing_first_name'>
                                                            <?php
                                                            if (get_post_meta($orderid, 'contributor_list_for_campaign', true) == '') {
                                                                if (get_post_meta($orderid, 'My Checkbox', true) == '1') {
                                                                    echo __('Anonymous', 'galaxyfunder');
                                                                } else {
                                                                    $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                    if ($mark_contributor_anonymous == 'yes') {
                                                                        echo __('Anonymous', 'galaxyfunder');
                                                                    } else {
                                                                        echo $billing_first_name . "&nbsp;" . $billing_last_name;
                                                                    }
                                                                }
                                                            } else {
                                                                if (get_post_meta($orderid, 'My Checkbox', true) == '1') {
                                                                    echo __('Anonymous', 'galaxyfunder');
                                                                } else {
                                                                    $mark_contributor_anonymous = get_post_meta($post->ID, '_crowdfunding_contributor_anonymous', true);
                                                                    if ($mark_contributor_anonymous == 'yes') {
                                                                        echo __('Anonymous', 'galaxyfunder');
                                                                    } else {
                                                                        echo get_post_meta($orderid, 'contributor_list_for_campaign', true);
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    &nbsp;
                                                    <?php if ($showhide_contributoremail == '1') { ?>
                                                        <td class='cf_billing_email' id='cf_billing_email'><?php echo $billing_email; ?></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_contribution == '1') { ?>
                                                        <td class='cf_order_total' id='cf_order_total'>
                                                            <?php
                                                            $target_method_id = get_post_meta($post->ID, '_target_end_selection', true);

                                                            if ($target_method_id == 5) {
                                                                echo $item['qty'];
                                                            } else {
                                                                $total_contribution = '';
                                                                if (get_option('cf_campaign_restrict_coupon_discount') == '1') {
                                                                    $total_contribution = $item['line_total'];
                                                                } else {
                                                                    $total_contribution = $item['line_subtotal'];
                                                                }
                                                                $paid_currency = fp_gf_get_order_currency($order);
                                                                $current_currency = WC()->session->get('client_currency');
                                                                $total_contribution_wmc = fp_wpml_multi_currency_in_cart($total_contribution, $paid_currency, $current_currency);
                                                                echo fp_formatted_price_on_order($total_contribution_wmc, $current_currency);
                                                            }
                                                            ?><br></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkname == '1') { ?>
                                                        <td class="cf_contribution_perk_name" id="cf_contribution_perk_name">
                                                            <?php
                                                            echo '<br>';
                                                            $cfperkname = get_post_meta($orderid, 'perkname' . $post->ID, true);
                                                            if (!is_array($cfperkname)) {
                                                                $cfperkname = (array) $cfperkname;
                                                            }

                                                            if (!empty($cfperkname)) {
                                                                $imploded_value = implode(', ', $cfperkname);
                                                                if ($imploded_value == '') {
                                                                    echo '-';
                                                                } else {
                                                                    echo $imploded_value;
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($showhide_perkamount == '1') { ?>
                                                        <td>
                                                            <?php
                                                            //$iterationid = get_post_meta($orderid, '_perk_iteration_id', true);
                                                            echo '<br>';
                                                            $implode = get_post_meta($orderid, 'perk_maincontainer' . $post->ID, true);
                                                            if (!is_array($implode)) {
                                                                $implode = (array) $implode;
                                                            }
                                                            if (!empty($implode)) {
                                                                $perk_price = implode(',', $implode);
                                                                $paid_currency = fp_gf_get_order_currency($order);
                                                                $current_currency = WC()->session->get('client_currency');
                                                                $perk_amount_wmc = fp_wpml_multi_currency_in_cart($perk_price, $paid_currency, $current_currency);
                                                                echo fp_formatted_price_on_order($perk_amount_wmc, $current_currency);
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if (get_option('cf_perk_quantity_column_show_hide') == '1') { ?>
                                                        <td>
                                                            <?php
                                                            $qty_array = get_post_meta($orderid, 'explodequantity' . $post->ID, true);

                                                            $perkqty = array();
                                                            if (!is_array($qty_array)) {
                                                                $qty_array = (array) $qty_array;
                                                            }

                                                            if (is_array($qty_array)) {
                                                                if (!empty($qty_array)) {
                                                                    foreach ($qty_array as $quantity) {
                                                                        $explode = explode('_', $quantity);

                                                                        if (!empty($explode[0])) {
                                                                            $perkqty[] = $explode[0];
                                                                        }
                                                                    }
                                                                    if (!empty($perkqty)) {
                                                                        echo implode(',', $perkqty);
                                                                        unset($perkqty);
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                } else {
                                                                    echo '-';
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($showhide_date == '1') { ?>
                                                        <td class='cf_order_date' id='cf_order_date'><?php echo $get_order_date; ?></td>
                                                    <?php } ?>
                                                    <?php if ($showhide_order_notes == '1') { ?>
                                                        <td class='cf_order_notes' id='cf_order_notes'><?php echo $cf_order_notes; ?></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr style="clear:both;">
                                <td colspan="7">
                                    <div class="pagination pagination-centered"></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php
                }
            }
        }

        public static function cf_order_notes_common_fn($myorderid) {
            $cf_order_notes = get_post_meta($myorderid, 'cf_order_notes', true);

            if ($cf_order_notes == '') {
                $cf_order_notes = '&nbsp;&nbsp;-';
            }
            return $cf_order_notes;
        }

        public static function crowdfunding_table() {
            if (get_option('cf_display_donation_table') == 'on') {
                echo self::common_function_for_crowdfunding_table();
            }
        }

        public static function crowdfunding_table_shortcode() {
            echo self::common_function_for_crowdfunding_table();
        }

        public static function cf_my_account_campaign() {
            if (get_option('cf_display_mycampaign_table') == "on") {
                echo self::cf_my_account_campaign_shortcode();
            }
        }

        public static function cf_my_account_campaign_shortcode() {
            $userid = get_current_user_id();
            $listids = self::get_list_of_post_ids($userid);
            ?>
            <h2><?php echo get_option('cf_mycampaign_title'); ?></h2>
            <?php
            echo FP_GF_Common_Functions::common_function_for_search_box();
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.example').footable().bind('footable_filtering', function (e) {
                        var selected = jQuery('.filter-status').find(':selected').text();
                        if (selected && selected.length > 0) {
                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                            e.clear = !e.filter;
                        }
                    });
                    jQuery('#change-page-sizes').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });
                });
            </script>

            <table data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" class = "example demo shop_table my_account_orders table-bordered">
                <thead>
                <th data-toggle="true"><?php echo get_option('cf_mycampaign_serial_number_label'); ?></th>
                <th data-toggle="true"><?php echo get_option('cf_mycampaign_campaign_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_date_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_goal_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_raised_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_raised_percent_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_extension_label'); ?></th>

                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_funders_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('cf_mycampaign_status_label'); ?></th>
                <th data-toggle="true" data-hide='phone,tablet'><?php echo get_option('_cf_customize_target_type_my_account'); ?></th>

            </thead>
            <tbody>
                <?php
                //var_dump($listids);
                $i = 1;
                if (is_array($listids)) {
                    foreach ($listids as $value) {
                        if (isset($value)) {
                            $target_id = get_post_meta($value->ID, '_target_end_selection', true);
                            $target_end_method = FP_GF_Common_Functions::target_end_method_fn($target_id);
                            $total_target_qty = get_post_meta($value->ID, '_crowdfundingquantity', true);
                            $gettargetgoal_value_number1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($value->ID, '_crowdfundinggettargetprice');
                            $gettargetgoal_value_number = fp_wpml_multi_currency($gettargetgoal_value_number1);
                            $gettotalpledged_value_number1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($value->ID, '_crowdfundingtotalprice');
                            $gettotalpledged_value_number = fp_wpml_multi_currency($gettotalpledged_value_number1);
                            //echo $target_id;
                            if ($target_id == '5') {
                                $remaining_qty = get_post_meta($value->ID, '_galaxy_funder_remaining_qty', true);
                                $saled_qty = get_post_meta($value->ID, '_gf_saled_qty', true);

                                if ($saled_qty != '') {
                                    $saled_qty = $saled_qty;
                                } else {
                                    $saled_qty = '0';
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo '<a href="' . get_permalink($value->ID) . '" target="_blank">' . get_the_title($value->ID) . '</a>'; ?></td>
                                <td><?php echo $value->post_modified; ?></td>
                                <td><?php
                                    if ($target_id == 5) {
                                        echo $total_target_qty;
                                    } else {
                                        $gettargetgoal_value1 = FP_GF_Common_Functions::format_price_in_proper_order(FP_GF_Common_Functions::get_galaxy_funder_post_meta($value->ID, '_crowdfundinggettargetprice'));
                                        $gettargetgoal_value = fp_wpml_multi_currency($gettargetgoal_value1);
                                        echo $gettargetgoal_value;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($target_id == 5) {
                                        echo $saled_qty;
                                    } else {
                                        $gettotalpledged_value = FP_GF_Common_Functions::format_price_in_proper_order(FP_GF_Common_Functions::get_galaxy_funder_post_meta($value->ID, '_crowdfundingtotalprice'));
                                        echo fp_wpml_multi_currency($gettotalpledged_value);
                                    }
                                    ?></td>

                                <td><?php
                                    if ($target_id == 5) {
                                        $count1 = $saled_qty / $total_target_qty;
                                        $count2 = $count1 * 100;
                                        $counter = number_format($count2, 0);
                                        $count = $counter;
                                        echo $count;
                                    } else {
                                        $totalpercentage = FP_GF_Common_Functions::update_percentage_value($gettargetgoal_value_number, $gettotalpledged_value_number, $value->ID);
                                        echo $totalpercentage = $totalpercentage != '' ? $totalpercentage : '0';
                                    }
                                    ?>%</td>
                                <td>
                                    <?php
                                    if ($value->post_status == 'publish') {
                                        if (FP_GF_Common_Functions::get_galaxy_funder_post_meta($value->ID, '_stock_status') == 'instock') {
                                            $status_of_extension = self::fetch_status_of_campaign_extension_request($value->ID);
                                            $page_id = get_option('cf_mycampaign_extension_pageid');

                                            if ($status_of_extension == 'pending') {
                                                $campaign_extension_status = "Request Pending";
                                            } else {
                                                $campaign_extension_status = '<a href="' . esc_url_raw(add_query_arg(array('product_id' => $value->ID), get_permalink($page_id))) . '">' . get_option('cf_mycampaign_extension_link_label') . '</a>';
                                            }
                                            echo $campaign_extension_status;
                                        } else {
                                            echo get_option('cf_outofstock_label');
                                        }
                                    } else {
                                        echo _e('Pending Review', 'galaxyfunder');
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    if (get_post_meta($value->ID, '_update_total_funders', true) == '') {
                                        $total_funders = 0;
                                    } else {
                                        $total_funders = get_post_meta($value->ID, '_update_total_funders', true);
                                    }
                                    echo $total_funders;
                                    ?>
                                </td>
                                <td><?php echo FP_GF_Common_Functions::status_checker_switch_statement($value->post_status, $value->ID); ?></td>
                                <td><?php echo $target_end_method; ?></td>

                            </tr>
                            <?php
                        }
                        $i++;
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="clear:both;">
                    <td colspan="8">
                        <div class="pagination pagination-centered"></div>
                    </td>
                </tr>
            </tfoot>
            </table>
            <?php
        }

        public static function get_list_of_post_ids($userid) {
            $args = array(
                'author' => $userid,
                'orderby' => 'post_date',
                'meta_query' => array(
                    array(
                        'key' => '_crowdfundingcheckboxvalue',
                        'value' => 'yes',
                    )
                ),
                'order' => 'ASC',
                'post_type' => 'product',
                'post_status' => 'draft,publish,pending',
                'posts_per_page' => '-1',
                'no_found_rows' => true,
                'update_post_term_cache' => false,
                'update_post_post_cache' => false,
                'cache_results' => false,
            );
            return get_posts($args);
        }

        public static function fetch_status_of_campaign_extension_request($product_id) {
            $compaign_modification_array = get_option('campaign_modification_list');
            if (is_array($compaign_modification_array)) {
                if (!is_null($compaign_modification_array)) {
                    foreach ($compaign_modification_array as $array_values) {
                        if (is_array($array_values)) {
                            if ((array_search($product_id, $array_values)) == true) {

                                return "pending";
                            } else {
                                return "not_in_array";
                            }
                        }
                    }
                } else {
                    return "not_in_array";
                }
            }
        }

    }

    FP_GF_Single_Product_Page_Other_Actions::init();
}

