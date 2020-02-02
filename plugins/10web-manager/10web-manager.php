<?php
/**
 * Plugin Name: 10WEB manager
 * Plugin URI: https://10web.io/
 * Description: 10Web Manager is an ideal plugin to effortlessly manage all of your 10Web products and services.
 * Version: 1.2.50
 * Author: 10WEB
 * Author URI: https://10web.io/
 * License: GPLv2 or later
 */
if (!defined('ABSPATH')) {
    exit;
}

function tenweb_check_plugin_requirements()
{
    global $wp_version;
    $php_version = explode("-", PHP_VERSION);
    $php_version = $php_version[0];
    $result = (
        version_compare($wp_version, '4.4', ">=") &&
        version_compare($php_version, '5.3.0', ">=")
    );

    return $result;
}

//use Tenweb_Manager\Manager;

if (tenweb_check_plugin_requirements()) {

    include_once dirname(__FILE__) . '/config.php';
    include_once TENWEB_INCLUDES_DIR . '/class-helper.php';
    include_once dirname(__FILE__) . '/manager.php';

    add_action('plugins_loaded', array('Tenweb_Manager\Manager', 'get_instance'), 1);
}


register_activation_hook(__FILE__, 'tenweb_activate');
register_deactivation_hook(__FILE__, 'tenweb_deactivate');


function tenweb_activate($to_die = "1")
{

    //when tenweb_check_plugin_requirements() return false
    include_once dirname(__FILE__) . '/config.php';

    $error_msg = array();
    if (tenweb_check_plugin_requirements() == false) {
        array_push($error_msg, "PHP or Wordpress version not compatible with plugin.");
    }

    if (plugin_basename(__FILE__) !== TENWEB_SLUG) {
        array_push($error_msg, "Plugin foldername/filename.php must be " . TENWEB_SLUG);
    }

    //send new state
    delete_site_transient(TENWEB_PREFIX . '_send_states_transient');
    update_option(TENWEB_PREFIX . '_version', TENWEB_VERSION);
    update_option(TENWEB_PREFIX . '_activated', '1');
    if (!empty($error_msg) && ($to_die == "1" || $to_die == "")) {
        $error_msg = implode("<br/>", $error_msg);
        die($error_msg);
    } else {
        return $error_msg;
    }
}


function tenweb_deactivate()
{
    if (tenweb_check_plugin_requirements() == false || is_multisite() === true) {
        return;
    }

    call_user_func(array('\Tenweb_Manager\Helper', 'send_state_before_deactivation'));

}