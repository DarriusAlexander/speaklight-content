<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FP_GF_Order_Related_Functions')) {

    final class FP_GF_Order_Related_Functions {

        public static function init() {
            //Set Outofstock and instock
            add_action('woocommerce_order_status_' . FP_GF_Common_Functions::get_order_status_for_contribution(), array(__CLASS__, 'out_of_stock_galaxy_funder_qty'));
            //Adding admin page values
            add_action('woocommerce_order_status_' . FP_GF_Common_Functions::get_order_status_for_contribution(), array(__CLASS__, 'crowdfunding_adminpage_values'), 1, 100);
            //Trash post
            add_action('wp_trash_post', array(__CLASS__, 'cf_crowdfunding_update_post_meta_order_delete'));
            //Order status refund
            add_action('woocommerce_order_status_refunded', array(__CLASS__, 'cf_crowdfunding_update_post_meta_order_delete'));
        }

        public static function out_of_stock_galaxy_funder_qty() {
            global $post;
            if (is_object($post)) {
                $product_id = $post->ID;
                $galaxy_check = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                if ($galaxy_check == 'yes') {
                    $target_end_selection = get_post_meta($product_id, '_target_end_selection', true);
                    $stock_status = get_post_meta($product_id, '_stock_status', true);
                    $stock_check = get_post_meta($product_id, 'out_of_stock_check', true);
                    if ($target_end_selection == '5' && $stock_check == 'yes' && $stock_status != 'outofstock') {
                        fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                    }
                    if ($target_end_selection == '5' && $stock_check == '' && $stock_status != 'instock') {
                        fp_gf_update_campaign_metas($product_id, '_stock_status', 'instock');
                    }
                }
            }
        }

        public static function update_total_contributed_amount_for_product($id, $line_total, $order) {
            $session_currency = fp_gf_get_order_currency($order);
            $getolddata = fp_wpml_orginal_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($id, '_crowdfundingtotalprice'), $session_currency);
            if ($getolddata == '') {
                $getolddata = '0';
            }
            $maintotal = $line_total + $getolddata; //Arithmetic Operation
            $maintotal1 = fp_wpml_orginal_currency($maintotal, $session_currency);
            FP_GF_Common_Functions::update_galaxy_funder_post_meta($id, '_crowdfundingtotalprice', $maintotal1);
            return;
        }

        public static function update_total_contributed_funder_for_product($id) {
            $getolddata = FP_GF_Common_Functions::get_galaxy_funder_post_meta($id, '_update_total_funders');
            if ($getolddata == '') {
                $getolddata = '0';
            }
            $updatedfunders = 1 + $getolddata; //Arithmetic Operation
            FP_GF_Common_Functions::update_galaxy_funder_post_meta($id, '_update_total_funders', $updatedfunders);
            return;
        }

        public static function galaxy_funder_percentage_contributed($productid, $order) {
            /* Get Target Goal for galaxy Funder */
            $session_currency = fp_gf_get_order_currency($order);
            $gettargetgoalamount1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggettargetprice'); // Target Goal from Single Product Page of galaxy Funder
            $gettargetgoalamount = fp_wpml_orginal_currency($gettargetgoalamount1, $session_currency);
            $getraisedamount = fp_wpml_orginal_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingtotalprice'), $session_currency); // Total Pledged Amount from Single Product Page of galaxy Funder
            $target_quantity = get_post_meta($productid, '_crowdfundingquantity', true);
            $remaining_qty = get_post_meta($productid, '_gf_saled_qty', true);

            if ($gettargetgoalamount != '') {
                if (($getraisedamount != '') && ($gettargetgoalamount > 0)) {
                    $count1 = $getraisedamount / $gettargetgoalamount;
                    $count2 = $count1 * 100;
                    $counter = number_format($count2, 0);
                    fp_gf_update_campaign_metas($productid, '_crowdfundinggoalpercent', $counter);
                }
            } else {
                $count1 = $remaining_qty / $target_quantity;
                $count2 = $count1 * 100;
                $counter = number_format($count2, 0);
                fp_gf_update_campaign_metas($productid, '_crowdfundinggoalpercent', $counter);
            }
        }

        public static function crowdfunding_adminpage_values($orderid) {

            $orderobject = fp_gf_get_order_object($orderid);
            foreach ($orderobject->get_items() as $eachitem) {
                $checkcrowdfunding = get_post_meta($eachitem['product_id'], '_crowdfundingcheckboxvalue', true);
                if ($checkcrowdfunding == 'yes') {
                    $getoldorderids = (array) get_post_meta($eachitem['product_id'], 'orderids', true);
                    $currentorderids = array($orderid);
                    $currentorderids = array_merge((array) $currentorderids, $getoldorderids);
                    fp_gf_update_campaign_metas($eachitem['product_id'], 'orderids', $currentorderids);
                }
            }
            $order_ids_array = get_option('updated_orderids');

            $order = fp_gf_get_order_object($orderid);
            if (!in_array($orderid, (array) $order_ids_array)) {
                $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
                $order_status = $formed_order_object->get_status;

//Check that order id should not in the list because to avoid duplication
                if ($order_status == FP_GF_Common_Functions::get_order_status_for_contribution()) {
                    foreach ($order->get_items() as $item) {
                        $product_id = $item['product_id'];
                        $checkcrowdfunding = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                        if ($checkcrowdfunding == 'yes') {
                            if (get_option('cf_campaign_restrict_coupon_discount') == '1') {
                                $fund_amount = $item['line_total'];
                            } else {
                                $fund_amount = $item['line_subtotal'];
                            }
                            $order_ids_array[] = FP_GF_Common_Functions::common_function_to_get_object_id($order);

                            self::update_total_contributed_amount_for_product($product_id, $fund_amount, $order);
                            self::galaxy_funder_percentage_contributed($product_id, $order);
                            self::update_total_contributed_funder_for_product($product_id);
                        }
                    }
                }
            }
            update_option('updated_orderids', $order_ids_array);
        }

        public static function cf_crowdfunding_update_post_meta_order_delete($post_id) {
            $post_type = get_post_type($post_id);
            $post_status = get_post_status($post_id);

            if ("shop_order" == $post_type) {
                $order = fp_gf_get_order_object($post_id);
                $session_currency = fp_gf_get_order_currency($order);
                if (in_array($post_id, (array) get_option('updated_orderids'))) {
                    $post_status = str_replace('wc-', '', $post_status);

//                    if (($post_status == FP_GF_Common_Functions::get_order_status_for_contribution()) || ($post_status == 'refunded')) {

                    foreach ($order->get_items() as $item) {

                        $check_crowdfunding_enabled = get_post_meta($item['product_id'], '_crowdfundingcheckboxvalue', true);
                        if ($check_crowdfunding_enabled == 'yes') {


                            $check_order_ids = get_option('updated_orderids');
                            $order_id = FP_GF_Common_Functions::common_function_to_get_object_id($order);
                            if (in_array($order_id, (array) $check_order_ids)) {
                                $old_totals = fp_wpml_orginal_currency(get_post_meta($item['product_id'], '_crowdfundingtotalprice', true), $session_currency);
                                $totals = $old_totals - $item['line_total'];


                                $getoverallgoalamount = fp_wpml_orginal_currency(get_post_meta($item['product_id'], '_crowdfundingtotalprice', true), $session_currency);
                                $currenttargetgoal1 = get_post_meta($item['product_id'], '_crowdfundinggettargetprice', true);
                                $currenttargetgoal = fp_wpml_orginal_currency($currenttargetgoal1, $session_currency);

                                if ($getoverallgoalamount >= $currenttargetgoal) {
// get stock status
                                    $stockstatus = get_post_meta($item['product_id'], '_stock_status', true);

                                    if ($stockstatus == 'outofstock') {
                                        if ($getoverallgoalamount > $totals) {
                                            fp_gf_update_campaign_metas($item['product_id'], '_stock_status', 'instock');
                                        }
                                    }
                                }
                                $session_currency = fp_gf_get_order_currency($order);
                                $maintotal1 = fp_wpml_orginal_currency($totals, $session_currency);
                                $old_order_ids_of_campaign = get_post_meta($item['product_id'], 'orderids', true);
                                $key = array_search($post_id, $old_order_ids_of_campaign);
                                unset($old_order_ids_of_campaign[$key]);
                                fp_gf_update_campaign_metas($item['product_id'], 'orderids', $old_order_ids_of_campaign);
                                fp_gf_update_campaign_metas($item['product_id'], '_crowdfundingtotalprice', $maintotal1);
                                $gettargetgoalamount1 = get_post_meta($item['product_id'], '_crowdfundinggettargetprice', true);
                                $gettargetgoalamount = fp_wpml_orginal_currency($gettargetgoalamount1, $session_currency);
                                $getordertotal = fp_wpml_orginal_currency(get_post_meta($item['product_id'], '_crowdfundingtotalprice', true), $session_currency);
                                if ($gettargetgoalamount != '') {
                                    if (($getordertotal != '') && ($gettargetgoalamount > 0)) {
                                        $count1 = $getordertotal / $gettargetgoalamount;
                                        $count2 = $count1 * 100;
                                        $counter = number_format($count2, 0);
                                        fp_gf_update_campaign_metas($item['product_id'], '_crowdfundinggoalpercent', $counter);
                                    }
                                }
                                $oldfunderscount = get_post_meta($item['product_id'], '_update_total_funders', true);
                                fp_gf_update_campaign_metas($item['product_id'], '_update_total_funders', $oldfunderscount - 1);
                            }
                        }
                    }
                    if (function_exists('wc_get_order_statuses')) {
                        $getpoststatus = array('wc-completed', 'wc-refunded');
                    } else {
                        $getpoststatus = 'publish';
                    }
                    $order = fp_gf_get_order_object($post_id);
                    $order_ids_array = get_option('updated_orderids');
                    if (!in_array($post_id, (array) $order_ids_array)) {
                        $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
                        $order_status = $formed_order_object->get_status;
                        //Check that order id should not in the list because to avoid duplication
                        if ($order_status == FP_GF_Common_Functions::get_order_status_for_contribution()) {
                            foreach ($order->get_items() as $item) {
                                $product_id = $item['product_id'];
                                $checkcrowdfunding = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                                if ($checkcrowdfunding == 'yes') {
                                    if (get_option('cf_campaign_restrict_coupon_discount') == '1') {
                                        $fund_amount = $item['line_total'];
                                    } else {
                                        $fund_amount = $item['line_subtotal'];
                                    }

                                    self::update_total_contributed_amount_for_product($product_id, $fund_amount, $order);
                                    self::galaxy_funder_percentage_contributed($product_id, $order);
                                    self::update_total_contributed_funder_for_product($product_id);
                                }
                            }
                        }
                    }
//                    }
                }
            }
        }

    }

    FP_GF_Order_Related_Functions::init();
}