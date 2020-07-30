<?php
if (!class_exists('CFPaymentsAdmin')) {
class CFPaymentsAdmin {
    
     public static function init() {
        //Payment tab settings hooks
            add_action('woocommerce_update_options_crowdfunding_payments', array(__CLASS__, 'crowdfunding_process_payments_update_settings'));
            add_action('init', array(__CLASS__, 'crowdfunding_payments_default_settings'));
            add_action('woocommerce_cf_settings_tabs_crowdfunding_payments', array(__CLASS__, 'crowdfunding_process_payments_admin_settings'));
            add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_payments_tab'), 2000);
            add_action('admin_init', array(__CLASS__, 'cf_payments_reset_values'), 2);
     }

    public static function crowdfunding_admin_payments_tab($settings_tabs) {
         if(!is_array($settings_tabs)){
            $settings_tabs=(array)$settings_tabs;
        }
        $settings_tabs['crowdfunding_payments'] = __('Payment', 'galaxyfunder');
        return $settings_tabs;
    }

    public static function crowdfunding_payments_admin_options() {
        return apply_filters('woocommerce_crowdfunding_payments_settings', array(
            array(
                'name' => __('PayPal Settings', 'galaxyfunder'),
                'type' => 'title',
                'desc' => '',
                'id' => '_cf_payments_inbuilt_text'
            ),
            array(
                'name' => __('Send Payment to Campaign Creator if PayPal ID is Provided', 'galaxyfunder'),
                'desc' => __('PayPal Payments are Directly Sent to Campaign Creator', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;',
                'id' => 'cf_enable_paypal_campaign_email_id',
                'std' => 'no',
                'type' => 'checkbox',
                'newids' => 'cf_enable_paypal_campaign_email_id',
                'desc_tip' => false,
            ),
            array('type' => 'sectionend', 'id' => '_cf_payments_inbuilt_text'),
        ));
    }

    public static function crowdfunding_process_payments_admin_settings() {
        woocommerce_admin_fields(CFPaymentsAdmin::crowdfunding_payments_admin_options());
    }

    public static function crowdfunding_process_payments_update_settings() {
        woocommerce_update_options(CFPaymentsAdmin::crowdfunding_payments_admin_options());
    }

    public static function crowdfunding_payments_default_settings() {
        global $woocommerce;
        foreach (CFPaymentsAdmin::crowdfunding_payments_admin_options() as $setting) {
            if (isset($setting['newids']) && ($setting['std'])) {
                if (get_option($setting['newids']) == FALSE) {
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }
    }

    public static function cf_payments_reset_values() {
        global $woocommerce;
        if (isset($_POST['reset'])) {
             if($_POST['reset_hidden']=='crowdfunding_payments'){
          
                echo FP_GF_Common_Functions::reset_common_function(CFPaymentsAdmin::crowdfunding_payments_admin_options());
        
            }
            }
            
            if(isset($_POST['resetall'])){
                echo FP_GF_Common_Functions::reset_common_function(CFPaymentsAdmin::crowdfunding_payments_admin_options());
            }
        }
}

 CFPaymentsAdmin::init();
}


function init_cf_paypal_adaptive_payment() {
    if (!class_exists('WC_Payment_Gateway'))
        return;

    class CFPaypalAdaptivePayment extends WC_Payment_Gateway {

        function __construct() {
            $this->id = 'cf_paypal_adaptive';
            $this->method_title = 'Galaxy Funder - PayPal Adaptive Split Payment';
            $this->has_fields = true;
            //$this->icon = plugins_url('images/paypal.jpg', __FILE__);
            $this->inits_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        function inits_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'galaxyfunder'),
                    'type' => 'checkbox',
                    'label' => __('Galaxy Funder - PayPal Adaptive Split Payment', 'galaxyfunder'),
                    'default' => 'yes'
                ),
                'cf_payment_mode' => array(
                    'title' => __('Payment Mode', 'woocommerce'),
                    'type' => 'select',
                    'label' => __('Payment Mode', 'woocommerce'),
                    'default' => 'chained',
                    'options' => array('parallel' => __('Parallel', 'galaxyfunder'), 'chained' => __('Chained', 'galaxyfunder'))
                ),
                'title' => array(
                    'title' => __('Title', 'galaxyfunder'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'galaxyfunder'),
                    'default' => __('Galaxy Funder - PayPal Adaptive Split Payment', 'galaxyfunder'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'galaxyfunder'),
                    'type' => 'textarea',
                    'default' => 'PayPal Adaptive Payment for Galaxy Funder',
                    'desc_tip' => true,
                    'description' => __('Enter Detail Description about PayPal Adaptive Payment Gateway for Galaxy Funder', 'galaxyfunder'),
                ),
                'primary_paypal_email' => array(
                    'title' => __('PayPal Email', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter Admin PayPal Email Address', 'galaxyfunder'),
                ),
                'percentage_to_receiver' => array(
                    'title' => __('Percentage for Admin', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter the Percentage Value of between for Admin 0 to 100', 'galaxyfunder'),
                ),
                'cf_paypal_application_id' => array(
                    'title' => __('PayPal Application ID', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter PayPal Application ID', 'galaxyfunder'),
                ),
                'cf_paypal_security_userid' => array(
                    'title' => __('PayPal Security UserID', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter the PayPal Security UserID', 'galaxyfunder'),
                ),
                'cf_paypal_security_password' => array(
                    'title' => __('PayPal Security Password', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter the PayPal Security Password', 'galaxyfunder'),
                ),
                'cf_paypal_security_signature' => array(
                    'title' => __('PayPal Security Signature', 'galaxyfunder'),
                    'type' => 'text',
                    'default' => '',
                    'desc_tip' => true,
                    'description' => __('Please Enter PayPal Security Signature', 'galaxyfunder'),
                ),
                'cf_paypal_cancel_url' => array(
                    'title' => __('Payment Cancel URL', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('If Payment is cancelled Where should they be redirected', 'woocommerce'),
                    'default' => __(site_url(), 'woocommerce'),
                    'desc_tip' => true,
                ),
                'cf_paypal_testmode' => array(
                    'title' => __('PayPal Adaptive sandbox', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Adaptive sandbox', 'woocommerce'),
                    'default' => 'no',
                    'description' => sprintf(__('PayPal Adaptive sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'woocommerce'), 'https://developer.paypal.com/'),
                ),
            );
        }

        function process_payment($order_id) {
            global $woocommerce;
            $order = fp_gf_get_order_object($order_id);
            $payment_url = "";
            $re = "";
            // Mark as on-hold (we're awaiting the cheque)
            //$order->update_status('pending', __('Awaiting cheque payment', 'woocommerce'));
            //$order->payment_complete();
            // Reduce stock levels
            $order->reduce_order_stock();

            // Remove cart
            // $woocommerce->cart->empty_cart();
//            var_dump($order->get_items());
//            // print_r($order->get_items(), true);
//            return;
            $primary_receiver_mail = $this->get_option('primary_paypal_email');
            $getprimary_percentage = $this->get_option('percentage_to_receiver');
            $secondary_remaining_percentage = 100 - $this->get_option('percentage_to_receiver');
            foreach ($order->get_items() as $item) {

                if (get_post_meta($item['product_id'], '_crowdfundingcheckboxvalue', true) == 'yes') {
                    $productprice = $item['line_total'];
                    $secondary_user_email[] = get_post_meta($item['product_id'], 'cf_campaigner_paypal_id', true);
                    $emailpaypal = get_post_meta($item['product_id'], 'cf_campaigner_paypal_id', true);
                    $secondaryamount = ($productprice * $secondary_remaining_percentage) / 100;
                    if (isset($emailpaypal) && !empty($emailpaypal)) {
                        $primary_user_amount [] = ($productprice * $getprimary_percentage) / 100;
                        $newsecondary_email[] = array($emailpaypal => $secondaryamount);
                        $secondary_user_amount[] = ($productprice * $secondary_remaining_percentage) / 100;
                    } else {
                        $primary_user_amount [] = $productprice;
                    }
                }
            }

            //var_dump(array_sum($newsecondary_email));
            $result = array();
            foreach ($secondary_user_email as $i => $v) {
                if (!isset($result[$v])) {
                    $result[$v] = 0;
                }
                $result[$v] += $secondary_user_amount[$i];
            }

            //   var_dump(count(array_unique($secondary_user_email)));
            //   return;
            // var_dump($result);
            // var_dump(array_combine($secondary_user_email, $secondary_user_amount));
            //  var_dump($secondary_user_email);
            //var_dump(array_unique($secondary_user_email));
            //var_dump(count($result));
            //var_dump($result);
            //   return;
            //  var_dump($primary_user_amount);
            //var_dump(array_sum($primary_user_amount));
            //$variablearray[] = (array) "receiverList.receiver(0).amount' => $primary_user_amount";
            $primary_sum_of_amount = array_sum($primary_user_amount);

            //     $variablearray = array();
            // var_dump(array_filter($variablearray));
            // var_dump(array_filter($receiveremailarray));
            //          return;
            if (count(array_unique($secondary_user_email)) > 5) {
                if (function_exists('wc_add_notice')) {
                    wc_add_notice(__('Payment error: Maximum Receiver Count has been Reached', 'woothemes'), 'error');
                } else {
                    $woocommerce->add_error(__('Payment error: Maximum Receiver Count has been Reached', 'woothemes'));
                }

                return;
            }
            $items = $order->get_items();
            foreach ($items as $item) {
                $productid[] = $item['product_id'];
            }

            //var_dump(get_woocommerce_currency());
            $campaigner_email = get_post_meta($productid[0], 'cf_campaigner_paypal_id', true);
            // var_dump($campaigner_email);
            //adaptive payment option
            $primary_receiver_mail = $this->get_option('primary_paypal_email');
            //$primary_user_percentage = $this->get_option('primary_r_amount_percentage');
            $secondary_percentage = 100 - $this->get_option('percentage_to_receiver');
            $order_total_amount = $order->order_total;
            if ($this->get_option('cf_payment_mode') == 'chained') {
                $primary_user_amount = $order_total_amount;
            } else {
                $primary_user_amount = ($order_total_amount * $this->get_option('percentage_to_receiver')) / 100;
            }
            $secondary_user1_amount = ($order_total_amount * $secondary_percentage) / 100;
            $success_url = $this->get_return_url($order);
            $cancel_url = str_replace("&amp;", "&", $order->get_cancel_order_url($this->get_option('cf_paypal_cancel_url')));
            if ("yes" == $this->get_option('cf_paypal_testmode')) {
                $paypal_pay_action_url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";
                $paypal_pay_auth_without_key_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=";
            } else {
                $paypal_pay_action_url = "https://svcs.paypal.com/AdaptivePayments/Pay";
                $paypal_pay_auth_without_key_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=";
            }
            $ipnNotificationUrl = esc_url_raw(add_query_arg(array('ipn' => 'set', 'self_custom' => $order_id), site_url('/')));
            $headers_array = array("X-PAYPAL-SECURITY-USERID" => $this->get_option('cf_paypal_security_userid'),
                "X-PAYPAL-SECURITY-PASSWORD" => $this->get_option('cf_paypal_security_password'),
                "X-PAYPAL-SECURITY-SIGNATURE" => $this->get_option('cf_paypal_security_signature'),
                "X-PAYPAL-APPLICATION-ID" => $this->get_option('cf_paypal_application_id'),
                "X-PAYPAL-REQUEST-DATA-FORMAT" => "NV",
                "X-PAYPAL-RESPONSE-DATA-FORMAT" => "JSON",
            );

            if ($this->get_option('cf_payment_mode') == 'parallel') {
                $data_array = array();
                $data_array['actionType'] = 'PAY';
                $data_array['currencyCode'] = get_woocommerce_currency();
                $data_array['ipnNotificationUrl'] = $ipnNotificationUrl;
                $data_array['receiverList.receiver(0).amount'] = $primary_sum_of_amount;
                $data_array['receiverList.receiver(0).email'] = $primary_receiver_mail;
                if (isset($secondary_user_email[0]) && !empty($secondary_user_email[0])) {
                    for ($x = 1; $x <= count(array_unique($secondary_user_email)); $x++) {
                        $newvalues = $x - 1;
                        $array_search_email = array_search($result[$secondary_user_email[$newvalues]], $result);
                        $data_array["receiverList.receiver($x).amount"] = $result[$secondary_user_email[$newvalues]];
                        $data_array["receiverList.receiver($x).email"] = $array_search_email;
                    }
                }
                $data_array['returnUrl'] = $success_url;
                $data_array['cancelUrl'] = $cancel_url;
                $data_array['requestEnvelope.errorLanguage'] = 'en_US';
            } else {
                $data_array = array();
                $data_array['actionType'] = 'PAY';
                $data_array['currencyCode'] = get_woocommerce_currency();
                $data_array['ipnNotificationUrl'] = $ipnNotificationUrl;
                $data_array["receiverList.receiver(0).amount"] = $order->order_total;
                $data_array["receiverList.receiver(0).email"] = $primary_receiver_mail;
                if (isset($secondary_user_email[0]) && !empty($secondary_user_email[0])) {
                    $data_array["receiverList.receiver(0).primary"] = 'true';
                    for ($x = 1; $x <= count(array_unique($secondary_user_email)); $x++) {
                        $newvalues = $x - 1;
                        $array_search_email = array_search($result[$secondary_user_email[$newvalues]], $result);
                        $data_array["receiverList.receiver($x).amount"] = $result[$secondary_user_email[$newvalues]];
                        $data_array["receiverList.receiver($x).email"] = $array_search_email;
                        $data_array["receiverList.receiver($x).primary"] = 'false';
                    }
                }
                $data_array["returnUrl"] = $success_url;
                $data_array["cancelUrl"] = $cancel_url;
                $data_array["requestEnvelope.errorLanguage"] = 'en_US';
            }
            $post_result = galaxy_fp_get_cURL_adaptive_split_response($paypal_pay_action_url, $headers_array, $data_array);
            if (is_wp_error($post_result)) {
                // $jso = json_decode($pay_result['body']);
                $re = print_r($post_result->get_error_message(), true);
                if (function_exists('wc_add_notice')) {
                    wc_add_notice(__('Payment error:', 'woothemes') . $re, 'error');
                } else {
                    $woocommerce->add_error(__('Payment error:', 'woothemes') . $re);
                }
                return;
            }
            $jso = json_decode($post_result);
            // echo $jso->responseEnvelope->ack;
            //  echo $jso->payKey;
            if (isset($jso->payKey)) {
                $payment_url = $paypal_pay_auth_without_key_url . $jso->payKey;
            }
            //header('Location:https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=' . $jso->payKey);
            // Return thankyou redirect
            if ("Success" == $jso->responseEnvelope->ack) {
                return array(
                    'result' => 'success',
                    'redirect' => $payment_url
                );
            } else {
                if (function_exists('wc_add_notice')) {
                    $error_code = "<br>Error Code: " . $jso->error[0]->errorId;
                    $error_message = $jso->error[0]->message;
                    wc_add_notice(__("$error_message $error_code", 'galaxyfunder'), 'error');
                } else {
                    //$woocommerce->add_error(__('Payment error: Please Report to admin about this problem', 'woothemes') . $re);
                    $error_code = "<br>Error Code: " . $jso->error[0]->errorId;
                    $error_message = $jso->error[0]->message;
                    $woocommerce->add_error(__("$error_message $error_code", 'galaxyfunder'));
                }
                return;
            }
        }

    }

    function cf_paypal_adaptive_payment($methods) {
        $methods[] = 'CFPaypalAdaptivePayment';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'cf_paypal_adaptive_payment');
}

add_action('init', 'init_cf_paypal_adaptive_payment');

function galaxy_fp_get_cURL_adaptive_split_response($url, $headers_array, $data_array) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_array));

    if (!empty($headers_array)) {
        $headers = array();
        foreach ($headers_array as $name => $value) {
            $headers[] = "{$name}: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    } else {
        curl_setopt($ch, CURLOPT_HEADER, false);
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function cf_pap_check_ipn() {
    if (isset($_GET['ipn'])) {
        $paypal_adaptive_payment = new CFPaypalAdaptivePayment();
        if ("yes" == $paypal_adaptive_payment->get_option('cf_paypal_testmode')) {
            $paypal_ipn_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate";
        } elseif ("no" == $paypal_adaptive_payment->get_option('cf_paypal_testmode')) {
            $paypal_ipn_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate";
        }
        $ipn_post = !empty($_POST) ? $_POST : false;
        if ($ipn_post) {
            header('HTTP/1.1 200 OK');
            $self_custom = $_GET['self_custom'];
            $received_post = file_get_contents("php://input"); // adaptive payment ipn message is different from normal so we handle like this
            $posted_response = galaxy_fp_get_cURL_adaptive_split_response($paypal_ipn_url, '', $received_post);
            update_option('adaptive_verify', $posted_response);
            $received_raw_post_array = explode('&', $received_post);
            $post_maded = array(); // making post from raw
            foreach ($received_raw_post_array as $keyval) {
                $keyval = explode('=', $keyval);
                if (count($keyval) == 2)
                    $post_maded[urldecode($keyval[0])] = urldecode($keyval[1]);
            }
            if (strcmp($posted_response, "VERIFIED") == 0) {

                $received_order_id = $self_custom;
                $total = 0;
                if ($paypal_adaptive_payment->get_option('cf_payment_mode') == 'parallel') {
                    for ($i = 0; $i <= 5; $i++) {
                        if (isset($post_maded["transaction[$i].amount"])) {
                            $total = $total + preg_replace("/[^0-9,.]/", "", $post_maded["transaction[$i].amount"]);
                        }
                    }
                } else {
                    $total = preg_replace("/[^0-9,.]/", "", $post_maded["transaction[0].amount"]);
                }
                update_option('pay_amount', $total);
                $payment_status = $post_maded['transaction[0].status']; // first user status
                if ($payment_status == FP_GF_Common_Functions::get_order_status_for_contribution()) {
                    $order = fp_gf_get_order_object($received_order_id);
                    $order_id = FP_GF_Common_Functions::common_function_to_get_object_id($order);
                    if (isset($order_id)) {

                        if ($total == $order->order_total) {

                            $order->update_status(FP_GF_Common_Functions::get_order_status_for_contribution());
                        }

                        update_post_meta($order_id, 'Transaction ID', $post_maded['transaction[0].id']);
                    }
                }
            }
        }
        update_option('adaptive_ipn', $received_post);
        update_option('adaptive_ipn_post_ar', $_POST);
        update_option('adaptive_self_cus', $_GET['self_custom']);
        update_option('ipn_url_decoded', $post_maded);
    }
}

add_action('init', 'cf_pap_check_ipn');
