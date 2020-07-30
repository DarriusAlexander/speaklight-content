<?php
/*
 * FrontEnd Form Related Functionality
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Product_Level_Entries')) {

    final class FP_GF_Product_Level_Entries {

        public static function init() {
            //Adding crowfunding fields in product page
            add_action('woocommerce_product_options_pricing', array(__CLASS__, 'FP_CF_crowdfunding_add_custom_field_admin_settings'));
            //Saving crowfunding datas in metas
            add_action('woocommerce_process_product_meta', array(__CLASS__, 'FP_CF_crowdfunding_save_product_post'));
            //product level head script
            add_action('admin_head', array(__CLASS__, 'FP_CF_crowdfunding_head_script'));
        }

        public static function FP_CF_crowdfunding_add_custom_field_admin_settings() {
            global $post;
            global $woocommerce;
            echo '<div class="options_group hide_if_grouped">';
            echo '</div>';
            echo '<div class="options_group hide_if_grouped">';
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundingcheckboxvalue',
                        'wrapper_class' => '',
                        //                    'value'=>'no',
                        'label' => __('Enable Galaxy Funder', 'galaxyfunder'),
                        'description' => __('Enable Galaxy Funder if you want users to contribute for your job by donating money', 'galaxyfunder')
                    )
            );
            if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                woocommerce_wp_select(array(
                    'id' => '_crowdfunding_options',
                    'class' => 'crowdfunding_options',
                    'label' => __('CrowdFunding Type', 'galaxyfunder'),
                    'options' => array(
                        '' => '',
                        '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                        '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                    )
                        )
                );
            } else {
                woocommerce_wp_select(array(
                    'id' => '_crowdfunding_options',
                    'class' => 'crowdfunding_options',
                    'label' => __('CrowdFunding Type', 'galaxyfunder'),
                    'options' => array(
                        '1' => __('Fundraising by CrowdFunding', 'galaxyfunder'),
                        '2' => __('Product Purchase by CrowdFunding', 'galaxyfunder'),
                    )
                        )
                );
            }
            woocommerce_wp_text_input(
                    array(
                        'id' => '_crowdfundinggetminimumprice',
                        'class' => '_crowdfundinggetminimumprice',
                        'name' => '_crowdfundinggetminimumprice',
                        'label' => __('Minimum Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                        'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundinghideminimum',
                        'wrapper_class' => '',
                        'label' => __('Hide Minimum Price', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_crowdfundinggetrecommendedprice',
                        'name' => '_crowdfundinggetrecommendedprice',
                        'label' => __('Recommended Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                        'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_crowdfundinggetmaximumprice',
                        'name' => '_crowdfundinggetmaximumprice',
                        'label' => __('Maximum Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                        'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundinghidemaximum',
                        'wrapper_class' => '',
                        'label' => __('Hide Maximum Price', 'galaxyfunder'),
                    )
            );
            echo FP_GF_Common_Functions::product_search_common_function();

            woocommerce_wp_text_input(
                    array(
                        'id' => '_crowdfundinggettargetprice',
                        'name' => '_crowdfundinggettargetprice',
                        'label' => __('Target Price (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                        'description' => __('Please Enter Only Numbers', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_use_selected_product_image',
                        'wrapper_class' => '',
                        'label' => __('Use Selected Product Featured Image', 'galaxyfunder'),
                        'description' => __('Check this Option to use the Featured Image of Selected Product (Works only When one Product is Chosen.)', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundinghidetarget',
                        'wrapper_class' => '',
                        'label' => __('Hide Target Price', 'galaxyfunder'),
                    )
            );
            $product_id = $post->ID;
            $var = get_post_meta($product_id, '_target_end_selection', true);
            ?>
            <div> <p class ="form-field target_end_selection_select">
                    <label ><?php echo __('Campaign End Method', 'galaxyfunder'); ?></label>
                    <select id="_target_end_selection" class ="target_end_selection"  name="_target_end_selection">
                        <option <?php if ($var == 3) { ?>selected="" <?php } ?> value ="3"> <?php echo __('Target Goal', 'galaxyfunder'); ?> </option>
                        <option <?php if ($var == 1) { ?>selected="" <?php } ?>value ="1"> <?php echo __('Target Date', 'galaxyfunder'); ?> </option>
                        <option <?php if ($var == 4) { ?>selected="" <?php } ?>value ="4"> <?php echo __('Target Goal & Date', 'galaxyfunder'); ?> </option>
                        <option <?php if ($var == 2) { ?>selected="" <?php } ?>value ="2"> <?php echo __('Campaign Never Ends', 'galaxyfunder'); ?> </option>

                        <?php if ($var == '5') { ?>
                            <option <?php if ($var == 5) { ?>selected="" <?php } ?>value ="5"> <?php echo __('Target Quantity', 'galaxyfunder'); ?> </option>
                        <?php } ?>

                    </select> </p>
            </div>

            <div><p class="form-field _crowdfundingfromdatepicker_field">
                    <label for="_crowdfundingfromdatepicker"><?php echo __('From Date', 'galaxyfunder') ?></label>
                    <input id="_crowdfundingfromdatepicker" class="_crowdfundingfromdatepicker_class" type="text" placeholder='From date'value="<?php echo get_post_meta($post->ID, '_crowdfundingfromdatepicker', true); ?>" name="_crowdfundingfromdatepicker" style="">
                    <input id="_crowdfundingfromhourdatepicker" type="text" size='0' maxlength='2'placeholder='HH' value="<?php echo get_post_meta($post->ID, '_crowdfundingfromhourdatepicker', true); ?>" name="_crowdfundingfromhourdatepicker" style="">
                    <input id="_crowdfundingfromminutesdatepicker" type="text" size='0' maxlength='2'placeholder='MM' value="<?php echo get_post_meta($post->ID, '_crowdfundingfromminutesdatepicker', true); ?>" name="_crowdfundingfromminutesdatepicker" style="">
                </p></div>
            <?php
            $user_req_days = get_post_meta($post->ID, '_crowdfundingtodateindays', true);

            $edited_days = get_post_meta($post->ID, '_crowdfundinguserenddays_text', true);

            $date_count_display = $user_req_days;
            if ($user_req_days != $edited_days && $edited_days != '') {
                $date_count_display = $edited_days;
            }
            $crowdfundingtodatepicker = get_post_meta($post->ID, '_crowdfundingtodatepicker', true);
            $post_id = '';
            if (isset($_GET['post'])) {
                $post_id = $_GET['post'];
            }

            if ($crowdfundingtodatepicker == '' && $post_id != '') {
                ?>
                <div><p class="form-field _crowdfundingtodatepicker_field">
                        <label for="_crowdfundinguserenddays"><?php echo __('User Requested Campaign Duration in days', 'galaxyfunder') ?></label>
                        <input id="_crowdfundinguserenddays_text" type="text"  value="<?php echo $date_count_display; ?>" name="_crowdfundinguserenddays_text" style="">

                    </p></div>
            <?php } ?>


            <div><p class="form-field _crowdfundingtodatepicker_field">
                    <label for="_crowdfundingtodatepicker"><?php echo __('To Date', 'galaxyfunder') ?></label>
                    <input id="_crowdfundingtodatepicker" class="_crowdfundingtodatepicker_class" type="text" placeholder='To date' value="<?php echo get_post_meta($post->ID, '_crowdfundingtodatepicker', true); ?>" name="_crowdfundingtodatepicker" style="">
                    <input id="_crowdfundingtohourdatepicker" type="text"  placeholder='HH'value="<?php echo get_post_meta($post->ID, '_crowdfundingtohourdatepicker', true); ?>" size='1' maxlength='2' max='60' min='0' name="_crowdfundingtohourdatepicker" style="">
                    <input id="_crowdfundingtominutesdatepicker" type="text" placeholder='MM' value="<?php echo get_post_meta($post->ID, '_crowdfundingtominutesdatepicker', true); ?>" size='1' max=60 min='0' maxlength='2'name="_crowdfundingtominutesdatepicker" style="">

                </p></div>
            <div id="_crowdfundingquantity_field"><p class="form-field _crowdfundingquantity_field" >  
                    <label> <?php echo __('Target Quantity ', 'galaxyfunder'); ?></label>
                    <input  type='number' min='1' step='1' class='_crowdfundingquantity' id='_crowdfundingquantity' name='_crowdfundingquantity' value='<?php echo get_post_meta($post->ID, '_crowdfundingquantity', true); ?>'>
                </p></div>
            <?php
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundingsocialsharing',
                        'wrapper_class' => '',
                        'label' => __('Enable Social Promotion', 'galaxyfunder'),
                        'description' => __('Enable Social Promotion for this Campaign', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundingsocialsharing_facebook',
                        'wrapper_class' => '',
                        'label' => __('Enable Social Promotion Through Facebook', 'galaxyfunder'),
                        'description' => __('Enable Social Promotion for this Campaign Through Facebook', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundingsocialsharing_twitter',
                        'wrapper_class' => '',
                        'label' => __('Enable Social Promotion Through Twitter', 'galaxyfunder'),
                        'description' => __('Enable Social Promotion for this Campaign Through Twitter', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfundingsocialsharing_google',
                        'wrapper_class' => '',
                        'label' => __('Enable Social Promotion Through Google+', 'galaxyfunder'),
                        'description' => __('Enable Social Promotion for this Campaign Through Google+', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfunding_showhide_contributor',
                        'wrapper_class' => '',
                        'label' => __('Show Contributor Table', 'galaxyfunder'),
                        'description' => __('Enable this option to display the contributors for this Campaign', 'galaxyfunder')
                    )
            );
            woocommerce_wp_checkbox(
                    array(
                        'id' => '_crowdfunding_contributor_anonymous',
                        'wrapper_class' => '',
                        'label' => __('Mark Contributors as Anonymous', 'galaxyfunder'),
                        'description' => __('Enable this option to display the contributors Name as Anonymous for this Campaign', 'galaxyfunder')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => 'cf_campaigner_paypal_id',
                        'wrapper_class' => '',
                        'label' => __('Campaigner PayPal ID', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_select(
                    array(
                        'id' => 'buttonstyles_galaxy',
                        'wrapper_class' => '',
                        'label' => __('Select Style', 'galaxyfunder'),
                        'description' => __('Select style for displaying price', 'galaxyfunder'),
                        'options' => array(
                            'default' => __('Editable Textbox', 'galaxyfunder'),
                            'default_non_editable' => __('Non-Editable Textbox', 'galaxyfunder'),
                            'radio' => __('Predefined Buttons', 'galaxyfunder'),
                            'dropdown' => __('Predefined ListBox', 'galaxyfunder'),
                            'button_editable_textbox' => __('Predefined Button & Editable TextBox', 'galaxyfunder'),
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount1_galaxy',
                        'name' => '_amount1_galaxy',
                        'label' => __('Amount 1 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount2_galaxy',
                        'name' => '_amount2_galaxy',
                        'label' => __('Amount 2 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount3_galaxy',
                        'name' => '_amount3_galaxy',
                        'label' => __('Amount 3 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount4_galaxy',
                        'name' => '_amount4_galaxy',
                        'label' => __('Amount 4 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount5_galaxy',
                        'name' => '_amount5_galaxy',
                        'label' => __('Amount 5 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_amount6_galaxy',
                        'name' => '_amount6_galaxy',
                        'label' => __('Amount 6 (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_recomended_amount_galaxy',
                        'name' => '_recomended_amount_galaxy',
                        'label' => __('Recomended Amount (' . get_woocommerce_currency_symbol() . ')', 'galaxyfunder'),
                    )
            );
            echo '</div>';
            ?>
            <style type="text/css">
                #_crowdfundingfromdatepicker,#_crowdfundingtodatepicker{
                    width: 100px;
                    margin-right:5px;
                }
                #_crowdfundingfromhourdatepicker,#_crowdfundingtohourdatepicker{
                    width: 40px;
                    margin-right:5px;
                }
                #_crowdfundingfromminutesdatepicker,#_crowdfundingtominutesdatepicker{
                    width: 40px;
                    margin-right:5px;
                }
            </style>
            <?php
            echo self::product_level_js_function();
        }

        public static function product_level_js_function() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var style_galaxy = document.getElementById("buttonstyles_galaxy");
                    var style_selected_galaxy = style_galaxy.options[style_galaxy.selectedIndex].value;
                    if (style_selected_galaxy == 'radio' || style_selected_galaxy == 'dropdown' || style_selected_galaxy == 'button_editable_textbox') {
                        jQuery("#_amount1_galaxy").parent().css("display", "block");
                        jQuery("#_amount2_galaxy").parent().css("display", "block");
                        jQuery("#_amount3_galaxy").parent().css("display", "block");
                        jQuery("#_amount4_galaxy").parent().css("display", "block");
                        jQuery("#_amount5_galaxy").parent().css("display", "block");
                        jQuery("#_amount6_galaxy").parent().css("display", "block");
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                    } else if (style_selected_galaxy == 'default_non_editable') {

                        jQuery("#_amount1_galaxy").parent().css("display", "none");
                        jQuery("#_amount2_galaxy").parent().css("display", "none");
                        jQuery("#_amount3_galaxy").parent().css("display", "none");
                        jQuery("#_amount4_galaxy").parent().css("display", "none");
                        jQuery("#_amount5_galaxy").parent().css("display", "none");
                        jQuery("#_amount6_galaxy").parent().css("display", "none");
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "block");
                    } else {
                        jQuery("#_amount1_galaxy").parent().css("display", "none");
                        jQuery("#_amount2_galaxy").parent().css("display", "none");
                        jQuery("#_amount3_galaxy").parent().css("display", "none");
                        jQuery("#_amount4_galaxy").parent().css("display", "none");
                        jQuery("#_amount5_galaxy").parent().css("display", "none");
                        jQuery("#_amount6_galaxy").parent().css("display", "none");
                        jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                    }
                    jQuery('#buttonstyles_galaxy').change(function () {
                        var style_galaxy = document.getElementById("buttonstyles_galaxy");
                        var style_selected_galaxy = style_galaxy.options[style_galaxy.selectedIndex].value;
                        if (style_selected_galaxy == 'radio' || style_selected_galaxy == 'dropdown' || style_selected_galaxy == 'button_editable_textbox') {
                            jQuery("#_amount1_galaxy").parent().css("display", "block");
                            jQuery("#_amount2_galaxy").parent().css("display", "block");
                            jQuery("#_amount3_galaxy").parent().css("display", "block");
                            jQuery("#_amount4_galaxy").parent().css("display", "block");
                            jQuery("#_amount5_galaxy").parent().css("display", "block");
                            jQuery("#_amount6_galaxy").parent().css("display", "block");
                            jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                        } else if (style_selected_galaxy == 'default_non_editable') {
                            jQuery("#_recomended_amount_galaxy").parent().css("display", "block");
                            jQuery("#_amount1_galaxy").parent().css("display", "none");
                            jQuery("#_amount2_galaxy").parent().css("display", "none");
                            jQuery("#_amount3_galaxy").parent().css("display", "none");
                            jQuery("#_amount4_galaxy").parent().css("display", "none");
                            jQuery("#_amount5_galaxy").parent().css("display", "none");
                            jQuery("#_amount6_galaxy").parent().css("display", "none");
                        } else {
                            jQuery("#_amount1_galaxy").parent().css("display", "none");
                            jQuery("#_amount2_galaxy").parent().css("display", "none");
                            jQuery("#_amount3_galaxy").parent().css("display", "none");
                            jQuery("#_amount4_galaxy").parent().css("display", "none");
                            jQuery("#_amount5_galaxy").parent().css("display", "none");
                            jQuery("#_amount6_galaxy").parent().css("display", "none");
                            jQuery("#_recomended_amount_galaxy").parent().css("display", "none");
                        }

                    });
                    var is_checked = jQuery("#_crowdfundingsocialsharing:checked").val() ? true : false;
                    if (is_checked == false) {
                        //                    alert(is_checked);
                        jQuery('#_crowdfundingsocialsharing_facebook').parent().hide();
                        jQuery('#_crowdfundingsocialsharing_twitter').parent().hide();
                        jQuery('#_crowdfundingsocialsharing_google').parent().hide();
                    } else {
                        jQuery('#_crowdfundingsocialsharing_facebook').parent().show();
                        jQuery('#_crowdfundingsocialsharing_twitter').parent().show();
                        jQuery('#_crowdfundingsocialsharing_google').parent().show();
                    }
                    jQuery("#_crowdfundingsocialsharing").on('click', function (e) {
                        var is_checked = jQuery("#_crowdfundingsocialsharing:checked").val() ? true : false;
                        if (is_checked == false) {
                            //                    alert(is_checked);
                            jQuery('#_crowdfundingsocialsharing_facebook').parent().hide();
                            jQuery('#_crowdfundingsocialsharing_twitter').parent().hide();
                            jQuery('#_crowdfundingsocialsharing_google').parent().hide();
                        } else {
                            jQuery('#_crowdfundingsocialsharing_facebook').parent().show();
                            jQuery('#_crowdfundingsocialsharing_twitter').parent().show();
                            jQuery('#_crowdfundingsocialsharing_google').parent().show();
                        }
                    });
                });

                jQuery(document).ready(function () {

                    if (jQuery('#_crowdfunding_options').val() == '2') {
                        jQuery('#_target_end_selection option[value=5]').remove();

                        jQuery('#_crowdfundingquantity').parent().hide();

                    } else {

                        jQuery('#_target_end_selection option:last').prev().after('<option value="5" ><?php echo __('Target Quantity', 'galaxyfunder'); ?></option>');
                    }

                    jQuery('#_crowdfunding_options').change(function () {

                        var selectvalue = jQuery(this).val();

                        if ((selectvalue == '2')) {

                            jQuery('#_target_end_selection option[value=5]').remove();
                            if (jQuery('#_crowdfundingquantity').is(":visible")) {
                                jQuery('#_crowdfundingquantity').parent().hide();

                            }
                        } else {

                            jQuery('#_target_end_selection').append('<option value="5"><?php echo __('Target Quantity', 'galaxyfunder'); ?></option>');

                        }
                    });



                    jQuery('#_target_end_selection').change(function () {

                        if (jQuery('#_target_end_selection').val() != '5') {
                            jQuery('._crowdfundinggetminimumprice_field').show();
                            jQuery('._crowdfundinghideminimum_field').show();
                            jQuery('._crowdfundinggetrecommendedprice_field ').show();
                            jQuery('._crowdfundinggetmaximumprice_field').show();
                            jQuery('._crowdfundinghidemaximum_field').show();
                            jQuery('._crowdfundinggettargetprice_field').show();
                            jQuery('._crowdfundinghidetarget_field').show();
                            jQuery('.buttonstyles_galaxy_field').show();
                            //                               jQuery('._cf_selection_field').show();

                        } else {
                            jQuery('._crowdfundinggetminimumprice_field').hide();
                            jQuery('._crowdfundinghideminimum_field').hide();
                            jQuery('._crowdfundinggetrecommendedprice_field ').hide();
                            jQuery('._crowdfundinggetmaximumprice_field').hide();
                            jQuery('._crowdfundinghidemaximum_field').hide();
                            jQuery('._crowdfundinggettargetprice_field').hide();
                            jQuery('._crowdfundinghidetarget_field').hide();
                            jQuery('.buttonstyles_galaxy_field').hide();
                            jQuery('._cf_selection_field').hide();
                        }

                    });

                    if (jQuery('#_target_end_selection').val() != '5') {
                        jQuery('#_crowdfundinggetminimumprice').parent().show();
                        jQuery('._crowdfundinghideminimum_field').show();
                        jQuery('._crowdfundinggetrecommendedprice_field ').show();
                        jQuery('._crowdfundinggetmaximumprice_field').show();
                        jQuery('._crowdfundinghidemaximum_field').show();
                        jQuery('._crowdfundinggettargetprice_field').show();
                        jQuery('._crowdfundinghidetarget_field').show();
                        jQuery('.buttonstyles_galaxy_field').show();
                        //                                   jQuery('._cf_selection_field').show();
                    } else {
                        jQuery('#_crowdfundinggetminimumprice').parent().hide();
                        jQuery('._crowdfundinghideminimum_field').hide();
                        jQuery('._crowdfundinggetrecommendedprice_field').hide();
                        jQuery('._crowdfundinggetmaximumprice_field').hide();
                        jQuery('._crowdfundinghidemaximum_field').hide();
                        jQuery('._crowdfundinggettargetprice_field').hide();
                        jQuery('._crowdfundinghidetarget_field').hide();
                        jQuery('.buttonstyles_galaxy_field').hide();
                        jQuery('._cf_selection_field').hide();
                    }


                    jQuery('#_target_end_selection').change(function () {
                        if (jQuery('#_target_end_selection').val() == '5') {
                            jQuery('#_crowdfundingquantity_field').show();
                        } else {
                            jQuery('#_crowdfundingquantity_field').hide();
                        }
                    });
                    if (jQuery('#_target_end_selection').val() == '5') {
                        jQuery('#_crowdfundingquantity_field').show();
                    } else {
                        jQuery('#_crowdfundingquantity_field').hide();
                    }

                });

                jQuery(document).ready(function () {
                    jQuery('#product-type').change(function () {
                        var selectvalue = jQuery(this).val();
                        if ((selectvalue === 'simple') || (selectvalue === 'subscription')) {
                            jQuery('._crowdfundingcheckboxvalue_field').css('display', 'block');
                            jQuery('._crowdfundinggetminimumprice_field').css('display', 'block');
                            jQuery('._crowdfundinghideminimum_field').css('display', 'block');
                            jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'block');
                            jQuery('._crowdfundinggetmaximumprice_field').css('display', 'block');
                            jQuery('._crowdfundinghidemaximum_field').css('display', 'block');
                        } else {
                            jQuery('._crowdfundingcheckboxvalue_field').css('display', 'none');
                            jQuery('._crowdfundinggetminimumprice_field').css('display', 'none');
                            jQuery('._crowdfundinghideminimum_field').css('display', 'none');
                            jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'none');
                            jQuery('._crowdfundinggetmaximumprice_field').css('display', 'none');
                            jQuery('._crowdfundinghidemaximum_field').css('display', 'none');
                        }
                    });
                    jQuery('#product-type').each(function () {
                        var selectvalue = jQuery(this).val();
                        if ((selectvalue === 'simple') || (selectvalue === 'subscription')) {
                            jQuery('._crowdfundingcheckboxvalue_field').css('display', 'block');
                            jQuery('._crowdfundinggetminimumprice_field').css('display', 'block');
                            jQuery('._crowdfundinghideminimum_field').css('display', 'block');
                            jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'block');
                            jQuery('._crowdfundinggetmaximumprice_field').css('display', 'block');
                            jQuery('._crowdfundinghidemaximum_field').css('display', 'block');
                        } else {
                            jQuery('._crowdfundingcheckboxvalue_field').css('display', 'none');
                            jQuery('._crowdfundinggetminimumprice_field').css('display', 'none');
                            jQuery('._crowdfundinghideminimum_field').css('display', 'none');
                            jQuery('._crowdfundinggetrecommendedprice_field').css('display', 'none');
                            jQuery('._crowdfundinggetmaximumprice_field').css('display', 'none');
                            jQuery('._crowdfundinghidemaximum_field').css('display', 'none');
                        }
                    });
                });
            </script>

            <?php
        }

        public static function FP_CF_crowdfunding_save_product_post($post_id) {
            $post_status = get_post_status($post_id);
            $check_for_new = $_POST['original_publish'] == 'Update' ? false : true;
            if ($post_status == 'draft') {
                $getdate = get_post_meta($post_id, '_crowdfundingfromdatepicker', true);
                $edited_days = $_POST['_crowdfundinguserenddays_text'];
                $todatenew = date(FP_GF_Common_Functions::fp_gf_date_format(), strtotime($getdate . ' + ' . $edited_days . ' days'));
                fp_gf_update_campaign_metas($post_id, '_crowdfundingtodatepicker', $todatenew, $check_for_new);
            }
            $woocommerce_radiobox = $_POST['_target_end_selection'];
            $woocommerce_admin_days = isset($_POST['_crowdfundinguserenddays_text']) ? $_POST['_crowdfundinguserenddays_text'] : '';
            $woocommerce_checkbox = isset($_POST['_crowdfundingcheckboxvalue']) ? 'yes' : 'no';
            $woocommerce_socialsharing = isset($_POST['_crowdfundingsocialsharing']) ? 'yes' : 'no';
            $woocommerce_socialsharing_facebook = isset($_POST['_crowdfundingsocialsharing_facebook']) ? 'yes' : 'no';
            $woocommerce_socialsharing_twitter = isset($_POST['_crowdfundingsocialsharing_twitter']) ? 'yes' : 'no';
            $woocommerce_socialsharing_google = isset($_POST['_crowdfundingsocialsharing_google']) ? 'yes' : 'no';
            $woocommerce_showhide_contributors = isset($_POST['_crowdfunding_showhide_contributor']) ? 'yes' : 'no';
            $woocommerce_mark_contributor_anonymous = isset($_POST['_crowdfunding_contributor_anonymous']) ? 'yes' : 'no';
            $selected_product_checkbox = isset($_POST['_use_selected_product_image']) ? 'yes' : 'no';
            $woocommerce_fundraising_type = $_POST['_crowdfunding_options'];
            $woocommerce_select_products = isset($_POST['_cf_product_selection']) ? $_POST['_cf_product_selection'] : '';
            $woocommerce_minimumprice = $_POST['_crowdfundinggetminimumprice'];
            $woocommerce_crowdfundinghideminimum = isset($_POST['_crowdfundinghideminimum']) ? 'yes' : 'no';
            $woocommerce_recommendedprice = isset($_POST['woocommerce_recommendedprice']) ? $_POST['woocommerce_recommendedprice'] : '';
            $woocommerce_maximumprice = $_POST['_crowdfundinggetmaximumprice'];
            $woocommerce_crowdfundinghidemaximum = isset($_POST['_crowdfundinghidemaximum']) ? 'yes' : 'no';
            $woocommerce_targetprice = $_POST['_crowdfundinggettargetprice'];
            $woocommerce_crowdfundinghidetarget = isset($_POST['_crowdfundinghidetarget']) ? 'yes' : 'no';
            $crowdfundingfromdatepicker = $_POST['_crowdfundingfromdatepicker'];
            $crowdfundingfromhourdatepicker = $_POST['_crowdfundingfromhourdatepicker'];
            $crowdfundingfromminutesdatepicker = $_POST['_crowdfundingfromminutesdatepicker'];
            $crowdfundingtodatetext = isset($_POST['_crowdfundinguserenddays_text']) ? $_POST['_crowdfundinguserenddays_text'] : '';
            $crowdfundingtodatepicker = $_POST['_crowdfundingtodatepicker'];
            $crowdfundingtohourdatepicker = $_POST['_crowdfundingtohourdatepicker'];
            $crowdfundingtotimedatetimepicker = $_POST['_crowdfundingtominutesdatepicker'];
            $crowdfunding_campaign_email = $_POST['cf_campaigner_paypal_id'];
            $woocommerce_selectbox1_galaxy = $_POST['buttonstyles_galaxy'];
            $woocommerce_amount1_galaxy = $_POST['_amount1_galaxy'];
            $woocommerce_amount2_galaxy = $_POST['_amount2_galaxy'];
            $woocommerce_amount3_galaxy = $_POST['_amount3_galaxy'];
            $woocommerce_amount4_galaxy = $_POST['_amount4_galaxy'];
            $woocommerce_amount5_galaxy = $_POST['_amount5_galaxy'];
            $woocommerce_amount6_galaxy = $_POST['_amount6_galaxy'];
            $woocommerce_recomended_amt_galaxy = $_POST['_recomended_amount_galaxy'];
            $amount_collection_galaxy = array($woocommerce_amount1_galaxy, $woocommerce_amount2_galaxy, $woocommerce_amount3_galaxy, $woocommerce_amount4_galaxy, $woocommerce_amount5_galaxy, $woocommerce_amount6_galaxy);

            $options = array(
                '_target_end_selection' => $woocommerce_radiobox,
                '_crowdfundinguserenddays_text' => $woocommerce_admin_days,
                '_crowdfundingcheckboxvalue' => $woocommerce_checkbox,
                '_crowdfundingsocialsharing' => $woocommerce_socialsharing,
                '_crowdfundingsocialsharing_facebook' => $woocommerce_socialsharing_facebook,
                '_crowdfundingsocialsharing_twitter' => $woocommerce_socialsharing_twitter,
                '_crowdfundingsocialsharing_google' => $woocommerce_socialsharing_google,
                '_crowdfunding_showhide_contributor' => $woocommerce_showhide_contributors,
                '_crowdfunding_contributor_anonymous' => $woocommerce_mark_contributor_anonymous,
                '_crowdfunding_options' => $woocommerce_fundraising_type,
                '_cf_product_selection' => $woocommerce_select_products,
                '_crowdfundinggetminimumprice' => $woocommerce_minimumprice,
                '_crowdfundinghideminimum' => $woocommerce_crowdfundinghideminimum,
                '$woocommerce_recommendedprice' => $woocommerce_recommendedprice,
                '_crowdfundinggetmaximumprice' => $woocommerce_maximumprice,
                '_crowdfundinghidemaximum' => $woocommerce_crowdfundinghidemaximum,
                '_crowdfundinggettargetprice' => $woocommerce_targetprice,
                '_crowdfundinghidetarget' => $woocommerce_crowdfundinghidetarget,
                '_crowdfundingfromdatepicker' => $crowdfundingfromdatepicker,
                '_crowdfundingfromhourdatepicker' => $crowdfundingfromhourdatepicker,
                '_crowdfundingfromminutesdatepicker' => $crowdfundingfromminutesdatepicker,
                '_crowdfundinguserenddays_text' => $crowdfundingtodatetext,
                '_crowdfundingtodatepicker' => $crowdfundingtodatepicker,
                '_crowdfundingtohourdatepicker' => $crowdfundingtohourdatepicker,
                '_crowdfundingtominutesdatepicker' => $crowdfundingtotimedatetimepicker,
                'cf_campaigner_paypal_id' => $crowdfunding_campaign_email,
                'buttonstyles_galaxy' => $woocommerce_selectbox1_galaxy,
                '_amount1_galaxy' => $woocommerce_amount1_galaxy,
                '_amount2_galaxy' => $woocommerce_amount2_galaxy,
                '_amount3_galaxy' => $woocommerce_amount3_galaxy,
                '_amount4_galaxy' => $woocommerce_amount4_galaxy,
                '_amount5_galaxy' => $woocommerce_amount5_galaxy,
                '_amount6_galaxy' => $woocommerce_amount6_galaxy,
                '_recomended_amount_galaxy' => $woocommerce_recomended_amt_galaxy,
                'ppcollection_galaxy' => $amount_collection_galaxy,
            );
            foreach ($options as $key => $value) {
                fp_gf_update_campaign_metas($post_id, $key, $value, $check_for_new);
            }

            if (isset($_POST['_use_selected_product_image'])) {
                if (count($_POST['_cf_product_selection']) == 1) {
                    fp_gf_update_campaign_metas($post_id, '_use_selected_product_image', $selected_product_checkbox, $check_for_new);
                    $feat_image = get_post_thumbnail_id(implode($_POST['_cf_product_selection']));
                    set_post_thumbnail($post_id, $feat_image);
                } else {
                    $selected_product_checkbox = 'no';
                    fp_gf_update_campaign_metas($post_id, '_use_selected_product_image', $selected_product_checkbox, $check_for_new);
                    delete_post_thumbnail($post_id);
                }
            }

            if (isset($_POST['_crowdfundingquantity'])) {
                $woocommerce_targetquantity = $_POST['_crowdfundingquantity'];
                if (get_post_meta($post_id, '_crowdfundingquantity', true) == '') {
                    fp_gf_update_campaign_metas($post_id, '_crowdfundingquantity', $woocommerce_targetquantity, $check_for_new);
                } else {
                    $old_target_quantity = get_post_meta($post_id, '_crowdfundingquantity', true);
                    if ($old_target_quantity < $woocommerce_targetquantity) {
                        fp_gf_update_campaign_metas($post_id, 'out_of_stock_check', '', $check_for_new);
                        fp_gf_update_campaign_metas($post_id, '_crowdfundingquantity', $woocommerce_targetquantity, $check_for_new);
                    }
                }
            }
        }

        public static function FP_CF_crowdfunding_head_script() {
            global $post;
            global $woocommerce;
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
            <?php
            if (isset($post->ID)) {
                if (( get_post_type($post->ID) == 'product') || (isset($_GET['post_type']) && $_GET['post_type'] == 'product')) {
                    ?>
                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                                jQuery('.crowdfunding_options').chosen();
                                //jQuery('#_cf_product_selection').chosen();
                                jQuery('.target_end_selection').chosen();
                    <?php } else { ?>
                                jQuery('.crowdfunding_options').select2();
                                // jQuery('#_cf_product_selection').select2();
                                jQuery('.target_end_selection').select2();
                    <?php } ?>

                    <?php
                    if (get_post_meta($post->ID, '_target_end_selection', true) != '5') {
                        if (get_post_meta($post->ID, '_crowdfunding_options', true) == '') {
                            ?>
                                    jQuery('._crowdfundinggetminimumprice_field').hide();
                                    jQuery('._crowdfundinghideminimum_field').hide();
                                    jQuery('._crowdfundinggetrecommendedprice_field').hide();
                                    jQuery('._crowdfundinghiderecommendedprice_field').hide();
                                    jQuery('._crowdfundinggetmaximumprice_field').hide();
                                    jQuery('._crowdfundinghidemaximum_field').hide();
                                    jQuery('._cf_selection_field').hide();
                                    jQuery('._crowdfundinggettargetprice_field').hide();
                                    jQuery('._crowdfundinghidetarget_field').hide();
                                    jQuery('._target_end_selection_field').hide();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                        <?php } elseif (get_post_meta($post->ID, '_crowdfunding_options', true) == '1') {
                            ?>
                                    jQuery('._crowdfundinggetminimumprice_field').show();
                                    jQuery('._crowdfundinghideminimum_field').show();
                                    jQuery('._crowdfundinggetrecommendedprice_field').show();
                                    jQuery('._crowdfundinghiderecommendedprice_field').show();
                                    jQuery('._crowdfundinggetmaximumprice_field').show();
                                    jQuery('._crowdfundinghidemaximum_field').show();
                                    jQuery('._cf_selection_field').hide();
                                    jQuery('._use_selected_product_image_field').hide();
                                    jQuery('._crowdfundinggettargetprice_field').show();
                                    jQuery('._crowdfundinghidetarget_field').show();
                                    jQuery('._target_end_selection_field').show();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                        <?php } else { ?>
                                    jQuery('._crowdfundinggetminimumprice_field').show();
                                    jQuery('._crowdfundinghideminimum_field').show();
                                    jQuery('._crowdfundinggetrecommendedprice_field').show();
                                    jQuery('._crowdfundinghiderecommendedprice_field').show();
                                    jQuery('._crowdfundinggetmaximumprice_field').show();
                                    jQuery('._crowdfundinghidemaximum_field').show();
                                    jQuery('._cf_selection_field').show();
                                    jQuery('._use_selected_product_image_field').show()
                                    jQuery('._crowdfundinggettargetprice_field').show();
                                    jQuery('._crowdfundinghidetarget_field').show();
                                    jQuery('._target_end_selection_field').show();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('#_crowdfundinggettargetprice').attr('readonly', true);
                        <?php } ?>

                                if (jQuery('#_crowdfunding_options').val() == '1') {
                                    jQuery('._crowdfundinggetminimumprice_field').show();
                                    jQuery('._crowdfundinghideminimum_field').show();
                                    jQuery('._crowdfundinggetrecommendedprice_field').show();
                                    jQuery('._crowdfundinghiderecommendedprice_field').show();
                                    jQuery('._crowdfundinggetmaximumprice_field').show();
                                    jQuery('._crowdfundinghidemaximum_field').show();
                                    jQuery('._cf_selection_field').hide();
                                    jQuery('._use_selected_product_image_field').hide();
                                    jQuery('._crowdfundinggettargetprice_field').show();
                                    jQuery('._crowdfundinghidetarget_field').show();
                                    jQuery('._target_end_selection_field').show();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                                }
                    <?php } ?>
                            jQuery('#_crowdfunding_options').change(function (e) {
                                jQuery('.maindivcf').show();
                                var updatevalue = jQuery(this).val();
                                //alert(updatevalue);
                                if (updatevalue === '2') {
                                    jQuery('._crowdfundinggetminimumprice_field').show();
                                    jQuery('._crowdfundinghideminimum_field').show();
                                    jQuery('._crowdfundinggetrecommendedprice_field').show();
                                    jQuery('._crowdfundinghiderecommendedprice_field').show();
                                    jQuery('._crowdfundinggetmaximumprice_field').show();
                                    jQuery('._crowdfundinghidemaximum_field').show();
                                    jQuery('._cf_selection_field').show();
                                    jQuery('._use_selected_product_image_field').show();
                                    jQuery('._crowdfundinggettargetprice_field').show();
                                    jQuery('._crowdfundinghidetarget_field').show();
                                    jQuery('._target_end_selection_field').show();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('#_crowdfundinggettargetprice').attr('readonly', true);
                                } else {
                                    jQuery('._crowdfundinggetminimumprice_field').show();
                                    jQuery('._crowdfundinghideminimum_field').show();
                                    jQuery('._crowdfundinggetrecommendedprice_field').show();
                                    jQuery('._crowdfundinghiderecommendedprice_field').show();
                                    jQuery('._crowdfundinggetmaximumprice_field').show();
                                    jQuery('._crowdfundinghidemaximum_field').show();
                                    jQuery('._cf_selection_field').hide();
                                    jQuery('._use_selected_product_image_field').hide();
                                    jQuery('._crowdfundinggettargetprice_field').show();
                                    jQuery('._crowdfundinghidetarget_field').show();
                                    jQuery('._target_end_selection_field').show();
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('#_crowdfundinggettargetprice').attr('readonly', false);
                                }

                            });
                            jQuery('#_target_end_selection').change(function (e) {
                                var currentvalue = jQuery(this).val();
                                if (currentvalue === '2' || currentvalue === '1') {
                                    jQuery('._crowdfundingfromdatepicker_field').show();
                                    jQuery('._crowdfundingtodatepicker_field').show();
                                    jQuery('._crowdfundingquantity_field').hide();

                                } else if (currentvalue === '3' || currentvalue === '4') {
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('._crowdfundingquantity_field').hide();
                                } else {
                                    jQuery('._crowdfundingfromdatepicker_field').hide();
                                    jQuery('._crowdfundingtodatepicker_field').hide();
                                    jQuery('._crowdfundingquantity_field').show();
                                }
                            });
                    <?php if (get_post_meta($post->ID, '_target_end_selection', true) == '2' || get_post_meta($post->ID, '_target_end_selection', true) == '1') { ?>
                                jQuery('._crowdfundingfromdatepicker_field').show();
                                jQuery('._crowdfundingtodatepicker_field').show();
                                jQuery('._crowdfundingquantity_field').hide();
                    <?php } else if (get_post_meta($post->ID, '_target_end_selection', true) == '3' || get_post_meta($post->ID, '_target_end_selection', true) == '4') {
                        ?>
                                jQuery('._crowdfundingfromdatepicker_field').hide();
                                jQuery('._crowdfundingtodatepicker_field').hide();
                                jQuery('._crowdfundingquantity_field').hide();
                    <?php } else { ?>
                                jQuery('._crowdfundingfromdatepicker_field').hide();
                                jQuery('._crowdfundingtodatepicker_field').hide();
                                jQuery('._crowdfundingquantity_field').show();
                    <?php }
                    ?>
                            jQuery('#_cf_product_selection').change(function () {
                                var thisvalue = 0;
                                jQuery('#_cf_product_selection > option:selected').each(function () {
                                    var value = jQuery(this).attr('data-price');
                                    thisvalue = parseFloat(thisvalue) + parseFloat(value);
                                    thisvalue = thisvalue.toFixed(2);
                                });
                                jQuery("#_crowdfundinggettargetprice").val(thisvalue);
                            });
                            jQuery("#_crowdfunding_showhide_contributor").ready(function () {
                                jQuery('#_crowdfunding_showhide_contributor').change(function () {
                                });
                            });
                    <?php
                }
            }
            ?>
                });</script>
            <?php
        }

    }

    FP_GF_Product_Level_Entries::init();
}