<?php
if (!class_exists('CFExtensionform')) {
    class CFExtensionform {

        public static function init() {
                 //shortcode for extension form
                add_shortcode('crowd_fund_extension', array(__CLASS__, 'cf_extension'));
                 //Extension Call back
                add_action('wp_ajax_nopriv_updatecontribution', array(__CLASS__, 'ajax_callback'));
                add_action('wp_ajax_updatecontribution', array(__CLASS__, 'ajax_callback'));
            }

        public static function cf_extension() {
            global $woocommerce;
        if (isset($_GET['id'])) {
            if ($_GET['id'] == 1) {
                ?>
                <script type="text/javascript">
                    location.reload();
                </script>
                <?php
            }
        }
            //Chosen related js files end
            if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                wp_enqueue_script('galaxyfunder_chosen_enqueue');
                wp_enqueue_style('galaxyfunder_chosen_style_enqueue');
            } else {
                $assets_path = str_replace(array('http:', 'https:'), '', WC()->plugin_url()) . '/assets/';
                wp_enqueue_script('select2');
                wp_enqueue_style('select2', $assets_path . 'css/select2.css');
            }
        global $woocommerce;
        $target_method = '';
        $targetvalue = '';
        $targetdescription = '';
        $test = 1;
        $date_with_colon = '';
        $target_quantity = '';
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            $product_link = get_permalink($product_id);
            $targetvalue1 = get_post_meta($product_id, '_crowdfundinggettargetprice',true);
            $targetvalue = fp_wpml_multi_currency($targetvalue1);
            $targetdescription = get_post_meta($product_id, '_crowdfundinggetdescription',true);
            $target_date = get_post_meta($product_id, '_crowdfundingtodatepicker',true);
            $targethour = get_post_meta($product_id, '_crowdfundingtohourdatepicker',true);
            $targetminutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker',true);
            $target_quantity = get_post_meta($product_id, '_crowdfundingquantity',true);
            $target_method_id = get_post_meta($product_id, '_target_end_selection',true);
            if ($target_method_id == 3) {
                $target_method = __('Target Goal', 'galaxyfunder');
            } elseif ($target_method_id == 2) {
                $target_method = __('Campaign Never Ends', 'galaxyfunder');
            } elseif ($target_method_id == 1) {
                $target_method = __('Target Date', 'galaxyfunder');
                $date_with_colon = ' : ' . $target_date . ' ' . $targethour . ' : ' . $targetminutes;
            }else{
                  $target_method = __('Target Quantity', 'galaxyfunder');
            }
        }
        ob_start();
        if (is_user_logged_in()) {
            ?>
            <style type="text/css">
                
            <?php echo get_option('cf_submission_camp_custom_css');?>
           
            </style>
            <form id="campaign_extension_form" class="campaign_extension_form" action="">
                <fieldset   style="border:1px solid black;padding:10px";>
                    <div id="campaign_options1"><p><label id="targetmethodextension "><h3></h3></label></p>
                        <p style="font-size:24px;" id="targetmethodexist"><h3><?php echo __('Target', 'galaxyfunder'); ?></h3><?php
                        echo $target_method;
                        echo $date_with_colon;
                        ?> 
                    </p>
                        <input type="hidden" id="ajax_hidden_value" value="<?php echo $product_id ?>">
                        <input type="hidden" id="hidden_target_value" value="<?php echo $targetvalue ?>">
                        <input type="hidden" id="hidden_target_quantity" value="<?php echo $target_quantity ?>">
                        <input type="hidden" id="hidden_target_description" value="<?php echo $targetdescription ?>">
                        <input type="hidden" id="hidden_target_method" value="<?php echo $target_method ?>">
                        <div id="campaign_options1"><p><label><h3><?php _e(' New Campaign End Method', 'galaxyfunder'); ?></h3></label></p>
                        <p><select name="_target_end_selection" id="_target_end_selection_extension" class="_target_end_selection">
                        <?php if($target_method_id != '5') {?>
                                    <option class="target_selection_3" value="3" ><?php _e('Target Goal', 'galaxyfunder'); ?></option>
                                    <option class="target_selection_1" value="1"><?php _e('Target Date', 'galaxyfunder'); ?></option>
                                    <option class="target_selection_2" value="2"><?php _e('Campaign Never Ends', 'galaxyfunder'); ?></option>
                        <?php } else{ ?> 
                    <option class="target_selection_4" value="5"><?php _e('Target Quantity', 'galaxyfunder'); ?></option>
                        <?php } ?>   
                        </select></p></div>
                        <?php
                        $getdate = FP_GF_Common_Functions::date_with_format();
                        $time = date("h");
                        $minutes = date("i");
                        $new_target_label = get_option('cf_submission_camp_targetprice_label_new') . '&nbsp;(' . get_woocommerce_currency_symbol(get_option('woocommerce_currency')) . ')';
                        ?>
                        <span class="cf_newcampaign_duration"><p><label><h3><?php _e('Campaign Extension Date', 'galaxyfunder'); ?></h3></label></p><p><input type="text" id="cf_campaign_duration_extension" name="crowdfunding_duration" value="" placeholder="<?php echo $getdate ?>">
                                <input type="text" id="cf_campaign_hour_duration_extension" maxlength="2" name="crowdfunding_hour_duration" value="" placeholder="<?php echo $time ?>"><?php echo ":" ?>
                                <input type="text" id="cf_campaign_minutes_duration_extension" maxlength="2" name="crowdfunding_minutes_duration" value="" placeholder="<?php echo $minutes ?>"></p>
                        </span>
                    </div>
                                <?php if($target_method_id != '5') {?>
                                <div class="cf_newcampaign_targetprice_exist"><p><label><h3><?php _e('Existing Goal Price:', 'galaxyfunder') . '(' . get_woocommerce_currency_symbol(get_option('woocommerce_currency')) . ')'; ?></h3></label></p><p><p id="target_value_exist" style="font-size:24px;"><?php echo $targetvalue ?></p>
                        <div class="cf_newcampaign_targetprice"><p><label><h3><?php echo $new_target_label; ?></h3></label></p><p><input type="n"  id="cf_campaign_target_value_exist" value="" Placeholder="<?php echo get_option("cf_submission_camp_targetprice_placeholder"); ?>"></p></div>
                       <?php }else{ ?> 
                                <div class="cf_newcampaign_targetquantity_exist"><p><label><h3><?php _e('Existing Target Quantity:', 'galaxyfunder');?></h3></label></p><p><p id="target_quantity_exist" style="font-size:24px;"><?php echo $target_quantity ;?></p>
                        <div class="cf_newcampaign_targetquantity"><p><label><h3><?php _e('New Target Quantity:', 'galaxyfunder');?></h3></label></p><p><input type="n"  id="cf_campaign_target_quantity_exist" value="" Placeholder="<?php echo get_option("cf_submission_camp_targetquantity_placeholder"); ?>"></p></div>

                <?php } ?>
                        <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    jQuery("#cf_campaign_target_value_exist").keydown(function (e) {
                                        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                                                (e.keyCode == 65 && e.ctrlKey === true) ||
                                                (e.keyCode == 67 && e.ctrlKey === true) ||
                                                (e.keyCode == 88 && e.ctrlKey === true) ||
                                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                                            return;
                                        }
                                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                            e.preventDefault();
                                        }
                                    });
                                });

                        </script>
                        <div class="cf_newcampaign_description"><p><label><h3><?php _e('Description', 'galaxyfunder'); ?></h3></label></p><p><textarea id="cf_campaign_target_description" name="cf_target_description"><?php echo $targetdescription ?></textarea> </p></div>
                        <div class="cf_campaign_extension_submit" style="margin-left:220px;
                             ">
                            <p >
                                <input id="submit_extension" type="button" data-attemptcount="1" value="<?php _e('Submit', 'galaxyfunder'); ?>" name="submit_extension">
                            </p>
                            <h3  id="div_error" style="margin-right:190px; font-size: 15px;  width: 200px;"> </h3>
                        </div>
                </fieldset>
            </form>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <style type="text/css">
                #cf_campaign_duration_extension{
                    width: 150px;
                    margin-right:5px;
                }
                #cf_campaign_hour_duration_extension,#cf_campaign_minutes_duration_extension{
                    width: 40px;
                    margin-right:5px;
                }
        </style>
        <script type="text/javascript">
                        jQuery(document).ready(function () {
                            var requestattempt = 0;
                            jQuery("#submit_extension").click(function () {
                                
                                var test = '';
                                var extensiongoal = jQuery('#cf_campaign_target_value_exist').val();
                                var extensionquantity = jQuery('#cf_campaign_target_quantity_exist').val();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                                var extensionendmethod = jQuery('#_target_end_selection_extension').val();
                                var extensiondate = jQuery('#cf_campaign_duration_extension').val();

                                    if (requestattempt == 0)
                                    {
                                        var extensionendmethod = jQuery('#_target_end_selection_extension').val();
                                        var extensiondate = jQuery('#cf_campaign_duration_extension').val();
                                        var extensionhour = jQuery('#cf_campaign_hour_duration_extension').val();
                                        var extensionminutes = jQuery('#cf_campaign_minutes_duration_extension').val();
                                        var extensiondescription = jQuery('#cf_campaign_target_description').val();
                                        var productid = jQuery('#ajax_hidden_value').val();
                                        var targetmethodexist = jQuery('#hidden_target_method').val();
                                        var target_value_exist = jQuery('#hidden_target_value').val();
                                        var attemptcount = jQuery(this).data('attemptcount');
                                        var existingquantity = jQuery('#hidden_target_quantity').val();
                                        var dataparam = ({
                                            action: 'updatecontribution',
                                            targetmethodexist: targetmethodexist,
                                            extensionendmethod: extensionendmethod,
                                            extensiondate: extensiondate,
                                            target_value_exist: target_value_exist,
                                            extensiongoal: extensiongoal,
                                            extensionquantity: extensionquantity,
                                            existingquantity: existingquantity,
                                            extensiondescription: extensiondescription,
                                            productid: productid,
                                            extensionhour: extensionhour,
                                            extensionminutes: extensionminutes,
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {


                                                    if (response == 1)
                                                    {
                                                        requestattempt++;
                                                        jQuery('#div_error').html("<?php _e("Submitted", "galaxyfunder"); ?>");
                                                    }
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                    }
                                                });
                                    }
                                    if (requestattempt > 0){
                                        jQuery('#div_error').html("<?php _e("Request Sent Already", "galaxyfunder"); ?>");
                                    }
//                                }
//                                else{
//                                    jQuery('#div_error').html("<?php _e("Please Fill All Fields", "galaxyfunder"); ?>");
//                                }
                            }
                            );
                        });
        </script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script type = "text/javascript" >
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                jQuery(document).ready(function () {
            <?php
            if (get_option('cf_show_hide_crowdfunding_type') == '2') {
                if (get_option('cf_crowdfunding_type_selection') == '2') {
                    ?>
                                                jQuery('.cf_newcampaign_title').show();
                                                jQuery('#campaign_options2').show();
                                                jQuery('.cf_product_choosing').show();
                                                jQuery('#campaign_options1').hide();
                                                jQuery('.cf_newcampaign_duration').hide();
                                                jQuery('.cf_newcampaign_targetprice').show();
                                                jQuery('.cf_newcampaign_minimumprice').hide();
                                                jQuery('.cf_newcampaign_maximumprice').hide();
                                                jQuery('.cf_newcampaign_recommendedprice').hide();
                                                jQuery('.cf_newcampaign_description').show();
                                                jQuery('#meta_inner').show();
                                                jQuery('.cf_newcampaign_featured').show();
                                                jQuery('.cf_newcampaign_billinginfo').show();
                                                jQuery('.cf_newcampaign_shippinginfo').show();
                                                jQuery('#billing_country').show();
                                                jQuery('#shipping_country').show();
                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                                jQuery('#billing_country').chosen();
                                                jQuery('#shipping_country').chosen();
                    <?php } else { ?>
                                                jQuery('#billing_country').select2();
                                                jQuery('#shipping_country').select2();
                    <?php } ?>
                                                //jQuery('.cf_newcampaign_shippinginfo').hide();
                                                jQuery('.cf_newcampaign_submit').show();
                                                jQuery('#cf_campaign_target_value').attr('readonly', true);
                <?php } if (get_option('cf_crowdfunding_type_selection') == '1') { ?>
                                                jQuery('.cf_newcampaign_title').show();
                                                jQuery('#campaign_options1').show();
                                                jQuery('.cf_product_choosing').hide();
                                                jQuery('#campaign_options2').hide();
                                                jQuery('.cf_newcampaign_duration').hide();
                                                jQuery('.cf_newcampaign_targetprice').show();
                                                jQuery('.cf_newcampaign_minimumprice').show();
                                                jQuery('.cf_newcampaign_maximumprice').show();
                                                jQuery('.cf_newcampaign_recommendedprice').show();
                                                jQuery('.cf_newcampaign_description').show();
                                                jQuery('#meta_inner').show();
                                                jQuery('.cf_newcampaign_featured').show();
                                                jQuery('.cf_newcampaign_billinginfo').hide();
                                                jQuery('.cf_newcampaign_shippinginfo').hide();
                                                jQuery('.cf_newcampaign_submit').show();
                                                jQuery('#cf_campaign_target_value').attr('readonly', false);
                    <?php
                }
            }
            ?>
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                jQuery('#cf_product_selection').chosen();
                jQuery('#crowdfunding_options').chosen();
                jQuery('._target_end_selection').chosen();
            <?php } else { ?>
                jQuery('#cf_product_selection').select2();
                jQuery('#crowdfunding_options').select2();
                jQuery('._target_end_selection').select2();
            <?php } ?>
            <?php if (get_option('cf_show_hide_crowdfunding_type') == '1') { ?>
                                //jQuery('.maindivcf').hide();
                                    jQuery('.cf_newcampaign_title').show();
                                    jQuery('#campaign_options1').show();
                                    jQuery('.cf_product_choosing').hide();
                                    jQuery('#campaign_options2').hide();
                                    jQuery('.cf_newcampaign_duration').hide();
                                    jQuery('.cf_newcampaign_targetprice').show();
                                    jQuery('.cf_newcampaign_minimumprice').show();
                                    jQuery('.cf_newcampaign_maximumprice').show();
                                    jQuery('.cf_newcampaign_recommendedprice').show();
                                    jQuery('.cf_newcampaign_description').show();
                                    jQuery('#meta_inner').show();
                                    jQuery('.cf_newcampaign_featured').show();
                                    jQuery('.cf_newcampaign_billinginfo').hide();
                                    jQuery('.cf_newcampaign_shippinginfo').hide();
                                    jQuery('.cf_newcampaign_submit').show();
                                    jQuery('#cf_campaign_target_value').attr('readonly', false);
            <?php } ?>
                jQuery('#crowdfunding_options').change(function (e) {
                    jQuery('.maindivcf').show();
                    var updatevalue = jQuery(this).val();
            
                if (updatevalue === '2') {
                    jQuery('.cf_newcampaign_title').show();
                    jQuery('#campaign_options2').show();
                    jQuery('.cf_product_choosing').show();
                    jQuery('#campaign_options1').hide();
                    jQuery('.cf_newcampaign_duration').hide();
            <?php if (get_option("cf_show_hide_target_product_purchase_frontend") == '1') { ?>
                    jQuery('.cf_newcampaign_targetprice').show();
            <?php } else { ?>
                    jQuery('.cf_newcampaign_targetprice').hide();
            <?php } ?>
                    jQuery('.cf_newcampaign_minimumprice').hide();
                    jQuery('.cf_newcampaign_maximumprice').hide();
                    jQuery('.cf_newcampaign_recommendedprice').hide();
                    jQuery('.cf_newcampaign_description').show();
                    jQuery('#meta_inner').show();
                    jQuery('.cf_newcampaign_featured').show();
                    jQuery('.cf_newcampaign_billinginfo').show();
                    jQuery('.cf_newcampaign_shippinginfo').show();
                    jQuery('#billing_country').show();
                    jQuery('#shipping_country').show();
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                jQuery('#billing_country').chosen();
                jQuery('#shipping_country').chosen();
                <?php } else { ?>
                jQuery('#billing_country').select2();
                jQuery('#shipping_country').select2();
            <?php } ?>
            //jQuery('.cf_newcampaign_shippinginfo').hide();
                jQuery('.cf_newcampaign_submit').show();
                jQuery('#cf_campaign_target_value').attr('readonly', true);
                } else {
                jQuery('.cf_newcampaign_title').show();
                jQuery('#campaign_options1').show();
                jQuery('.cf_product_choosing').hide();
                jQuery('#campaign_options2').hide();
                jQuery('.cf_newcampaign_duration').hide();
                jQuery('.cf_newcampaign_targetprice').show();
                jQuery('.cf_newcampaign_minimumprice').show();
                jQuery('.cf_newcampaign_maximumprice').show();
                jQuery('.cf_newcampaign_recommendedprice').show();
                jQuery('.cf_newcampaign_description').show();
                jQuery('#meta_inner').show();
                jQuery('.cf_newcampaign_featured').show();
                jQuery('.cf_newcampaign_billinginfo').hide();
                jQuery('.cf_newcampaign_shippinginfo').hide();
                jQuery('.cf_newcampaign_submit').show();
                jQuery('#cf_campaign_target_value').attr('readonly', false);
                }
                });
                jQuery('._target_end_selection').change(function (e) {
                var currentvalue = jQuery(this).val();
                if (currentvalue === '1') {
                jQuery('.cf_newcampaign_duration').show();
                } else {
                jQuery('.cf_newcampaign_duration').hide();
                }
                });
                jQuery('#cf_product_selection').change(function () {

                var thisvalue = 0;
                jQuery('#cf_product_selection > option:selected').each(function () {
                var value = jQuery(this).attr('data-price');
                thisvalue = parseFloat(thisvalue) + parseFloat(value);
                thisvalue = thisvalue.toFixed(2);
                });
                jQuery("#cf_campaign_target_value").val(thisvalue);
                });
                });
                jQuery(document).ready(function () {
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                ?>
                    jQuery('.cf_newcampaign_choose_category').chosen();
            <?php } else { ?>
                    jQuery('.cf_newcampaign_choose_category').select2();
            <?php } ?>
                jQuery(".cf_newcampaign_mark_contributors_anonymous").hide();
                jQuery("#cf_newcampaign_show_hide_contributors").change(function () {
                    jQuery(".cf_newcampaign_mark_contributors_anonymous").toggle();
                });
                });
                jQuery(document).ready(function () {
                    jQuery(".cf_newcampaign_social_sharing_facebook").hide();
                    jQuery(".cf_newcampaign_social_sharing_twitter").hide();
                    jQuery(".cf_newcampaign_social_sharing_google").hide();
                    jQuery("#cf_newcampaign_social_sharing").change(function () {
                        jQuery(".cf_newcampaign_social_sharing_facebook").toggle();
                        jQuery(".cf_newcampaign_social_sharing_twitter").toggle();
                        jQuery(".cf_newcampaign_social_sharing_google").toggle();
                    });
                });</script>
                <script type="text/javascript">
            <?php
            if (get_option('cf_campiagn_success_redirection_option') == '2') {
                ?>
                var campaign_success_redirect_url = "<?php echo get_option("cf_campiagn_success_redirection_url_content") ?>";
            <?php } else {
                ?>
                var campaign_success_redirect_url = '';
            <?php }
            ?>
            </script>
            <?php
            'function cf_response(responseText, statusText, xhr, $form) {
//do extra stuff after submit
//alert(statusText);
jQuery("#crowd_form")[0].reset(function() {
jQuery("#crowdfunding_options").prop("selected", function() {
return this.defaultSelected;
});
});

if(statusText=="success") {
if(campaign_success_redirect_url!="") {
window.location = campaign_success_redirect_url;
}
}
//alert(responseText);
//jQuery("#cf_response").html(responseText);
}
});
</script>';
        } else {
            $url_to_redirect = get_option("cf_submission_camp_guest_url");
            $newurl_to_redirect = esc_url_raw(add_query_arg('redirect_to', get_permalink(), $url_to_redirect));
            header('Location:' . $newurl_to_redirect);
        }

        $returncontent = ob_get_clean();
        return $returncontent;
    }

        public static function ajax_callback() {
                        $exist_goal = $_POST['target_value_exist'];
                        $exist_end_method = $_POST['targetmethodexist'];
                        $new_goal = $_POST['extensiongoal'];
                        //echo $new_goal;
                        $existingquantity = $_POST['existingquantity'];
                        $new_quantity = $_POST['extensionquantity'];
                        $new_description = $_POST['extensiondescription'];
                        $new_end_method = $_POST['extensionendmethod'];
                        //echo $new_end_method;
                        $extensiondate = $_POST['extensiondate'];
                        //echo $extensiondate;
                        $extensionhour = $_POST['extensionhour'];
                        $extensionminutes = $_POST['extensionminutes'];
                        $productid = $_POST['productid'];
                        //echo $productid;
                        $ajaxrequest_array = array(
                            "productid" => $productid,
                            "existendmethod" => $exist_end_method,
                            "newendmethod" => "$new_end_method",
                            "existgoal" => $exist_goal,
                            "newgoal" => $new_goal,
                            "newquantity" => $new_quantity,
                            "existingquantity" => $existingquantity,
                            "newdescription" => "$new_description",
                            "newdate" => "$extensiondate",
                            "newhour" => $extensionhour,
                            "newminutes" => $extensionminutes,
                        );


                        $list_of_campaign_modification = array($ajaxrequest_array);

                        $prev_array = get_option('campaign_modification_list');
                        //var_dump($prev_array);
                        if ($prev_array == '') {

                            update_option('campaign_modification_list', $list_of_campaign_modification);
                        } else {
                            $updated_array = array_merge($prev_array, $list_of_campaign_modification);
                            update_option('campaign_modification_list', $updated_array);
                            //var_dump($updated_array);
                        }
                        echo 1;
                        exit();
//        var_dump($list_of_campaign_modification);
                    }
    }
    CFExtensionform::init();
}

