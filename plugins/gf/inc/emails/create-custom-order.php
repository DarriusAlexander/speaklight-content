
<?php

if (!function_exists('wc_create_order')) {
    $order_data = array(
        'post_type' => 'shop_order',
        'post_title' => sprintf(__('Order &ndash; %s', 'woocommerce'), strftime(_x('%b %d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'woocommerce'))),
        'post_status' => 'publish',
        'post_author' => 1,
    );
// Insert or update the post data
    $create_new_order = true;
    $regularprice1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
    $regularprice = fp_wpml_multi_currency($regularprice1);
    $userid = get_post_field('post_author', $product_id);
    $selectproductid = get_post_meta($product_id, '_cf_product_selection', true);
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
    if ($create_new_order) {
        //update_option('counterorder', $i);
        $order_id = wp_insert_post($order_data, true);
        /* Shipping Information */
        update_post_meta($order_id, '_shipping_first_name', $ship_first_name);
        update_post_meta($order_id, '_shipping_last_name', $ship_last_name);
        update_post_meta($order_id, '_shipping_company', $ship_company);
        update_post_meta($order_id, '_shipping_address_1', $ship_address1);
        update_post_meta($order_id, '_shipping_address_2', $ship_address2);
        update_post_meta($order_id, '_shipping_city', $ship_city);
        update_post_meta($order_id, '_shipping_postcode', $ship_postcode);
        update_post_meta($order_id, '_shipping_country', $ship_country);
        update_post_meta($order_id, '_shipping_state', $ship_state);
        /* Billing Information */
        update_post_meta($order_id, '_billing_first_name', $bill_first_name);
        update_post_meta($order_id, '_billing_last_name', $bill_last_name);
        update_post_meta($order_id, '_billing_company', $bill_company);
        update_post_meta($order_id, '_billing_address_1', $bill_address1);
        update_post_meta($order_id, '_billing_address_2', $bill_address2);
        update_post_meta($order_id, '_billing_city', $bill_city);
        update_post_meta($order_id, '_billing_postcode', $bill_postcode);
        update_post_meta($order_id, '_billing_country', $bill_country);
        update_post_meta($order_id, '_billing_state', $bill_state);
        update_post_meta($order_id, '_billing_email', $bill_email);
        update_post_meta($order_id, '_billing_phone', $bill_phone);
        update_post_meta($order_id, '_payment_method', 'other');
        /* Update User Information for this order */
        update_post_meta($order_id, '_customer_user', $userid);
        update_post_meta($order_id, '_order_total', $regularprice);
        update_post_meta($order_id, '_order_key', 'wc_' . apply_filters('woocommerce_generate_order_key', uniqid('order_')));
        /* Update Status to Completed and Downloadable File Information */
    } 
    if(is_array($selectproductid)){
    foreach ($selectproductid as $eachvalue) {
        $titleforselectedproduct = get_the_title($eachvalue);
        $item_id = wc_add_order_item($order_id, array(
            'order_item_name' => $titleforselectedproduct,
            'order_item_type' => 'line_item'
        ));
        if ($item_id) {
            wc_add_order_item_meta($item_id, '_product_id', $eachvalue);
            wc_add_order_item_meta($item_id, '_line_total', get_post_meta($eachvalue, '_price', true));
            wc_add_order_item_meta($item_id, '_line_subtotal', get_post_meta($eachvalue, '_price', true));
            wc_add_order_item_meta($item_id, '_line_tax', '0');
            wc_add_order_item_meta($item_id, '_line_subtotal_tax', '0');
            wc_add_order_item_meta($item_id, '_qty', '1');
        }
    }
    }else{
                        if(isset($selectproductid)){
                          $titleforselectedproduct = get_the_title($selectproductid);
        $item_id = wc_add_order_item($order_id, array(
            'order_item_name' => $titleforselectedproduct,
            'order_item_type' => 'line_item'
        ));
        if ($item_id) {
            wc_add_order_item_meta($item_id, '_product_id', $selectproductid);
            wc_add_order_item_meta($item_id, '_line_total', get_post_meta($selectproductid, '_price', true));
            wc_add_order_item_meta($item_id, '_line_subtotal', get_post_meta($selectproductid, '_price', true));
            wc_add_order_item_meta($item_id, '_line_tax', '0');
            wc_add_order_item_meta($item_id, '_line_subtotal_tax', '0');
            wc_add_order_item_meta($item_id, '_qty', '1');
        }  
                        }
                    }
    $order = fp_gf_get_order_object($order_id);
    // var_dump(sizeof($order->get_items()));
    if (sizeof($order->get_items()) > 0) {
        foreach ($order->get_items() as $item) {
            $_product = $order->get_product_from_item($item);

            if ($_product && $_product->exists() && $_product->is_downloadable()) {
                $downloads = $_product->get_files();
                foreach (array_keys($downloads) as $download_id) {
                    if(is_array($selectproductid)) {
                    foreach ($selectproductid as $eachvalue) {
                        wc_downloadable_file_permission($download_id, $eachvalue, $order);
                    }
                    } else{
                        if(isset($selectproductid)){
                         wc_downloadable_file_permission($download_id, $selectproductid, $order);    
                        }
                    }
                }
            }
        }
    }
    update_post_meta($order_id, '_download_permissions_granted', 1);
    wp_set_object_terms($order_id, FP_GF_Common_Functions::get_order_status_for_contribution(), 'shop_order_status');
} else {
    $userid = get_post_field('post_author', $product_id);
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
    $billingaddress = array(
        'first_name' => $bill_first_name,
        'last_name' => $bill_last_name,
        'company' => $bill_company,
        'email' => $bill_email,
        'phone' => $bill_phone,
        'address_1' => $bill_address1,
        'address_2' => $bill_address2,
        'city' => $bill_city,
        'state' => $bill_state,
        'postcode' => $bill_postcode,
        'country' => $bill_country
    );
    $shippingaddress = array(
        'first_name' => $ship_first_name,
        'last_name' => $ship_last_name,
        'company' => $ship_company,
        'address_1' => $ship_address1,
        'address_2' => $ship_address2,
        'city' => $ship_city,
        'state' => $ship_state,
        'postcode' => $ship_postcode,
        'country' => $ship_country
    );
    $order_data = array(
        'status' => apply_filters('woocommerce_default_order_status', 'completed'),
        'customer_id' => $userid,
    );
    $order = wc_create_order($order_data);
    $selectproductid = get_post_meta($product_id, '_cf_product_selection', true);
//      if (isset($selectproductid)) {
      if (is_array($selectproductid)){
        foreach ($selectproductid as $eachproductid) {
            $order->add_product(FP_GF_Common_Functions::get_woocommerce_product_object($eachproductid), 1);
        }
    
      }else{
           if (isset($selectproductid)){
           $order->add_product(FP_GF_Common_Functions::get_woocommerce_product_object($selectproductid), 1);
           }
      }
//      }
    $order->set_address($billingaddress, 'billing');
    $order->set_address($shippingaddress, 'shipping');
    $order->calculate_totals();
    //For Download
    $order_id = FP_GF_Common_Functions::common_function_to_get_object_id($order);
    $orders = fp_gf_get_order_object($order_id);
  
    if (sizeof($orders->get_items()) > 0) {
        foreach ($orders->get_items() as $item) {
            $_product = $orders->get_product_from_item($item);

            if ($_product && $_product->exists() && $_product->is_downloadable()) {
                $downloads = $_product->get_files();
                foreach (array_keys($downloads) as $download_id) {
                    if (is_array($selectproductid)) {
                        foreach ($selectproductid as $eachproductid) {
                            wc_downloadable_file_permission($download_id, $eachproductid, $orders);
                        }
                    } else{
                        if(isset($selectproductid)){
                 wc_downloadable_file_permission($download_id, $selectproductid, $orders);
                        }
                    }
                }
            }
        }
    }
   
    update_post_meta($order_id, '_download_permissions_granted', 1);
}