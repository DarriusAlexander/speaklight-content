<?php
/**
 * Plugin Name: Premium Cryptocurrency Widgets
 * Description: Premium Cryptocurrency Widgets plugin allows you to easily add various types of widgets with live cryptocurrency quotes.
 * Text Domain: premium-cryptocurrency-widgets
 * Version: 2.15.0, built on Monday, April 6, 2020
 * Author: Financial Apps and Plugins <info@financialplugins.com>
 * Author URI: https://financialplugins.com/
 * Plugin URI: https://cryptowidgets.financialplugins.com/
 * Purchase: https://1.envato.market/mvJYM
 * Like: https://www.facebook.com/financialplugins/
 */

defined('ABSPATH') or die('Direct access is not allowed');

// define plugin root folder to be used by other classess
define('PCW_ROOT_DIR', dirname(__FILE__));

// register autoload function
spl_autoload_register(function ($className) {
    if (strpos($className,'PremiumCryptocurrencyWidgets')!==FALSE) {
        $classFileName = 'classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $classFileName))
            require_once $classFileName;
    }
});

// plugin activation hook
register_activation_hook(__FILE__, ['\\PremiumCryptocurrencyWidgets\\WPHelper', 'activate']);

// instantiate a new plugin instance
$premiumCryptocurrencyWidgets = new \PremiumCryptocurrencyWidgets\Plugin();
