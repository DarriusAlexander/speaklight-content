<?php
/*
 * Shop Related Functionality
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Shop_Functions')) {

    /**
     * Shop Class.
     */
    class FP_GF_Shop_Functions {

        public static function init() {
            //Including Progress bar by using get price html(shop,admin side product list)
            add_filter('woocommerce_get_price_html', array(__CLASS__, 'crowdfunding_remove_product_pricing'), 10, 2);
            //Including Progress bar before add to cart
            add_action('woocommerce_before_add_to_cart_button', array(__CLASS__, 'add_contribution_field_to_pledge'));
            //displaying multiple input types core
            add_action('woocommerce_single_product_summary', array(__CLASS__, 'checkingfrontend_galaxy'));
            //Minimum maximum js codes
            add_action('wp_head', array(__CLASS__, 'add_internal_js_for_frontend'));
            //Hiding a campaigns
            if (get_option('cf_campaign_in_shop_page') == '1' && get_option('cf_hide_closed_campaigns') == '2') {
                add_action('pre_get_posts', array(__CLASS__, 'cf_hide_outofstock_shop_and_category_page_crowdfunding'));
            }
            if (get_option('cf_campaign_in_shop_page') == '2') {
                add_action('pre_get_posts', array(__CLASS__, 'cf_hide_shop_page_crowdfunding'));
            }
        }

        public static function add_internal_js_for_frontend() {
            global $post;
            if (is_product() || is_shop()) {
                $checkvalue = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingcheckboxvalue');
                if ($checkvalue == 'yes') {
                    $minimumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundinggetminimumprice'));
                    $maximumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundinggetmaximumprice'));

                    $isthereminimum = $minimumprice == '' ? '0' : '1';
                    $istheremaximum = $maximumprice == '' ? '0' : '1';

                    $findarray = array('[mincontribution]', '[maxcontribution]');
                    $replacearray = array($minimumprice, $maximumprice);

                    $minimum_error_message = str_replace($findarray, $replacearray, get_option('cf_min_price_error_msg'));
                    $maximum_error_message = str_replace($findarray, $replacearray, get_option('cf_max_price_error_msg'));

                    $empty_error_message = str_replace($findarray, $replacearray, get_option('_cf_empty_contribution_error_msg'));

                    $target_end_type = get_post_meta($post->ID, '_target_end_selection', true);
                    ?>
                    <script type='text/javascript'>
                        jQuery(document).ready(function () {
                            var minimumprice = parseInt("<?php echo $minimumprice; ?>");
                            var maximumprice = parseInt("<?php echo $maximumprice; ?>");
                            var isthereminimum = parseInt("<?php echo $isthereminimum; ?>");
                            var istheremaximum = parseInt("<?php echo $istheremaximum; ?>");
                            jQuery('.single_add_to_cart_button').click(function () {

                    <?php if ($target_end_type != 5) { ?>
                                    var contribution = parseInt(jQuery('.addfundraiser<?php echo $post->ID ?>').val());
                                    if (isNaN(contribution) === true) {
                                        jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').html("<?php echo esc_html($empty_error_message); ?>");
                                        jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeIn(3000);
                                        jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeOut(1000);
                                        return false;
                                    } else {
                                        if ((isthereminimum !== 0) && (istheremaximum !== 0)) {
                                            if (contribution < minimumprice) {
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').html("<?php echo esc_html($minimum_error_message); ?>");
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeIn(3000);
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeOut(1000);
                                                return false;
                                            } else if (contribution > maximumprice) {

                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').html("<?php echo esc_html($maximum_error_message); ?>");
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeIn(3000);
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeOut(1000);
                                                return false;
                                            }

                                        } else if ((isthereminimum !== 0) && (istheremaximum === 0)) {

                                            if (contribution < minimumprice) {


                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').html("<?php echo esc_html($minimum_error_message); ?>");
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeIn(3000);
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeOut(1000);
                                                return false;
                                            }
                                        } else if ((isthereminimum === 0) && (istheremaximum !== 0)) {
                                            if (contribution > maximumprice) {

                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').html("<?php echo esc_html($maximum_error_message); ?>");
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeIn(3000);
                                                jQuery('.singlecrowdfunding<?php echo $post->ID; ?>').fadeOut(1000);
                                                return false;
                                            }
                                        }

                                    }
                    <?php } ?>
                            });

                        });

                    </script>


                    <?php
                }
            }

            if (is_product()) {
                $newid = $post->ID;
                $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                $checkmethod = get_post_meta($newid, '_target_end_selection', true);
                $payyourpricelabel = get_option('cf_contributor_label');
                if ($payyourpricelabel != '') {
                    $payyourpricecaption = $payyourpricelabel;
                    $colonpay = ":";
                }
                $recommendedvalue = get_post_meta($newid, '_crowdfundinggetrecommendedprice', true);
                if ($checkvalue == 'yes') {
                    $contribute_style = get_post_meta($post->ID, 'buttonstyles_galaxy', true);
                    if (($contribute_style == 'radio') || ($contribute_style == 'dropdown') || ($contribute_style == 'btn_editable')) {
                        ?>
                        <style type="text/css">
                            .gf_contribution_row{
                                display: none
                            }
                        </style>
                        <?php
                    } else {
                        ?>
                        <style type="text/css">
                            .gf_contribution_row{
                                display: block
                            }
                        </style>
                        <?php
                    }

                    if ($contribute_style == 'radio' || $contribute_style == 'dropdown' || $contribute_style == 'default_non_editable') {
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery('.singleproductinputfieldcrowdfunding').hide();
                            });
                        </script>
                        <?php
                    }

                    if ($contribute_style == 'dropdown') {
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                var vari = jQuery('.cf_amount_dropdown').attr('cf_data-amount');
                                jQuery('.addfundraiser<?php echo $post->ID ?>').val(vari);
                            });
                        </script>
                        <?php
                    }
                    if ($contribute_style == 'default_non_editable') {
                        $amount = get_post_meta($post->ID, '_recomended_amount_galaxy', true);
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery('.addfundraiser<?php echo $post->ID ?>').val('<?php echo $amount ?>');
                                jQuery('.addfundraiser<?php echo $post->ID ?>').attr('readonly', true);
                            });
                        </script>
                        <?php
                    }
                    ?>
                    <style type="text/css">
                        .qty{
                            display: none
                        }
                    </style>
                    <?php
                    FP_GF_Common_Functions::common_function_to_find_from_to_date($post->ID, 'from');
                }
            }
        }

        public static function checkingfrontend_galaxy() {
            global $post;
            ?>
            <script type='text/javascript'>
                jQuery(document).ready(function () {
                    jQuery('._target_end_selection').css("display", "none");
                    //var newdata_universe = jQuery('.cf_amount_dropdown').attr('uf_data-amount');
                    jQuery('.cf_amount_button').click(function () {
                        var newdata_galaxy = jQuery(this).attr('cf_data-amount');
                        jQuery('.addfundraiser<?php echo $post->ID; ?>').val(newdata_galaxy);
                        jQuery('.cf_amount_button').removeClass('cf_amount_button_clicked');
                        jQuery(this).addClass('cf_amount_button_clicked');
                    });
                    jQuery('.cf_amount_dropdown').click(function () {
                        var newdata_galaxy = jQuery(this).attr('cf_data-amount');
                        jQuery('.addfundraiser<?php echo $post->ID; ?>').val(newdata_galaxy);
                    });
                    if (!jQuery.trim(jQuery('.cf_container_galaxy').html()).length) {
                        jQuery('.cf_container_galaxy').css("display", "none");
                    } else {
                        jQuery('.cf_container_galaxy').css("display", "block");
                    }
                });
            </script>
            <?php
            $hidebutton_galaxy = get_post_meta($post->ID, 'buttonstyles_galaxy', true);

            global $post;
            $id = $post->ID;

            if (get_post_meta($id, 'buttonstyles_galaxy', true) == 'button_editable_textbox') {
                if (get_option('predefined_button_caption_show_hide') == '1') {
                    ?>
                    <span><?php echo get_option('predefined_button_caption'); ?></span>
                    <?php
                }
            }
            if (is_array(get_post_meta($post->ID, 'ppcollection_galaxy', true))) {
                ?>
                    <?php if ($hidebutton_galaxy == 'radio' || $hidebutton_galaxy == 'button_editable_textbox') { ?>
                    <div class="cf_container_galaxy">
                        <?php } elseif ($hidebutton_galaxy == 'dropdown') { ?>
                        <select>
                            <?php } else { ?>
                            <div style="display:none">
                            <?php } ?>
                            <?php
                            foreach (array_filter(get_post_meta($post->ID, 'ppcollection_galaxy', true)) as $value) {

                                if ($hidebutton_galaxy == 'radio' || $hidebutton_galaxy == 'button_editable_textbox') {
                                    ?>
                                    <div class="cf_amount_button" cf_data-amount ='<?php echo fp_wpml_multi_currency($value); ?>' ><?php echo get_woocommerce_currency_symbol() . fp_wpml_multi_currency($value); ?></div>

                                <?php } elseif ($hidebutton_galaxy == 'dropdown') {
                                    ?>
                                    <option class="cf_amount_dropdown" cf_data-amount ='<?php echo fp_wpml_multi_currency($value); ?>' ><?php echo get_woocommerce_currency_symbol() . fp_wpml_multi_currency($value); ?></option>
                                <?php } else {
                                    ?>
                                    <div class="cf_amount_button" cf_data-amount ='<?php echo fp_wpml_multi_currency($value); ?>' ><?php echo get_woocommerce_currency_symbol() . fp_wpml_multi_currency($value); ?></div>
                                <?php } ?>
                            <?php } ?>
                        <?php if ($hidebutton_galaxy == 'radio' || $hidebutton_galaxy == 'button_editable_textbox') { ?>
                            </div >
                    <?php } elseif ($hidebutton_galaxy == 'dropdown') { ?>
                        </select>
                <?php } else { ?>
                    </div >
                <?php }
                ?>
                <div></div>
                <style type="text/css">
                    .cf_amount_button{
                        float:left;
                        width: 85px;
                        margin-right:10px;
                        margin-top:10px;
                        height:50px;
                        border: 1px solid #ddd;
                        background: #<?php echo get_option('cf_button_color'); ?>;
                        color:#<?php echo get_option('cf_button_text_color'); ?>;
                        text-align: center;
                        padding-top: 10px;
                        cursor: pointer;
                        <?php if (get_option('cf_button_box_shadow') == '1') { ?>
                            box-shadow: 3px 3px 2px  #888888;
                        <?php } else { ?>
                            box-shadow: none;
                <?php } ?>
                    }
                    .cf_amount_button_clicked{
                        background: #<?php echo get_option('cf_selected_button_color'); ?>;
                        color:#<?php echo get_option('cf_selected_button_text_color'); ?>;
                    }
                </style>
                <?php
            }
            $style = '140px';
            if (get_post_meta($id, 'buttonstyles_galaxy', true) == 'button_editable_textbox') {

                if (get_option('amount_you_wish_show_hide') == '1') {
                    $style = "0px;";
                    echo '<p class ="label_befor_cart"><center>' . get_option('label_for_button_line1') . '</center>' . get_option('label_for_button_line2') . '</p>';
                }
                ?>

                <?php
            }
            if (get_post_meta($id, 'buttonstyles_galaxy', true) == 'button_editable_textbox') {
                $contributionlabel = get_option('crowdfunding_payyourprice_price_tab_product');
                $recommendedvalue = get_post_meta($id, '_crowdfundinggetrecommendedprice', true);
                if ($contributionlabel != '') {
                    $colonpay = " : ";
                    $contributionlabel = $contributionlabel . $colonpay;
                }
                $woocommerce_currency_symbol = self::currency_symbol_shortcode();
                if (get_option('amount_you_wish_show_hide') == '1') {
                    ?>

                    <p class="single_pdt_funding" id="single_pdt_funding"style = 'margin-bottom:10px;float:left;'>
                        <?php
                        echo $contributionlabel . '&nbsp;' . $woocommerce_currency_symbol;
                        ?>
                        <input style='width:80px;height:47px;' type='number' min='1' step='any' class='addfundraiser<?php echo $id; ?>' name='addfundraiser<?php echo $id; ?>' value='<?php echo $recommendedvalue; ?>'/>

                    </p>
                    <br>
                    <br>

                    <?php
                }
            }
        }

        public static function crowdfunding_remove_product_pricing($price, $product) {
            global $post;
            $checkinstock = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_stock_status');
            $checkvalue = get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true);

            if ($checkvalue == 'yes') {
                if (is_product()) {
                    if (self::get_woocommerce_product_type($post->ID) == 'simple') {
                        if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                            if (!is_shop()) {
                                if ($checkinstock == 'outofstock') {
                                    ob_start();
                                    echo FP_GF_Common_Functions::common_function_for_single_product_page_progressbar($post->ID);
                                    //End of minimal style
                                    ?>

                                    <div class='singlecrowdfunding<?php echo $post->ID; ?>'></div>
                                    <?php
                                    $data = ob_get_clean();
                                    return $data;
                                }
                            }
                        }
                    } else {
                        return '';
                    }
                } else {
                    if (is_shop() || is_product_category() || is_single() || is_page() || is_front_page() || is_home()) {
                        ob_start();
                        echo FP_GF_Shortcode_Functions::common_function_for_progressbar($post->ID, 'shop');
                        return ob_get_clean();
                    }
                }
            } else {
                return $price;
            }
        }

        public static function add_contribution_field_to_pledge() {
            global $post;
            $product_id = $post->ID;
            $checkinstock = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_stock_status');
            $galaxyfunderenable = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingcheckboxvalue');
            //Get Target End selection
            $gettargetendselection = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_target_end_selection');

            //Get contributed amount
            $gettotalcontrinuted = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingtotalprice'));
            $gettotalcontrinuted_value = $gettotalcontrinuted == '' ? '0' : $gettotalcontrinuted;

            //Get target goal
            $gettargetgoal1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundinggettargetprice');
            $gettargetgoal = fp_wpml_multi_currency($gettargetgoal1);
            $gettargetgoal_value = $gettargetgoal == '' ? '0' : $gettargetgoal;


            $getrecommendedprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundinggetrecommendedprice');

            $colon_symbol = " : ";
            //Contribution labels
            $contributionlabel = get_option('crowdfunding_payyourprice_price_tab_product');
            if ($contributionlabel != '') {
                $contributioncaption = $contributionlabel;
            }


            if (self::get_woocommerce_product_type($post->ID) == 'simple') {
                if ($galaxyfunderenable == 'yes') {
                    if (!is_shop()) {
                        echo FP_GF_Common_Functions::common_function_for_single_product_page_progressbar($post->ID);
                        //End of minimal style
                        ?>
                        <?php
                        if ($checkinstock != 'outofstock') {
                            $woocommerce_currency_symbol = self::currency_symbol_shortcode();
                            $contribution_label_final = $contributioncaption . ' ' . $colon_symbol . '&nbsp;' . $woocommerce_currency_symbol;
                            $hidebutton_galaxy = get_post_meta($post->ID, 'buttonstyles_galaxy', true);
                            ?>
                            <table id="singleproductinputfieldcrowdfunding" class="variations singleproductinputfieldcrowdfunding" cellspacing="0">
                                <tbody>
                                    <?php
                                    if ($gettargetendselection != 5) {
                                        ?>
                                        <tr >
                                            <td class=""><label><?php echo $contribution_label_final; ?></label></td>
                                            <td class="value">

                                                <input style='width:80px;height:47px;' type='number' min='1' step='any' class='addfundraiser<?php echo $post->ID; ?>' name='addfundraiser<?php echo $post->ID; ?>' value='<?php echo $getrecommendedprice; ?>'/>
                                            </td>
                                        </tr>
                                        <?php
                                    } else if ($gettargetendselection == 5) {
                                        ?>
                                        <tr>
                                            <td class="label"><label><?php echo __('Quantity Contribution'); ?></label></td>
                                            <td class="value">
                                                <label>
                                                    <input style='width:80px;height:47px;margin-bottom:5px' type='number' min='1' step='1' id="addquantity<?php echo $post->ID; ?>" class='addquantity<?php echo $post->ID; ?>' name='addquantity<?php echo $post->ID; ?>'value='addquantity<?php echo $post->ID; ?>'/></p>

                                            </td>
                                        </tr>

                                    <?php } ?>
                                    <?php
                                    /* To Check Status of Campaign */
                                    $fromdate = FP_GF_Common_Functions::common_function_to_find_from_to_date($post->ID, 'from');
                                    $todate = FP_GF_Common_Functions::common_function_to_find_from_to_date($post->ID, 'to');
                                    self::check_statusof_campaign($product_id, $gettargetgoal_value, $gettotalcontrinuted_value, $gettargetendselection, $fromdate, $todate);
                                    // if (get_option('check_show_hide_funder_name', true) == '1') {
                                    ?>
                            <?php //}   ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        if ($hidebutton_galaxy != 'button_editable_textbox') {
                            ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    jQuery('div.quantity').hide();
                                    jQuery(".single_add_to_cart_button").attr("data-productid", "<?php echo $post->ID; ?>");
                                    jQuery('<?php
                            if (get_post_meta($post->ID, "_crowdfundingcheckboxvalue", true) == "yes") {

                                if (get_option("cf_check_show_hide_contributor_name") == "1") {
                                    ?> <tr><td class="contributor_name_field_value"><label for="cf_contributor_name_field_value"><?php echo get_option("cf_contributor_name_caption"); ?></label></td><td><input type="text" name="cf_contributor_name_field_value" id="cf_contributor_name_field_value" class="cf_contributor_name_field_value" value="" /><br><small><?php _e(get_option("cf_contributor_name_description"), "galaxyfunder"); ?></small></td></tr><?php
                                }
                            }
                            ?>').appendTo(".singleproductinputfieldcrowdfunding");
                            <?php if (get_option('display_select_box_crowdfunding') == 'top') { ?>
                                        jQuery('.singleproductinputfieldcrowdfunding').before("<div class = 'singlecrowdfunding<?php echo $post->ID; ?>' style = 'float:left;'></div>");
                            <?php } ?>
                            <?php if (get_option('display_select_box_crowdfunding') == 'bottom') { ?>
                                        jQuery('.singleproductinputfieldcrowdfunding').after("<div class = 'singlecrowdfunding<?php echo $post->ID; ?>' style = 'float:left;'></div>");
                            <?php } ?>
                                });
                            </script>
                            <?php
                        }
                    }
                }
            }
        }

        public static function get_woocommerce_product_type($post_id) {
            if (function_exists('wc_get_product')) {
                $product = wc_get_product($post_id);
                if ($product->is_type('simple')) {
                    return 'simple';
                }
            } else {
                $product = get_product($post_id);
                return (float) WC()->version >= (float) '3.0.0' ? $product->get_type() : $product->product_type;
            }
        }

        public static function check_percentage_is_valid($getpercentage) {
            if ($getpercentage != '') {
                if ($getpercentage > 100) {
                    $getpercentage = 100;
                } else {
                    $getpercentage = $getpercentage;
                }
            } else {
                $getpercentage = 0;
            }
            return $getpercentage;
        }

        public static function check_statusof_campaign($product_id, $gettargetgoal, $getpledeged, $gettargetendselection, $fromdate, $todate) {
            if ($gettargetendselection == '3') {
                if ($getpledeged >= $gettargetgoal) {
                    //fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                }
            } else if ($gettargetendselection == '1') {
                self::check_date_status_target_end_method($todate, $fromdate, $product_id);
            } else if ($gettargetendselection == '2') {
                if ($getpledeged >= $gettargetgoal) {
                    // fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
                } else if ($gettargetendselection == '4') {
                    self::check_date_status_target_end_method($todate, $fromdate, $product_id);
                }
            }
        }

        public static function check_date_status_target_end_method($todate, $fromdate, $product_id) {

            $local_current_time = strtotime(FP_GF_Common_Functions::date_time_with_format());
            if ((strtotime($fromdate) > $local_current_time) && ($todate > $local_current_time)) {
                $message = get_option('cf_campaign_start_message');
                $timeformat = get_option('time_format');
                $dateformat = get_option('date_format') . ' ' . $timeformat;
                $update_start_date = date($dateformat, strtotime($fromdate));
                $org_message = str_replace('{from_date}', $update_start_date, $message);
                echo $org_message;
                ?>
                <style type="text/css">
                    .single_add_to_cart_button{
                        display:none !important;
                    }
                    .show-hide{
                        display:none !important;
                    }
                    #cf_price_new_date_remain{
                        display:none !important;
                    }
                    .singleproductinputfieldcrowdfunding{
                        display:none !important;
                    }
                </style>
                <?php
                fp_gf_update_campaign_metas($product_id, '_stock_status', 'instock');
            } else {
                //fp_gf_update_campaign_metas($product_id, '_stock_status', 'outofstock');
            }
        }

        public static function currency_symbol_shortcode() {
            $currency_symbol_user_sc = get_option('cf_singleproduct_page_currency_symbol');


            $woocommerce_currency_symbol = get_woocommerce_currency_symbol();

            $replaced_text = str_replace('woocommerce_currency_symbol', $woocommerce_currency_symbol, $currency_symbol_user_sc);

            return $replaced_text;
        }

        public static function cf_hide_shop_page_crowdfunding($query) {
            if (!$query->is_main_query())
                return;
            $products = FP_GF_Common_Functions::common_function_for_get_post('');
            $crowdproductid = array();
            foreach ($products as $product) {
                $product_id = $product->ID;
                $checkproduct = FP_GF_Common_Functions::get_woocommerce_product_object($product_id);
                if ($checkproduct->is_type('simple')) {
                    $checkgalaxy = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                    if ($checkgalaxy == 'yes') {
                        $crowdproductid[] = $product_id;
                    }
                }
            }
            if (isset($query->query['post_type'])) {
                if (!is_admin() && $query->query['post_type'] == "product" && is_shop()) {
                    $query->set('post__not_in', $crowdproductid);
                }
            }
        }

        public static function cf_hide_outofstock_shop_and_category_page_crowdfunding($query) {
            if (!$query->is_main_query())
                return;
            $products = FP_GF_Common_Functions::common_function_for_get_post('');
            $crowdproductid = '';
            foreach ($products as $product) {
                $product_id = $product->ID;
                $checkproduct = FP_GF_Common_Functions::get_woocommerce_product_object($product_id);
                if ($checkproduct->is_type('simple')) {
                    $checkgalaxy = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                    $checkstock = get_post_meta($product_id, '_stock_status', true);
                    if ($checkgalaxy == 'yes') {
                        if ($checkstock == 'outofstock') {
                            $crowdproductid[] = $product_id;
                        }
                    }
                }
            }
            if (isset($query->query['post_type'])) {
                if (!is_admin() && (!$query->query['post_type'] || is_shop())) {
                    $query->set('post__not_in', $crowdproductid);
                }
            } else {
                if (!is_admin()) {
                    $query->set('post__not_in', $crowdproductid);
                }
            }
        }

    }

    FP_GF_Shop_Functions::init();
}