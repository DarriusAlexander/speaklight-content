<?php
//Cart related fuctions

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FP_GF_Cart_Related_Functions')) {

    final class FP_GF_Cart_Related_Functions {

        public static function init() {
            //Coupon Restrictions
            add_action('wp_head', array(__CLASS__, 'restricton_of_coupon_field'));
            //Display the Subscribe option in My Account Page
            add_action('woocommerce_before_my_account', array(__CLASS__, 'subscribe_option_in_my_account_page'));
            //Get Subscribe value
            add_action('wp_ajax_gf_subscribevalue', array(__CLASS__, 'gf_get_sub_value'));
            //Replace add to cart label
            add_filter('add_to_cart_text', array(__CLASS__, 'change_add_to_cart_button_caption'));  //It will support in single Product as well as Shop Page with Older Version of WooCommerce < 2.1
            add_filter('woocommerce_product_single_add_to_cart_text', array(__CLASS__, 'change_add_to_cart_button_caption')); // Support WooCommerce 2.1+ in Single Product Page
            add_filter('woocommerce_product_add_to_cart_text', array(__CLASS__, 'change_add_to_cart_button_caption')); // Support WooCommerce 2.1+ for Shop Base Page
            //Cart and checkout redirection
            if (get_option('cf_add_to_cart_redirection') == '1' || get_option('cf_add_to_cart_redirection') == '2') {
                add_filter('woocommerce_add_to_cart_redirect', array(__CLASS__, 'cf_add_to_cart_redirection'));
            }
            //Add to cart button label
            add_filter('woocommerce_loop_add_to_cart_link', array(__CLASS__, 'redirect_to_product_page_if_product_has_campaign'), 10, 2);
            // Enable Sell Individually for Single Product
            if (get_option('cf_campaign_restrict_other_products') == '1') {
                add_filter('woocommerce_add_to_cart_validation', array(__CLASS__, 'make_campaign_product_sell_individually'), 10, 5);
            }
            //Call to set the Crowdfunding amount as the Product Price
            add_action('woocommerce_add_to_cart', array(__CLASS__, 'set_gf_contribution_amount_session'), 1, 5);
            //Setting a session
            add_action('woocommerce_add_to_cart', array(__CLASS__, 'set_quantity_session'));
            //Call to change the contribution amount as the Product Price
            add_action('woocommerce_before_calculate_totals', array(__CLASS__, 'set_gf_contribution_amount_as_product_price'), 1, 1);
            //Call for saving the contributor's name in the contribution order id
            add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'save_gf_contributor_name_in_order'));
            //Add checkbox field to the checkout
            add_action('woocommerce_after_order_notes', array(__CLASS__, 'my_custom_checkout_field'));
            //Update the order meta with field value
            add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'my_custom_checkout_field_update_order_meta'));
            //Ajax select perk actions
            add_action('wp_ajax_nopriv_selectperkoption', array(__CLASS__, 'ajax_request_response_perk_metabox'));
            add_action('wp_ajax_selectperkoption', array(__CLASS__, 'ajax_request_response_perk_metabox'));
            //Session handling
            if (get_option('cf_session_destroy_hook') == '1') {
                add_action('woocommerce_checkout_update_order_meta', array(__CLASS__, 'galaxyfunder_sessionhandler'), 10, 2);
            } else if (get_option('cf_session_destroy_hook') == '2') {
                add_action('woocommerce_thankyou', array(__CLASS__, 'thankyou_page_session_destroy'));
            }
            if (get_option('cf_enable_paypalasp_when_campaign_is_in_cart') == '2') {
                add_filter('woocommerce_available_payment_gateways', array(__CLASS__, 'galaxy_funder_filter_gateways'), 10, 1);
            }
        }

        public static function change_add_to_cart_button_caption($message) {
            global $post;
            if (FP_GF_Shop_Functions::get_woocommerce_product_type($post->ID) == 'simple') {
                if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                    return get_option('cf_add_to_cart_label');
                } else {
                    return $message;
                }
            }
            return $message;
        }

        public static function cf_add_to_cart_redirection() {
            if (isset($_POST['add-to-cart'])) {
                if (get_post_meta($_POST['add-to-cart'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                    if (get_option('cf_add_to_cart_redirection') == '1') {
                        return get_permalink(get_option('woocommerce_cart_page_id'));
                    } elseif (get_option('cf_add_to_cart_redirection') == '2') {
                        return get_permalink(get_option('woocommerce_checkout_page_id'));
                    }
                }
            } else {
                return get_permalink(get_option('woocommerce_cart_page_id'));
            }
        }

        public static function restricton_of_coupon_field() {
            if (is_checkout()) {
                if (get_option('cf_campaign_restrict_coupon_field') == '2') {
                    $cart_product_check = FP_GF_Common_Functions::cart_checkout_common_fn();
                    if ($cart_product_check == 1) {
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery('.showcoupon').parent().hide();
                            });
                        </script>

                        <?php
                    }
                }
            }

            if (is_cart()) {
                if (get_option('cf_campaign_restrict_coupon_field') == '2') {
                    $cart_product_check = FP_GF_Common_Functions::cart_checkout_common_fn();
                    if ($cart_product_check == 1) {
                        ?>
                        <style type="text/css">
                            .coupon{
                                display:none;
                            }
                        </style>
                        <?php
                    }
                }
            }

            if (get_option('gf_show_hide_your_subscribe_link') == '1') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('#gf_email_subscribeoption').click(function () {
                            var subscribe = jQuery('#gf_email_subscribeoption').is(':checked') ? 'yes' : 'no';
                            var getcurrentuser =<?php echo get_current_user_id(); ?>;
                            var data = {
                                action: 'gf_subscribevalue',
                                subscribe: subscribe,
                                getcurrentuser: getcurrentuser,
                                //dataclicked:dataclicked,
                            };
                <?php
                if (get_option('cf_load_ajax_from_ssl') == '2') {
                    $ajaxurl = site_url('/wp-admin/admin-ajax.php');
                } else {
                    $ajaxurl = admin_url('admin-ajax.php');
                }
                ?>
                            jQuery.post("<?php echo $ajaxurl; ?>", data,
                                    function (response) {
                                        //var newresponse = response.replace(/\s/g, '');
                                        if (response === '2') {
                                            alert("Successfully Unsubscribed...");
                                        } else {
                                            alert("Successfully Subscribed...");
                                        }
                                    });
                        });
                    });
                </script>
                <?php
            }
        }

        public static function make_campaign_product_sell_individually($passed, $product_id, $quantity, $variation_id = '', $variations = '') {
            global $woocommerce;
            if (2 == 2) {
                $cart_count = $woocommerce->cart->cart_contents_count;
                $cart_object = $woocommerce->cart;
                foreach ($cart_object->cart_contents as $key => $value) {
                    if (get_post_meta($value['product_id'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                        if ($cart_count > 0) {
                            wc_add_notice(__(get_option('cf_campaign_restrict_error_message')), 'error');
                        }
                        $passed = false;
                    }
                }
            }
            return $passed;
        }

        public static function set_gf_contribution_amount_session($cart_item_key, $product_id = null, $quantity = null, $variation_id = null, $variation = null) {
            $session_currency = class_exists('WCML_Multi_Currency') ? WC()->session->get('client_currency') : get_option('woocommerce_currency');
            if (isset($_POST['addfundraiser' . $product_id])) {
                WC()->session->set($cart_item_key . '_get_galaxyfunder_contributionamount', $_POST['addfundraiser' . $product_id]);
                WC()->session->set($cart_item_key . '_get_galaxyfunder_contributioncurrency', $session_currency);
            } else {
                WC()->session->__unset($cart_item_key . '_get_galaxyfunder_contributionamount');
                WC()->session->__unset($cart_item_key . '_get_galaxyfunder_contributioncurrency');
            }
            if (isset($_POST['addquantity' . $product_id])) {
                WC()->session->set($cart_item_key . '_get_galaxyfunder_quaantity', $_POST['addquantity' . $product_id]);
            } else {
                WC()->session->__unset($cart_item_key . '_get_galaxyfunder_quaantity');
            }
            if (isset($_POST['cf_contributor_name_field_value'])) {
                WC()->session->set($cart_item_key . '_get_galaxyfunder_contributorname', $_POST['cf_contributor_name_field_value']);
            } else {
                WC()->session->__unset($cart_item_key . '_get_galaxyfunder_contributorname');
            }
        }

        public static function set_quantity_session() {
            global $woocommerce;
            $cart_object = WC()->cart->cart_contents;
            $cart_count = $woocommerce->cart->cart_contents_count;
            foreach ($cart_object as $key => $value) {
                $_product = $value['data'];
                $product_id = $_product->get_id();
                $currentproductiscampaign = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                if ($currentproductiscampaign == 'yes') {
                    $cf_user_qty = WC()->session->get($key . '_get_galaxyfunder_quaantity');
                    if ($cf_user_qty != '' && $cart_count == 1) {
                        $woocommerce->cart->set_quantity($key, $cf_user_qty);
                    }
                }
            }
        }

        public static function set_gf_contribution_amount_as_product_price($cart_object) {

            foreach ($cart_object->cart_contents as $key => $value) {
                if (WC()->session->__isset($key . '_get_galaxyfunder_contributionamount')) {
                    $session_price = WC()->session->get($key . '_get_galaxyfunder_contributionamount');
                    if (class_exists('WCML_Multi_Currency')) {// Compatible for WPML MultiCurrency Switcher
                        global $woocommerce_wpml;
                        $session_currency = WC()->session->get($key . '_get_galaxyfunder_contributioncurrency');
                        $current_currency = WC()->session->get('client_currency');
                        $wpml_currency = 1;
                        if ($current_currency != $session_currency) {
                            $wpml_currency = $woocommerce_wpml->settings['currency_options'][$session_currency]['rate'];
                            $session_price = fp_wpml_multi_currency_in_cart($session_price, $session_currency, $current_currency);
                        }
                    }
                    $value['data']->set_price($session_price);
                }
            }
        }

        public static function subscribe_option_in_my_account_page() {
            if (get_option('gf_show_hide_your_subscribe_link') == '1') {
                ?>

                <br><h3><input type="checkbox" name="gf_email_subscribeoption" id="gf_email_subscribeoption" value="yes" <?php checked("yes", get_user_meta(get_current_user_id(), 'gf_email_unsub_value', true)); ?>/>    <?php echo get_option('gf_unsubscribe_message_myaccount_page'); ?></h3>
                <?php
            }
        }

        public static function gf_get_sub_value() {
            if ($_POST['getcurrentuser'] && $_POST['subscribe'] == 'no') {
                update_user_meta($_POST['getcurrentuser'], 'gf_email_unsub_value', 'no');
                echo "1";
            } else {
                update_user_meta($_POST['getcurrentuser'], 'gf_email_unsub_value', 'yes');
                echo "2";
            }
            exit();
        }

        public static function redirect_to_product_page_if_product_has_campaign($add_to_cart_text, $product) {
            $product_id = FP_GF_Common_Functions::common_function_to_get_object_id($product);
            $postmeta = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
            if ($postmeta == 'yes') {
                $product_type = FP_GF_Common_Functions::get_woocommerce_product_type($product_id);
                $add_to_cart_text = sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>', esc_url(get_permalink($product_id)), esc_attr($product_id), esc_attr($product->get_sku()), $product->is_purchasable() && $product->is_in_stock() ? '' : '', esc_attr($product_type), esc_html(get_option('cf_add_to_cart_label')));
            }
            return $add_to_cart_text;
        }

        public static function save_gf_contributor_name_in_order($order_id) {
            global $woocommerce;
            $current_cart_contents = $woocommerce->cart->cart_contents;
            foreach ($current_cart_contents as $key => $value) {
                if (WC()->session->get($key . '_get_galaxyfunder_contributorname')) {
                    $contributor_name = WC()->session->get($key . '_get_galaxyfunder_contributorname');
                    if (isset($contributor_name)) {
                        update_post_meta($order_id, 'contributor_list_for_campaign', $contributor_name);
                    }
                }
            }
        }

        public static function my_custom_checkout_field($checkout) {
            foreach (WC()->session->get('cart') as $key => $value) {
                if (get_post_meta($value['product_id'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                    if (get_option('cf_show_hide_mark_anonymous_checkbox') == '1') {
                        echo '<div id="my-new-field">';
                        woocommerce_form_field('my_checkbox', array(
                            'type' => 'checkbox',
                            'class' => array('input-checkbox'),
                            'label' => get_option('cf_checkout_textbox'),
                            'required' => false,
                                ), $checkout->get_value('my_checkbox'));
                        echo '</div>';
                    }
                }
            }
        }

        public static function my_custom_checkout_field_update_order_meta($order_id) {
            if (isset($_POST['my_checkbox']))
                update_post_meta($order_id, 'My Checkbox', esc_attr(isset($_POST['my_checkbox'])));
        }

        public static function ajax_request_response_perk_metabox() {
            global $post;
            if ((isset($_POST['productid'])) && (isset($_POST['listiteration'])) && ($_POST['session_destroy'] == '0')) {
                $objectcart = new WC_Cart();
                $cartid = $objectcart->generate_cart_id($_POST['productid']);
                WC()->session->set($cartid . '_perk_iteration_id', $_POST['iteration'] ? $_POST['iteration'] : '');
                WC()->session->set($cartid . '_perk_amount', $_POST['getamount']);
                WC()->session->set($cartid . '_perk_name', $_POST['getname']);
                WC()->session->set($cartid . '_perk_quantity', $_POST['explodequantity']);
                WC()->session->set($cartid . 'getlistofperksquantity', $_POST['getlistofperksquantity']);
                WC()->session->set($cartid . '_perk_selectedproduct', $_POST['choosedproduct'] ? $_POST['choosedproduct'] : '');
                echo "success";
            } else {
                if (isset($_POST['session_destroy']) && (isset($_POST['productid']))) {
                    $objectcart = new WC_Cart();
                    $cartid = $objectcart->generate_cart_id($_POST['productid']);
                    if ($_POST['session_destroy'] == '1') {
                        WC()->session->__unset($cartid . '_perk_iteration_id');
                        WC()->session->__unset($cartid . '_perk_amount');
                        WC()->session->__unset($cartid . '_perk_name');
                        WC()->session->__unset($cartid . '_perk_quantity');
                        WC()->session->__unset($cartid . 'getlistofperksquantity');
                        WC()->session->__unset($cartid . '_perk_selectedproduct');
                        echo "success";
                    }
                }
            }

            exit();
        }

        public static function galaxyfunder_sessionhandler($order_id, $order_posted) {
            $getdataperkiteration = array();
            $getcartcontents = WC()->cart->cart_contents;
            foreach ($getcartcontents as $key => $value) {
                $getdataperkiteration[] = WC()->session->get($key . '_perk_iteration_id');
                $getdataperkname = WC()->session->get($key . '_perk_name');
                $getdataperkquantity = WC()->session->get($key . '_perk_quantity');
                $qtyiteration = WC()->session->get($key . 'getlistofperksquantity');
                $getdataperkaount = WC()->session->get($key . '_perk_amount');
                $perkselectedproduct = WC()->session->get($key . '_perk_selectedproduct');
                $getdataperkname = array_filter((array) $getdataperkname);
                update_post_meta($order_id, 'getlistofquantities', $qtyiteration);
                update_post_meta($order_id, 'listiteration', $getdataperkiteration);

                fp_gf_update_order_metas_with_wpml_product_support($order_id, $value['product_id'], 'perkname', $getdataperkname);
                fp_gf_update_order_metas_with_wpml_product_support($order_id, $value['product_id'], 'perk_maincontainer', array_filter((array) $getdataperkaount));
                fp_gf_update_order_metas_with_wpml_product_support($order_id, $value['product_id'], 'explodequantity', array_filter((array) $getdataperkquantity));
                fp_gf_update_order_metas_with_wpml_product_support($order_id, $value['product_id'], 'perk_choosed_product', array_filter((array) $perkselectedproduct));
            }
            update_post_meta($order_id, '_perk_iteration_id', array_filter((array) $getdataperkiteration));

            foreach ($getcartcontents as $key => $value) {
                WC()->session->__unset($key . '_perk_iteration_id');
                WC()->session->__unset($key . '_perk_name');
                WC()->session->__unset($key . 'perk_maincontainer');
                WC()->session->__unset($key . '_perk_quantity');
                WC()->session->__unset($key . 'getlistofperksquantity');
                WC()->session->__unset($key . '_perk_selectedproduct');
            }
        }

        public static function galaxy_funder_filter_gateways($gateways) {

            global $woocommerce;
            $campaign_is_in_cart = array();

            foreach ($woocommerce->cart->cart_contents as $values) {
                if (isset($values['product_id'])) {
                    $getpostmeta = get_post_meta($values['product_id'], '_crowdfundingcheckboxvalue', true);
                    if ($getpostmeta == 'yes') {
                        $campaign_is_in_cart[] = 'yes';
                        $galaxy_funder_payment_method = array('cf_paypal_adaptive');
                        if (function_exists('WC')) {
                            foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                                if (!in_array($gateway->id, $galaxy_funder_payment_method)) {
                                    unset($gateways[$gateway->id]);
                                }
                            }
                        } else {
                            if (class_exists('WC_Payment_Gateways')) {
                                $paymentgateway = new WC_Payment_Gateways();
                                foreach ($paymentgateway->payment_gateways()as $gateway) {
                                    if (!in_array($gateway->id, $galaxy_funder_payment_method)) {
                                        unset($gateways[$gateway->id]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!in_array('yes', $campaign_is_in_cart)) {
                unset($gateways['cf_paypal_adaptive']);
            }
            return $gateways;
        }

        public static function thankyou_page_session_destroy() {

            if (isset($_SESSION)) {
                session_destroy();
            }
        }

    }

    FP_GF_Cart_Related_Functions::init();
}