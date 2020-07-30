<?php
if (!class_exists('CFCampaignslist')) {

    class CFCampaignslist {

        public static function init() {
            //Adding list table in settings array
            add_filter('woocommerce_cf_settings_tabs_array', array(__CLASS__, 'crowdfunding_admin_new_tab'), 150);
        }

        public static function crowdfunding_admin_new_tab($settings_tabs) {
            if (!is_array($settings_tabs)) {
                $settings_tabs = (array) $settings_tabs;
            }
            $settings_tabs['crowdfunding_listtable'] = __('Campaigns', 'galaxyfunder');
            return $settings_tabs;
        }

        public static function get_list_of_all_campaigns() {
            $args = array(
                'orderby' => 'post_date',
                'meta_query' => array(
                    array(
                        'key' => '_crowdfundingcheckboxvalue',
                        'value' => 'yes',
                    )
                ),
                'order' => 'ASC',
                'post_type' => 'product',
                'posts_per_page' => '-1',
                'no_found_rows' => true,
                'update_post_term_cache' => false,
                'update_post_post_cache' => false,
                'cache_results' => false,
            );
            return get_posts($args);
        }

        public static function crowdfunding_adminpage() {
            $listids = self::get_list_of_all_campaigns();

            echo FP_GF_Common_Functions::common_function_for_search_box();
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('.example').footable().bind('footable_filtering', function (e) {
                        var selected = jQuery('.filter-status').find(':selected').text();
                        if (selected && selected.length > 0) {
                            e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                            e.clear = !e.filter;
                        }
                    });
                    jQuery('#change-page-size').change(function (e) {
                        e.preventDefault();
                        var pageSize = jQuery(this).val();
                        jQuery('.footable').data('page-size', pageSize);
                        jQuery('.footable').trigger('footable_initialized');
                    });

                });
            </script>
            <style type="text/css">
                p.submit {
                    display:none;
                }
                #mainforms {
                    display:none;
                }
            </style>
            <table class="example wp-list-table widefat fixed posts" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="campaign_monitor" cellspacing="0">
                <thead>
                <th data-toggle="true"><?php _e('S.No', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Campaign Name', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Campaign Creator', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Date', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Target Goal/Target Quantity', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Raised', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Raised %', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Funders', 'galaxyfunder'); ?></th>
                <th data-toggle="true"><?php _e('Status', 'galaxyfunder'); ?></th>
            </thead>
            <tbody>
                <?php
                //var_dump($listids);
                $i = 1;
                if (is_array($listids)) {
                    foreach ($listids as $value) {
                        if (isset($value)) {
                            $productid = $value->ID;

                            $funders = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_update_total_funders');
                            $target_id = get_post_meta($productid, '_target_end_selection', true);
                            $target_end_method = FP_GF_Common_Functions::target_end_method_fn($target_id);
                            $total_target_qty = get_post_meta($productid, '_crowdfundingquantity', true);
                            $gettotalraisedamount = fp_wpml_multi_currency(get_post_meta($productid, '_crowdfundingtotalprice', true));
                            //echo $target_id;
                            if ($target_id == '5') {
                                $saled_qty = get_post_meta($productid, '_gf_saled_qty', true);

                                if ($saled_qty != '') {
                                    $remaining_qty = $total_target_qty - $saled_qty;
                                } else {
                                    $remaining_qty = '0';
                                }
                            }
                            ?>
                            <tr class="<?php echo $i % 2 == '0' ? 'alternate' : ''; ?>">
                                <td><?php echo $i; ?></td>
                                <?php
                                if ($gettotalraisedamount != 0) {
                                    ?>
                                    <td class="campaign_creator_name">
                                        <a href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=crowdfunding_callback&tab=perk_info&order_id=' . $productid); ?>" >
                                            <?php
                                            echo $value->post_title;
                                            ?>
                                        </a>
                                    </td>
                                    <?php
                                }
                                if ($gettotalraisedamount == 0) {
                                    ?>
                                    <td class="campaign_creator_name">
                                        <?php
                                        echo $value->post_title;
                                        ?>
                                        </a>
                                    </td>

                                <?php } ?>

                                <td>
                                    <?php
                                    $author_id = $value->post_author;
                                    echo the_author_meta('user_nicename', $author_id);
                                    ?>
                                </td>
                                <td><?php echo $value->post_modified; ?></td>
                                <td><?php
                                    if ($target_id == 5) {
                                        echo $total_target_qty;
                                    } else {
                                        echo fp_wpml_multi_currency(FP_GF_Common_Functions::format_price_in_proper_order(FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggettargetprice')));
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    if ($target_id == 5) {
                                        echo $saled_qty;
                                    } else {
                                        echo fp_wpml_multi_currency(FP_GF_Common_Functions::format_price_in_proper_order(FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundingtotalprice')));
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($target_id == 5) {
                                        $count1 = $saled_qty / $total_target_qty;
                                        $count2 = $count1 * 100;
                                        $counter = number_format($count2, 0);
                                        $count = $counter;
                                        echo $count;
                                    } else {
                                        $totalpercentage = FP_GF_Common_Functions::get_galaxy_funder_post_meta($productid, '_crowdfundinggoalpercent');
                                        echo $totalpercentage = $totalpercentage != '' ? $totalpercentage : '0';
                                    }
                                    ?>%
                                </td>
                                <td><?php echo $funders = $funders != '' ? $funders : '0';
                                    ; ?></td>
                                <td><?php echo FP_GF_Common_Functions::status_checker_switch_statement($value->post_status, $productid); ?></td>
                            </tr>

                            <?php
                        }
                        $i++;
                    }
                }
                ?>
            </tbody>
            </table>
            <div class="pagination pagination-centered"></div>
            <?php
        }

    }

    CFCampaignslist::init();
}
