<?php

namespace PremiumCryptocurrencyWidgets;

/**
 * Class WPHelper - WordPress related helper functions
 * @package PremiumCryptocurrencyWidgets
 */
class WPHelper
{
    public static function activate()
    {
        self::checkPhpVersion();
        self::addConfig();
        self::register();
    }

    public static function checkPhpVersion()
    {
        $errorMessage = NULL;

        // Check current PHP version against the min version required for the plugin to run
        if (version_compare(PHP_VERSION, Plugin::MIN_PHP_VERSION, '<')) {
            $errorMessage = sprintf('<p>PHP <b>%s+</b> is required to use <b>%s</b> plugin. You have <b>%s</b> installed.</p>', Plugin::MIN_PHP_VERSION, Plugin::NAME, PHP_VERSION);
        }

        if ($errorMessage) {
            wp_die(
                $errorMessage,
                Plugin::NAME . ': Activation Error',
                ['response' => 200, 'back_link' => TRUE]
            );
        }

        return;
    }

    public static function addConfig()
    {
        add_option('pcw_config', [
            'cryptocompare_api_key'     => '',
            'locale'                    => 'en',
            'thousands_separator'       => ',',
            'decimal_separator'         => '.',
            'price_margin'              => 0,
            'google_maps_api_key'       => '',
            'asset_recognition_regexp'  => '',
            'asset_page_regexp'         => '',
            'asset_page_content'        => '',
            'enqueue_priority'          => 10,
        ]);
    }

    public static function register()
    {
        wp_remote_post('https://financialplugins.com/api/installations/register', [
            'method'        => 'POST',
            'timeout'       => 10,
            'redirection'   => 5,
            'blocking'      => FALSE,
            'sslverify'     => FALSE,
            'headers'       => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ],
            'body'          => [
                'hash'      => '51e1cff4b57e9c9bb365d6c4d31cd28c',
                'version'   => Plugin::VERSION,
                'domain'    => site_url(),
                'info'      => [
                    'php' => PHP_VERSION
                ]
            ]
        ]);
    }
}