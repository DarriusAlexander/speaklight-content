<?php
if (!class_exists('CFSettingssubmenu')) {
class CFSettingssubmenu {

    public static function init() {
        
        //Adding Screen ids hooks
        if (isset($_GET['page'])) {    
            if ($_GET['page'] == 'crowdfunding_callback') {
                add_filter('woocommerce_screen_ids', array(__CLASS__, 'Galaxy_funder_woocommerce_load_enqueues'), 9, 1);
            }
        }
        //Class for multi select
        add_action('admin_head', array(__CLASS__, 'class_for_multiselect'));
        
        //Admin head js
        add_action('admin_head', array(__CLASS__, 'admin_head_js'));
        
        //Adding admin menu for Galaxy funder
        add_action('admin_menu', array(__CLASS__, 'register_my_custom_submenu_page'));

        //include tabs files 
        include_once 'tabs/class-crowdfunding-general-tab.php';
        include_once 'tabs/class-single-product-page.php';
        include_once 'tabs/class-shop-page.php';
        include_once 'tabs/class-frontend-submission-settings.php';
        include_once 'tabs/class-mycampaign.php';
        include_once 'tabs/class-email-settings.php';
        include_once 'tabs/class-campaigns-list-settings.php';
        include_once 'tabs/class-error-message.php';
        include_once 'tabs/class-shortcode-generator.php';
        include_once 'tabs/contribution-extension.php';
        include_once 'tabs/class-campaign-payment.php';
        
        
    }
    
    public static function class_for_multiselect() {
            global $woocommerce;
            if (isset($_GET['page'])) {
                if ($_GET['page'] == 'crowdfunding_callback') {
?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
            <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                    jQuery('#cf_campaign_submission_frontend_exclude_role_control').chosen();
            <?php } else { ?>
                    jQuery('#cf_campaign_submission_frontend_exclude_role_control').select2();
            <?php } ?>
                });
            </script>
                    <?php
                }
            }
        }
    
    public static function Galaxy_funder_woocommerce_load_enqueues() {
            global $my_admin_page;
            if (isset($_GET['page'])) {
                if (($_GET['page'] == 'crowdfunding_callback')) {
                    $array[] = 'woocommerce_page_' . $_GET['page'];
                    return $array;
                } else {
                    $array[] = '';
                    return $array;
                }
            }
        }

    public static function admin_head_js() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                //Current tab reset
                jQuery("#resetid").click(function () {
                    if (confirm("Are You Sure ? Do You Want to Reset Your Current Tab?") == true) {
                        return true;
                    } else {
                        return false;
                    }
                });

                //All tab reset
                jQuery("#resetallid").click(function () {
                    if (confirm("Are You Sure ? Do You Want to Reset All Tab?") == true) {
                        return true;
                    } else {
                        return false;
                    }
                });
            });

        </script>
        <?php
    }

    public static function register_my_custom_submenu_page() {
        global $my_admin_page;

        $my_admin_page = add_submenu_page('woocommerce', __('Galaxy Funder', 'galaxyfunder'), __('Galaxy Funder', 'galaxyfunder'), 'manage_options', 'crowdfunding_callback', array('CFSettingssubmenu', 'my_custom_submenu_page_callback'));
    }

    public static function my_custom_submenu_page_callback() {
        global $woocommerce, $woocommerce_settings, $current_section, $current_tab;
        $tabs = array();
        do_action('woocommerce_cf_settings_start');
        $current_tab = ( empty($_GET['tab']) ) ? 'crowdfunding' : sanitize_text_field(urldecode($_GET['tab']));
        $current_section = ( empty($_REQUEST['section']) ) ? '' : sanitize_text_field(urldecode($_REQUEST['section']));
        if (!empty($_POST['save'])) {
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings'))
                die(__('Action failed. Please refresh the page and retry.', 'galaxyfunder'));

            if (!$current_section) {
                switch ($current_tab) {
                    default :
                        if (isset($woocommerce_settings[$current_tab]))
                            woocommerce_update_options($woocommerce_settings[$current_tab]);
                        do_action('woocommerce_update_options_' . $current_tab);
                        break;
                }

                do_action('woocommerce_update_options');
                if ($current_tab == 'general' && get_option('woocommerce_frontend_css') == 'yes') {
                    
                }
            } else {
                do_action('woocommerce_update_options_' . $current_tab . '_' . $current_section);
            }
            delete_transient('woocommerce_cache_excluded_uris');
            $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
            if (isset($_POST['subtab'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
// Get any returned messages
        $error = ( empty($_GET['wc_error']) ) ? '' : urldecode(stripslashes($_GET['wc_error']));
        $message = ( empty($_GET['wc_message']) ) ? '' : urldecode(stripslashes($_GET['wc_message']));

        if ($error || $message) {

            if ($error) {
                echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
            } else {
                echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
            }
        } elseif (!empty($_GET['saved'])) {

            echo '<div id="message" class="updated fade"><p><strong>' . __('Your settings have been saved.', 'galaxyfunder') . '</strong></p></div>';
        }
        ?>
        <div class="wrap woocommerce">
            <form method="post" id="mainform" action="" enctype="multipart/form-data">
                <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <?php
                    $tabs = apply_filters('woocommerce_cf_settings_tabs_array', $tabs);
                    foreach ($tabs as $name => $label) {
                       // if($label!=''){
                            echo '<a href="' . admin_url('admin.php?page=crowdfunding_callback&tab=' . $name) . '" class="nav-tab ';
                            if ($current_tab == $name)
                                echo 'nav-tab-active';
                            echo '">' . $label . '</a>';
                    //}
                    }
                    do_action('woocommerce_cf_settings_tabs');
                    ?>
                </h2>

                <?php
                switch ($current_tab) :
                    case "crowdfunding_listtable" :
                        CFCampaignslist::crowdfunding_adminpage();
                        break;
                    case "perk_info" :
                        FP_GF_Perk_Meta_Box::perk_info_check();
                        break;
                    case "Contribution extension" :

                        CFContributioneextension::cf_contribution_extension_table();
                        break;
                    default :
                        do_action('woocommerce_cf_settings_tabs_' . $current_tab);
                        break;
                endswitch;
                ?>

                <p class="submit">
                    <?php if (!isset($GLOBALS['hide_save_button'])) : ?>
                        <input name="save" id="saveid"  class="button-primary" type="submit" value="<?php _e('Save changes', 'woocommerce'); ?>" />
                        <?php
                        if (isset($_GET['tab'])) {
                            $tab_name = $_GET['tab'];
                            if ($tab_name == 'perk_info' || $tab_name == 'Contribution extension') {
                                ?>
                                <script type="text/javascript">
                                    jQuery('#saveid').css("display", "none");</script>

                                <?php
                            }
                        }
                        ?>
                    <?php endif; ?>
                    <input type="hidden" name="subtab" id="last_tab" />
                    <?php wp_nonce_field('woocommerce-settings', '_wpnonce', true, true); ?>
                </p>

            </form>
            <form method="post" id="mainforms" action="" enctype="multipart/form-data" style="float: left; margin-top: -52px; margin-left: 109px;">

                <?php
                $reset_key = '';
                if (isset($_GET['page']) && isset($_GET['tab'])) {
                    $reset_key = $_GET['tab'];
                } else if (isset($_GET['page']) && !isset($_GET['tab'])) {
                    $reset_key = $_GET['page'];
                }
                ?>
                <input type="hidden" value="<?php echo $reset_key; ?>" name="reset_hidden">
                <input name="reset" id="resetid" class="button-secondary" type="submit" value="<?php _e('Reset', 'woocommerce'); ?>"/>
                <input name="resetall" id="resetallid" class="button-secondary" type="submit" value="<?php _e('Reset All', 'woocommerce'); ?>"/>
                <?php wp_nonce_field('woocommerce-reset_settings', '_wpnonce', true, true); ?>
                <?php
                if (isset($_GET['tab'])) {
                    $tab_name = $_GET['tab'];
                    if ($tab_name == 'perk_info' || $tab_name == 'Contribution extension') {
                        ?>
                        <script type="text/javascript">
                            jQuery('#resetid').css("display", "none");
                            jQuery('#resetallid').css("display", "none");
                                                    
                        </script>
                        <?php
                    }
                }
                ?>
            </form>

        </div>
        <?php
    }
}
CFSettingssubmenu::init();
}

 
