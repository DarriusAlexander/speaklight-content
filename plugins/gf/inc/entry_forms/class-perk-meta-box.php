<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Perk_Meta_Box')) {

    class FP_GF_Perk_Meta_Box {

        public static function init() {
            //Adding Meta box
            add_action('add_meta_boxes', array(__CLASS__, 'galaxy_funder_register_meta_box'));
            //Saving perk post values
            add_action('save_post', array(__CLASS__, 'save_perk_data_to_galaxyfunder'));
        }

        public static function galaxy_funder_register_meta_box() {
            add_meta_box('FP_GF_Perk_Meta_Box::galaxy_funder_add_meta_box', __('Perk Rule', 'galaxyfunder'), 'FP_GF_Perk_Meta_Box::galaxy_funder_add_meta_box', 'product', 'normal', 'core');
        }

        public static function galaxy_funder_add_meta_box() {
            global $woocommerce;
            global $post;

            wp_nonce_field(plugin_basename(__FILE__), 'cfperkrulenonce');
            ?>
            <div id="meta_inner">
                <?php
                $perkrule = get_post_meta($post->ID, 'perk', true);
                if (is_array($perkrule)) {
                    foreach ($perkrule as $i => $perk) {

                        if (isset($perk['choose_products']) && !empty($perk['choose_products'])) {
                            $selected_product_id = $perk['choose_products'];
                        }
                        ?>
                        <div class="panel woocommerce_options_panel" style="display: block;">
                            <div class="options_group"  style="border-bottom: 1px solid #DFDFDF !important; border-top: 1px solid #FFFFFF !important; padding-bottom:10px; margin-bottom:10px;">
                                <p class="form-field">
                                    <label><?php echo __('Name of Perk', 'galaxyfunder'); ?></label>
                                    <input type="text" name="perk[<?php echo $i; ?>][name]" class="short" value="<?php echo $perk['name']; ?>"/>
                                </p>

                                <p class="form-field _cf_selection_fields "><label for="_cf_selection_fields"><?php echo __('Choose Products', 'galaxyfunder'); ?></label>

                                    <?php if ((float) $woocommerce->version >= (float) ('3.0.0')) { ?> 
                                        <select data-action='ajax_product_search'  class="wc-product-search"  style="width: 50%" id="perkproductselection<?php echo $i; ?>" name="perk[<?php echo $i; ?>][choose_products]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>">
                                            <?php
                                            if (isset($perk['choose_products'])) {
                                                if (is_admin()) {
                                                    if ($perk['choose_products'] != '') {
                                                        $product_id = $selected_product_id;
                                                        $product = wc_get_product($product_id);
                                                        echo '<option value="' . esc_attr($product_id) . '"' . selected(1, 1) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    <?php } else { ?>

                                        <input type="hidden" class="wc-product-search" style="width: 50%;" id="perkproductselection<?php echo $i; ?>" name="perk[<?php echo $i; ?>][choose_products]" data-placeholder="<?php _e('Search for a product&hellip;', 'galaxyfunder'); ?>" data-action="ajax_product_search" data-multiple="false" data-selected="<?php
                                        $json_ids = array();
                                        if ($perk['choose_products'] != '') {
                                            $product_id = $selected_product_id;
                                            if ($product_id != "") {
                                                $list_of_produts = $product_id;
                                                if (!is_array($list_of_produts)) {
                                                    $list_of_produts = explode(',', $list_of_produts);
                                                    $product_ids = array_filter(array_map('absint', (array) explode(',', $product_id)));
                                                } else {
                                                    $product_ids = $list_of_produts;
                                                }
                                                if ($product_ids != NULL) {
                                                    foreach ($product_ids as $product_id) {
                                                        if (isset($product_id)) {
                                                            $product = wc_get_product($product_id);
                                                            if (is_object($product)) {
                                                                $json_ids = wp_kses_post(strip_tags($product->get_formatted_name()));
                                                            }
                                                        }
                                                    } echo strip_tags($product->get_formatted_name());
                                                }
                                            }
                                            ?>" value="<?php
                                                   echo $product_id;
                                               }
                                               ?>" />


                                    <?php }
                                    ?>
                                </p>

                                <p class="form-field">
                                    <label><?php echo __('Perk Amount', 'galaxyfunder'); ?></label>
                                    <input type="text" name="perk[<?php echo $i; ?>][amount]" id="perkamount<?php echo $i; ?>" class="short" value="<?php echo $perk['amount']; ?>"/>
                                </p>

                                <p class = "form-field">
                                    <label><?php echo __('Description', 'galaxyfunder'); ?></label>
                                    <textarea rows = "3" cols = "14" style = "height:110px;
                                              width:360px;
                                              " name = "perk[<?php echo $i;
                                    ?>][description]" class = "short"><?php echo $perk['description'];
                                    ?></textarea>
                                </p>
                                <p class="form-field">
                                    <label><?php echo __('Limit Perk Claim', 'galaxyfunder'); ?></label>
                                    <select name="perk[<?php echo $i; ?>][limitperk]" id="perk_limitation<?php echo $i; ?>" class="cf_limit_perk_count">
                                        <option value="cf_limited" <?php
                                        if (isset($perk['limitperk']) && !empty($perk['limitperk'])) {
                                            echo $perk['limitperk'] == 'cf_limited' ? 'selected=selected' : '';
                                        }
                                        ?>><?php echo __('Limited', 'galaxyfunder'); ?></option>
                                        <option value="cf_unlimited" <?php
                                        if (isset($perk['limitperk']) && !empty($perk['limitperk'])) {
                                            echo $perk['limitperk'] == 'cf_unlimited' ? 'selected=selected' : '';
                                        }
                                        ?>><?php echo __('Unlimited', 'galaxyfunder'); ?></option>
                                    </select>

                                </p>
                                <p class="form-field">
                                    <label><?php echo __('Perk Claim Max Count', 'galaxyfunder'); ?></label>
                                    <input type ="text" name="perk[<?php echo $i; ?>][claimcount]" id="perk_claimcount<?php echo $i; ?>" class="short test" value="<?php echo $perk['claimcount']; ?>"/>
                                </p>
                                <p class="form-field">
                                    <label><?php echo __('Estimated Delivery on', 'galaxyfunder'); ?></label>
                                    <input type="text" name="perk[<?php echo $i; ?>][deliverydate]" id="perkid<?php echo $i; ?>" class="short" value="<?php
                                    if (isset($perk['deliverydate']) && !empty($perk['deliverydate'])) {
                                        echo $perk['deliverydate'];
                                    }
                                    ?>"/>
                                </p>
                                <span class="remove button-secondary"><?php echo __('Remove Perk Rule', 'galaxyfunder'); ?></span>
                            </div>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {

                                //set price function
                                jQuery("#perkproductselection<?php echo $i; ?>").change(function () {
                                    var product_id_array = jQuery(this).val();
                                    var dataparam = ({
                                        action: 'ajax_get_product_price',
                                        product_id_array: product_id_array,
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                            function (response) {
                                                var newresponse = response.replace(/\s/g, '');
                    <?php if (is_admin()) { ?>

                                                    jQuery("#perkamount<?php echo $i; ?>").val(newresponse);
                    <?php }
                    ?>
                                            });
                                });


                                if (jQuery('#perk_limitation<?php echo $i; ?>').val() == 'cf_unlimited') {
                                    jQuery('#perk_claimcount<?php echo $i; ?>').parent().hide();
                                } else {
                                    jQuery('#perk_claimcount<?php echo $i; ?>').parent().show();
                                }
                                jQuery(document).on('change', '#perk_limitation<?php echo $i; ?>', function () {
                                    if (jQuery(this).val() == 'cf_unlimited') {
                                        jQuery('#perk_claimcount<?php echo $i; ?>').parent().hide();
                                    } else {
                                        jQuery('#perk_claimcount<?php echo $i; ?>').parent().show();
                                    }
                                });

                            });</script>

                        <?php
                        $i = $i + 1;
                    }
                }
                ?>
                <span id="here"></span>
                <span class="add button-primary"><?php _e('Add Perk Rule'); ?></span>
                <script>
                    jQuery(document).ready(function () {
                        jQuery(".add").click(function () {
                            var countperk = Math.round(new Date().getTime() + (Math.random() * 100));
                            var product_selection = '';
            <?php if ((float) $woocommerce->version >= (float) ('3.0.0')) { ?>
                                product_selection = '<select data-action="ajax_product_search" class="wc-product-search"  style="width: 320px;" id="_perk_product_selection' + countperk + '" name="perk[' + countperk + '][choose_products]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>"></select>';
            <?php } else { ?>
                                product_selection = '<input type="hidden" data-action="ajax_product_search" class="wc-product-search" style="width: 50%;" id="_perk_product_selection' + countperk + '" name="perk[' + countperk + '][choose_products]" data-placeholder="<?php _e('Search for a product&hellip;', 'galaxyfunder'); ?>"  data-multiple="false" data-selected=""/>';
            <?php } ?>


                            jQuery('#here').append('<div class="panel woocommerce_options_panel" style="display: block;"><div class="options_group" style="border-bottom: 1px solid #DFDFDF !important; border-top: 1px solid #FFFFFF !important;padding-bottom:10px; margin-bottom:10px;"><p class="form-field"><label>Name of Perk</label><input type="text" name="perk[' + countperk + '][name]" class="short" value=""/></p>\n\
                    \n\<p class="form-field _cf_selection_field " style="display: block;"><label for="_cf_product_selection">Choose Products</label>' + product_selection + '</p><p class="form-field"><label>Perk Amount</label><input type="text" id="perkamount' + countperk + '" name="perk[' + countperk + '][amount]" class="short" value=""/></p>\n\
                    <p class="form-field"><label>Description</label><textarea rows="3" cols="14" style="height:110px;width:360px;" name="perk[' + countperk + '][description]" class="short" value=""></textarea></p>\n\
                        <p class="form-field"><label>Limit Perk Claim</label><select name="perk[' + countperk + '][limitperk]" id="perk_limitation' + countperk + '" class="cf_limit_perk_count"><option value ="cf_limited" >Limited</option><option value ="cf_unlimited">Unlimited</option></select></p>\n\
            <p class="form-field"><label>Perk Claim Max Count</label><input type ="text" id="perk_claimcount' + countperk + '" name="perk[' + countperk + '][claimcount]" class="short test"  value=""/></p>\n\
             <p class="form-field"><label>Estimated Delivery on</label><input type="text" name="perk[' + countperk + '][deliverydate]" id="perkid' + countperk + '" class="short" value=""/></p><span class="remove button-secondary">Remove Perk Rule</span></div></div>');
                            jQuery('body').trigger('wc-enhanced-select-init');
                            jQuery("#_perk_product_selection" + countperk).change(function () {

                                var product_id_array = jQuery(this).val();

                                var dataparam = ({
                                    action: 'ajax_get_product_price',
                                    product_id_array: product_id_array,
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                        function (response) {
                                            var newresponse = response.replace(/\s/g, '');
            <?php if (is_admin()) { ?>
                                                jQuery("#perkamount" + countperk).val(newresponse);
            <?php }
            ?>
                                        });
                            });
                            //set price function
                            if (jQuery('#perk_limitation' + countperk).val() == 'cf_unlimited') {
                                jQuery('#perk_claimcount' + countperk).parent().hide();
                            } else {
                                jQuery('#perk_claimcount' + countperk).parent().show();
                            }
                            jQuery(document).on('change', '#perk_limitation' + countperk, function () {
                                if (jQuery(this).val() == 'cf_unlimited') {
                                    jQuery('#perk_claimcount' + countperk).parent().hide();
                                } else {
                                    jQuery('#perk_claimcount' + countperk).parent().show();
                                }
                            });

                            jQuery('#perkid' + countperk).datepicker({
                                changeMonth: true,
                            });
                            return false;

                        });

                        jQuery(document).on('click', '.remove', function () {
                            jQuery(this).parent().parent().remove();
                        });
                    });</script>
            </div><?php
        }

        public static function save_perk_data_to_galaxyfunder($post_id) {
            $perks = '';
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if (!isset($_POST['cfperkrulenonce']))
                return;
            if (!wp_verify_nonce($_POST['cfperkrulenonce'], plugin_basename(__FILE__)))
                return;
            if (isset($_POST['perk'])) {
                $perks = $_POST['perk'];
            }
            $check_for_new = $_POST['original_publish'] == 'Update' ? false : true;
            fp_gf_update_campaign_metas($post_id, 'perk', $perks, $check_for_new);
        }

        public static function perk_info_check() {
            $order_id = $_GET['order_id'];

            global $post;
            echo $post;
            global $woocommerce;
            $products = $order_id;
            $i = 0;
            ?>
            <style type="text/css">
            <?php $cf_button_color = get_option('cf_button_color'); ?>
            <?php $cf_button_text_color = get_option('cf_button_text_color'); ?>
            <?php $cf_selected_button_color = get_option('cf_selected_button_color'); ?>
            <?php $cf_selected_button__text_color = get_option('cf_selected_button_text_color'); ?>
                .cf_amount_button{
                    float:left;
                    width: 85px;
                    margin-right:10px;
                    margin-top:10px;
                    height:50px;
                    border: 1px solid #ddd;
                    background: #<?php echo $cf_button_color; ?>;
                    color:#<?php echo $cf_button_text_color; ?>;
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
                    background: #<?php echo $cf_selected_button_color; ?>;
                    color:#<?php echo $cf_selected_button__text_color; ?>;
                }
            </style>
            <?php
            $showhide_serialnumber = get_option('cf_serial_number_show_hide');
            $showhide_contributorname = get_option('cf_contributor_name_show_hide');
            $showhide_contributoremail = get_option('cf_contributor_email_show_hide');
            $showhide_contribution = get_option('cf_contribution_show_hide');
            $showhide_date = get_option('cf_date_column_show_hide');
            $showhide_perkname = get_option('cf_perk_name_column_show_hide');
            $showhide_perkamount = get_option('cf_perk_amount_column_show_hide');
            ?>
            <?php
            if (function_exists('wc_get_order_statuses')) {
                $getpoststatus = array_keys(wc_get_order_statuses());
            } else {
                $getpoststatus = 'publish';
            }

            $listofcontributedorderids = array_unique(array_filter((array) get_post_meta($order_id, 'orderids', true)));
            if (is_array($listofcontributedorderids)) {
                foreach ($listofcontributedorderids as $order) {

                    $myorderid = $order;
                    $order = fp_gf_get_order_object($order);

                    foreach ($order->get_items() as $item) {
                        $products = array();
                        $product_id = $item['product_id'];
                        $products[] = $product_id;
                        if (in_array($order_id, $products)) {
                            $newpostid = $order_id;
                            $funding_options = get_post_meta($item['product_id'], '_crowdfunding_options', true);
                            $userid = get_post_field('post_author', $products);
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
                            $ship_email = get_user_meta($userid, 'billing_email', true);

                            $bill_first_name = get_user_meta($userid, 'billing_first_name', true);
                            $bill_last_name = get_user_meta($userid, 'billing_last_name', true);
                            $bill_company = get_user_meta($userid, 'billing_company', true);
                            $bill_address1 = get_user_meta($userid, 'billing_address_1', true);
                            $bill_address2 = get_user_meta($userid, 'billing_address_2', true);
                            $bill_city = get_user_meta($userid, 'billing_city', true);
                            $bill_country = get_user_meta($userid, 'billing_country', true);
                            $bill_postcode = get_user_meta($userid, 'billing_postcode', true);
                            $bill_state = get_user_meta($userid, 'billing_state', true);
                            $bill_phone = get_user_meta($userid, 'billing_phone', true);


                            if ($order->get_status() == FP_GF_Common_Functions::get_order_status_for_contribution()) {
                                if ($i == 0) {
                                    $formed_order_object = FP_GF_Common_Functions:: common_function_to_get_order_object_datas($order);
                                    $billing_first_name = $formed_order_object->get_billing_firstname;
                                    $billing_last_name = $formed_order_object->get_billing_lastname;
                                    $billing_email = $formed_order_object->get_billing_email;
                                    $get_order_date = $formed_order_object->get_order_date;

                                    echo FP_GF_Common_Functions::common_function_for_search_box();
                                    ?>
                                    <table class="wp-list-table widefat fixed posts" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="campaign_monitor" cellspacing="0">
                                        <h3> <?php echo __('Campaign Contribution Table', 'galaxyfunder'); ?></h3>
                                        <thead>
                                            <tr>

                                                <th class="cf_serial_number_label" id="cf_serial_number_label" data-toggle="true" data-sort-initial = "true"><?php echo get_option('cf_serial_number_label'); ?></th>
                                                <th class="cf_contributor_label" id="cf_contributor_label"><?php echo get_option('cf_contributor_label'); ?></th>
                                                <th class="cf_contribution_label" id="cf_contribution_label" data-hide="phone"><?php echo get_option('cf_donation_label'); ?></th>
                                                <th class="cf_contribution_perk_name" id="cf_contribution_perk_name" data-hide="phone"><?php echo get_option('cf_perk_name_label'); ?></th>
                                                <th class="cf_contribution_perk_amount" id="cf_contribution_perk_amount" data-hide="phone"><?php echo get_option('cf_perk_amount_label'); ?></th>
                                                <th class="cf_perkquantity" id="cf_perk_label" data-hide="phone,tablet"><?php echo get_option('cf_perk_quantity_label'); ?></th>
                                                <th class="cf_date_label" id="cf_date_label" data-hide="phone,tablet"><?php echo get_option('cf_date_label'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        }
                                        $i++;
                                        ?>
                                        <tr>
                                            <?php if ($showhide_serialnumber == '1') { ?>
                                                <td class='serial_id' data-value="<?php echo $i; ?>" id='serial_id'><?php echo $i; ?>
                                                </td>
                                            <?php } ?>
                                            <?php if ($showhide_contributorname == '1') { ?>
                                                <td class='cf_billing_first_name' id='cf_billing_first_name'>
                                                    <?php
                                                    if (get_post_meta($myorderid, 'contributor_list_for_campaign', true) == '') {
                                                        if (get_post_meta($order_id, 'My Checkbox', true) == '1') {
                                                            echo __('Anonymous', 'galaxyfunder');
                                                        } else {
                                                            $mark_contributor_anonymous = get_post_meta($order_id, '_crowdfunding_contributor_anonymous', true);
                                                            if ($mark_contributor_anonymous == 'yes') {
                                                                echo __('Anonymous', 'galaxyfunder');
                                                            } else {
                                                                echo $billing_first_name . "&nbsp;" . $billing_last_name;
                                                            }
                                                        }
                                                    } else {
                                                        if (get_post_meta($order_id, 'My Checkbox', true) == '1') {
                                                            echo __('Anonymous', 'galaxyfunder');
                                                        } else {
                                                            $mark_contributor_anonymous = get_post_meta($order_id, '_crowdfunding_contributor_anonymous', true);
                                                            if ($mark_contributor_anonymous == 'yes') {
                                                                echo __('Anonymous', 'galaxyfunder');
                                                            } else {
                                                                echo get_post_meta($myorderid, 'contributor_list_for_campaign', true);
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
                                            <?php
                                            if ($showhide_contribution == '1') {
                                                if (get_option('cf_campaign_restrict_coupon_discount') == '1') {
                                                    $cf_total = $item['line_total'];
                                                } else {
                                                    $cf_total = $item['line_subtotal'];
                                                }
                                                ?>
                                                <td class='cf_order_total' id='cf_order_total'><?php echo FP_GF_Common_Functions::format_price_in_proper_order($cf_total); ?><br></td>
                                            <?php } ?>
                                            <?php if ($showhide_perkname == '1') { ?>
                                                <td class="cf_contribution_perk_name" id="cf_contribution_perk_name"><?php
                                                    $cfperkname = get_post_meta($myorderid, 'perkname' . $order_id, true);
                                                    if (!is_array($cfperkname)) {
                                                        if ($cfperkname != '') {
                                                            $cfperkname = $cfperkname;
                                                        } else {
                                                            $cfperkname = '-';
                                                        }
                                                        echo $cfperkname;
                                                    } else {
                                                        $imploded_value = implode(', ', $cfperkname);
                                                        if ($imploded_value == '') {
                                                            echo '-';
                                                        } else {
                                                            echo $imploded_value;
                                                        }
                                                    }
                                                    ?></td>
                                            <?php } ?>
                                            <?php if ($showhide_perkamount == '1') { ?>
                                                <td class="cf_contribution_perk_amount" id="cf_contribution_perk_amount"><?php
                                                    $cfperkamount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $order_id, true);
                                                    if ($cfperkamount != 0) {
                                                        $cfperkamount = FP_GF_Common_Functions::format_price_in_proper_order($cfperkamount);
                                                    } else {
                                                        $cfperkamount = '-';
                                                    }
                                                    echo $cfperkamount;
                                                    ?></td>
                                            <?php } ?>

                                            <td class="cf_perk_quantity" id="cf_perk_quantity">
                                                <?php
                                                $total_contribution = $item['line_total'];
                                                $perk_amount = (int) get_post_meta($myorderid, 'perk_maincontainer' . $order_id, true);
                                                if ($perk_amount > 0) {
                                                    $perk_quantity = $total_contribution / $perk_amount;

                                                    echo $perk_quantity;
                                                } else {

                                                    echo'-';
                                                }
                                                ?>
                                            </td>
                                            <?php if ($showhide_date == '1') { ?>
                                                <td class='cf_order_date' id='cf_order_date'><?php echo $get_order_date; ?></td>
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
            </table>
            <div class="pagination pagination-centered"></div>
            <?php if ($funding_options == '2') { ?>
                <h3> <?php echo __('Campaign Contributor Billing & Shipping Information', 'galaxyfunder'); ?></h3>
                <div><b><?php echo _e('Compaign Creator Email ID : ', 'galaxyfunder'); ?></b><?php echo $ship_email; ?></div>
                <div>
                    <table class="widefat wp-list_shipping-table" cellspacing="0" style="width:500px;height:370px;border: 10px solid #ddd;display:inline-block;" >
                        <tbody>
                            <tr>
                                <td scope="col" ><h3><?php _e('Shipping Address', 'galaxyfunder'); ?></h3></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_company; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_first_name . ' ' . $ship_last_name; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_address1; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_address2; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_city . '-' . $ship_postcode; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_state; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_country; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="widefat wp-list_shipping-table" cellspacing="0" style="width:500px;height:370px;border: 10px solid #ddd;float:left" >
                        <tbody>
                            <tr>
                                <td scope="col" ><h3><?php _e('Billing Address', 'galaxyfunder'); ?></h3></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $ship_company; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_first_name . ' ' . $bill_last_name; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_address1; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_address2; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_city . '-' . $bill_postcode; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_state; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_country; ?></td>
                            </tr>
                            <tr>
                                <td scope="col"><?php echo $bill_phone; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            }
// }
//}
        }

    }

    FP_GF_Perk_Meta_Box::init();
}