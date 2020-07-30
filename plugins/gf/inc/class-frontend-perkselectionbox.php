<?php
//Perk related fuctions

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('FP_GF_Perk_Frontend')) {

    final class FP_GF_Perk_Frontend {

        public static function init() {
            //Perk selection frontend
            $cf_perk_showhide = get_option('cf_perk_table_show_hide');
            $cf_perk_showhide_table_position = get_option('cf_perk_table_position');
            if ($cf_perk_showhide == 1) {
                if ($cf_perk_showhide_table_position == '1') {
                    add_action('woocommerce_before_single_product_summary', array(__CLASS__, 'single_product_page_frontend'));
                } elseif ($cf_perk_showhide_table_position == '2') {
                    add_action('woocommerce_after_single_product', array(__CLASS__, 'single_product_page_frontend'));
                } elseif ($cf_perk_showhide_table_position == '3') {
                    add_action('woocommerce_after_single_product_summary', array(__CLASS__, 'single_product_page_frontend'));
                } else {
                    add_action('woocommerce_before_add_to_cart_button', array(__CLASS__, 'single_product_page_frontend'));
                }
            }
            //Perk selection shortcode
            add_shortcode('cf_perks_list', array(__CLASS__, 'single_product_page_frontend'));
        }

        public static function get_woocommerce_formatted_price($price) {

            if (function_exists('wc_price')) {
                return wc_price(fp_wpml_multi_currency($price));
            } else if (function_exists('woocommerce_price')) {
                return woocommerce_price(fp_wpml_multi_currency($price));
            }
        }

        public static function get_attribute_slug($varidid) {
            $get_productid = FP_GF_Common_Functions::get_woocommerce_product_object($varidid);
            $get_variations = $get_productid->get_variation_attributes();
            foreach ($get_variations as $key => $value) {
                $var[] = $key . " : " . $value;
            }
            return $var;
        }

        public static function single_product_page_frontend() {
            global $post;
            global $woocommerce;
            $objectcart = new WC_Cart();
//        $order = fp_gf_get_order_object($orderid);
            $galaxyfunder_is_enable = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_crowdfundingcheckboxvalue');
            $perk_image_size = get_option('cf_perk_url__image_type_size');
            if ($galaxyfunder_is_enable == 'yes') {
                $generate_cart_id = $objectcart->generate_cart_id($post->ID);
                $perkrule = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, 'perk');
                $previousperkclaimedlist = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post->ID, '_listofperkclaimed');

                $i = 0;
                $getperkprice = array();
                if (is_array($perkrule)) {
                    ?>
                    <style type="text/css">
                        .perkrule {
                            display:inline-table;
                            background:#ccc;
                            border-radius: 10px;
                            padding-left:10px;
                            padding-right:10px;
                            margin-bottom:10px;
                            width:100%;
                        }
                        .disableperkrule {
                            display:inline-table;
                            background:#ccc;
                            border-radius: 10px;
                            padding-left:10px;
                            padding-right:10px;
                            margin-bottom:10px;
                            width:100%;
                        }
                        .h5perkrule {
                            margin:5px 0;
                        }
                        .h6perkrule {
                            margin-top:10px;
                            margin-bottom:20px;
                            padding-bottom:10px;
                            border-bottom:1px solid #fbf9ee;
                        }
                        .perkruledescription {
                            margin-bottom:10px;
                        }
                        .perkruleclaimprize {
                            margin-bottom:14px;
                        }
                        .perkrule:hover {
                            background: #99ccff;
                            cursor:pointer;
                        }
                        .selected {
                            background:#99ccff;
                        }
                        .nodropclass {
                            display:inline-table;
                            background:#99ccff;
                            border-radius: 10px;
                            padding-left:10px;
                            padding-right:10px;
                            margin-bottom:10px;
                            width:100%;
                            cursor:no-drop;
                        }
                    </style>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                    <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                                var getlistofperksquantity = [];
                                jQuery('.perkrulequantity').val('1');
                                jQuery('.noperk').click(function () {
                                    jQuery('.addfundraiser<?php echo $post->ID; ?>').removeAttr('readonly');
                                    jQuery('.subdivquantity').show();
                                });
                                jQuery('.perkrule').click(function (event) {

                                    jQuery('.perkrule').removeClass("selected");
                                    jQuery(this).addClass('selected');
                                    var getamount = jQuery(this).attr('cf_data-amount');
                                    var getchoosedproduct = jQuery(this).attr('data-choose_products');
                                    jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                                    jQuery('.addfundraiser<?php echo $post->ID; ?>').data('perk', getamount);
                                    var productid = jQuery(this).attr('data-productid');
                                    var getname = jQuery(this).attr('data-perkname');
                        <?php if (get_post_meta($post->ID, '_target_end_selection', true) != '5') { ?>
                                        jQuery('.addfundraiser<?php echo $post->ID; ?>').val(getamount * jQuery(this).attr('data-quantity'));
                        <?php } else { ?>
                                        jQuery('.addquantity<?php echo $post->ID; ?>').val(jQuery(this).attr('data-quantity'));
                        <?php } ?>
                                    var getdataquantity = jQuery(this).attr('data-quantity');
                                    var dataiteration = jQuery(this).attr('data-iteration');
                                    jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                                    jQuery('.subdivquantity').show();
                                    if (jQuery(this).hasClass('selected')) {
                                        jQuery(this).find('.subdivquantity').hide();
                                    }

                                    var perkiteration = jQuery(this).attr('data-iteration');
                                    listiteration = perkiteration;
                                    console.log(listiteration);
                                    var mainiteration = jQuery(this).attr('data-iteration');
                                    var getmyquantity = getdataquantity;

                                    getlistofperksquantity = (mainiteration + "_" + getmyquantity);
                                    //console.log(getlistofperksquantity);
                                    if (getamount === '') {
                                        var dataparam = ({
                                            action: 'selectperkoption',
                                            session_destroy: '1',
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                        //location.reload();
                                                    }
                                                });
                                        return false;
                                    } else {
                                        var whichperk = jQuery(this).attr('id');
                                        var dataparam = ({
                                            action: 'selectperkoption',
                                            getamount: getamount,
                                            getname: getname,
                                            productid: productid,
                                            explodequantity: getdataquantity + '_' + getamount,
                                            choosedproduct: getchoosedproduct,
                                            listiteration: listiteration,
                                            getlistofperksquantity: getlistofperksquantity,
                                            session_destroy: '0',
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                        //location.reload();
                                                    }
                                                });
                                        return false;
                                    }
                                });
                    <?php } else { ?>
                                var pvalue = [];
                                var listproduct = [];
                                var productids = [];
                                var listperkname = [];
                                var prkamount = [];
                                var quantitys = [];
                                var perknamequantity = [];
                                var explodequantity = [];
                                var getlistofperksquantity = [];
                                var maindatas = [];
                                var listiteration = [];
                                var qvalue = [];
                                jQuery(document).on('click', '.perkrule', function () {
                                    //                        jQuery('.perkrule').click(function (event) {
                                    // console.log(jQuery(this).find('.perkrulequantity').val());
                                    if (jQuery(this).hasClass('selected')) {
                                        jQuery(this).removeClass('selected');
                                    } else {
                                        jQuery(this).addClass('selected');
                                        //jQuery('.noperk').removeClass('selected');
                                    }
                                    if (jQuery(this).attr('data-quantity')) {
                                        var getamount = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');
                                    }
                                    var getchoosedproduct = jQuery(this).attr('data-choose_products');
                                    if (jQuery.inArray(getchoosedproduct, listproduct) === -1) {
                                        listproduct.push(getchoosedproduct);
                                    } else {
                                        listproduct = jQuery.grep(listproduct, function (value) {
                                            return value !== getchoosedproduct;
                                        });
                                    }

                                    var getcurrentquantity = jQuery(this).attr('data-quantity');
                                    var gtamnt = jQuery(this).attr('cf_data-amount');
                                    if (jQuery.inArray(getcurrentquantity + '_' + gtamnt, explodequantity) === -1) {
                                        explodequantity.push(getcurrentquantity + '_' + gtamnt);
                                    } else {
                                        explodequantity = jQuery.grep(explodequantity, function (value) {
                                            return value !== getcurrentquantity + '_' + gtamnt;
                                        });
                                    }
                                    //  console.log(explodequantity);
                                    var getquantity = jQuery(this).find('.perkrulequantity').val();
                                    if (jQuery.inArray(getquantity, perknamequantity) === -1) {
                                        perknamequantity.push(getquantity);
                                    }
                                    var perkiteration = jQuery(this).attr('data-iteration');
                                    if (jQuery.inArray(perkiteration, listiteration) === -1) {
                                        listiteration.push(perkiteration);
                                    } else {
                                        listiteration = jQuery.grep(listiteration, function (value) {
                                            return value !== perkiteration;
                                        });
                                    }
                                    console.log(listiteration);
                                    var mainiteration = jQuery(this).attr('data-iteration');
                                    var getmyquantity = jQuery(this).attr('data-quantity');
                                    //                                    alert(getmyquantity);
                                    if (jQuery.inArray(mainiteration + "_" + getmyquantity, getlistofperksquantity) === -1) {
                                        getlistofperksquantity.push(mainiteration + "_" + getmyquantity);
                                    } else {
                                        getlistofperksquantity = jQuery.grep(getlistofperksquantity, function (value) {

                                            return value !== (mainiteration + "_" + getmyquantity);
                                        });
                                    }
                                    console.log(getlistofperksquantity);
                                    var getiteration = jQuery(this).attr('data-iteration');
                                    var indiamount = jQuery(this).attr('cf_data-amount');
                                    if (jQuery.inArray(getiteration + "_" + indiamount, prkamount) === -1) {
                                        prkamount.push(indiamount);
                                    } else {
                                        prkamount = jQuery.grep(prkamount, function (value) {
                                            return value !== (getiteration + "_" + indiamount);
                                        });
                                    }
                                    var getproductid = jQuery(this).attr('data-productid');
                                    if (jQuery.inArray(getproductid, productids) === -1) {
                                        productids.push(getproductid);
                                    } else {
                                        productids = jQuery.grep(productids, function (value) {
                                            return value !== getproductid;
                                        });
                                    }
                                    var getperkname = jQuery(this).attr('data-perkname');
                                    if (jQuery.inArray(getperkname, listperkname) === -1) {
                                        listperkname.push(getperkname);
                                    } else {
                                        listperkname = jQuery.grep(listperkname, function (value) {
                                            return value !== getperkname;
                                        });
                                    }
                                    jQuery('.single_add_to_cart_button').attr('data-perk', getamount);
                                    var productid = jQuery(this).attr('data-productid');
                                    var getname = jQuery(this).attr('data-perkname');
                                    if (jQuery(this).attr('data-quantity')) {
                                        if (jQuery(this).hasClass('selected')) {

                                        }
                                    }

                                    var elementValue = jQuery(this).attr('cf_data-amount') * jQuery(this).attr('data-quantity');


                                    var elemquantity = jQuery(this).attr('data-quantity');


                                    jQuery(this).find('.perkquantity').html(jQuery(this).attr('data-quantity'));
                                    if (jQuery(this).hasClass('selected')) {
                                        var indnames = jQuery(this).attr('data-perkname');
                                        var indamount = jQuery(this).attr('cf_data-amount');
                                        jQuery(this).find('.subdivquantity').hide();
                                    } else {
                                        jQuery(this).find('.subdivquantity').show();
                                    }
                                    //pvalue = [jQuery(this).attr('data-iteration')];
                                    if (jQuery.inArray(getiteration + "_" + elementValue, pvalue) === -1) {
                                        pvalue.push(getiteration + "_" + elementValue);
                                    } else {
                                        pvalue = jQuery.grep(pvalue, function (value) {
                                            return value !== (getiteration + "_" + elementValue);
                                        });
                                    }
                                    var total = 0;
                                    for (var i = 0; i < pvalue.length; i++) {
                                        // alert(jQuery('#perkrulequantityvalue' + i).val());

                                        total += parseFloat(pvalue[i].split('_')[1]);
                                    }


                                    if (jQuery.inArray(getiteration + "_" + elemquantity, qvalue) === -1) {
                                        qvalue.push(getiteration + "_" + elemquantity);
                                    } else {
                                        qvalue = jQuery.grep(qvalue, function (value) {
                                            return value !== (getiteration + "_" + elemquantity);
                                        });
                                    }


                                    var totalquantity = 0;
                                    for (var i = 0; i < qvalue.length; i++) {
                                        //alert(parseFloat(qvalue[i].split('_')[1]));

                                        totalquantity += parseFloat(qvalue[i].split('_')[1]);
                                    }


                                    jQuery('.addfundraiser<?php echo $post->ID; ?>').val(total);

                                    jQuery('.addquantity<?php echo $post->ID; ?>').val(totalquantity);

                                    jQuery('.single_add_to_cart_button').attr('data-perk', total);
                                    if ((total === 0) || elementValue === '') {
                                        var dataparam = ({
                                            action: 'selectperkoption',
                                            session_destroy: '1'
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                        //jQuery('.perkrule').removeClass('selected');
                                                        // jQuery('.noperk').addClass('selected');
                                                        //jQuery(this).addClass('selected');
                                                    }
                                                });
                                        return false;
                                    } else {
                                        var dataparam = ({
                                            action: 'selectperkoption',
                                            getamount: total,
                                            getname: listperkname,
                                            productid: productid,
                                            sendquantity: perknamequantity,
                                            choosedproduct: listproduct,
                                            explodequantity: explodequantity,
                                            indnames: indnames,
                                            indamount: indamount,
                                            listamount: prkamount,
                                            listiteration: listiteration,
                                            getlistofperksquantity: getlistofperksquantity,
                                            session_destroy: '0'
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                    }
                                                });
                                        return false;
                                    }
                                });
                    <?php } ?>



                            jQuery('.perkrule a').click(function (evt) {
                                evt.stopPropagation();
                            });
                            jQuery('.perkrule .subdivquantity').click(function (evt) {
                                evt.stopPropagation();
                            });

                            jQuery('.perkrulequantity').val('1');
                            jQuery('.perkrule').attr('data-quantity', '1');


                            jQuery('.cfplus').click(function () {
                                var parentselector = jQuery(this).parent().parent().attr('id');
                                var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                                var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                                var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                                if (jQuery(this).attr("data-limit") == 'limited') {
                                    var max_claim = jQuery(this).attr("data-max");
                                    if (getquantityvalue < max_claim) {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                                    }
                                    else {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                                    }
                                }
                                else {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue + 1);
                                }
                                if (getquantityvalue < 1) {
                                    jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                }
                                var newupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                jQuery(this).parent().parent().attr('data-quantity', parseInt(newupdate));
                                return false;
                            });
                            jQuery('.cfminus').click(function () {

                                var parentselector = jQuery(this).parent().parent().attr('id');
                                var getvalue = jQuery('#' + parentselector).attr('cf_data-amount');
                                var getiteration = jQuery('#' + parentselector).attr('data-iteration');
                                var getquantityvalue = parseInt(jQuery('#perkrulequantityvalue' + getiteration).val());
                                if (jQuery(this).attr("data-limit") == 'limited') {
                                    var max_claim = jQuery(this).attr("data-max");
                                    if (getquantityvalue > max_claim) {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(max_claim);
                                    }
                                    else {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                        if (getquantityvalue > 1) {
                                            jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                            var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                            jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                        } else {
                                            jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                            var minusupdate = jQuery('.perkrulequantity').val();
                                            jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                        }
                                    }
                                }
                                else {
                                    if (getquantityvalue > 1) {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(getquantityvalue - 1);
                                        var minusupdate = jQuery('#perkrulequantityvalue' + getiteration).val();
                                        jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                    } else {
                                        jQuery('#perkrulequantityvalue' + getiteration).val(1);
                                        var minusupdate = jQuery('.perkrulequantity').val();
                                        jQuery(this).parent().parent().attr('data-quantity', parseInt(minusupdate));
                                    }
                                }
                                return false;
                            });
                        });</script>

                    <?php
                    if (get_post_meta($post->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {

                        ob_start();
                        ?>
                        <div id="informationperk"></div>
                        <h3><?php echo get_option('cf_perk_head_label'); ?></h3>
                        <?php if (get_option('cf_perk_selection_type') == '1') { ?>
                            <div class="perkrule noperk" id="perk_maincontainer" data-productid="<?php echo $post->ID; ?>" cf_data-amount="">
                                <?php echo get_option('cf_no_perk_label'); ?>
                            </div>
                        <?php } ?>
                        <?php
                        foreach ($perkrule as $i => $perk) {
                            $newperkname = str_replace('', '_', $perk['name']);
                            $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                            if ($newcounter == '') {
                                $newcounter = 0;
                            } else {
                                $newcounter = $newcounter;
                            }
                            $newcounterclaim = $newcounter;
                            $targetclaim = $perk['claimcount'];
                            $max = '';
                            if ($targetclaim > 0 && $targetclaim > 0) {
                                $max = $targetclaim - $newcounterclaim;
                            }

                            $is_unlimited = $perk['limitperk'];
                            if ($is_unlimited == 'cf_limited') {
                                if (($targetclaim > $newcounterclaim) && ($targetclaim != '')) {
                                    ?>
                                    <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo fp_wpml_multi_currency($perk['amount']); ?>" cf_data-choosed-product="<?php echo $perk['choose_products'] ?>" data-quantity="1" >
                                        <?php
                                        if (get_option('cf_perk_quantity_selection') == '1') {
                                            if (get_option('cf_perk_quantity_display_selection') == '1') {
                                                ?>
                                                <div class="subdivquantity">
                                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >-</button>
                                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                                </div>
                                                <?php
                                            } elseif (get_option('cf_perk_quantity_display_selection') == '2') {
                                                ?>
                                                <div class="subdivquantity" style="float:right;">
                                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >-</button>
                                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                                </div>
                                                <?php
                                            }
                                        }
                                        if (get_option('cf_perk_quantity_selection') == '1') {
                                            ?>
                                            <h5 class="h5perkrule">
                                                <span class="perkquantity"></span> * <?php echo self::get_woocommerce_formatted_price($perk['amount']); ?>
                                            </h5>
                                        <?php } else { ?>
                                            <h5 class="h5perkrule">
                                                <?php echo self::get_woocommerce_formatted_price($perk['amount']); ?>
                                            </h5>
                                        <?php } ?>
                                        <h6 class="h6perkrule">

                                            <?php
                                            if (isset($perk['choose_products']) && $perk['choose_products'] != '') {
                                                $product = FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products']);
                                                if ($product->is_type('simple')) {

                                                    if (get_option('cf_perk_url_type') == '1') {
                                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                                    } else {
                                                        if (get_option('cf_perk_url__image_type') == '2') {

                                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                        } elseif (get_option('cf_perk_url__image_type') == '1') {
                                                            echo '<a class="linkclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . '</div></a>';
                                                        } else {

                                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                            echo '<a class="linkclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . '</div></a>';
                                                        }
                                                    }
                                                } else {
                                                    $return_attribute_slug = self::get_attribute_slug($perk['choose_products']);
                                                    $link = implode("&", $return_attribute_slug);
                                                    $link1 = str_replace('attribute_', '', $link);
                                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink(FP_GF_Common_Functions::common_function_to_get_parent_id($product)));
                                                    if (get_option('cf_perk_url_type') == '1') {
                                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                                    } else {
                                                        if (get_option('cf_perk_url__image_type') == '2') {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                        } elseif (get_option('cf_perk_url__image_type') == '1') {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                        } else {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo $perk['name'];
                                            }
                                            ?>
                                        </h6>
                                        <p class="form-field perkruledescription">
                                            <?php echo $perk['description']; ?>
                                        </p>
                                        <p class="form-field perkruleclaimprize">
                                            <strong><?php
                                                $newperkname = str_replace('', '_', $perk['name']);
                                                $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);


                                                if ($newcounter == '') {
                                                    $newcounter = 0;
                                                } else {
                                                    $newcounter = $newcounter;
                                                }
                                                echo $newcounter;
                                                ?> <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                                        </p>
                                        <p class="form-field perkruledelivery">
                                            <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                        </p>
                                        <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                            <?php
                                            if (get_option('cf_perk_quantity_display_selection') == '3') {
                                                ?>
                                                <div class="subdivquantity" style="float:left;">
                                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >-</button>
                                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                                </div>

                                                <?php
                                            } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                                ?>
                                                <div class="subdivquantity" style="float:right;">
                                                    <button class="button cfminus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >-</button>
                                                    <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                    <button class="button cfplus perkruleaddition<?php echo $i; ?>"  data-limit="limited" data-max="<?php echo $max ?>" >+</button>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="disableperkrule" id="perk_maincontainer<?php echo $i; ?>" data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo fp_wpml_multi_currency($perk['amount']); ?>">
                                        <h5 class="h5perkrule">
                                            <?php echo self::get_woocommerce_formatted_price($perk['amount']); ?>
                                        </h5>
                                        <h6 class="h6perkrule">

                                            <?php echo $perk['name']; ?>
                                        </h6>
                                        <p class="form-field perkruledescription">
                                            <?php echo $perk['description']; ?>
                                        </p>
                                        <p class="form-field perkruleclaimprize">
                                            <strong><?php
                                                $newperkname = str_replace('', '_', $perk['name']);
                                                $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                                if ($newcounter == '') {
                                                    $newcounter = 0;
                                                } else {
                                                    $newcounter = $newcounter;
                                                }
                                                echo $newcounter;
                                                ?>

                                                claimed out of <?php echo $perk['claimcount']; ?> </strong>
                                        </p>
                                        <p class="form-field perkruledelivery">
                                            <label>Estimated Delivery:</label> <em><?php echo $perk['deliverydate']; ?></em>
                                        </p>
                                    </div>
                                    <?php
                                }
                            } elseif ($is_unlimited == 'cf_unlimited') {
                                ?>

                                <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo fp_wpml_multi_currency($perk['amount']); ?>" cf_data-choosed-product="<?php echo $perk['choose_products'] ? $perk['choose_products'] : '' ?>" data-quantity="1" >
                                    <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                        <?php if (get_option('cf_perk_quantity_display_selection') == '1') { ?>
                                            <div class="subdivquantity">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                            </div>
                                            <?php
                                        }
                                        if (get_option('cf_perk_quantity_display_selection') == '2') {
                                            ?>
                                            <div class="subdivquantity" style="float:right;">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                        <h5 class="h5perkrule">
                                            <span class="perkquantity"></span> * <?php echo self::get_woocommerce_formatted_price($perk['amount']); ?>
                                        </h5>
                                    <?php } else { ?>
                                        <h5 class="h6perkrule">
                                            <?php echo self::get_woocommerce_formatted_price($perk['amount']); ?>
                                        </h5>
                                    <?php } ?>
                                    <h6 class="h6perkrule">
                                        <?php
                                        if (isset($perk['choose_products'])) {
                                            if ($perk['choose_products'] != '') {
                                                $product = FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products']);
                                                if ($product->is_type('simple')) {
                                                    if (get_option('cf_perk_url_type') == '1') {
                                                        echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank">' . $perk['name'] . '</a>';
                                                    } else {
                                                        if (get_option('cf_perk_url__image_type') == '2') {
                                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                        } elseif (get_option('cf_perk_url__image_type') == '1') {
                                                            echo '<a class="linkclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . '</div></a>';
                                                        } else {
                                                            echo '<a class="linkclass" href=' . get_permalink($perk['choose_products']) . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                            echo '<a class="linkclass" href="' . get_permalink($perk['choose_products']) . '" target="_blank"><div style="width:100px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . '</div></a>';
                                                        }
                                                    }
                                                } else {
                                                    $return_attribute_slug = self::get_attribute_slug($perk['choose_products']);
                                                    $link = implode("&", $return_attribute_slug);
                                                    $link1 = str_replace('attribute_', '', $link);
                                                    $url = add_query_arg('variation_id', $perk['choose_products'], get_permalink(FP_GF_Common_Functions::common_function_to_get_parent_id($product)));
//                                            echo $link;
                                                    if (get_option('cf_perk_url_type') == '1') {
                                                        echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . $perk['name'] . '</a>';
                                                    } else {
                                                        if (get_option('cf_perk_url__image_type') == '2') {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                        } elseif (get_option('cf_perk_url__image_type') == '1') {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                        } else {
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank"><div style="width:' . $perk_image_size . 'px">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_image() . '</div></a>';
                                                            echo '<a class="linkclass" href=' . $url . "&" . $link . ' target="_blank">' . FP_GF_Common_Functions::get_woocommerce_product_object($perk['choose_products'])->get_title() . " " . $link1 . '</a>';
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            echo $perk['name'];
                                        }
                                        ?>
                                    </h6>

                                    <p class="form-field perkruledescription">
                                        <?php echo $perk['description']; ?>
                                    </p>
                                    <p class="form-field perkruleclaimprize">
                                        <strong><?php
                                            $newperkname = str_replace('', '_', $perk['name']);
                                            $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                            if ($newcounter == '') {
                                                $newcounter = 0;
                                            } else {
                                                $newcounter = $newcounter;
                                            }
                                            echo $newcounter;
                                            ?>  <?php echo get_option('cf_out_of_claimed_unlimited_label'); ?>   </strong>
                                    </p>
                                    <p class="form-field perkruledelivery">
                                        <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                    </p>
                                    <?php if (get_option('cf_perk_quantity_selection') == '1') { ?>
                                        <?php
                                        if (get_option('cf_perk_quantity_display_selection') == '3') {
                                            ?>
                                            <div class="subdivquantity" style="float:left;">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                            </div>

                                            <?php
                                        } if (get_option('cf_perk_quantity_display_selection') == '4') {
                                            ?>
                                            <div class="subdivquantity" style="float:right;">
                                                <button class="button cfminus perkruleaddition<?php echo $i; ?>">-</button>
                                                <input type="text" size="4" name="perkrulequantityvalue<?php echo $i; ?>" style="text-align:center;" class="perkrulequantity" id="perkrulequantityvalue<?php echo $i; ?>"/>
                                                <button class="button cfplus perkruleaddition<?php echo $i; ?>" >+</button>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="perkrule" id="perk_maincontainer<?php echo $i; ?>"  data-iteration ="<?php echo $i; ?>"  data-productid="<?php echo $post->ID; ?>" data-perkname="<?php echo $perk['name']; ?>" cf_data-amount="<?php echo fp_wpml_multi_currency($perk['amount']); ?>">
                                    <h5 class="h5perkrule">
                                        <?php echo FP_GF_Common_Functions::format_price_in_proper_order($perk['amount']); ?>
                                    </h5>
                                    <p class="form-field perkruledescription">
                                        <?php echo $perk['description']; ?>
                                    </p>
                                    <p class="form-field perkruleclaimprize">
                                        <strong><?php
                                            $newperkname = str_replace('', '_', $perk['name']);
                                            $newcounter = get_post_meta($post->ID, $newperkname . $perk['amount'] . 'update_perk_claim', true);
                                            if ($newcounter == '') {
                                                $newcounter = 0;
                                            } else {
                                                $newcounter = $newcounter;
                                            }
                                            echo $newcounter;
                                            ?>  <?php echo get_option('cf_out_of_claimed_label'); ?>  <?php echo $perk['claimcount']; ?> </strong>
                                    </p>
                                    <p class="form-field perkruledelivery">
                                        <label><?php echo get_option('cf_estimated_delivery_label'); ?></label> <em><?php echo $perk['deliverydate']; ?></em>
                                    </p>

                                </div>
                                <?php
                            }
                        }
                        echo ob_get_clean();
                    }
                }
            }
        }

    }

    FP_GF_Perk_Frontend::init();
}