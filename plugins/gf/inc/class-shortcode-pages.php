<?php
/*
 * Shortcode Related Functionality
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('FP_GF_Shortcode_Functions')) {
    /**
     *Shortcode Class.
     */
    final class FP_GF_Shortcode_Functions {    
        public static function init() {
            //Available Short code pages
            add_shortcode('galaxyfunder_my_campaign', array(__CLASS__, 'cf_list_user_campaigns'));
            add_shortcode('galaxyfunder_all_campaign_list', array(__CLASS__, 'list_all_users_campaign'));
            add_shortcode('galaxyfunder_running_campaigns', array(__CLASS__, 'list_all_users_running_campaign'));
            add_shortcode('galaxyfunder_closed_campaigns', array(__CLASS__, 'list_all_users_closed_campaign'));
            add_shortcode('galaxyfunder_campaign', array(__CLASS__, 'cf_shortcode_extract_code'));
        }
        
        public static function common_function_for_fetch_query($user_id){
              
                $args = array('post_type' => 'product',
                    'posts_per_page' => '-1',
                    'author' => $user_id,
                    'post_status' => 'draft,publish',
                    'no_found_rows' => true,
                    'update_post_term_cache' => false,
                    'update_post_post_cache' => false,
                    'cache_results' => false,
                );
                $listmycampaign = new WP_Query($args);
                return $listmycampaign;
        }
        
        public static function cf_list_user_campaigns() {
            ob_start();
            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();
                $listmycampaign = self::common_function_for_fetch_query($user_ID);
                if ($listmycampaign->have_posts()) {
                    while ($listmycampaign->have_posts()) {
                        $listmycampaign->the_post();
                        $newid = get_the_ID();
                        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                        self::common_function_for_shortcode_campaigns($checkvalue, $newid);
                    }
                } else {
                    echo "Sorry No Campaigns Found";
                }
                wp_reset_postdata();
            } else {
                echo "Please Login to see your Campaign";
            }
            $newflush = ob_get_contents();
            ob_end_clean();
            return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
        }

        public static function list_all_users_campaign() {
            ob_start();
            if (is_user_logged_in()) {
                 $listmycampaign = self::common_function_for_fetch_query('');
                if ($listmycampaign->have_posts()) {
                    while ($listmycampaign->have_posts()) {
                        $listmycampaign->the_post();
                        $newid = get_the_ID();
                        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                        self::common_function_for_shortcode_campaigns($checkvalue, $newid);
                    }
                } else {
                    echo "Sorry No Campaigns Found";
                }
                wp_reset_postdata();
            } else {
                echo "Please Login to see your Campaign";
            }
            $newflush = ob_get_contents();
            ob_end_clean();
            return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
        }
        
        public static function list_all_users_running_campaign() {
            ob_start();
            if (is_user_logged_in()) {
                 $listmycampaign = self::common_function_for_fetch_query('');
                if ($listmycampaign->have_posts()) {
                    while ($listmycampaign->have_posts()) {
                        $listmycampaign->the_post();
                        $newid = get_the_ID();
                        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                        if (get_post_meta($newid, '_stock_status', true) == 'instock') {
                            self::common_function_for_shortcode_campaigns($checkvalue, $newid);
                        }
                    }
                } else {
                    echo __("Sorry No Campaigns Found", 'galaxyfunder');
                }
                wp_reset_postdata();
            } else {
                echo __("Please Login to see your Campaign");
            }
            $newflush = ob_get_contents();
            ob_end_clean();
            return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
        }
        
        public static function list_all_users_closed_campaign() {
            ob_start();
            if (is_user_logged_in()) {
                 $listmycampaign = self::common_function_for_fetch_query('');
                if ($listmycampaign->have_posts()) {
                    while ($listmycampaign->have_posts()) {
                        $listmycampaign->the_post();
                        $newid = get_the_ID();
                        $checkvalue = get_post_meta($newid, '_crowdfundingcheckboxvalue', true);
                        if (get_post_meta($newid, '_stock_status', true) == 'outofstock') {
                            self::common_function_for_shortcode_campaigns($checkvalue, $newid);
                        }
                    }
                } else {
                    echo __("Sorry No Campaigns Found", 'galaxyfunder');
                }
                wp_reset_postdata();
            } else {
                echo __("Please Login to see your Campaign");
            }
            $newflush = ob_get_contents();
            ob_end_clean();
            return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
        }

        public static function cf_shortcode_extract_code($atts) {
            if (!is_shop()) {
                $content = '';
                extract(shortcode_atts(array(
                    'id' => '',
                                ), $atts));
                ob_start();
                $checkvalue = get_post_meta($id, '_crowdfundingcheckboxvalue', true);
                self::common_function_for_shortcode_campaigns($checkvalue, $id);
                $newflush = ob_get_contents();
                ob_end_clean();
                return '<div class="woocommerce" ><ul class="products" >' . $newflush . '</ul></div>';
            }
        }
        
        public static function common_function_for_progressbar($post_id, $flag) {
            //variable declaration
            $total_qty = '';
            $remainingdays = '';
            $sin_mul_remainingdays = '';
            $remainingdaysleft_galaxyfunder = '';
            $checkinstock = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_stock_status');
            //Get Target End selection
            $gettargetendselection = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_target_end_selection');
            
            //Get contributed amount
            $gettotalcontribution_goal = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundingtotalprice'));
            $gettotalcontribution_goal_org = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundingtotalprice'));
            $gettotalcontribution_goal_value = $gettotalcontribution_goal == '' ? '0' : $gettotalcontribution_goal;
            $gettotalcontribution_goal = FP_GF_Common_Functions:: format_price_in_proper_order($gettotalcontribution_goal_value);
            
            //Get target goal
            $gettargetgoal1 = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinggettargetprice');
            $gettargetgoal = fp_wpml_multi_currency($gettargetgoal1);
            $gettargetgoal_value = $gettargetgoal == '' ? '0' : $gettargetgoal;
            $gettargetgoal = FP_GF_Common_Functions:: format_price_in_proper_order($gettargetgoal, $gettargetgoal_value);
           
            //Get percentage
            $getpercentage = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinggoalpercent');
            
            //Get Total Funders
            $gettotalfunders_with_number = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_update_total_funders');

            //Get minimum maximum labels
            $getminimumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinggetminimumprice'));
            $getminimumprice = $getminimumprice == '' ? '0' : $getminimumprice;
            $getminbeforeformat = $getminimumprice;
            $getmaximumprice = fp_wpml_multi_currency(FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinggetmaximumprice'));
            $getmaximumprice = $getmaximumprice == '' ? '0' : $getmaximumprice;
            $getmaxbeforeformat = $getmaximumprice;
            $getminimumprice = FP_GF_Common_Functions:: format_price_in_proper_order($getminimumprice);
            $getmaximumprice = FP_GF_Common_Functions:: format_price_in_proper_order($getmaximumprice);

            //Get minimum maximum recommended prices
            $hideminimumprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinghideminimum');
            $hidemaximumprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinghidemaximum');
            $hidetargetprice = FP_GF_Common_Functions::get_galaxy_funder_post_meta($post_id, '_crowdfundinghidetarget');

            //Target Quantity check and functionalities
            if ($gettargetendselection == '5') {
                $total_qty = get_post_meta($post_id, '_crowdfundingquantity', true);
                $remaining_qty = get_post_meta($post_id, 'remaining_qty', true);
                $saled_qty = get_post_meta($post_id, '_gf_saled_qty', true);
                if ($saled_qty != '') {
                    $saled_qty = $saled_qty;
                } else {
                    $saled_qty = '0';
                }
                $count = $saled_qty / $total_qty;
                $getpercentage = number_format($count * 100);
                $gettargetgoal=$total_qty;
                $gettotalcontribution_goal_org=$saled_qty;
                $gettotalcontrinuted=$saled_qty;
                $goal_label=__('Quantity', 'galaxyfunder');
            } else {
                $getpercentage = FP_GF_Common_Functions::update_percentage_value($gettargetgoal_value, $gettotalcontribution_goal_value, $post_id);
                $goal_label=__('Goal', 'galaxyfunder');
                $gettotalcontrinuted = $gettotalcontribution_goal;
            }
            $getpercentage_before_validate = $getpercentage == '' ? '0' : $getpercentage;
            if($getpercentage_before_validate>100){
                $progress_bar_getpercentage=100;
            }else{
                $progress_bar_getpercentage=$getpercentage_before_validate;
            }
            
            //Get Total Funders
            if ($gettotalfunders_with_number == '') {
                $gettotalfunders_with_number = 0;
            }
            $funders_label= get_option('cf_funder_label_shop');
            if ($gettotalfunders_with_number != '') {
                $gettotalfunders = '<span class="price" id="cf_get_total_funders" style="float:right">' . $gettotalfunders_with_number . '<small> ' . __($funders_label, 'galaxyfunder') . '</small> </span>';
            } else {
                $gettotalfunders = '<span class="price" id="cf_get_total_funders"  style="float:right"> 0 <small>' . __($funders_label, 'galaxyfunder') . '</small> </span>';
            }
            $currentfunderscount='';
            if (get_option('cf_funders_count_show_hide_shop') == '1') {
                $currentfunderscount = $gettotalfunders;
            }
         
            if (get_option('cf_day_left_show_hide_shop') == '1') {
               $remainingdaysleft_galaxyfunder=FP_GF_Common_Functions::common_function_to_find_day_difference($post_id);
            }
              
       
             $raisedamountshow='';
             $raisedpercentshow='';
             $daysleftshow='';
             $nooffundershow='';
             $description_position='';
            if ($flag == 'shop') {
                $finalminimumpricelabel = get_option('crowdfunding_min_price_shop_page');
                $finalmaximumpricelabel = get_option('crowdfunding_maximum_price_shop_page');
                $targetpricelabel = get_option('crowdfunding_target_price_shop_page');
                $totalpricelabel = get_option('crowdfunding_totalprice_label_shop_page');
                $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label_shop_page');
                $inbuilt_designs = get_option("cf_inbuilt_shop_design");
                $load_design = get_option('load_inbuilt_shop_design');
                $progress_bar_type = get_option('shop_page_prog_bar_type');
                $default_css_script = get_option('cf_shop_page_contribution_table_default_css');
                $custom_css_script = get_option('cf_shop_page_contribution_table_custom_css');
                if (get_option('cf_raised_amount_show_hide_shop') == 2) {
                    $raisedamountshow = 'no';
                }
                if (get_option('cf_raised_percentage_show_hide_shop') == 2) {
                    $raisedpercentshow = 'no';
                }
                if (get_option('cf_day_left_show_hide_shop') == 2) {
                    $daysleftshow = 'no';
                }
                if (get_option('cf_funders_count_show_hide_shop') == 2) {
                    $nooffundershow = 'no';
                }
                
                $description_position = get_option('crowdfunding_description_on_shop_page');
                
                
            } else if ($flag == 'shortcode') {
                $finalminimumpricelabel = get_option('crowdfunding_min_price_shop_page_shortcode');
                $finalmaximumpricelabel = get_option('crowdfunding_maximum_price_shop_page_shortcode');
                $targetpricelabel = get_option('crowdfunding_target_price_shop_page_shortcode');
                $totalpricelabel = get_option('crowdfunding_totalprice_label_shop_page_shortcode');
                $totalpricepercentlabel = get_option('crowdfunding_totalprice_percent_label_shop_page_shortcode');
                $inbuilt_designs = get_option("cf_inbuilt_shop_design_shortcode");
                $load_design = get_option('load_inbuilt_shop_design_shortcode');
                $progress_bar_type = get_option('shortcode_page_prog_bar_type');
                $default_css_script = get_option('cf_shop_page_contribution_table_default_css_shortcode');
                $custom_css_script = get_option('cf_shop_page_contribution_table_custom_css_shortcode');
                if (get_option('cf_raised_amount_show_hide_shortcode') == 2) {
                    $raisedamountshow = 'no';
                }
                if (get_option('cf_raised_percentage_show_hide_shortcode') == 2) {
                    $raisedpercentshow = 'no';
                }
                if (get_option('cf_day_left_show_hide_shortcode') == 2) {
                    $daysleftshow = 'no';
                }
                if (get_option('cf_funders_count_show_hide_shortcode') == 2) {
                    $nooffundershow = 'no';
                }
                
            }
           
            $colon_symbol = " : ";
            //Single product page labels
            if ($targetpricelabel != '') {
                $targetpricecaption = $targetpricelabel . $colon_symbol;
            }

            if ($totalpricelabel != '') {
                $totalpricecaption = $totalpricelabel . $colon_symbol;
            }

            if ($totalpricepercentlabel != '') {
                $totalpricepercentcaption = $totalpricepercentlabel . $colon_symbol;
            }

            if ($inbuilt_designs == '1') {
                ?>
                <style type="text/css">
                <?php echo $default_css_script; ?>
                </style>
                <?php
            }
            if ($inbuilt_designs == '2') {
                ?>
                <style type="text/css">
                <?php echo $custom_css_script; ?>
                </style>
            <?php }
            ?>
            <div class='' >     
            <?php if (($getminbeforeformat != 0) && ($hideminimumprice != 'yes')) { ?>
                                            <p id="cf_min_price_label" class="price">
                <?php echo $finalminimumpricelabel; ?> <?php echo $getminimumprice; ?>
                                            </p>
            <?php } if (($getmaxbeforeformat != 0) && ($hidemaximumprice != 'yes')) { ?>
                                            <p id='cf_max_price_label' class="price">
                <?php echo $finalmaximumpricelabel; ?> <?php echo $getmaximumprice; ?>
                                            </p>
            <?php } ?>
            </div>
            <?php
            //Start of minimal style
            if ($load_design == 1) {
             
                if($flag == 'shop' && $description_position=='above_stylebar' ) {
                    echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
                }
                    
                
                if($hidetargetprice!='yes') {
                   
                ?>
                    <p class="price" id="cf_target_price_label">
                        <label><?php echo $targetpricecaption; ?> </label>
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount"><?php echo $gettargetgoal; ?></span>
                        </span>
                    </p>
                    <?php
                }
                    if($gettotalcontribution_goal_org!=0 && $raisedamountshow!='no'){ ?>
                    <p class="price" id="cf_total_price_raised">
                        <label><?php echo $totalpricecaption; ?>  </label>
                        <span class="amount">
                            <span class="woocommerce-Price-amount amount"><?php echo $gettotalcontribution_goal; ?></span>
                        </span>
                    </p>
                    <?php }
                    if($raisedpercentshow!='no'){
                    ?>
                    <p class="price" id="cf_total_price_in_percentage">
                        <label><?php echo $totalpricepercentcaption; ?> </label>
                        <span class="amount"><?php echo $getpercentage_before_validate; ?>%</span>
                    </p>
                <?php
                
                    }
            }

            if ($progress_bar_type == '1' && $load_design == 1) {
                ?>
                        <div id="cf_total_price_in_percentage_with_bar" style="">
                            <div id="cf_percentage_bar" style="width: <?php echo $progress_bar_getpercentage; ?>%;">&nbsp;</div>
                        </div>
            <?php } ?>
            <?php if ($progress_bar_type == '2' && $load_design == 1) { ?>
                        <div class="pledgetracker" style="clear:both;">
                            <span style="width: <?php echo $progress_bar_getpercentage; ?>px;clear:both;">
                                <span class="currentpledgegoal"> </span>
                            </span>
                        </div> 

                <?php
            }
            
            if($load_design == 1 && $flag == 'shop' && $description_position=='below_stylebar') {
                echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
            }

            //End of minimal style
            //Start of ICG style
            if( $load_design == 2 && $flag == 'shop' && $description_position=='above_stylebar') {
                echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
            }
            if ($progress_bar_type == '1' && $load_design == 2) {
                
               if($hidetargetprice!='yes') {
                ?>
                        <p id="cf_total_price_raise" class="price" style="margin-bottom:0px;">
                            <span class="amount">
                                <span class="woocommerce-Price-amount amount">
                        <?php echo $gettargetgoal; ?>
                                </span>
                            </span> 
                        </p>
               <?php } if($raisedpercentshow!='no'){?>
                        <p class="price" id="cf_total_raised_percentage" style=""><?php echo $getpercentage_before_validate; ?>%</p>
                        <?php } ?>        
                <div id="cf_total_price_in_percent_with_bar" style="">
                            <div id="cf_percent_bar" style="width: <?php echo $progress_bar_getpercentage; ?>%; clear:both;"></div>

                        </div> 
            <?php
            }

            if ($progress_bar_type == '2' && $load_design == 2) {
                if($hidetargetprice!='yes') {
                ?>
                        <p id="cf_total_price_raise" class="price" style="margin-bottom:0px;">
                            <span class="amount">
                                <span class="woocommerce-Price-amount amount">
                        <?php echo $gettargetgoal; ?>
                                </span>
                            </span> 
                        </p>
                <?php }  if($raisedpercentshow!='no'){?>
                        <p class="price" id="cf_total_raised_percentage" style=""><?php echo $getpercentage_before_validate; ?>%</p>
                           <?php } ?>
                        <div class="pledgetracker" style="clear:both;">
                            <span style="width: <?php echo $getpercentage_before_validate; ?>px;clear:both;">
                                <span class="currentpledgegoal">
                                </span>
                            </span>
                        </div> 

            <?php
            }
            
            if ($load_design == 2) {
                if($daysleftshow!='no'){
                ?>
                        <p id="cf_price_new_date_remain" class="price">
                        <?php echo $remainingdaysleft_galaxyfunder; ?>
                        </p>
                <?php } ?>
                        <?php if($nooffundershow!='no'){ ?>
                        <p class="price" id="cf_update_total_funders"> <?php echo $currentfunderscount; ?> </p>
                <?php
                }
            }
            if( $load_design == 2 && $flag == 'shop' && $description_position=='below_stylebar') {
                echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
            }
            //End of ICG style
            //Start of KS style
            if( $load_design == 3 && $flag == 'shop' && $description_position=='above_stylebar') {
                echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
            }
            if ($progress_bar_type == '1' && $load_design == 3) {
                ?>
                        <div id="cf_total_price_in_percenter_with_bar" style="float:left">
                            <div id="cf_percenter_bar" style="width: <?php echo $progress_bar_getpercentage; ?>%;"></div>
                        </div>
                                                        
                                                        
            <?php
            }

            if ($progress_bar_type == '2' && $load_design == 3) {
                ?>
                        <div class="pledgetracker" style="clear:both;">
                            <span style="width: <?php echo $progress_bar_getpercentage; ?>px;clear:both;">
                                <span class="currentpledgegoal">
                                </span>
                            </span>
                        </div> 
                <?php
            }

            
            if ($load_design == 3) {
                   if($raisedpercentshow!='no'){
                ?>
                        <span id="cf_total_raised_in_percentage" class="price" style=""><?php echo $getpercentage_before_validate; ?>%
                                <small> <?php echo __(' RAISED', 'galaxyfunder'); ?></small>
                        </span>
                   <?php } ?>
                        <?php if($gettotalcontribution_goal_org!=0 && $raisedamountshow!='no'){ ?>
                        <span id="cf_total_price_raiser" class="price"><?php echo $gettotalcontrinuted; ?>
                                <small> <?php echo __('RAISED') ?> </small>
                        </span>
                        <?php } 
                        if($daysleftshow!='no'){
                        ?>
                        <span id="cf_days_remainings" class="price" style="float:left">
                        <?php echo $remainingdaysleft_galaxyfunder; ?>
                        </span>
                <?php
                        }
            }
            if( $load_design == 3 && $flag == 'shop' && $description_position=='below_stylebar') {
                echo FP_GF_Common_Functions::common_function_to_get_description($post_id,$description_position);
            }
            //Start of KS style
            ?>

                <div class='galaxy_funder_warning_message'></div>
            <?php
        }

        public static function common_function_for_shortcode_campaigns($checkvalue, $id) {
            if ($checkvalue == 'yes') {

                $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                if ($thumbnail != false || $thumbnail != '') {
                    $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id));
                    $width = "22.05%";
                }
                if ($thumbnail == false || $thumbnail == '') {
                    $thumbnail_url = get_the_post_thumbnail($id, array(150, 150));
                }
                $description = '';
                if (get_option('crowdfunding_enable_description_for_shortcode')) {
                    $content_post = get_post($id);
                    if (isset($content_post->post_content)) {
                        $content = $content_post->post_content;
                        $description = '<p class="price">' . wp_trim_words($content, get_option('crowdfunding_number_of_words_to_trim')) . '</p>';
                    }
                }
                ?>
                <style type="text/css">
                    .woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img {
                        width:130px !important; height:130px !important;
                    }
                </style>
                <?php
                $enabletitle='';
                if (get_option('crowdfunding_enable_title_for_shortcode') == 'yes') {
                        $enabletitle = '<h3>' . get_the_title($id) . '</h3>';
                }
                if ($checkvalue == 'yes') {
                    if ($thumbnail != '') {
                        ?>
                                                
                            <li class="product" style="width:170px;">
                                    <a href="<?php echo get_permalink($id); ?>"><img style="display:list-item;" src="<?php echo $thumbnail; ?>"/>
                                <?php echo $enabletitle; ?>
                                        
                                <?php echo FP_GF_Shortcode_Functions::common_function_for_progressbar($id, 'shortcode');
                                      echo $description;
                                ?>
                                    </a>
                            </li>
                        <?php
                    } else {
                        $url = plugins_url();
                        $thumbnail = $url . "/woocommerce/assets/images/placeholder.png";
                        $width = "100%";
                        ?>
                            <li class="product" style="width:170px;">
                                <a href="<?php echo get_permalink($id); ?>">
                                    <img style="display:list-item;" width="180" height="150" src="<?php echo $thumbnail; ?>" alt="Placeholder" class="woocommerce-placeholder wp-post-image"/>
                            <?php echo $enabletitle; ?>
                            <?php echo FP_GF_Shortcode_Functions::common_function_for_progressbar($id, 'shortcode');
                                  echo $description;
                            ?>
                                </a>
                            </li>
                        <?php
                    }
                }
            }
        }

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
    }
    
     FP_GF_Shortcode_Functions::init();
}