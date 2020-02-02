<?php

if (!defined('ABSPATH')) {
    exit;
}

define('TENWEB_VERSION', '1.2.50');

//Directories
define('TENWEB_PREFIX', 'tenweb');
define('TENWEB_DIR', dirname(__FILE__));
define('TENWEB_FILE', TENWEB_DIR . '/10web-manager.php');
define('TENWEB_INCLUDES_DIR', TENWEB_DIR . '/includes');
define('TENWEB_VIEWS_DIR', TENWEB_DIR . '/views');
define('TENWEB_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('TENWEB_URL_CSS', plugins_url(plugin_basename(dirname(__FILE__))) . '/assets/css');
define('TENWEB_URL_JS', plugins_url(plugin_basename(dirname(__FILE__))) . '/assets/js');
define('TENWEB_URL_IMG', plugins_url(plugin_basename(dirname(__FILE__))) . '/assets/images');
define('TENWEB_MANAGER_ID', 51);
define('TENWEB_SLUG', "10web-manager/10web-manager.php");
define('TENWEB_LANG', "tenweb");
define('TENWEB_USERNAME', "tenweb_manager_plugin");
define('TENWEB_SITE_URL', "https://10web.io");
define('TENWEB_DASHBOARD', "https://my.10web.io");
define('TENWEB_MIGRATION_DEBUG', 0);
define('TENWEB_MIGRATION_ARCHIVE_TYPE', 'gzip');
define('TENWEB_MIGRATION_MAX_FILES_RESTART', 20000);
define('TENWEB_MIGRATION_BULK_FILES_COUNT', 10000);
define('TENWEB_MIGRATION_BULK_DB_ROWS_COUNT', 500);
define('TENWEB_MIGRATION_EXEC_TIME_OFFSET', 10);

//Domain / URL / Address
define('TENWEB_API_URL', "https://manager.10web.io/api"); //live

define('TENWEB_REST_NAMESPACE', 'tenweb/v1');
define('TENWEB_S3_BUCKET', '10web-products-production');//live

global $tenweb_services;
$tenweb_services = array(
	'optimizer.10web.io',
	'security.10web.io',
	'seo.10web.io',
	'backup.10web.io',
	'manager.10web.io',
	'core.10web.io',
	'lxd.10web.io'
);
