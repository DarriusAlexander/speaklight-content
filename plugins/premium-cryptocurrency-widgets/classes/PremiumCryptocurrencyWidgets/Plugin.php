<?php

namespace PremiumCryptocurrencyWidgets;

/**
 * Class Plugin - WordPress plugin
 * @package PremiumCryptocurrencyWidgets
 */
class Plugin
{
    const VERSION               = '2.15.0';
    const MIN_PHP_VERSION       = '5.4.0';
    const CODE                  = 'pcw';
    const ID                    = 'premium-cryptocurrency-widgets';
    const SHORT_NAME            = 'Cryptocurrency Widgets';
    const NAME                  = 'Premium Cryptocurrency Widgets';
    const SHORTCODE             = 'cryptocurrency_widget';
    const JS_GLOBAL_VAR         = 'premiumCryptocurrencyWidgets';
    const ADMIN_TEMPLATES       = 'admin';

    private $config;
    private $pluginDir;
    private $pluginUrl;
    private $jsVariables;

    // note that constructor can be called multiple times during page load, due to possibly multiple AJAX requests
    function __construct()
    {
        $this->pluginDir = PCW_ROOT_DIR . DIRECTORY_SEPARATOR;
        $this->pluginUrl = plugins_url() . '/' . self::ID; // on SSL enabled websites WP_PLUGIN_URL still contains plain HTTP protocol, so it using function instead
        $this->config = get_option(self::CODE . '_config');

        $enqueuePriority = $this->config['enqueue_priority'];

        // shortcode
        add_shortcode(self::SHORTCODE, [$this, 'shortcode']);

        // actions
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'loadAssets'], $enqueuePriority);
        add_action('admin_init', [$this, 'adminInit']);
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_enqueue_scripts', [$this, 'loadAdminAssets'], $enqueuePriority);
        add_action('template_redirect', [$this, 'displayVirtualAssetPage']);

        // filters
        add_filter('query_vars', [$this, 'addAssetQueryVariables']);
        add_filter('the_title', [$this, 'disableTitle']);
        add_filter('plugin_action_links_' . self::ID . '/' . self::ID . '.php', [$this, 'addPluginActionLinks']);
    }

    /**
     * Load assets for public users
     */
    public function loadAssets()
    {
        $this->loadStyles();
        $this->loadScripts();
    }

    /**
     * Load assets for admin users
     */
    public function loadAdminAssets()
    {
        // always enqueue CSS styles, because fontawesome icons are required on all pages
        $this->loadStyles();

        $script = basename($_SERVER['PHP_SELF']);
        // enqueue JS scripts only when plugin pages are viewed
        if ($script == 'admin.php' && strpos($_SERVER['QUERY_STRING'], 'page='.self::ID) !== FALSE) {
            $this->loadAdminScripts();
        }
    }

    /**
     * Load CSS files for public users
     */
    private function loadStyles()
    {
        $this->loadStyle(self::CODE . '-plugin-style', 'assets/css/style.css');
    }

    /**
     * Load JS scripts for public users
     */
    private function loadScripts()
    {
        $this->loadScript(self::CODE . '-plugin-main', 'assets/js/dist/app.js', ['jquery'], TRUE);
        $this->localizeScript(self::CODE . '-plugin-main', self::JS_GLOBAL_VAR, $this->jsVariables);
    }

    /**
     * Load JS scripts for WP admin
     */
    private function loadAdminScripts()
    {
        $this->loadScript(self::CODE . '-plugin-main', 'assets/js/dist/builder.js', ['jquery'], TRUE);
        $this->localizeScript(self::CODE . '-plugin-main', self::JS_GLOBAL_VAR, $this->jsVariables);
    }

    /**
     * Init function
     */
    public function init()
    {
        // load translation files
        load_plugin_textdomain(self::ID, false, self::ID . '/languages');

        // add a rewrite rule if virtual page regexp is set
        if (isset($this->config['asset_page_regexp']) && $this->config['asset_page_regexp']) {
            // rewrite rule will transform URL into query string (GET) parameters
            // e.g. ^crypto/([a-zA-Z0-9-\*]+)/([a-zA-Z0-9-\*]+)
            add_rewrite_rule($this->config['asset_page_regexp'], 'index.php?' . self::CODE . '_asset_from=$matches[1]&' . self::CODE . '_asset_to=$matches[2]', 'top');
        }

        // it's important to call translation functions AFTER load_plugin_textdomain() is called, otherwise translations will not work
        $this->jsVariables = [
            'shortcodeOpenTag' => '[' . self::SHORTCODE . ' ',
            'shortcodeCloseTag' => ']',
            'pluginUrl' => $this->pluginUrl,
            'websiteUrl' => get_site_url(), // WordPress root URL
            'locale' => $this->config['locale'],
            'thousandsSeparator' => $this->config['thousands_separator'],
            'decimalSeparator' => $this->config['decimal_separator'],
            'priceMargin' => isset($this->config['price_margin']) ? (int) $this->config['price_margin'] : 0,
            'cryptoComareApiKey' => isset($this->config['cryptocompare_api_key']) ? $this->config['cryptocompare_api_key'] : '',
            'googleMapsApiKey' => isset($this->config['google_maps_api_key']) ? $this->config['google_maps_api_key'] : '',
            'assetRecognitionRegexp' => isset($this->config['asset_recognition_regexp']) ? $this->config['asset_recognition_regexp'] : '',
            // text.json needs to be modified when the below strings changed
            'text' => [
                '_self' => esc_html__('Same window', 'premium-cryptocurrency-widgets'),
                '_blank' => esc_html__('New window', 'premium-cryptocurrency-widgets'),
                '60min' => esc_html__('1 hour', 'premium-cryptocurrency-widgets'),
                '24h' => esc_html__('24 hours', 'premium-cryptocurrency-widgets'),
                '5d' => esc_html__('5 days', 'premium-cryptocurrency-widgets'),
                '7d' => esc_html__('7 days', 'premium-cryptocurrency-widgets'),
                '14' => esc_html__('14 days', 'premium-cryptocurrency-widgets'),
                '1m' => esc_html__('1 month', 'premium-cryptocurrency-widgets'),
                '2m' => esc_html__('2 months', 'premium-cryptocurrency-widgets'),
                '3m' => esc_html__('3 months', 'premium-cryptocurrency-widgets'),
                '6m' => esc_html__('6 months', 'premium-cryptocurrency-widgets'),
                '1y' => esc_html__('1 year', 'premium-cryptocurrency-widgets'),
                '2y' => esc_html__('2 years', 'premium-cryptocurrency-widgets'),
                '3y' => esc_html__('3 years', 'premium-cryptocurrency-widgets'),
                '5y' => esc_html__('5 years', 'premium-cryptocurrency-widgets'),
                'algorithm' => esc_html__('Algorithm', 'premium-cryptocurrency-widgets'),
                'anonymity' => esc_html__('Anonymity', 'premium-cryptocurrency-widgets'),
                'api' => esc_html__('API', 'premium-cryptocurrency-widgets'),
                'api_type' => esc_html__('API type', 'premium-cryptocurrency-widgets'),
                'asc' => esc_html__('Ascending', 'premium-cryptocurrency-widgets'),
                'ask' => esc_html__('Ask', 'premium-cryptocurrency-widgets'),
                'asset' => esc_html__('Pair', 'premium-cryptocurrency-widgets'),
                'assets' => esc_html__('Assets', 'premium-cryptocurrency-widgets'),
                'asset_short' => esc_html__('Pair short', 'premium-cryptocurrency-widgets'),
                'average_fee' => esc_html__('Average fee', 'premium-cryptocurrency-widgets'),
                'axes' => esc_html__('Display axes', 'premium-cryptocurrency-widgets'),
                'bar' => esc_html__('Bar', 'premium-cryptocurrency-widgets'),
                'bid' => esc_html__('Bid', 'premium-cryptocurrency-widgets'),
                'block_reward_reduction' => esc_html__('Block reward reduction', 'premium-cryptocurrency-widgets'),
                'bottomCenter' => esc_html__('Bottom center', 'premium-cryptocurrency-widgets'),
                'bottomLeft' => esc_html__('Bottom left', 'premium-cryptocurrency-widgets'),
                'bottomRight' => esc_html__('Bottom right', 'premium-cryptocurrency-widgets'),
                'buy' => esc_html__('Buy', 'premium-cryptocurrency-widgets'),
                'center' => esc_html__('Center', 'premium-cryptocurrency-widgets'),
                'change_abs' => esc_html__('Change', 'premium-cryptocurrency-widgets'),
                'change_pct' => esc_html__('% Change', 'premium-cryptocurrency-widgets'),
                'change_abs_24h' => esc_html__('Change 24h', 'premium-cryptocurrency-widgets'),
                'change_pct_24h' => esc_html__('% Change 24h', 'premium-cryptocurrency-widgets'),
                'chart' => esc_html__('Chart', 'premium-cryptocurrency-widgets'),
                'close' => esc_html__('Close', 'premium-cryptocurrency-widgets'),
                'color' => esc_html__('Color', 'premium-cryptocurrency-widgets'),
                'copy' => esc_html__('Copy', 'premium-cryptocurrency-widgets'),
                'country' => esc_html__('Country', 'premium-cryptocurrency-widgets'),
                'cryptocurrencies' => esc_html__('Cryptocurrencies', 'premium-cryptocurrency-widgets'),
                'cryptocurrency' => esc_html__('Cryptocurrency', 'premium-cryptocurrency-widgets'),
                'currency' => esc_html__('Currency', 'premium-cryptocurrency-widgets'),
                'currencies' => esc_html__('Currencies', 'premium-cryptocurrency-widgets'),
                'cursor' => esc_html__('Cursor', 'premium-cryptocurrency-widgets'),
                'customizable' => esc_html__('Customizable', 'premium-cryptocurrency-widgets'),
                'customize' => esc_html__('Customize', 'premium-cryptocurrency-widgets'),
                'date_time' => esc_html__('Date / time', 'premium-cryptocurrency-widgets'),
                'desc' => esc_html__('Descending', 'premium-cryptocurrency-widgets'),
                'description' => esc_html__('Description', 'premium-cryptocurrency-widgets'),
                'difficulty_adjustment' => esc_html__('Difficulty adjustment', 'premium-cryptocurrency-widgets'),
                'direction' => esc_html__('Direction', 'premium-cryptocurrency-widgets'),
                'display_currency' => esc_html__('Quote (display) currency', 'premium-cryptocurrency-widgets'),
                'display_chart' => esc_html__('Display chart for selected coin', 'premium-cryptocurrency-widgets'),
                'display_header' => esc_html__('Display header', 'premium-cryptocurrency-widgets'),
                'ease_of_use' => esc_html__('Ease of use', 'premium-cryptocurrency-widgets'),
                'features' => esc_html__('Features', 'premium-cryptocurrency-widgets'),
                'fee' => esc_html__('Fee', 'premium-cryptocurrency-widgets'),
                'field' => esc_html__('Field', 'premium-cryptocurrency-widgets'),
                'field_animation' => esc_html__('Price change animation', 'premium-cryptocurrency-widgets'),
                'fields' => esc_html__('Fields', 'premium-cryptocurrency-widgets'),
                'flash' => esc_html__('Flash on quotes change', 'premium-cryptocurrency-widgets'),
                'graph_type' => esc_html__('Graph type', 'premium-cryptocurrency-widgets'),
                'high' => esc_html__('High', 'premium-cryptocurrency-widgets'),
                'high_24h' => esc_html__('High 24h', 'premium-cryptocurrency-widgets'),
                'id' => esc_html__('ID', 'premium-cryptocurrency-widgets'),
                'yes' => esc_html__('Yes', 'premium-cryptocurrency-widgets'),
                'last_market' => esc_html__('Last market', 'premium-cryptocurrency-widgets'),
                'last_trade_id' => esc_html__('Last trade ID', 'premium-cryptocurrency-widgets'),
                'last_update' => esc_html__('Last udpate', 'premium-cryptocurrency-widgets'),
                'last_update_ago' => esc_html__('Last udpate', 'premium-cryptocurrency-widgets'),
                'last_volume' => esc_html__('Last volume', 'premium-cryptocurrency-widgets'),
                'last_volume_from' => esc_html__('Last volume', 'premium-cryptocurrency-widgets'),
                'last_volume_to' => esc_html__('Last volume', 'premium-cryptocurrency-widgets'),
                'left' => esc_html__('Left', 'premium-cryptocurrency-widgets'),
                'limit' => esc_html__('Limit', 'premium-cryptocurrency-widgets'),
                'line' => esc_html__('Line', 'premium-cryptocurrency-widgets'),
                'link' => esc_html__('Link', 'premium-cryptocurrency-widgets'),
                'load_more' => esc_html__('Load more', 'premium-cryptocurrency-widgets'),
                'logo' => esc_html__('Logo', 'premium-cryptocurrency-widgets'),
                'logo_name' => esc_html__('Name', 'premium-cryptocurrency-widgets'),
                'logo_name_link' => esc_html__('Name', 'premium-cryptocurrency-widgets'),
                'low' => esc_html__('Low', 'premium-cryptocurrency-widgets'),
                'low_24h' => esc_html__('Low 24h', 'premium-cryptocurrency-widgets'),
                'market' => esc_html__('Market', 'premium-cryptocurrency-widgets'),
                'market_cap' => esc_html__('Market cap', 'premium-cryptocurrency-widgets'),
                'market_cap2' => esc_html__('Mkt cap', 'premium-cryptocurrency-widgets'),
                'markup' => esc_html__('Text (markup)', 'premium-cryptocurrency-widgets'),
                'max' => esc_html__('Max', 'premium-cryptocurrency-widgets'),
                'min_payout' => esc_html__('Min payout', 'premium-cryptocurrency-widgets'),
                'mode' => esc_html__('Mode', 'premium-cryptocurrency-widgets'),
                'multiple_from' => esc_html__('Multiple coins / single display currency', 'premium-cryptocurrency-widgets'),
                'multiple_to' => esc_html__('Single coin / multiple display currencies', 'premium-cryptocurrency-widgets'),
                'name' => esc_html__('Name', 'premium-cryptocurrency-widgets'),
                'name_lc' => esc_html__('Name (LC)', 'premium-cryptocurrency-widgets'),
                'name_link' => esc_html__('Name', 'premium-cryptocurrency-widgets'),
                'no' => esc_html__('No', 'premium-cryptocurrency-widgets'),
                'number_b' => esc_html__('b', 'premium-cryptocurrency-widgets'),
                'number_k' => esc_html__('k', 'premium-cryptocurrency-widgets'),
                'number_m' => esc_html__('m', 'premium-cryptocurrency-widgets'),
                'number_t' => esc_html__('b', 'premium-cryptocurrency-widgets'),
                'open' => esc_html__('Open', 'premium-cryptocurrency-widgets'),
                'open_24h' => esc_html__('Open 24h', 'premium-cryptocurrency-widgets'),
                'page_url' => esc_html__('Coin page URL', 'premium-cryptocurrency-widgets'),
                'pagination' => esc_html__('Pagination', 'premium-cryptocurrency-widgets'),
                'pause' => esc_html__('Pause on hover', 'premium-cryptocurrency-widgets'),
                'payment_type' => esc_html__('Payment type', 'premium-cryptocurrency-widgets'),
                'period' => esc_html__('Period', 'premium-cryptocurrency-widgets'),
                'period_short_60min' => esc_html__('1h', 'premium-cryptocurrency-widgets'),
                'period_short_24h' => esc_html__('24h', 'premium-cryptocurrency-widgets'),
                'period_short_5d' => esc_html__('5d', 'premium-cryptocurrency-widgets'),
                'period_short_7d' => esc_html__('7d', 'premium-cryptocurrency-widgets'),
                'period_short_14' => esc_html__('14d', 'premium-cryptocurrency-widgets'),
                'period_short_1m' => esc_html__('1m', 'premium-cryptocurrency-widgets'),
                'period_short_2m' => esc_html__('2m', 'premium-cryptocurrency-widgets'),
                'period_short_3m' => esc_html__('3m', 'premium-cryptocurrency-widgets'),
                'period_short_6m' => esc_html__('6m', 'premium-cryptocurrency-widgets'),
                'period_short_1y' => esc_html__('1y', 'premium-cryptocurrency-widgets'),
                'period_short_2y' => esc_html__('2y', 'premium-cryptocurrency-widgets'),
                'period_short_3y' => esc_html__('3y', 'premium-cryptocurrency-widgets'),
                'period_short_5y' => esc_html__('5y', 'premium-cryptocurrency-widgets'),
                'period_short_max' => esc_html__('max', 'premium-cryptocurrency-widgets'),
                'platforms' => esc_html__('Platforms', 'premium-cryptocurrency-widgets'),
                'position' => esc_html__('Position', 'premium-cryptocurrency-widgets'),
                'preview' => esc_html__('Preview', 'premium-cryptocurrency-widgets'),
                'price' => esc_html__('Price', 'premium-cryptocurrency-widgets'),
                'price_to' => esc_html__('Price (currency)', 'premium-cryptocurrency-widgets'),
                'proof_type' => esc_html__('Proof type', 'premium-cryptocurrency-widgets'),
                'portfolio_date' => esc_html__('Purchase date', 'premium-cryptocurrency-widgets'),
                'portfolio_price' => esc_html__('Purchase price', 'premium-cryptocurrency-widgets'),
                'portfolio_sell_date' => esc_html__('Sell date', 'premium-cryptocurrency-widgets'),
                'portfolio_sell_price' => esc_html__('Sell price', 'premium-cryptocurrency-widgets'),
                'portfolio_structure' => esc_html__('Portfolio structure', 'premium-cryptocurrency-widgets'),
                'portfolio_quantity' => esc_html__('Quantity', 'premium-cryptocurrency-widgets'),
                'portfolio_change_abs' => esc_html__('Change', 'premium-cryptocurrency-widgets'),
                'portfolio_change_pct' => esc_html__('% Change', 'premium-cryptocurrency-widgets'),
                'portfolio_cost' => esc_html__('Cost', 'premium-cryptocurrency-widgets'),
                'portfolio_value' => esc_html__('Market value', 'premium-cryptocurrency-widgets'),
                'portfolio_return_abs' => esc_html__('Return', 'premium-cryptocurrency-widgets'),
                'portfolio_return_pct' => esc_html__('% Return', 'premium-cryptocurrency-widgets'),
                'range_24h' => esc_html__('24h range', 'premium-cryptocurrency-widgets'),
                'rank' => esc_html__('Rank', 'premium-cryptocurrency-widgets'),
                'realtime' => esc_html__('Real-time updates', 'premium-cryptocurrency-widgets'),
                'read_more' => esc_html__('Read more', 'premium-cryptocurrency-widgets'),
                'right' => esc_html__('Right', 'premium-cryptocurrency-widgets'),
                'rows_per_page' => esc_html__('Rows per page', 'premium-cryptocurrency-widgets'),
                'quantity' => esc_html__('Quantity', 'premium-cryptocurrency-widgets'),
                'save' => esc_html__('Save', 'premium-cryptocurrency-widgets'),
                'search' => esc_html__('Search', 'premium-cryptocurrency-widgets'),
                'search2' => esc_html__('Search...', 'premium-cryptocurrency-widgets'),
                'search_not_found' => esc_html__('No matching records found', 'premium-cryptocurrency-widgets'),
                'sell' => esc_html__('Sell', 'premium-cryptocurrency-widgets'),
                'security' => esc_html__('Security', 'premium-cryptocurrency-widgets'),
                'shortcode' => esc_html__('Shortcode', 'premium-cryptocurrency-widgets'),
                'server_locations' => esc_html__('Server locations', 'premium-cryptocurrency-widgets'),
                'sort_field' => esc_html__('Sort field', 'premium-cryptocurrency-widgets'),
                'sort_direction' => esc_html__('Sort direction', 'premium-cryptocurrency-widgets'),
                'source' => esc_html__('Source', 'premium-cryptocurrency-widgets'),
                'source_code_link' => esc_html__('Source code link', 'premium-cryptocurrency-widgets'),
                'supply' => esc_html__('Supply', 'premium-cryptocurrency-widgets'),
                'supported_coins' => esc_html__('Supported coins', 'premium-cryptocurrency-widgets'),
                'symbol' => esc_html__('Symbol', 'premium-cryptocurrency-widgets'),
                'symbol_from' => esc_html__('From', 'premium-cryptocurrency-widgets'),
                'symbol_from_lc' => esc_html__('From (LC)', 'premium-cryptocurrency-widgets'),
                'symbol_to' => esc_html__('To', 'premium-cryptocurrency-widgets'),
                'speed' => esc_html__('Speed', 'premium-cryptocurrency-widgets'),
                'start_date' => esc_html__('Start date', 'premium-cryptocurrency-widgets'),
                'start_expanded' => esc_html__('Start expanded', 'premium-cryptocurrency-widgets'),
                'style' => esc_html__('Extra CSS styles', 'premium-cryptocurrency-widgets'),
                'target' => esc_html__('Target', 'premium-cryptocurrency-widgets'),
                'technology' => esc_html__('Technology', 'premium-cryptocurrency-widgets'),
                'template' => esc_html__('Template', 'premium-cryptocurrency-widgets'),
                'theme' => esc_html__('Theme', 'premium-cryptocurrency-widgets'),
                'timeout' => esc_html__('Timeout', 'premium-cryptocurrency-widgets'),
                'topCenter' => esc_html__('Top center', 'premium-cryptocurrency-widgets'),
                'topLeft' => esc_html__('Top left', 'premium-cryptocurrency-widgets'),
                'topRight' => esc_html__('Top right', 'premium-cryptocurrency-widgets'),
                'total' => esc_html__('Total', 'premium-cryptocurrency-widgets'),
                'total_portfolio_change_abs' => esc_html__('Total change', 'premium-cryptocurrency-widgets'),
                'total_portfolio_change_pct' => esc_html__('Total % change', 'premium-cryptocurrency-widgets'),
                'total_portfolio_cost' => esc_html__('Total cost', 'premium-cryptocurrency-widgets'),
                'total_portfolio_value' => esc_html__('Total value', 'premium-cryptocurrency-widgets'),
                'total_volume_24h_from' => esc_html__('Total volume 24h', 'premium-cryptocurrency-widgets'),
                'total_volume_24h_to' => esc_html__('Total volume 24h', 'premium-cryptocurrency-widgets'),
                'total_volume_24h_to2' => esc_html__('Vol 24h', 'premium-cryptocurrency-widgets'),
                'total_volume_pct' => esc_html__('% from total volume', 'premium-cryptocurrency-widgets'),
                'trade_date' => esc_html__('Date', 'premium-cryptocurrency-widgets'),
                'trade_date_ago' => esc_html__('Time', 'premium-cryptocurrency-widgets'),
                'trading_assets' => esc_html__('Trading pairs', 'premium-cryptocurrency-widgets'),
                'treemap' => esc_html__('Treemap', 'premium-cryptocurrency-widgets'),
                'type' => esc_html__('Type', 'premium-cryptocurrency-widgets'),
                'twitter' => esc_html__('Twitter', 'premium-cryptocurrency-widgets'),
                'unknown' => esc_html__('Unknown', 'premium-cryptocurrency-widgets'),
                'url' => esc_html__('URL', 'premium-cryptocurrency-widgets'),
                'validation_type' => esc_html__('Validation type'),
                'variables' => esc_html__('Available variables'),
                'volume' => esc_html__('Volume', 'premium-cryptocurrency-widgets'),
                'volume_day_from' => esc_html__('Day volume', 'premium-cryptocurrency-widgets'),
                'volume_day_to' => esc_html__('Day volume', 'premium-cryptocurrency-widgets'),
                'volume_24h' => esc_html__('Volume 24h', 'premium-cryptocurrency-widgets'),
                'volume_24h_from' => esc_html__('Volume 24h', 'premium-cryptocurrency-widgets'),
                'volume_24h_to' => esc_html__('Volume 24h', 'premium-cryptocurrency-widgets'),
                'volume_from' => esc_html__('Volume', 'premium-cryptocurrency-widgets'),
                'volume_to' => esc_html__('Volume', 'premium-cryptocurrency-widgets'),
                'vwap' => esc_html__('VWAP', 'premium-cryptocurrency-widgets'),
                'website' => esc_html__('Website', 'premium-cryptocurrency-widgets'),
            ]
        ];
    }

    /**
     * Customize plugin action links on plugins page
     * @param $links
     * @return mixed
     */
    public function addPluginActionLinks($links)
    {
        $link = '<a href="https://codecanyon.net/downloads" target="_blank"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> Rate plugin</a>';
        array_unshift($links, $link);
        return $links;
    }

    /**
     * Init function for admin
     */
    public function adminInit()
    {
        //
    }

    /**
     * Add custom query (GET) variables
     * @param $vars
     * @return array
     */
    public function addAssetQueryVariables($vars)
    {
        $vars[] = self::CODE . '_asset_from';
        $vars[] = self::CODE . '_asset_to';
        return $vars;
    }

    /**
     * Create a virtual page and display custom asset content.
     * This function is called when the asset page regexp is matched.
     *
     * @return bool
     */
    public function displayVirtualAssetPage()
    {
        global $wp, $wp_query;

        $assetFrom = get_query_var(self::CODE . '_asset_from');
        $assetTo = get_query_var(self::CODE . '_asset_to');

        // it's important to check that the regexp was matched and hence necessary variables were popuplated, because this hook is called for every page request.
        if (!$assetFrom || !$assetTo)
            return;

        // check that requested coin and currency exist in the JSON config file
        $staticData = json_decode(file_get_contents($this->pluginDir . 'assets/js/dist/static-data.json'), JSON_OBJECT_AS_ARRAY);
        if (!$staticData ||
            !array_key_exists($assetFrom, $staticData['coins']) ||
            (!array_key_exists($assetTo, $staticData['coins']) && !array_key_exists($assetTo, $staticData['currencies']))) {
            $wp_query->set_404();
            status_header(404);
            return;
        }

        $asset = $assetFrom . '~' . $assetTo;

        // replace assets attribute in all widgets with the asset retrieved from the URL
        $content = preg_replace('#(assets=")[a-z0-9-~\*]*(")#i', '$1'.$asset.'$2', $this->config['asset_page_content']);

        // create virtual WordPress page
        $post_id = -1;
        $post = new \stdClass();
        $post->ID = $post_id;
        $post->post_author = 1;
        $post->post_date = current_time('mysql');
        $post->post_date_gmt = current_time('mysql', 1);
        $post->post_title = $asset; // title will be hidden, so it doesn't matter what to put here
        $post->post_content = $content;
        $post->comment_status = 'closed';
        $post->ping_status = 'closed';
        $post->post_name = 'some name';
        $post->post_type = 'page';
        $post->filter = 'raw';

        $wp_post = new \WP_Post($post);

//        wp_cache_add($post_id, $wp_post, 'posts');

        $wp_query->post = $wp_post;
        $wp_query->posts = [ $wp_post ];
        $wp_query->queried_object = $wp_post;
        $wp_query->queried_object_id = $post_id;
        $wp_query->found_posts = 1;
        $wp_query->post_count = 1;
        $wp_query->max_num_pages = 1;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_single = false;
        $wp_query->is_attachment = false;
        $wp_query->is_archive = false;
        $wp_query->is_category = false;
        $wp_query->is_tag = false;
        $wp_query->is_tax = false;
        $wp_query->is_author = false;
        $wp_query->is_date = false;
        $wp_query->is_year = false;
        $wp_query->is_month = false;
        $wp_query->is_day = false;
        $wp_query->is_time = false;
        $wp_query->is_search = false;
        $wp_query->is_feed = false;
        $wp_query->is_comment_feed = false;
        $wp_query->is_trackback = false;
        $wp_query->is_home = false;
        $wp_query->is_embed = false;
        $wp_query->is_404 = false;
        $wp_query->is_paged = false;
        $wp_query->is_admin = false;
        $wp_query->is_preview = false;
        $wp_query->is_robots = false;
        $wp_query->is_posts_page = false;
        $wp_query->is_post_type_archive = false;
    }

    /**
     * Disable title on virtual coin pages
     * @param $title
     */
    public function disableTitle($title)
    {
        $assetFrom = get_query_var(self::CODE . '_asset_from');
        $assetTo = get_query_var(self::CODE . '_asset_to');

        if ($assetFrom && $assetTo && in_the_loop())
            return;

        return $title;
    }

    /**
     * Add custom plugin menu and sub menu items
     */
    public function addAdminMenu()
    {
        add_menu_page(self::SHORT_NAME, self::SHORT_NAME, 'edit_posts', self::ID, [$this, 'displayWidgetConfigPage'], $this->pluginUrl . '/assets/images/icon.png');
        $subMenuId = add_submenu_page(self::ID, self::NAME, '<i class="fa fa-wrench"></i> ' . esc_html__('Builder', 'premium-cryptocurrency-widgets'), 'edit_posts', self::ID . '-builder', [$this, 'displayWidgetConfigPage']);
        add_action('load-' . $subMenuId, [$this, 'addHelp']);

        // add Settings sub menu
        $subMenuId = add_submenu_page(self::ID, self::NAME, '<i class="fa fa-cogs"></i> ' . esc_html__('Settings', 'premium-cryptocurrency-widgets'), 'edit_posts', self::ID . '-settings', [$this, 'displayPluginSettingsPage']);
        add_action('load-' . $subMenuId, [$this, 'addHelp']);

        // explicitly remove the default top sub menu added by Wordpress
        remove_submenu_page(self::ID, self::ID);
    }

    public function addHelp()
    {
        $screen = get_current_screen();
        $screen->add_help_tab([
            'id' => self::ID . '-overview',
            'title' => 'Usage',
            'content' => file_get_contents($this->pluginDir . self::ADMIN_TEMPLATES . '/help/usage.html')
        ]);
        $screen->add_help_tab([
            'id' => self::ID . '-advanced',
            'title' => 'Advanced',
            'content' => file_get_contents($this->pluginDir . self::ADMIN_TEMPLATES . '/help/advanced.html')
        ]);
        $screen->set_help_sidebar(file_get_contents($this->pluginDir . self::ADMIN_TEMPLATES . '/help/sidebar.html'));
    }

    /**
     * Generate widget for given shortcode
     * @param shortcode params
     * @return shortcode HTML
     */
    public function shortcode($shortcode)
    {
        // shortcode() is called each time the plugin is initialized, so need to check if anything was passed or not
        if (empty($shortcode)) return;

        // transform shortcode array into key="value" string
        $shortcodeParams = implode(' ', array_map(function ($value, $key) {
            return $key . '="' . $value . '"';
        }, array_values($shortcode), array_keys($shortcode)));

        return '<crypto-widget ' . $shortcodeParams . '></crypto-widget>';
    }

    public function displayWidgetConfigPage()
    {
        require_once($this->pluginDir . self::ADMIN_TEMPLATES . '/builder.php');
    }

    public function displayPluginSettingsPage()
    {
        $settingsSaved = FALSE;

        if (!empty($_POST)) {
            $this->config['cryptocompare_api_key'] = isset($_POST['cryptocompare_api_key']) ? $_POST['cryptocompare_api_key'] : '';
            $this->config['locale'] = isset($_POST['locale']) ? $_POST['locale'] : 'en';
            $this->config['thousands_separator'] = isset($_POST['thousands_separator']) ? $_POST['thousands_separator'] : ',';
            $this->config['decimal_separator'] = isset($_POST['decimal_separator']) ? $_POST['decimal_separator'] : '.';
            $this->config['price_margin'] = isset($_POST['price_margin']) ? $_POST['price_margin'] : 0;
            $this->config['google_maps_api_key'] = isset($_POST['google_maps_api_key']) ? $_POST['google_maps_api_key'] : '';
            $this->config['asset_recognition_regexp'] = isset($_POST['asset_recognition_regexp']) ? stripslashes($_POST['asset_recognition_regexp']) : '';
            $this->config['asset_page_regexp'] = isset($_POST['asset_page_regexp']) ? stripslashes($_POST['asset_page_regexp']) : '';
            $this->config['asset_page_content'] = isset($_POST['asset_page_content']) ? stripslashes($_POST['asset_page_content']) : '';
            $this->config['enqueue_priority'] = isset($_POST['enqueue_priority']) ? $_POST['enqueue_priority'] : 10;
            // save settings
            $settingsSaved = update_option(self::CODE . '_config', $this->config);
            flush_rewrite_rules(FALSE);
        }

        require_once($this->pluginDir . self::ADMIN_TEMPLATES . '/settings.php');
    }

    /**
     * Enqueue style
     * @param $code
     * @param $filePath
     * @param array $dependencies
     */
    private function loadStyle($code, $filePath, $dependencies = [])
    {
        wp_enqueue_style($code, substr($filePath, 0, 4) != 'http' ? $this->pluginUrl . '/' . $filePath : $filePath, $dependencies, self::VERSION);
    }

    /**
     * Enqueue JavaScript
     * @param $code
     * @param $filePath
     * @param array $dependencies
     * @param bool|FALSE $inFooter
     */
    private function loadScript($code, $filePath = NULL, $dependencies = [], $inFooter = FALSE)
    {
        // load dev build if it exists and debug mode is turned on, otherwise load production build
        if ($filePath) {
            wp_enqueue_script($code, substr($filePath, 0, 4) != 'http' ? $this->pluginUrl . '/' . $filePath : $filePath, $dependencies, self::VERSION, $inFooter);
        } else {
            // enqueue built-in script like jQuery UI, underscore etc
            wp_enqueue_script($code);
        }
    }

    /**
     * Add custom JavaScript variables
     * @param $code
     * @param $objectName
     * @param $objectProperties
     */
    private function localizeScript($code, $objectName, $objectProperties)
    {
        wp_localize_script($code, $objectName, $objectProperties);
    }
}

?>
