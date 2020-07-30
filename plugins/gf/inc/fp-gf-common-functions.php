<?php
/*
 * Galaxy Funder common functions
 * 
 */
if (!class_exists('FP_GF_Common_Functions')) {

    class FP_GF_Common_Functions {

        public static function init() {
            
        }

        public static function cf_all_categories() {
            $selectedcategories = array();
            $categories = get_terms('product_cat', array('hide_empty' => false));
            $category_id = array();
            $category_name = array();
            if (!is_wp_error($categories)) {
                if (!empty($categories)) {
                    if ($categories != NULL) {
                        foreach ($categories as $value) {
                            $category_id[] = $value->term_id;
                        }
                    }
                }
            }

            return $category_id;
        }

        public static function common_function_to_get_description($post_id, $postion) {
            $content = '';
            $content_post = get_post($post_id);

            if (get_option('crowdfunding_description_type') == '1') {
                if (isset($content_post->post_content)) {
                    $content = $content_post->post_content;
                }
            } else {
                if (isset($content_post->post_excerpt)) {
                    $content = $content_post->post_excerpt;
                }
            }
            $enabledescription_above_stylebar = '<p class="price">' . wp_trim_words($content, get_option('crowdfunding_description_words_count')) . '</p>';
            $enabledescription_below_stylebar = '<p class="price" style="float:left;">' . wp_trim_words($content, get_option('crowdfunding_description_words_count')) . '</p>';

            if ($postion == 'above_stylebar') {
                return $enabledescription_above_stylebar;
            }
            if ($postion == 'below_stylebar') {
                return $enabledescription_below_stylebar;
            }
            return;
        }

        public static function common_function_to_get_parent_id($object) {
            global $woocommerce;
            if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                $parent_id = $object->get_parent_id();
            } else {
                $parent_id = $object->parent->id;
            }
            return $parent_id;
        }

        public static function array_check_function($value) {
            if (!is_array($value)) {
                $array_value = (array) $value;
            } else {
                $array_value = (array) $value;
            }
            return $array_value;
        }

        public static function common_function_to_get_order_object_datas($order_object) {
            global $woocommerce;
            if (is_object($order_object)) {
                if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                    $billing_first_name = $order_object->get_billing_first_name();
                    $billing_last_name = $order_object->get_billing_last_name();
                    $billing_email = $order_object->get_billing_email();
                    $get_order_date = $order_object->get_date_created();
                    $get_status = $order_object->get_status();
                    $get_user_id = $order_object->get_user_id();
                } else {
                    $billing_first_name = $order_object->billing_first_name;
                    $billing_last_name = $order_object->billing_last_name;
                    $billing_email = $order_object->billing_email;
                    $get_order_date = $order_object->order_date;
                    $get_status = $order_object->post_status;
                    $get_status = str_replace('wc-', '', $get_status);
                    $get_user_id = $order_object->user_id;
                }
            } else {
                $billing_first_name = '';
                $billing_last_name = '';
                $billing_email = '';
                $get_order_date = '';
                $get_status = '';
                $get_user_id = '';
            }
            $formed_order_object = (object) array(
                        'get_billing_firstname' => $billing_first_name,
                        'get_billing_lastname' => $billing_last_name,
                        'get_billing_email' => $billing_email,
                        'get_order_date' => $get_order_date,
                        'get_status' => $get_status,
                        'get_user_id' => $get_user_id,
            );
            return $formed_order_object;
        }

        public static function common_function_to_get_object_id($object) {
            global $woocommerce;

            if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                $object_id = $object->get_id();
            } else {
                $object_id = $object->id;
            }
            return $object_id;
        }

        public static function get_woocommerce_product_object($post_id) {
            if (function_exists('wc_get_product')) {
                $product = wc_get_product($post_id);
            } else {
                $product = get_product($post_id);
            }
            return $product;
        }

        public static function get_woocommerce_product_type($post_id) {
            if (function_exists('wc_get_product')) {
                $product = wc_get_product($post_id);
                if ($product->is_type('simple')) {
                    return 'simple';
                } else {
                    return 'others';
                }
            } else {
                $product = get_product($post_id);
                return (float) WC()->version >= (float) '3.0.0' ? $product->get_type() : $product->product_type;
            }
        }

        public static function status_checker_switch_statement($value, $postid) {
            $target_method_id = get_post_meta($postid, '_target_end_selection', true);
            switch ($value) {
                case 'publish':
                    $target_method = FP_GF_Common_Functions::target_end_method_fn($target_method_id);
                    if ($target_method == 'Target Date') {
                        $status = FP_GF_Common_Functions::common_function_to_find_day_difference($postid);
                        _e($status, 'galaxyfunder');
                        break;
                    }
                    $campaignisactive = FP_GF_Common_Functions::get_galaxy_funder_post_meta($postid, '_stock_status');
                    $outofstockmessage = get_option('cf_outofstock_label');
                    $campaignisactive = $campaignisactive == 'instock' ? 'Active' : $outofstockmessage;
                    echo $campaignisactive;
                    break;
                case 'pending':
                    _e('Pending Review', 'galaxyfunder');
                    break;
                case 'outofstock':
                    _e('campaign closed', 'galaxyfunder');
                    break;
            }
        }

        //common function  to get post
        public static function common_function_for_get_post($user_id) {
            $args = array(
                'post_type' => 'product',
                'author' => $user_id,
                'post_status' => array('draft', 'publish'),
                'posts_per_page' => '-1',
                'meta_value' => 'yes',
                'meta_key' => '_crowdfundingcheckboxvalue',
                'no_found_rows' => true,
                'update_post_term_cache' => false,
                'update_post_post_cache' => false,
                'cache_results' => false,
            );
            $dataofgetposts = get_posts($args);
            return $dataofgetposts;
        }

        //get galaxy funder is enabled in product level
        public static function get_galaxy_funder_post_meta($id, $name) {
            $getdata = get_post_meta($id, $name, true);
            return $getdata;
        }

        //Update Post Meta with galaxy Funder
        public static function update_galaxy_funder_post_meta($id, $name, $value) {
            fp_gf_update_campaign_metas($id, $name, $value);
            return;
        }

        //Admin reset common function
        public static function reset_common_function($reset_key) {
            foreach ($reset_key as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
        }

        //Get active campaigns
        public static function getcountofactivecampaigns($userid) {
            $mainuserid = $userid == '' ? get_current_user_id() : $userid;
            $args = array(
                'post_type' => 'product',
                'author' => $mainuserid,
                'post_status' => array('draft', 'publish'),
                'posts_per_page' => '-1',
                'meta_value' => 'yes',
                'meta_key' => '_crowdfundingcheckboxvalue',
                'no_found_rows' => true,
                'update_post_term_cache' => false,
                'update_post_post_cache' => false,
                'cache_results' => false,
            );
            $dataofgetposts = get_posts($args);
            $listofactivecampaigns = array();

            if (isset($dataofgetposts)) {
                foreach ($dataofgetposts as $eachposts) {
                    //var_dump($eachposts->ID);
                    $mainproduct = new WC_Product($eachposts->ID);
                    if ($mainproduct->is_in_stock()) {
                        if (get_post_meta($eachposts->ID, '_crowdfundingcheckboxvalue', true) == 'yes') {
                            $listofactivecampaigns[] = $eachposts->ID;
                        }
                    }
                }
            }
            return count($listofactivecampaigns);
        }

        //return date format
        public static function fp_gf_date_format() {
            return 'm/d/Y';
        }

        //Get Date Format
        public static function date_with_format() {
            $date_with_format = date(FP_GF_Common_Functions::fp_gf_date_format());

            return $date_with_format;
        }

        //Get Date Format
        public static function date_time_with_format() {
            $date_time_with_format = date(FP_GF_Common_Functions::fp_gf_date_format() . get_option('time_format'));

            return $date_time_with_format;
        }

        //Common function to find day difference
        public static function common_function_to_find_day_difference($product_id) {
            $new_date_remain = '';
            $checkinstock = FP_GF_Common_Functions::get_galaxy_funder_post_meta($product_id, '_stock_status');
            if ($checkinstock == 'instock') {
                $crowdfunding_end_method = get_post_meta($product_id, '_target_end_selection', true);
                $date = self::common_function_to_find_from_to_date($product_id, 'to');

                $local_current_time = strtotime(FP_GF_Common_Functions::date_time_with_format());
                if (get_option('cf_day_left_show_hide') == '1') {
                    if ($crowdfunding_end_method == '1' || $crowdfunding_end_method == '4') {
                        if ($date >= $local_current_time) {
                            $diff = $date - $local_current_time;
                            $day = ceil($diff / 86400);
                            $days = floor($diff / 86400);
                            $minutes = $diff / 60 % 60;
                            $daysinhour = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                            $hours = $diff / 3600 % 24;
                            if ($hours > 1) {
                                $hours = $hours . __(' hours ', 'galaxyfunder');
                            } else {
                                $hours = $hours . __(' hour ', 'galaxyfunder');
                            }
                            $hour = ceil(($diff - $daysinhour * 60 * 60 * 24) / (60 * 60));
                            if ($hour > 1) {
                                $hour = $hour . __(' hours ', 'galaxyfunder');
                            } else {
                                $hour = $hour . __(' hour ', 'galaxyfunder');
                            }
                            $days_label = __(' days ', 'galaxyfunder');
                            $day_label = __(' day ', 'galaxyfunder');
                            $days_left_label = __(' left', 'galaxyfunder');
                            $stock_status = get_post_meta($product_id, '_stock_status', true);
                            if ($stock_status != 'outofstock') {
                                if (get_option('cf_campaign_day_time_display') == '1') {
                                    if ($day > 1) {
                                        $new_date_remain = $day . $days_label . $days_left_label;
                                    } else {
                                        $new_date_remain = $day . $days_label . $days_left_label;
                                    }
                                } elseif (get_option('cf_campaign_day_time_display') == '2') {
                                    if ($days > 1) {
                                        $new_date_remain = $days . $days_label . $hour . $days_left_label;
                                    } else {
                                        $new_date_remain = $days . $days_label . $hour . $days_left_label;
                                    }
                                } else {
                                    if ($days > 1) {
                                        $new_date_remain = $days . $days_label . $hours . $minutes . __(' minutes ', 'galaxyfunder') . $days_left_label;
                                    } else {
                                        $new_date_remain = $days . $day_label . $hours . $minutes . __(' minutes ', 'galaxyfunder') . $days_left_label;
                                    }
                                }
                            }
                        } else {
                            
                        }
                    } else {
                        $new_date_remain = '';
                    }
                    return $new_date_remain;
                }
            }
        }

        //common function to find from date to date
        public static function common_function_to_find_from_to_date($product_id, $date_flag) {
            $getdate = FP_GF_Common_Functions::date_with_format();
            $gethour = date("h");
            $getminutes = date("i");
            $fromdate = get_post_meta($product_id, '_crowdfundingfromdatepicker', true);
            $todate = get_post_meta($product_id, '_crowdfundingtodatepicker', true);
            $tominutes = get_post_meta($product_id, '_crowdfundingtominutesdatepicker', true);
            $tohours = get_post_meta($product_id, '_crowdfundingtohourdatepicker', true);
            $fromminutes = get_post_meta($product_id, '_crowdfundingfromminutesdatepicker', true);
            $fromhours = get_post_meta($product_id, '_crowdfundingfromhourdatepicker', true);
            $checkmethod = get_post_meta($product_id, '_target_end_selection', true);
            if ($fromdate != '') {
                if ($fromhours == '' || $fromminutes == '') {
                    $fromdate = $fromdate . "00:00:00";
                } else {
                    $time = $fromhours . ':' . $fromminutes . ':' . '00';
                    $fromdate = $fromdate . $time;
                }
            } else {
                if ($tohours == '' || $tominutes == '') {
                    $fromdate = $getdate;
                } else {
                    $fromdate = $getdate;
                    $fromhour = $gethour;
                    $fromminutes = $getminutes;
                }
                fp_gf_update_campaign_metas($product_id, '_crowdfundingfromdatepicker', $getdate);
                fp_gf_update_campaign_metas($product_id, '_crowdfundingfromhourdatepicker', $gethour);
                fp_gf_update_campaign_metas($product_id, '_crowdfundingfromminutesdatepicker', $getminutes);
            }
            if ($tohours != '' || $tominutes != '') {
                $time = $tohours . ':' . $tominutes . ':' . '00';

                $todate = $todate . $time; //Your date
                $toreal = $todate . $time;
            } else {
                $todate = $todate . "23:59:59";
                $toreal = $todate . "23:59:59";
            }
            $todate = strtotime($todate);
            if ($date_flag == 'from') {
                return $fromdate;
            } else if ($date_flag == 'to') {
                return $todate;
            } else if ($date_flag == 'toreal') {
                return $toreal;
            }
        }

        //Get Difference between two date
        public static function get_difference_date($from, $to) {
            $date1 = $from;
            $date2 = $to;
            $days = '';

            $diff = strtotime($date2) - $date1;

            if ($diff >= 0) {

                $days = floor($diff / (60 * 60 * 24));
            }

            return $days;
        }

        //get the order status from option
        public static function get_order_status_for_contribution() {
            $order_status = (array) get_option('cf_add_contribution');
            foreach ($order_status as $status) {
                
            }
            return $status;
        }

        //Get shortcodes values 
        public static function get_values_for_shortcode($productid) {
            ob_start();
            $userid = get_post_field('post_author', $productid);
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
            ?>
            <table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <th scope="col" ><?php _e('Shipping Address', 'galaxyfunder'); ?></th>
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

            <?php
            $shipping_address = ob_get_clean();
            $campaign_name = get_the_title($productid);
            $campaign_page_url = get_permalink($productid);
            $campaign_site = get_site_url();
            $values = array($campaign_name, $campaign_site, $campaign_page_url, $shipping_address);
            return $values;
        }

        //Cart check out common function
        public static function cart_checkout_common_fn() {
            global $woocommerce;
            $cart_check = '';
            $cart_object = WC()->cart->cart_contents;
            $cart_count = $woocommerce->cart->cart_contents_count;
            if ($cart_count == 1) {
                foreach ($cart_object as $key => $value) {
                    $product_id = $value['product_id'];
                    $currentproductiscampaign = get_post_meta($product_id, '_crowdfundingcheckboxvalue', true);
                    if ($currentproductiscampaign == 'yes') {
                        $cart_check = 1;
                    } else {
                        $cart_check = 2;
                    }
                }
            }
            return $cart_check;
        }

        //search box common function
        public static function common_function_for_search_box() {

            $search_pagination = '<p style="display:inline-table"> ' . __('Search:', 'galaxyfunder') . '<input id="filter" type="text"/>  ' . __('Page Size:', 'galaxyfunder') . '
                    <select id="change-page-size">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select></p>';

            return $search_pagination;
        }

        public static function common_functionps($selected_product_ids, $flag) {
            global $woocommerce;
            $selected_products_check = get_option('cf_frontend_product_selection_type');
            if ($flag == 'frontend') {
                if ($selected_products_check == '1') {
                    $data_action = 'ajax_product_search_no_filter';
                } else {
                    $data_action = 'ajax_product_search';
                }
            } else {
                $data_action = 'ajax_product_search_no_filter';
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery("#_cf_product_selection").change(function () {
                        var product_id_array = jQuery(this).val();
                        var dataparam = ({
                            action: 'ajax_get_product_price',
                            product_id_array: product_id_array,
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {

                                    var newresponse = response.replace(/\s/g, '');
            <?php if (is_admin()) { ?>

                                        jQuery("#_crowdfundinggettargetprice").val(newresponse);
            <?php } else {
                ?>
                                        jQuery("#cf_campaign_target_value").val(newresponse);
            <?php }
            ?>
                                });
                    });
            <?php if ((float) $woocommerce->version < (float) ('3.0.0')) { ?>
                        jQuery("#_cf_product_selection").select2({
                            placeholder: "Enter atleast 3 characters",
                            allowClear: true,
                            enable: false,
                            readonly: false,
                            multiple: false,
                            minimumInputLength: 3,
                            tags: [],
                            escapeMarkup: function (m) {
                                return m;
                            },
                            initSelection: function (data, callback) {
                                //callback({id: '1',text: 'test'});
                                var dataselected = jQuery(data).attr('data-selected');
                                console.log(dataselected);
                                var newjson = dataselected;
                                newjson = JSON.parse(newjson);
                                console.log(newjson);
                                var data_show = [];
                                jQuery.each(newjson, function (index, item) {
                                    var formatdata = item;
                                    //item = jQuery(formatdata).html(formatdata);
                                    data_show.push({id: index, text: item});
                                });
                                callback(data_show);
                            },
                            ajax: {
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                type: "GET",
                                quietMillis: 250,
                                data: function (term) {
                                    return {
                                        term: term,
                                        action: "<?php echo $data_action; ?>",
                                    };
                                },
                                results: function (data) {
                                    var terms = [];
                                    if (data) {
                                        jQuery.each(data, function (id, text) {

                                            terms.push({
                                                id: id,
                                                text: text
                                            });
                                        });
                                    }

                                    return {results: terms};
                                },
                            },
                        }).select2('val', '1');
            <?php } ?>
                });
            </script>
            <?php
            if ((float) $woocommerce->version >= (float) ('3.0.0')) {
                if (is_admin()) {
                    $width = '50 %';
                } else {
                    $width = '100 %';
                }
                ?>
                <select data-action="<?php echo $data_action; ?>"   class="wc-product-search" multiple="multiple" style="width:<?php echo $width; ?>;" id="_cf_product_selection" name="_cf_product_selection[]" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>">
                    <?php
                    if (is_admin() && is_array($selected_product_ids) && !empty($selected_product_ids)) {
                        foreach ($selected_product_ids as $product_id) {
                            $product = wc_get_product($product_id);
                            if (is_object($product)) {
                                echo '<option value="' . esc_attr($product_id) . '"' . selected(1, 1) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <?php
            } else if ((float) $woocommerce->version < (float) ('3.0.0')) {
                $json_ids = '';
                if ($selected_product_ids != '' && (is_admin())) {
                    $sele_array = array_filter(array_map('absint', explode(',', $selected_product_ids)));
                    foreach ($sele_array as $sele_value) {
                        $product_details = FP_GF_Common_Functions::get_woocommerce_product_object($sele_value);
                        $product_name = $product_details->get_formatted_name();
                        $json_ids[$sele_value] = $product_name;
                    }
                }
                ?>
                <input type="text" name="_cf_product_selection" id="_cf_product_selection" data-price="test" data-selected="<?php echo esc_attr(json_encode($json_ids)); ?>"  style="width:320px;"/>
            <?php } ?>

            <?php
        }

        //Common function for product search
        public static function product_search_common_function() {
            global $woocommerce;
            $product_id_selected = '';
            $json_ids = array();
            if (isset($_GET['post'])) {
                $product_id_selected = $_GET['post'];
            }
            $selected_product_ids = get_post_meta($product_id_selected, '_cf_product_selection', true);
            ?>



            <p class = "form-field _cf_selection_field " style = "display: block;">
                <label><?php echo __('Choose Products'); ?></label>
                <?php echo FP_GF_Common_Functions::common_functionps($selected_product_ids, 'backend'); ?>

            </p>
            <?php
        }

        //Display price in proper order
        public static function format_price_in_proper_order($price) {
            $num_decimals = absint(get_option('woocommerce_price_num_decimals'));
            $currency = isset($args['currency']) ? $args['currency'] : '';
            $currency_symbol = get_woocommerce_currency_symbol($currency);
            $decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
            $thousands_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_thousand_sep')), ENT_QUOTES);

            if ($price < 0) {
                $price = $price * -1;
                $negative = true;
            } else {
                $negative = false;
            }

            $price = apply_filters('raw_woocommerce_price', floatval($price));
            $price = apply_filters('formatted_woocommerce_price', number_format($price, $num_decimals, $decimal_sep, $thousands_sep), $price, $num_decimals, $decimal_sep, $thousands_sep);

            if (apply_filters('woocommerce_price_trim_zeros', false) && $num_decimals > 0) {
                $price = wc_trim_zeros($price);
            }

            $formatted_price = ( $negative ? '-' : '' ) . sprintf(get_woocommerce_price_format(), $currency_symbol, $price);
            return $formatted_price;
        }

        //target end method function
        public static function target_end_method_fn($target_id) {
            $target_end_label = '';
            if ($target_id == 1) {
                $target_end_label = 'Target Goal';
            } else if ($target_id == 2) {
                $target_end_label = 'Target Date';
            } else if ($target_id == 3) {
                $target_end_label = 'Target Goal & Date';
            } else if ($target_id == 4) {
                $target_end_label = 'Campaign Never Ends';
            } else if ($target_id == 5) {
                $target_end_label = 'Target Quantity';
            }

            return $target_end_label;
        }

        //updating a percentage value
        public static function update_percentage_value($gettargetgoal, $getpledgedvalue, $product_id) {
            if (($getpledgedvalue != '') && ($gettargetgoal > 0)) {
                $count1 = $getpledgedvalue / $gettargetgoal;
                $count2 = $count1 * 100;
                $counter = number_format($count2, 0);
                $count = $counter;
            } else {
                $count = '0';
            }


            return $count;
        }

        //Progress bar Function for single product page
        public static function common_function_for_single_product_page_progressbar($productid) {
            $remainingdaysleft_galaxyfunder = '';
            $currentfunderscount = '';
            $colon_symbol = ':';
            //Get Target End selection
            $gettargetendselection = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_target_end_selection');

            //Get target goal
            $gettargetgoal1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggettargetprice');
            $gettargetgoal = fp_wpml_multi_currency($gettargetgoal1);
            $gettargetgoal_value = $gettargetgoal == '' ? '0' : $gettargetgoal;
            $gettargetgoal = FP_GF_Common_Functions:: format_price_in_proper_order($gettargetgoal, $gettargetgoal_value);

            //Get contributed amount
            $gettotalcontrinuted1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingtotalprice');
            $gettotalcontrinuted = fp_wpml_multi_currency($gettotalcontrinuted1);

            $gettotalcontrinuted_value = $gettotalcontrinuted == '' ? '0' : $gettotalcontrinuted;
            $gettotalcontrinuted = FP_GF_Common_Functions:: format_price_in_proper_order($gettotalcontrinuted_value);

            //Get percentage
            $getpercentage = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggoalpercent');

            //Get Total Funders
            $gettotalfunders_with_number = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_update_total_funders');

            //Target Quantity check and functionalities
            if ($gettargetendselection == '5') {
                $total_qty = get_post_meta($productid, '_crowdfundingquantity', true);
                $remaining_qty = get_post_meta($productid, 'remaining_qty', true);
                $saled_qty = get_post_meta($productid, '_gf_saled_qty', true);
                if ($saled_qty != '') {
                    $saled_qty = $saled_qty;
                } else {
                    $saled_qty = '0';
                }
                $count = $saled_qty / $total_qty;
                $getpercentage = number_format($count * 100);
                $gettargetgoal = $total_qty;
                $gettotalcontrinuted = $saled_qty;
                $goal_label = __('Quantity', 'galaxyfunder');
            } else {
                $getpercentage = FP_GF_Common_Functions::update_percentage_value($gettargetgoal_value, $gettotalcontrinuted_value, $productid);
                $goal_label = __('Goal', 'galaxyfunder');
            }
            if ($getpercentage > 100) {
                $progress_bar_getpercentage = 100;
            } else {
                $progress_bar_getpercentage = $getpercentage;
            }
            //Get minimum maximum labels
            $getminimumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggetminimumprice'));
            $getminimumprice = $getminimumprice == '' ? '0' : $getminimumprice;
            $getminbeforeformat = $getminimumprice;
            $getmaximumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggetmaximumprice'));
            $getmaximumprice = $getmaximumprice == '' ? '0' : $getmaximumprice;
            $getmaxbeforeformat = $getmaximumprice;
            $getminimumprice = FP_GF_Common_Functions:: format_price_in_proper_order($getminimumprice);
            $getmaximumprice = FP_GF_Common_Functions:: format_price_in_proper_order($getmaximumprice);

            //Get minimum maximum recommended prices
            $getrecommendedprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggetrecommendedprice');
            $hideminimumprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinghideminimum');
            $hidemaximumprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinghidemaximum');
            $hidetargetprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinghidetarget');



            //Get Total Funders
            if ($gettotalfunders_with_number == '') {
                $gettotalfunders_with_number = 0;
            }
            $funders_label = get_option('cf_funder_label');
            if ($gettotalfunders_with_number != '') {
                $gettotalfunders = '<span class="price" id="cf_get_total_funders" style="float:right">' . $gettotalfunders_with_number . '<small> ' . __($funders_label, 'galaxyfunder') . '</small> </span>';
            } else {
                $gettotalfunders = '<span class="price" id="cf_get_total_funders"  style="float:right"> 0 <small>' . __($funders_label, 'galaxyfunder') . '</small> </span>';
            }
            if (get_option('cf_funders_count_show_hide') == '1') {
                $currentfunderscount = $gettotalfunders;
            }
            $remainingdaysleft_galaxyfunder = '';
            if (get_option('cf_day_left_show_hide') == '1') {
                $remainingdaysleft_galaxyfunder = FP_GF_Common_Functions::common_function_to_find_day_difference($productid);
            }
            //Single product page labels
            $targetpricelabel = get_option('crowdfunding_target_price_tab_product');
            if ($targetpricelabel != '') {
                $targetpricecaption = $targetpricelabel . $colon_symbol;
            }

            $totalpricelabel = get_option('crowdfunding_totalprice_label');
            if ($totalpricelabel != '') {
                $totalpricecaption = $totalpricelabel . $colon_symbol;
            }

            $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label');
            if ($totalpricepercentlabel != '') {
                $totalpricepercentcaption = $totalpricepercentlabel . $colon_symbol;
            }

            //Minimum Price lable
            $finalminimumpricelabel = get_option('crowdfunding_min_price_tab_product');
            //Maximum Price lable
            $finalmaximumpricelabel = get_option('crowdfunding_maximum_price_tab_product');

            $inbuilt_designs = get_option("cf_inbuilt_design");
            $load_designs = get_option('load_inbuilt_design');
            if ($inbuilt_designs == '1') {
                if ($load_designs == '1') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_single_product_contribution_table_default_css'); ?>
                    </style>
                    <?php
                }
                if ($load_designs == '2') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_single_product_contribution_table_default_css'); ?>
                    </style>
                    <?php
                }
                if ($load_designs == '3') {
                    ?>
                    <style type="text/css">
                    <?php echo get_option('cf_single_product_contribution_table_custom_css'); ?>
                    </style>
                    <?php
                }
            }
            if ($inbuilt_designs == '2') {
                ?>
                <style type="text/css">
                <?php echo get_option('cf_single_product_contribution_table_custom_css'); ?>
                </style>
            <?php }
            ?>
            <?php if (($getminbeforeformat != 0) && ($hideminimumprice != 'yes')) { ?>
                <p id="cf_min_price_label" class="price">
                    <?php echo $finalminimumpricelabel; ?> <?php echo $getminimumprice; ?>
                </p>
            <?php } if (($getmaxbeforeformat != 0) && ($hidemaximumprice != 'yes')) { ?>
                <p id='cf_max_price_label' class="price">
                    <?php echo $finalmaximumpricelabel; ?> <?php echo $getmaximumprice; ?>
                </p>
            <?php } ?>

            <?php
            $raisedamountshow = '';
            if (get_option('cf_raised_amount_show_hide') == 2) {
                $raisedamountshow = 'no';
            }
            $raisedpercentshow = '';
            if (get_option('cf_raised_percentage_show_hide') == 2) {
                $raisedpercentshow = 'no';
            }
            $daysleftshow = '';
            if (get_option('cf_day_left_show_hide') == 2) {
                $daysleftshow = 'no';
            }
            $nooffundershow = '';
            if (get_option('cf_funders_count_show_hide') == 2) {
                $nooffundershow = 'no';
            }
            ?>
            <?php
            //Start of minimal style
            if (get_option('load_inbuilt_design') == 1) {
                ?>
                <p class="price" id="cf_target_price_label">
                    <label><?php echo $targetpricecaption; ?> </label>
                    <span class="amount">
                        <span class="woocommerce-Price-amount amount">
                            <?php echo $gettargetgoal; ?>
                        </span>
                    </span>
                </p>
                <?php if ($raisedamountshow != 'no') { ?>
                    <p id="cf_total_price_raised" class="price">
                        <label><?php echo $totalpricecaption; ?>  </label>
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount"><?php echo $gettotalcontrinuted; ?></span>
                        </span>
                    </p>
                    <?php
                }
                if ($raisedpercentshow != 'no') {
                    ?>
                    <p class="price" id="cf_total_price_in_percentage">
                        <label><?php echo $totalpricepercentcaption; ?> </label><span class="amount"><?php echo $getpercentage; ?>%</span>
                    </p>
                    <?php
                }
            }

            if (get_option('single_product_prog_bar_type') == '1' && get_option('load_inbuilt_design') == 1) {
                ?>
                <div id="cf_total_price_in_percentage_with_bar" style="">
                    <div id="cf_percentage_bar" style="width: <?php echo $progress_bar_getpercentage; ?>%;">&nbsp;</div>
                </div>
            <?php } ?>
            <?php if (get_option('single_product_prog_bar_type') == '2' && get_option('load_inbuilt_design') == 1) {
                ?>
                <div class="pledgetracker" style="clear:both;">
                    <span style="width: <?php echo $progress_bar_getpercentage; ?>px;clear:both;">
                        <span class="currentpledgegoal"> </span>
                    </span>
                </div> 

                <?php
            }
            //End of minimal style
            //Start of ICG style
            if (get_option('single_product_prog_bar_type') == '1' && get_option('load_inbuilt_design') == 2) {
                if ($raisedamountshow != 'no') {
                    ?>
                    <p id="cf_total_price_raise" class="price" style="margin-bottom:0px;">
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount">
                                <?php echo $gettotalcontrinuted; ?>
                            </span>
                        </span> 
                    </p>
                    <?php
                }
                if ($raisedpercentshow != 'no') {
                    ?>
                    <p class="price" id="cf_total_raised_percentage" style=""><?php echo $getpercentage; ?>%</p>
                <?php } ?>
                <div id="cf_total_price_in_percent_with_bar" style="">
                    <div id="cf_percent_bar" style="width: <?php echo $progress_bar_getpercentage; ?>%; clear:both;"></div>
                </div> 
                <?php
            }

            if (get_option('single_product_prog_bar_type') == '2' && get_option('load_inbuilt_design') == 2) {
                if ($raisedamountshow != 'no') {
                    ?>
                    <p id="cf_total_price_raise" class="price" style="margin-bottom:0px;">
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount">
                                <?php echo $gettotalcontrinuted; ?>
                            </span>
                        </span> 
                    </p>
                    <?php
                }
                if ($raisedpercentshow != 'no') {
                    ?>
                    <p class="price" id="cf_total_raised_percentage" style=""><?php echo $getpercentage; ?>%</p>
                <?php } ?>
                <div class="pledgetracker" style="clear:both;">
                    <span style="width: <?php echo $progress_bar_getpercentage; ?>px;clear:both;">
                        <span class="currentpledgegoal">
                        </span>
                    </span>
                </div> 
                <?php
            }
            if (get_option('load_inbuilt_design') == 2) {
                if ($hidetargetprice != 'yes') {
                    ?>
                    <p class="price" id="cf_target_price_labels">
                        <label> <?php echo __('Raised of', 'galaxyfunder'); ?> </label>
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount">
                                <?php echo $gettargetgoal; ?>
                            </span>
                        </span> <?php echo $goal_label; ?>
                    </p>
                    <?php
                }
                if ($daysleftshow != 'no') {
                    ?>
                    <p id="cf_price_new_date_remain" class="price">
                        <?php echo $remainingdaysleft_galaxyfunder; ?>
                    </p>
                    <?php
                }
                if ($nooffundershow != 'no') {
                    ?>
                    <p class="price" id="cf_update_total_funders"> <?php echo $currentfunderscount; ?> </p>
                    <?php
                }
            }
            //End of minimal style
            //Start of ICG style
            if (get_option('load_inbuilt_design') == 3) {
                if ($nooffundershow != 'no') {
                    ?>
                    <p class="price" id="cf_update_total_funders">
                        <span class="totalfundercounts"> <?php echo $gettotalfunders_with_number; ?></span>
                        <small style="display:block;"> <?php echo $funders_label; ?></small>
                    </p>
                <?php } ?>
                <?php if ($raisedamountshow != 'no') { ?>
                    <p class="price" id="cf_total_price_raiser">

                        <span class="amount">
                            <span id="cf_total_pricer_raiser">
                                <span class="woocommerce-Price-amount amount">
                                    <?php echo $gettotalcontrinuted; ?>
                                </span>
                            </span>
                        </span>
                    </p> 
                    <?php
                }
                if ($hidetargetprice != 'yes') {
                    ?>
                    <p class="price" id="cf_target_price_labelers">
                        <small><label><?php echo __('Raised of', 'galaxyfunder'); ?>  </label>
                            <span class="amount"> &nbsp;
                                <span class="woocommerce-Price-amount amount">
                                    <?php echo $gettargetgoal; ?> 
                                </span> &nbsp;
                            </span> <?php echo $goal_label; ?>
                        </small>
                    </p> 
                    <?php
                }
                if ($daysleftshow != 'no') {
                    ?>
                    <p id="cf_price_new_date_remain" class="price">
                        <?php echo $remainingdaysleft_galaxyfunder; ?>
                    </p>
                    <?php
                }
            }
        }

    }

    FP_GF_Common_Functions::init();
}

function fp_wpml_multi_currency($price) {
    if (class_exists('WCML_Multi_Currency')) {// Compatible for WPML MultiCurrency Switcher
        global $woocommerce_wpml;
        $session_currency = is_object(WC()->session) ? WC()->session->get('client_currency') : get_option('woocommerce_currency');
        $site_currency = get_option('woocommerce_currency');
        $value = 1;
        if ($site_currency != $session_currency) {
            $value = $woocommerce_wpml->settings['currency_options'][$session_currency]['rate'];
            $price = $price * $value;
        }
    }
    return $price;
}

function fp_wpml_orginal_currency($price, $session_currency) {
    if (class_exists('WCML_Multi_Currency')) {// Compatible for WPML MultiCurrency Switcher
        global $woocommerce_wpml;
        $site_currency = get_option('woocommerce_currency');
        $value = 1;
        if ($session_currency) {
            if ($site_currency != $session_currency) {
                $value = $woocommerce_wpml->settings['currency_options'][$session_currency]['rate'];
                $price = $price / $value;
            }
        }
    }
    return $price;
}

function fp_wpml_multi_currency_in_cart($price, $previous_currency, $current_currency) {
    $return = $price;
    if (class_exists('WCML_Multi_Currency')) {// Compatible for WPML MultiCurrency Switcher
        global $woocommerce_wpml;
        $site_currency = get_option('woocommerce_currency');
        if ($site_currency != $previous_currency) {
            $previous_value = $woocommerce_wpml->settings['currency_options'][$previous_currency]['rate'];
            $original_amount = $price / $previous_value;
        } else {
            $original_amount = $price;
        }
        if ($site_currency != $current_currency) {
            $current_value = $woocommerce_wpml->settings['currency_options'][$current_currency]['rate'];
            $return = $original_amount * $current_value;
        } else {
            $return = $original_amount;
        }
    }
    return $return;
}

function fp_formatted_price_on_order($price, $currency) {
    if (function_exists('wc_price')) {
        $price = wc_price($price, array('currency' => $currency));
    } else {
        $price = woocommerce_price($price, array('currency' => $currency));
    }
    return $price;
}

function fp_gf_get_order_currency($order) {
    if ((float) WC()->version >= (float) '3.0.0') {
        $currency = $order->get_currency();
    } else {
        $currency = $order->get_order_currency();
    }
    return $currency;
}

function fp_gf_get_order_object($order_id) {
    if ((float) WC()->version >= (float) '2.2') {
        $order = wc_get_order($order_id);
    } else {
        $order = new WC_Order($order_id);
    }
    return $order;
}

function fp_gf_update_campaign_metas($campaign_id, $meta_key, $meta_value, $check_for_new = false) {
    if (is_plugin_active('sitepress-multilingual-cms/sitepress.php' && !$check_for_new)) {
        global $sitepress;
        $trid = $sitepress->get_element_trid($campaign_id);
        $translations = $sitepress->get_element_translations($trid);
        foreach ($translations as $translation) {
            $id_from_other_lang = $translation->element_id;
            update_post_meta($id_from_other_lang, $meta_key, $meta_value);
        }
    } else {
        update_post_meta($campaign_id, $meta_key, $meta_value);
    }
}

function fp_gf_update_order_metas_with_wpml_product_support($order_id, $campaign_id, $meta_key, $meta_value) {
    if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
        global $sitepress;
        $trid = $sitepress->get_element_trid($campaign_id);
        $translations = $sitepress->get_element_translations($trid);
        foreach ($translations as $translation) {
            $id_from_other_lang = $translation->element_id;
            update_post_meta($order_id, $meta_key . $id_from_other_lang, $meta_value);
        }
    } else {
        update_post_meta($order_id, $meta_key . $campaign_id, $meta_value);
    }
}
