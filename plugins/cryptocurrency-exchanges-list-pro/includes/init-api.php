<?php
namespace CryptocurrencyExchangesListProREG;
class CELP_ApiConf{
    const PLUGIN_NAME = 'Cryptocurrency Exchanges List PRO';
    const PLUGIN_VERSION = CELP_VERSION;
    const PLUGIN_PREFIX = 'celp';
    const PLUGIN_AUTH_PAGE = 'celp-registration';
    const PLUGIN_URL = CELP_URL;
}

    require_once 'class.settings-api.php';
    require_once 'CryptocurrencyExchangesListProBase.php';
    require_once 'api-auth-settings.php';

	new CELP_Settings();