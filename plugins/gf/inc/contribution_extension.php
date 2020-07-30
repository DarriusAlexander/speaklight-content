<?php

class CFContributioneextension {

    public function __construct() {
        //contribution extension table ajax and settings hooks
        add_action('wp_ajax_nopriv_updateextension', array('CFContributioneextension', 'ajax_extension'));
        add_action('wp_ajax_updateextension', array('CFContributioneextension', 'ajax_extension'));
        add_action('wp_ajax_nopriv_rejectextension', array('CFContributioneextension', 'rejectextension'));
        add_action('wp_ajax_rejectextension', array('CFContributioneextension', 'rejectextension'));
        add_filter('woocommerce_cf_settings_tabs_array', array('CFContributioneextension', 'crowdfunding_Contributioneextension_tab'), 1500);
    }

    public static function crowdfunding_Contributioneextension_tab($settings_tabs) {
        if (!is_array($settings_tabs))
            $settings_tabs = (array) $settings_tabs;
        $settings_tabs['Contribution extension'] = __('Campaign Extension Requests ', 'galaxyfunder');
        return array_filter($settings_tabs);
    }

    public static function rejectextension() {
        // echo 1;
        if (isset($_POST['productid'])) {
            $productid = $_POST['productid'];

            $compaign_modification_array = array_filter(get_option('campaign_modification_list'));


            foreach ($compaign_modification_array as $check_array) {
                if ((array_search($productid, $check_array) == true)) {
                    echo "yes";
                    unset($check_array);
                }
                $row[] = $check_array;
            }
            //var_dump($row);


            update_option('campaign_modification_list', $row);
        }
    }

    public static function ajax_extension() {
        if (isset($_POST['productid'])) {
            $productid = $_POST['productid'];
            $newgoal = $_POST['newgoal'];
            $newquantity = $_POST['newquantity'];
            $endmethod = $_POST['endmethod'];
            $newdate = $_POST['newdate'];
            $newhour = $_POST['newhour'];
            $newminutes = $_POST['newminutes'];
            $newdescription = $_POST['newdescription'];
            $compaign_modification_array = array_filter(get_option('campaign_modification_list'));
            foreach ($compaign_modification_array as $check_array) {
                if ((array_search($productid, $check_array) == true)) {
                    //  echo "yes";
                    unset($check_array);
                }
                $row[] = @$check_array;
            }
            update_option('campaign_modification_list', $row);
            if ($newdate == '') {
//echo "empty";
            } else {
                fp_gf_update_campaign_metas($productid, '_crowdfundingtodatepicker', $newdate);
                fp_gf_update_campaign_metas($productid, '_crowdfundingtohourdatepicker', $newhour);
                fp_gf_update_campaign_metas($productid, '_crowdfundingtominutesdatepicker', $newminutes);
            }
            $my_post = array(
                'ID' => $productid,
                'post_content' => $newdescription,
            );
            wp_update_post($my_post);
            fp_gf_update_campaign_metas($productid, '_crowdfundinggettargetprice', $newgoal);
            fp_gf_update_campaign_metas($productid, '_target_end_selection', $endmethod);
            fp_gf_update_campaign_metas($productid, '_crowdfundingquantity', $newquantity);
            fp_gf_update_campaign_metas($productid, 'out_of_stock_check', '');


            // echo 1;
        }
    }

// $comp_modification = get_option('list_of_campaign_modification');
//echo $product_id;
//        foreach ($comp_modification as $innerarrays) {
//            //unset($comp_modification['2']);
//            //var_dump($comp_modification);
//
//            foreach ($innerarrays as $labels => $values) {
//
//                $product_id = $innerarrays['productid'];
//                $existendmethod = $innerarrays['existendmethod'];
//                $newendmethod = $innerarrays['newendmethod'];
//                $existgoal = $innerarrays['existgoal'];
//
//                $new_goal = $innerarrays['newgoal'];
//            }
//        }

    public static function cf_contribution_extension_table() {
        $array_position = '0';
        ?>
        <br>
        <h3><?php echo __('Campaign Extension Requests', 'galaxyfunder'); ?></h3>
        <?php
        echo FP_GF_Common_Functions::common_function_for_search_box();
        ?>
        <table class="wp-list-table widefat fixed posts" data-filter = "#filter" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next" id="campaign_monitor" cellspacing="0">
            <thead>
                <tr>
                    <th scope='col' id='campaign_name' class='manage-column column-campaign_name'  style=""><?php _e('S.No', 'galaxyfunder'); ?></th>
                    <th scope='col' id='campaign_name' class='manage-column column-campaign_name'  style=""><?php _e('Product Name', 'galaxyfunder'); ?></th>
                    <th scope='col' id='campaign_creator' data-hide="phone" class='manage-column column-campaign_creator'  style=""><?php _e('Existing End Method', 'galaxyfunder'); ?></th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span> <?php _e('New Description', 'galaxyfunder'); ?></span>

                        </a>
                    </th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span><?php _e('New End Method', 'galaxyfunder'); ?></span>

                        </a>
                    </th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span> <?php _e('New End Date', 'galaxyfunder'); ?></span>

                        </a>
                    </th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span> <?php _e('Existing Goal', 'galaxyfunder'); ?></span>

                        </a>
                    </th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span><?php _e('New Goal', 'galaxyfunder'); ?></span>

                        </a>
                    </th>

                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span><?php _e('Existing Quantity', 'galaxyfunder'); ?></span>

                        </a>
                    </th>
                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span><?php _e('New Quantity', 'galaxyfunder'); ?></span>

                        </a>
                    </th>

                    <th scope='col' id='campaign_created_date' data-hide="phone" class='manage-column column-campaign_created_date'  style="">
                        <a href="#">                            <span><?php _e('Options', 'galaxyfunder'); ?></span>

                        </a>
                    </th>



            </thead>
            <?php
            $product_id = '';
            $existendmethod = '';
            $new_endmethod_show = '';
            $newdate = '';
            $newhour = '';
            $newminutes = '';
            $newdescription = '';
            $existgoal = '';
            $new_goal = '';
            $newquantity = '';
            $existingquantity = '';
            $x = 0;

            $comp_modification_new = get_option('campaign_modification_list');
            //$test = wp_list_pluck($comp_modification, 'productid');
            //var_dump($test);
            if (is_array($comp_modification_new)) {
                //var_dump($comp_modification_new);
                if (!is_null($comp_modification_new)) {
                    // echo 1;
                    // var_dump($comp_modification_new);

                    foreach ($comp_modification_new as $innerarrays_new) {
                        //var_dump($innerarrays_new);
//                        echo "<pre>";
//                        var_dump($innerarrays_new);
//                        echo "</pre>";
                        if (is_array($innerarrays_new)) {

                            foreach ($innerarrays_new as $labels => $val) {
                                $product_id = $innerarrays_new['productid'];
                                $existendmethod = $innerarrays_new['existendmethod'];
                            }
                            //echo $existendmethod;
                            if ($existendmethod == 'Target Goal') {
                                $contributed_amount1 = $order_total = get_post_meta($product_id, '_crowdfundingtotalprice', true);
                                $contributed_amount = fp_wpml_multi_currency($contributed_amount1);
                                $target_value_array1 = get_post_meta($product_id, '_crowdfundinggettargetprice', true);
                                $target_end_amount = fp_wpml_multi_currency($target_value_array1);
                                if ($contributed_amount > $target_end_amount) {

                                    $compaign_modification_array = array_filter(get_option('campaign_modification_list'));


                                    foreach ($compaign_modification_array as $check_array) {
                                        //echo "entered";
                                        if ((array_search($product_id, $check_array) == true)) {
                                            //echo "if";
                                            unset($check_array);
                                        }
                                        $row[] = @$check_array;
                                    }
                                    update_option('campaign_modification_list', $row);
                                    //echo '<td id=8>Campaign closed</td>';
                                    //$x++;
                                }
                            } elseif ($existendmethod == 'Target Date') {
                                // echo 1;
                                $datestr = get_post_meta($product_id, '_crowdfundingtodatepicker', true) . " 23:59:59"; //Your date
                                $date = strtotime($datestr); //Converted to a PHP date (a second count)
                                $local_current_time = strtotime(FP_GF_Common_Functions::date_time_with_format());
                                if (get_post_status($product_id) == 'publish') {
                                    if ($date >= $local_current_time) {
                                        $diff = $date - $local_current_time; //time returns current time in seconds
                                        $days = floor($diff / (60 * 60 * 24)); //seconds/minute*minutes/hour*hours/day)
                                        //$hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                        //Report
                                        if ($days > 1) {
                                            //_e($days . "days to go", "galaxyfunder");
                                            // echo $days . __('days to go', 'galaxyfunder');
                                        } else {
                                            // _e($days . "day to go", "galaxyfunder");
                                            //echo $days . __('day to go', 'galaxyfunder');
                                        }
                                    } else {
                                        // echo 2;
                                        //echo $status_inc;
                                        //_e("Campaign Closed", "galaxyfunder");
                                        //echo "campaign closed";
                                        $compaign_modification_array = array_filter(get_option('campaign_modification_list'));
                                        foreach ($compaign_modification_array as $check_array) {
                                            if ((array_search($product_id, $check_array) == true)) {
                                                //echo "yes";
                                                unset($check_array);
                                            }
                                            $row[] = @$check_array;
                                        }
                                        update_option('campaign_modification_list', $row);
                                        //echo __('Campaign Closed', 'galaxyfunder');
                                        // echo '<td id=9>campaign closed</td>';
                                        //$x++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $comp_modification_new = get_option('campaign_modification_list');
            if (is_array($comp_modification_new)) {
                //var_dump($comp_modification_new);
                if (!is_null($comp_modification_new)) {
                    //echo 1;
                    // var_dump($comp_modification_new);
                    foreach ($comp_modification_new as $innerarrays_new) {
                        //var_dump($comp_modification_new);
//                        echo "<pre>";
//                        var_dump($innerarrays_new);
//                        echo "</pre>";
                        if (is_array($innerarrays_new)) {
                            foreach ($innerarrays_new as $labels => $val) {

                                $product_id = $innerarrays_new['productid'];
                                $newdescription = $innerarrays_new['newdescription'];
                                $existendmethod = $innerarrays_new['existendmethod'];
                                $newendmethod = $innerarrays_new['newendmethod'];
                                $newdate = $innerarrays_new['newdate'];
                                $newhour = $innerarrays_new['newhour'];
                                $newminutes = $innerarrays_new['newminutes'];
                                $existgoal = $innerarrays_new['existgoal'];
                                $existingquantity = $innerarrays_new['existingquantity'];
                                $new_goal = $innerarrays_new['newgoal'];
                                $newquantity = $innerarrays_new['newquantity'];
                                if ($newendmethod == 3) {
                                    $new_endmethod_show = __('Target Goal', 'galaxy funder');
                                    $status = "---";
                                } if ($newendmethod == 2) {
                                    $new_endmethod_show = __('Campaign Never Ends', 'galaxy funder');
                                    $status = "---";
                                } if ($newendmethod == 5) {
                                    $new_endmethod_show = __('Target Quantity', 'galaxy funder');
                                    $status = "---";
                                }
//                                if($newquantity != ''){
//                                      $new_endmethod_show = __('Target Quantity', 'galaxy funder');
//                                       fp_gf_update_campaign_metas($product_id,'_target_end_selection','5');
//                                        $status = "---";
//                                }
                                if ($newendmethod == 1) {
                                    $new_endmethod_show = __('Target Date', 'galaxy funder');
                                    $status = $newdate . " " . $newhour . " : " . $newminutes;
                                }
                            }
                            $x++;
                            ?>

                            <tr class="row_id_<?php echo $product_id; ?>" id="row_id_<?php echo $product_id; ?>" >
                                <td  class='serial_id'  id='serial_id'><?php echo $x; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo get_the_title($product_id); ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $existendmethod; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $newdescription; ?>
                                </td>

                                <td  class='serial_id'  id='serial_id'><?php echo $new_endmethod_show; ?>
                                </td>

                                <td  class='serial_id'  id='serial_id'><?php echo $status; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $existgoal; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $new_goal; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $existingquantity; ?>
                                </td>
                                <td  class='serial_id'  id='serial_id'><?php echo $newquantity; ?>
                                </td>

                                <td  class='serial_id'   id='serial_id'> <input type="button" class="accept_extension_<?php echo $product_id ?>" id="accept_extension_<?php echo $product_id ?>" data-arrayposition="<?php echo $array_position; ?>" data-newdescription="<?php echo $newdescription; ?>" data-newdate="<?php echo $newdate ?>" data-newhour="<?php echo $newhour; ?>" data-newminutes="<?php echo $newminutes; ?>"  data-productid="<?php echo $product_id; ?>" data-newgoal="<?php echo $new_goal; ?>" data-newquantity="<?php echo $newquantity; ?>" data-endmethod="<?php echo $newendmethod ?>"  name="accept_extension"  value="Accept"> &nbsp;&nbsp;
                                    <input type="button" data-arrayposition_reject="<?php echo $array_position; ?>" data-productid="<?php echo $product_id; ?>" class="reject_extension" id="reject_extension_<?php echo $product_id ?>" name="reject_extension " value="Reject">
                                </td>
                            </tr>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {


                                    jQuery("#accept_extension_<?php echo $product_id ?>").click(function () {
                                        // alert(1);
                                        // $("#campaign_monitor .row_id").hide();
                                        jQuery('#row_id_<?php echo $product_id ?>').css({opacity: '0.5', filter: 'alpha(opacity=50)'});
                                        var productid = jQuery(this).data('productid');
                                        var newgoal = jQuery(this).data('newgoal');
                                        var newquantity = jQuery(this).data('newquantity');
                                        var endmethod = jQuery(this).data('endmethod');
                                        var newdate = jQuery(this).data('newdate');
                                        var newhour = jQuery(this).data('newhour');
                                        var newminutes = jQuery(this).data('newminutes');
                                        var newdescription = jQuery(this).data('newdescription');
                                        var arrayposition = jQuery(this).data('arrayposition');

                                        //buttonid = jQuery(".accept_extension").thisid();
                                        var dataparam = ({
                                            action: 'updateextension',
                                            productid: productid,
                                            newgoal: newgoal,
                                            newquantity: newquantity,
                                            endmethod: endmethod,
                                            newdate: newdate,
                                            newhour: newhour,
                                            newminutes: newminutes,
                                            newdescription: newdescription
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    //alert(response);
                                                    //alert(response);
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                        //jQuery('.perkrule').removeClass('selected');
                                                        // jQuery('.noperk').addClass('selected');
                                                        //jQuery(this).addClass('selected');
                                                    }
                                                });
                                    });
                                });
                            </script>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    jQuery("#reject_extension_<?php echo $product_id ?>").click(function () {
                                        //alert(2);

                                        jQuery('#row_id_<?php echo $product_id ?>').css({opacity: '0.5', filter: 'alpha(opacity=50)'});
                                        var productid = jQuery(this).data('productid');

                                        var dataparam = ({
                                            action: 'rejectextension',
                                            productid: productid
                                        });
                                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                                function (response) {
                                                    //alert(response);
                                                    //alert(response);
                                                    var newresponse = response.replace(/\s/g, '');
                                                    if (newresponse === 'success') {
                                                        //jQuery('.perkrule').removeClass('selected');
                                                        // jQuery('.noperk').addClass('selected');
                                                        //jQuery(this).addClass('selected');
                                                    }

                                                });

                                    });

                                });
                            </script>
                            <?php
                        }
                    }
                }
            }
            // echo $x;
            ?>
            <tfoot>
                <tr style="clear:both;">
                    <td colspan="11">
                        <div class="pagination pagination-centered"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php
    }

}

new CFContributioneextension();
