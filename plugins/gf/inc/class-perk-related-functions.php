<?php 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FP_GF_Perk_Related_Functions')) {

    final class FP_GF_Perk_Related_Functions {
    
       public static function init() {
             //Updating a perk claim based on order status
             add_action('woocommerce_order_status_' . FP_GF_Common_Functions::get_order_status_for_contribution(), array(__CLASS__, 'update_perk_claim_main_function'));
        }
        
        public static function update_perk_claim_main_function($order_id) {
            
            $order = fp_gf_get_order_object($order_id);
            foreach ($order->get_items() as $eachitem) {
                
                $checkcrowdfundingisenable = get_post_meta($eachitem['product_id'], '_crowdfundingcheckboxvalue', true);
                if ($checkcrowdfundingisenable == 'yes') {
                    FP_GF_Perk_Related_Functions::main_perk_claim_updation($order_id, $eachitem['product_id']);
                }
            }
        }
        
        public static function main_perk_claim_updation($orderid, $productid) {
            $getlistofperksorder = get_post_meta($orderid, 'getlistofquantities', true);
            $listofperkiteration = get_post_meta($orderid, 'listofiteration', true);
            $getallcampaignperks = get_post_meta($productid, 'perk', true);
            if(!is_array($getlistofperksorder)){
                $getlistofperksorder=(array)$getlistofperksorder;
            }
            if(!empty($getlistofperksorder)){
                    foreach ($getlistofperksorder as $eachiteration) {
                        if($eachiteration!='') {
                            $eachiterationkey = explode('_', $eachiteration);
                            if(is_array($eachiterationkey) && !empty($eachiterationkey)) {
                            $perkname = $getallcampaignperks[$eachiterationkey[0]]['name'];
                            $perkname = str_replace('', '_', $perkname);
                            $amount = $getallcampaignperks[$eachiterationkey[0]]['amount'];
                            $getcountofpreviousclaimed = get_post_meta($productid, $perkname . $amount . 'update_perk_claim', true);
                            $currentclaimedcount = $eachiterationkey[1];

                            $overalllength = $getcountofpreviousclaimed + $currentclaimedcount;
                            $perkclaimcount = $eachiterationkey[0]['claimcount'] ? $eachiterationkey[0]['claimcount'] : 0;
                            $limitationofperk = $eachiterationkey[0]['limitperk'] ? $eachiterationkey[0]['limitperk'] : 'cf_unlimited';
                            if ($limitationofperk == 'cf_limited') {
                                if ($perkclaimcount > $getcountofpreviousclaimed) {
                                    fp_gf_update_campaign_metas($productid, $perkname . $amount . 'update_perk_claim', $overalllength);
                                }
                            } else {
                                    fp_gf_update_campaign_metas($productid, $perkname . $amount . 'update_perk_claim', $overalllength);
                            }

                            $getchoosedproducts = $getallcampaignperks[$eachiterationkey[0]]['choose_products'] ? $getallcampaignperks[$eachiterationkey[0]]['choose_products'] : '';
                            if ($getchoosedproducts != '') {
                                FP_GF_Perk_Related_Functions::add_additional_item_to_order($orderid, $getchoosedproducts, $eachiterationkey[1]);
                            }
                    }
                        }
                    }
            }
        }
        
        public static function add_additional_item_to_order($order_id, $choosedproduct, $qty) {
            $order = fp_gf_get_order_object($order_id);
            $regularprice = get_post_meta($choosedproduct,'_price',true);
            $item_id = wc_add_order_item($order_id, array(
                'order_item_name' => get_the_title($choosedproduct), //get_the_title($perk['choose_products']),
                'order_item_type' => 'line_item'
            ));
            if ($item_id) {
                wc_add_order_item_meta($item_id, '_product_id', $choosedproduct);
                wc_add_order_item_meta($item_id, '_line_total', $regularprice);
                wc_add_order_item_meta($item_id, '_line_subtotal', $regularprice);
                wc_add_order_item_meta($item_id, '_line_tax', '0');
                wc_add_order_item_meta($item_id, '_line_subtotal_tax', '0');
                wc_add_order_item_meta($item_id, '_qty', $qty);
            }

            if (sizeof($order->get_items()) > 0) {
                foreach ($order->get_items() as $item) {
                    $_product = $order->get_product_from_item($item);
                    if ($_product && $_product->exists() && $_product->is_downloadable()) {
                        $downloads = $_product->get_files();
                        foreach (array_keys($downloads) as $download_id) {
                            wc_downloadable_file_permission($download_id, $item['product_id'], $order);
                        }
                    }
                }
            }
            update_post_meta($order_id, '_download_permissions_granted', 1);
        }

    }
    
    FP_GF_Perk_Related_Functions::init();
}