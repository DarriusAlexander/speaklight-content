<?php


class CMC_Advanced_Single_Shortcode
{

    protected $titan_settings;
    /*
    |--------------------------------------------------------------------------
    | bootstraping all shortcodes and all required methods
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        if (!is_admin()) {

            add_action('init', array($this, 'cmc_save_metadata'));
            add_action('init', array($this, 'cmc_init_titan'));
            add_action('wp_enqueue_scripts', array($this, 'cmc_single_assets'));
            add_filter('body_class', array($this, 'cmc_add_class_to_body_tag'),10,1);

            add_filter('the_title', array($this, 'cmc_custom_page_title'), 10, 2);

           // add_shortcode('cmc-coin-name', array($this, 'cmc_coin_name_shortcode'));
        }

        // single shortcode for coin details page advanced design
        add_shortcode('cmc-single-coin-details-advanced-design', array($this, 'cmc_shortcode_advanced_details_page_design'));

        add_filter('pre_get_document_title', array($this, 'cmc_add_coin_name_to_title'), 10, 1);
        add_action('wp_head', array($this, 'cmc_custom_meta_des'), 5);
        /* Yoast Filter hooks */
        
        add_filter('rank_math/frontend/title', array($this, 'cmc_add_coin_name_to_title'));
        add_filter('rank_math/frontend/description', array($this, 'cmc_add_coin_name_to_title') );
        add_filter('rank_math/frontend/canonical', array($this, 'rankmath_canonical_url') );

        add_filter('wpseo_title', array($this, 'cmc_add_coin_name_to_title'), 10, 1);
        add_filter('wpseo_opengraph_title', array($this, 'cmc_add_coin_name_to_title'), 10, 1);
        add_filter('wpseo_metadesc', array($this, 'cmc_open_graph_desc'), 10, 1);
        add_filter('wpseo_opengraph_desc', array($this, 'cmc_open_graph_desc'), 10, 1);
        add_action('wp', array($this, 'remove_canonical')); //After WP object is

        add_action('plugins_loaded', array($this, 'cmc_load_calculator'));

        add_action('wp_ajax_nopriv_cmc_coin_chart', array($this, 'cmc_coin_historical_callback'));
        add_action('wp_ajax_cmc_coin_chart', array($this, 'cmc_coin_historical_callback'));
    }

    public function rankmath_canonical_url(){
        $coin_symbol = get_query_var('coin_symbol');
        $coin_name = trim(get_query_var('coin_id'));
        global $post,$wp;
        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');
        if ($post == null || $post->ID != $single_page_id ) {
            return;
        }

        $single_page_slug = cmc_get_page_slug();
        $coin_url = esc_url(home_url($single_page_slug . '/' . $coin_symbol . '/' . $coin_name . '/', '/'));
        $desc = $this->cmc_generate_desc($position = "top");
        $meta_des = esc_html($desc);
        
        $current_page = home_url($wp->request);
        $site_name = get_bloginfo('name');
        echo '<meta property="og:url" content="' . $current_page . '"/>
        <meta property="og:site_name" content="' . $site_name . '"/>';

        $coin_id=(string) $coin_name;
        if(isset($coin_id)){
            $logo= coin_logo_url($coin_id);
            if(isset($logo['logo'])){
                    $logo_path=  $logo['logo'] ;
                 echo '<meta property="og:image" content="'. $logo_path .'"/>';
               }
           }

           echo '
           <link rel="canonical" href="' . $coin_url . '" />';
    }

    /*
    |-------------------------------------------------------------------|
    |   Add a custom class to the HTML body tag,                        |
    |   only if current page is selected coin details page              |
    |-------------------------------------------------------------------|
    */
    public function cmc_add_class_to_body_tag($classes){

        GLOBAL $post;
        if( isset( $post->ID ) && $post->ID == get_option('cmc-coin-advanced-single-page-id') ){
            return array_merge($classes, array('cmc-advanced-single-page') );
        }
        return $classes;
    }

    /*
    |----------------------------------------------------------|
    | On init saved meta data                                  |
    |----------------------------------------------------------|
    */
    public function cmc_save_metadata()
    {
        if (false === ($cache = get_transient('cmc-saved-extradata'))) {
            $rs = save_cmc_extra_data();
            update_option('cmc-coins-meta-saving-time', time());
            $timing = MONTH_IN_SECONDS;
            set_transient('cmc-saved-extradata', date('d/m H:s:i'), $timing);
        }
        if (false === ($cache = get_transient('cmc-saved-desc'))) {
            //fetching coin full description
            $rs = save_coin_desc_data();
            update_option('cmc-coins-desc-saving-time', time());
            $timing = MONTH_IN_SECONDS;
            set_transient('cmc-saved-desc', date('d/m H:s:i'), $timing);
        }

    }

    /*
    |---------------------------------------------------------------|
    | Ajax Callback handler for coin chart and historical table     |
    |---------------------------------------------------------------|
    */
    public function cmc_coin_historical_callback()
    {
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'cmc-ajax-nonce' ) ){
        die ('Please refresh window and check it again');
        }
        $coin_symbol = $_REQUEST['symbol'];
        $type = $_REQUEST['type'];
        if ($type == "chart") {
            $resposne['status'] = 'success';
            $resposne['data'] = coin_chart_data_json($coin_symbol, $type);
            echo json_encode($resposne);
        } else {
            $c_data = coin_chart_data_json($coin_symbol, $type);
            if ($c_data != null) {
                $resposne['data'] = $c_data;

            } else {
                $resposne['data'] = array();
            }
            echo json_encode($resposne);
        }
        wp_die();
    }

    /*
    |-------------------------------------------------------------------|
    |Remove - Canonical from - [cmc currency details - Page]            |
    |-------------------------------------------------------------------|
    */
    public function remove_canonical()
    {
        if (  is_page('cmc-currency-details-advanced-design')) {
            add_filter('wpseo_canonical', '__return_false', 10, 1);
        }
    }

    /*
    |-------------------------------------------------------------------|
    |  Single Page URL Bar Title                                        |
    |-------------------------------------------------------------------|
    */
    public function cmc_add_coin_name_to_title($cmc_title)
    {
        global $post;
        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');
        if ($post == null) {
            return;
        }
        if ($post->ID == $single_page_id) {
            $cmc_title = $this->cmc_generate_title($position = 'top');
        }
        /* Return the title. */
        return $cmc_title;
    }

    /*
    |-----------------------------------------------------------------------|
    |  Single Page Title                                                    |
    |-----------------------------------------------------------------------|
    */
    public function cmc_custom_page_title($title, $id = null)
    {
        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');
        if ($id == $single_page_id) {
            $title = $this->cmc_generate_title($position = 'default');
        }
        return $title;
    }

    /*
    |--------------------------------------------------------------------------|
    | load calculator shortcode                                                |
    |--------------------------------------------------------------------------|
    */
    public function cmc_load_calculator()
    {
        //calculator
        require_once('cmc-calculator.php');
        add_shortcode('cmc-calculator-ad', 'cmc_calculator');
    }

    /*
    |--------------------------------------------------------------------------|
    | Yoast open tag description                                               |
    |--------------------------------------------------------------------------|
    */
    public function cmc_open_graph_desc($desc)
    {
        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');
        if (is_page($single_page_id)) {
            return $desc = $this->cmc_generate_desc($position = "top");

        }
        return $desc;
    }

    /*
    |--------------------------------------------------------------------------|
    | Display custom meta description                                          |
    |--------------------------------------------------------------------------|
    */
    public function cmc_custom_meta_des()
    {

        // coin detail page meta desc
        global $post;
        if ($post == null) {
            return;
        }
        $single_page_id = get_option('cmc-coin-advanced-single-page-id');
        if ($post->ID == $single_page_id && !class_exists( 'RankMath' ) ) {
            $coin_symbol = get_query_var('coin_symbol');
            $coin_name = trim(get_query_var('coin_id'));
            global $wp;
            $single_page_slug = cmc_get_page_slug();
            $coin_url = esc_url(home_url($single_page_slug . '/' . $coin_symbol . '/' . $coin_name . '/', '/'));
            $desc = $this->cmc_generate_desc($position = "top");
            $meta_des = esc_html($desc);
            echo '<link rel="canonical" href="' . $coin_url . '" />';

            if (!defined('WPSEO_VERSION')) {
                echo '<meta name="description" content="' . $meta_des . '"/>';
                echo '<meta property="og:description" content="' . $meta_des . '"/>';
                echo '<meta property="og:title" content="' . get_the_title() . '"/>';

                $current_page = home_url($wp->request);

                echo '<meta property="og:type" content="article"/>';
                echo '<meta property="og:url" content="' . $current_page . '"/>';
                $site_name = get_bloginfo('name');
                // Customize the below with the name of your site
                echo '<meta property="og:site_name" content="' . $site_name . '"/>';
                $coin_id=(string) $coin_name;
			    if(isset($coin_id)){
			        $logo= coin_logo_url($coin_id);
				    if(isset($logo['logo'])){
					        $logo_path=$logo['logo'];
            			 echo '<meta property="og:image" content="'.$logo_path.'"/>';

		       		}
	   			}
            }
        }

    }

    /*
    |--------------------------------------------------------------------------|
    |Dynamic Title shortcode                                                   |
    |--------------------------------------------------------------------------|
    */
    public function cmc_coin_name_shortcode($atts, $content = null)
    {
        $atts = shortcode_atts(array(
            'type' => 'name',
        ), $atts);
        $output = '';
        if (get_query_var('coin_id')) {
            $coin_id = (string) trim(get_query_var('coin_id'));
            $coin_symbol = (string) trim(get_query_var('coin_symbol'));
            $name = str_replace("-", " ", $coin_id);
            if ($atts['type'] == "symbol") {
                $output .= '<span class="cmc-coin-symbol">' . $coin_symbol . '</span>';
            } else {
                $output .= '<span class="cmc-coin-name">' . ucwords($name) . '</span>';
            }

        }
        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    |Dynamic description for SEO                                               |
    |--------------------------------------------------------------------------|
    */
    public function cmc_dynamic_description_ad()
    {

        $output = '';
        $desc = $this->cmc_generate_desc($position = "default");
        $output .= '<div class="cmc_dynamic_description"><p>
             ' . $desc . '</p></div>';
        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | generating coin dynamic description                                      |
    |--------------------------------------------------------------------------|
    */
    public function cmc_generate_desc($position)
    {
        $desc = '';
        $dynamic_desciption = $this->titan_settings['dynamic_desciption'];
        $enable_formatting = $this->titan_settings['s_enable_formatting'];
        if (get_query_var('coin_id')) {
            //changed to coin name
            $coin_id = (string) trim(get_query_var('coin_id'));
            $real_cur = get_query_var('currency');
            $single_default_currency = $this->titan_settings['default_currency'];
            $old_currency = trim($real_cur) !== "" ? trim($real_cur) : $single_default_currency;
            $currency_icon = cmc_old_cur_symbol($old_currency);
            $fiat_c_rate = cmc_usd_conversions($old_currency);
            //grabing data from DB
            $coin = cmc_get_coin_details($coin_id);
            if ($coin) {
                $coin_symbol = $coin['symbol'];
                $coin_name = $coin['name'];
                $supply = $coin["circulating_supply"];
                if (!empty($coin['price'])) {
                    $price = $coin['price'] * $fiat_c_rate;
                    $coin_price = format_number($price);
                }

                $market_cap = '';
                if (isset($coin['market_cap'])) {
                    $market_cap = $coin['market_cap'] * $fiat_c_rate;
                    if ($enable_formatting) {
                        $market_cap = $currency_icon . cmc_format_coin_values($market_cap);
                    } else {
                        $market_cap = $currency_icon . format_number($market_cap);
                    }
                } else {
                    $market_cap = __('N/A', 'cmc');
                }

                $change_sign_minus = "-";
                $change_lbl = '';
                if (strpos($coin['percent_change_24h'], $change_sign_minus) !== false) {
                    $change_lbl = __('down', 'cmc');
                } else {
                    $change_lbl = __('up', 'cmc');
                }
                $changes = number_format($coin['percent_change_24h'], '2', '.', '') . '%';
                $name = $coin_name . ' (' . $coin_symbol . ')';
                $dynamic_array = array($name, $currency_icon . $coin_price, $market_cap, $changes . ' ' . $change_lbl);
                $placeholders = array('[coin-name]', '[coin-price]', '[coin-marketcap]', '[coin-changes]');
                $desc = str_replace($placeholders, $dynamic_array, $dynamic_desciption);
            }
            return $desc;
        }

    }

    /*
    |--------------------------------------------------------------------------|
    | Dynamic Title for SEO                                                    |
    |--------------------------------------------------------------------------|
    */
    public function cmc_dynamic_title_ad()
    {
        
        $output = '';
        $desc = '';
        $title_txt = $this->cmc_generate_title($position = 'default');
        $output = '<h1 class="cmc-dynamic-title">' . $title_txt . '</h1>';
        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin custom dynamic title from plugin settings panel                     |
    | Shortcode:-[cmc-dynamic-title]                                           |
    |--------------------------------------------------------------------------|
    */
    //creating dynamic title
    public function cmc_generate_title($position)
    {
        $title_txt = '';
        if (get_query_var('coin_id')) {
            $dynamic_title = $this->titan_settings['dynamic_title'];
            $single_default_currency = $this->titan_settings['default_currency'];
            $enable_formatting = $this->titan_settings['s_enable_formatting'];
            $coin_id = (string) trim(get_query_var('coin_id'));
            $real_cur = get_query_var('currency');
            $old_currency = trim($real_cur) !== "" ? trim($real_cur) : $single_default_currency;

            $currency_icon = cmc_old_cur_symbol($old_currency);
            $fiat_c_rate = cmc_usd_conversions($old_currency);
            //grabing data from DB
            $coin = cmc_get_coin_details($coin_id);
            if ($coin) {
                $coin_symbol = $coin['symbol'];
                $coin_name = $coin['name'];
                $supply = $coin["circulating_supply"];
                $market_cap = $coin["market_cap"];

                if (!empty($coin['price'])) {
                    $price = $coin['price'] * $fiat_c_rate;
                    $coin_price = format_number($price);
                }

                $market_cap = '';
                if (isset($coin['market_cap'])) {
                    $market_cap = $coin['market_cap'] * $fiat_c_rate;
                    if ($enable_formatting) {
                        $market_cap = $currency_icon . cmc_format_coin_values($market_cap);
                    } else {
                        $market_cap = $currency_icon . format_number($market_cap);
                    }
                } else {
                    $market_cap = __('N/A', 'cmc');
                }

                $change_sign_minus = "-";
                $change_lbl = '';
                if (strpos($coin['percent_change_24h'], $change_sign_minus) !== false) {
                    $change_lbl = __('down', 'cmc');
                } else {
                    $change_lbl = __('up', 'cmc');
                }
                $changes = $coin['percent_change_24h'] . '%';
                $name = $coin_name . ' (' . $coin_symbol . ')';
                $dynamic_array = array($name, $currency_icon . $coin_price, $market_cap, $changes);
                $placeholders = array('[coin-name]', '[coin-price]', '[coin-marketcap]', '[coin-changes]');
                $title_txt = str_replace($placeholders, $dynamic_array, $dynamic_title);
            }
            return $title_txt;
        }
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin custom dynamic description from plugin settings panel               |
    | Shortcode:-[cmc-dynamic-description]                                     |
    |--------------------------------------------------------------------------|
    */
    public function cmc_description_ad()
    {

        $output = '';
        $description = '';
        $display_api_desc = $this->titan_settings['display_api_desc'];
        if (get_query_var('coin_id')) {
            $coin_id = (string) trim(get_query_var('coin_id'));

            if ($display_api_desc) {
                $dbDescription = cmc_get_coin_desc($coin_id);
                if ($dbDescription !== false) {
                    $description = $dbDescription;
                }
            }

            // The Query
            $query = array('post_type' => 'cmc-description', 'meta_value' => $coin_id);
            $the_query = new WP_Query($query);
            // The Loop
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $cmcd_id = get_the_ID();
                    //$meta = get_post_meta($cmcd_id, 'cmc_single_settings_coin_description_editor', true);
                    $meta = cmc_get_wysiwyg_output('cmc_single_settings_coin_description_editor', $cmcd_id);
                }
                /* Restore original Post Data*/
                wp_reset_postdata();
            }

            $coin_desc = !empty($meta) ? $meta : $description;
            if ($coin_desc != '') {
                $output .= '<div class="cmc-coin-info">' . $coin_desc . '</div>';
            }
        }
        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin main information handler shortcode                                  |
    | Shortcode:-[coin-market-cap-details-ad]                                  |
    |--------------------------------------------------------------------------|
    */
    public function cmc_coin_details_ad()
    {
        $output = '';

        $post_id = get_option('cmc-post-id');
        $coin_released = '365day';

        if (get_query_var('coin_id')) {
            // changed from symbol to name based
            $coin_id = (string) trim(get_query_var('coin_id'));
            $coin = cmc_get_coin_details($coin_id);
            $real_cur = get_query_var('currency');
            $single_default_currency = $this->titan_settings['default_currency'];
            $enable_formatting = $this->titan_settings['s_enable_formatting'];
            $single_live_updates = $this->titan_settings['single_live_updates'];

            $old_currency = trim($real_cur) !== "" ? trim($real_cur) : $single_default_currency;

            $fiat_c_rate = cmc_usd_conversions($old_currency);
            $currency_symbol = cmc_old_cur_symbol($old_currency);
            if ($coin) {
                $mainId = $coin['id'];
                $coin_symbol = $coin['symbol'];
                $coin_name = $coin['name'];
                $currency_icon = cmc_old_cur_symbol($old_currency);

                $percent_change_24h = number_format($coin['percent_change_24h'], '2', '.', '') . '%';
                $supply = $coin["circulating_supply"];

                if (!empty($coin['price'])) {
                    $price = $coin['price'] * $fiat_c_rate;
                    $coin_price = format_number($price);
                }
                if (isset($coin['total_volume'])) {
                    $volume = $coin['total_volume'] * $fiat_c_rate;
                    if ($enable_formatting) {
                        $volume = cmc_format_coin_values($volume);
                    } else {
                        $volume = format_number($volume);
                    }
                } else {
                    $volume = __('N/A', 'cmc');
                }
                $market_cap = '';
                if (isset($coin['market_cap'])) {
                    $market_cap = $coin['market_cap'] * $fiat_c_rate;
                    if ($enable_formatting) {
                        $market_cap = $currency_icon . cmc_format_coin_values($market_cap);
                    } else {
                        $market_cap = $currency_icon . format_number($market_cap);
                    }
                } else {
                    $market_cap = __('N/A', 'cmc');
                }
                if ($supply) {
                    if ($enable_formatting) {
                        $available_supply = cmc_format_coin_values($supply);
                    } else {
                        $available_supply = number_format($supply);
                    }
                } else {
                    $available_supply = __('N/A', 'cmc');
                }

                $change_sign = '<i class="cmc_icon-up" aria-hidden="true"></i>';
                $change_class = 'cmc-up';
                $change_sign_minus = "-";
                $change_sign_24h = '<i class="cmc_icon-up" aria-hidden="true"></i>';
                $change_class_24h = 'cmc-up';
                if (strpos($coin['percent_change_24h'], $change_sign_minus) !== false) {
                    $change_sign_24h = '<i class="cmc_icon-down" aria-hidden="true"></i>';
                    $change_class_24h = 'cmc-down';
                }
                $all_c_p_html = '';

                $live_updates_cls = "";
                if ($single_live_updates) {
                    $live_updates_cls = "cmc_live_updates";
                }
                $coin_logo = cmc_coin_single_logo($coin_id,50);
                $coin_price_html = '';
                $coin_price_html .= '<span  data-coin-price="' . $price . '" class="cmc_coin_price coin-price">' . $currency_icon . $coin_price . '</span>';
                $output .= '
                <div id="cmc-single-style1"
                class="single_lU_wrp  ' . $live_updates_cls . '"
				data-currency-symbol="' . $currency_symbol . '"
                data-currency-rate="' . $fiat_c_rate . '"
                data-currency-name="' . $old_currency . '"
                data-coin-symbol="' . $coin_symbol . '"
                data-coin-id="' . $coin_id . '"
                data-coin-price="' . $coin['price'] . '">
                    <div class="cmc-top-style1">
                        <div class="cmc-logo-style1"><h2>' . $coin_logo . ' ' . $coin_name . ' (' . $coin_symbol . ')</h2></div>

                        <div class="cmc-price-style1">
                        <div class="chart_coin_price CCP-' . $coin_symbol . '">' . $coin_price_html . '</div>';
                        if ( $this->titan_settings['display_changes24h_single'] ) {
                            $output .= '<div class="cmc-changes-style1 ' . $change_class_24h . '">' . $change_sign_24h . $percent_change_24h . '<span> (' . __('24H', 'cmc') . ')</span></div>';
                        }
                        $output .= '<div data-watch-title="'.__('Add to watch list','cmc').'" data-watch-text="'.__('Watch','cmc').'" data-unwatch-text="'.__('Unwatch','cmc').'" data-unwatch-title="'.__('Remove from watch list','cmc').'" data-coin-id="' . $coin_id . '" class="btn_cmc_watch_list cmc_icon-star-empty">' . __('Watch', 'cmc') . '</div>
                        </div>

                        <div class="cmc-buy-sell-style1">' . $this->cmc_affiliate_links_ad() . '</div>
                    </div>


                    <div class="cmc-middle-style1">';
                        $output .= $this->cmc_coin_extra_data_ad();

                        $output .= '<div class="cmc-info-style1">
                            <table>
                            <tr>';
                            if ( $this->titan_settings['display_market_cap_single'] ) {
                                $output .= '<th>' . __('Market Cap', 'cmc') . '</th>';
                            }
                            if ( $this->titan_settings['display_Volume_24h_single'] ) {
                                $output .= '<th>' . __('Volume', 'cmc') . '</th>';
                            }
                            if ( $this->titan_settings['display_supply_single'] ) {
                                $output .= '<th>' . __('Available Supply', 'cmc') . '</th>';
                            }
                            $output .= '</tr>
                            <tr>';
                            if ( $this->titan_settings['display_market_cap_single'] ) {
                                $output .= '<td><span class="CCMC">' . $market_cap . '</span></td>';
                            }
                            if ( $this->titan_settings['display_Volume_24h_single'] ) {
                                $output .= ' <td><span class="CCV-' . $coin_symbol . '">' . $currency_icon . $volume . '</span></td>';
                            }
                            if ( $this->titan_settings['display_supply_single'] ) {
                                $output .= '<td><span class="CCS-' . $coin_symbol . '">' . $available_supply . '</span> <span class="coin-symbol">' . $coin_symbol . '</span></td>';
                            }
                            $output .= '</tr>
                            </table>';

                            //$output .= do_shortcode('[cmc-technical-analysis autosize="true" theme="light"]');
                            $output .= $this->cmc_dynamic_description_ad();
                            $output .= $this->cmc_description_ad();
                        $output .= '</div>
                    </div>
                </div>';
            } else {
                return __('Currency Not Found', 'cmc');
            }
        } else {
            return __('Something wrong with URL', 'cmc');
        }

        return $output;

    }

    /*
    |--------------------------------------------------------------------------|
    | Coin historical data datatable shortcode                                 |
    | Shortcode:-[cmc-history]                                                 |
    |--------------------------------------------------------------------------|
    */
    public function cmc_historical_data_ad()
    {
        $output = '';
        $real_cur = get_query_var('currency');
        $single_default_currency = $this->titan_settings['default_currency'];
        $enable_formatting = $this->titan_settings['s_enable_formatting'];
        $old_currency = trim($real_cur) !== "" ? trim($real_cur) : $single_default_currency;
        //$selected_currency_rate = cmc_usd_conversions($old_currency);
        //$currency_symbol = cmc_old_cur_symbol($old_currency);
        if (get_query_var('coin_id')) {
            $coin_id = trim(get_query_var('coin_id'));
            if ($coin_id == "MIOTA") {
                $coin_id = 'IOT';
            } else if ($coin_id == "BTX") {
                $coin_id = 'BTX2';
            } else if ($coin_id == '0xBTC') {
                $coin_id = '0XBTC';
            }

            $cmc_prev = __('Previous', 'cmc');
            $cmc_next = __('Next', 'cmc');
            $cmc_show = __('Show', 'cmc');
            $cmc_entries = __('Entries', 'cmc');
            $no_data = __('No Historical Data Available', 'cmc');
            $cmc_show_entries = sprintf("%s _MENU_ %s", $cmc_show, $cmc_entries);
            $output .= '<div class="cmc-coin-historical-data">

	<table  id="cmc_historical_tbl" data-number-formating="' . $enable_formatting . '"
	class="display table table-striped table-bordered" data-no-data-lbl="' . $no_data . '"
   data-per-page="10" data-show-entries="' . $cmc_show_entries . '" data-prev="' . $cmc_prev . '" data-next="' . $cmc_next . '"
	data-coin-id="'. $coin_id.'" data-currency-symbol="$">
	<thead><tr>
	<th data-classes="cmc_h_date"  data-index="date">' . __('Date', 'cmc') . '</th>
	<th data-classes="cmc_h_price" data-index="value">' . __('Price', 'cmc') . '</th>
	<th data-classes="cmc_h_volume" data-index="volume">' . __('Volume', 'cmc') . '</th>
	<th data-classes="cmc_h_marketcap" data-index="market_cap">' . __('MarketCap', 'cmc') . '</th>
	</tr></thead><tbody>';
            $output .= '</tbody>
	</table></div>';
        } else {
            return '<b>' . __('Something Wrong With URL', 'cmc') . '</b>';
        }

        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin Full Chart shortcode callback                                       |
    | Shortcode:-[cmc-chart]                                                   |
    |--------------------------------------------------------------------------|
    */
    public function cmc_chart_shortcode_ad()
    {
        $output = '';

        if (get_query_var('coin_id')) {

            $single_default_currency = $this->titan_settings['default_currency'];
            $coin_symbol = get_query_var('coin_symbol');
            $coin_id = (string) trim(get_query_var('coin_id'));
            $real_cur = get_query_var('currency');
            $old_currency = trim($real_cur) !== "" ? trim($real_cur) : $single_default_currency;
            $fiat_c_rate = cmc_usd_conversions($old_currency);
            $currentVol = '';
            $currentPrice = '';
            //grabing data from DB
            $coin = cmc_get_coin_details($coin_id);
            if ($coin) {
                $currentVol = $coin['total_volume'];
                $currentPrice = $coin['price'];
            }

            $chart_height = '100%';
            $coin_released = '365day';

            if ($coin_symbol == "MIOTA") {
                $coin_symbol = 'IOT';
            } else if ($coin_symbol == "BTX") {
                $coin_symbol = 'BTX2';
            } else if ($coin_symbol == '0xBTC') {
                $coin_symbol = '0XBTC';
            }

            $c_color = $this->titan_settings['chart_color'];
            if (isset($c_color) && !empty($c_color)) {
                $chart_color = $c_color;
            } else {
                $chart_color = "#8BBEED";
            }
            $chart_from = __('From', 'cmc');
            $chart_to = __('To', 'cmc');
            $chart_zoom = __('Zoom', 'cmc');
            $chart_price = __('Price', 'cmc');
            $chart_volume = __('Volume', 'cmc');
            $output .= '<div class="cmc-chart" data-coin-current-price="' . $currentPrice . '" data-coin-current-vol="' . $currentVol . '" data-fiat-c-rate="' . $fiat_c_rate . '" data-coin-period="' . $coin_released . '" data-coin-id="' . $coin_id . '"
		data-chart-color="' . $chart_color . '" data-chart-from="' . $chart_from . '" data-chart-to="' . $chart_to . '"
		data-chart-zoom="' . $chart_zoom . '" data-chart-price="' . $chart_price . '" data-chart-volume="' . $chart_volume . '">';
            $output .= '<div id="cmc-chart-preloader"><img class="cmc-preloader" src="' . CMC_URL . 'images/chart-loading.svg"><br/>' . __('Loading Chart...', 'cmc') . '</div>';
            $output .= '<div style="display:none" id="cmc-no-data">' . __('No Graphical Data', 'cmc') . '</div>';
            $output .= '<div class="cmc-wrp"  id="CMC-CHART-' . $coin_id . '" style="width:100%; height:' . $chart_height . ';" >
		</div></div>';

        }
        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin twitter feed handler shortcode                                      |
    | Shortcode:-[cmc-twitter-feed]                                            |
    |--------------------------------------------------------------------------|
    */
    public function cmc_twitter_feed_ad()
    {
        if (get_query_var('coin_id')) {            
            $twitter_feed_type = $this->titan_settings['twitter_feed_type'];
            $coin_id = (string) trim(get_query_var('coin_id'));
            $coin_symbol = get_query_var('coin_symbol');

            // The Query
            $query = array('post_type' => 'cmc-description', 'meta_value' => $coin_id);
            $the_query = new WP_Query($query);
            $twitter_name = '';
            // The Loop
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $cmcd_id = get_the_ID();
                    $coin_twt_name = get_post_meta($cmcd_id, 'cmc_single_settings_coin_twt', true);
                    $twitter_name = $this->cmc_get_twitter_id_from_url($coin_twt_name);
                }

            } else {

                $coin_data = cmc_get_coin_meta($coin_id);
                $twitter_name = '';
                if (isset($coin_data['twitter']) && $coin_data['twitter'] != "N/A") {
                    $twitter_name = $this->cmc_get_twitter_id_from_url($coin_data['twitter']);
                }
            }
            /* Restore original Post Data*/
            wp_reset_postdata();

            if ($twitter_feed_type == 'hashtag' || $twitter_name == '') {
                return do_shortcode('[custom-twitter-feeds hashtag="#' . $coin_symbol . '"]');
            } else {
                return do_shortcode('[custom-twitter-feeds screenname="' . $twitter_name . '"]');
            }

        }
    }

    /*
    |--------------------------------------------------------------------------|
    | Coin links shortcode handler                                             |
    | Shortcode:- [cmc-coin-extra-data]                                        |
    |--------------------------------------------------------------------------|
    */
    public function cmc_coin_extra_data_ad()
    {

        global $wpdb;

        if (get_query_var('coin_id')) {
            $coin_id = (string) trim(get_query_var('coin_id'));
            $coin_symbol = (string) trim(get_query_var('coin_symbol'));
            $coin_data = cmc_get_coin_meta($coin_id);

            $rank = 'N/A';
            $coin_rank = 1;
            $id = (string) trim(get_query_var('coin_id'));
            $table = $wpdb->base_prefix . 'cmc_coins';
            $result = $wpdb->get_results("SELECT coin_id FROM $table ORDER BY market_cap DESC");
            $result = objectToArray( $result );
            
            foreach ($result as $coins) {

                if (strcasecmp( $id , trim($coins['coin_id'])) === 0) {
                    $rank = $coin_rank;
                    break;
                }
                $coin_rank++;
            }
            // The Query
            $query = array('post_type' => 'cmc-description', 'meta_value' => $coin_id);
            $the_query = new WP_Query($query);
            // The Loop
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $cmcd_id = get_the_ID();
                    $coin_be = get_post_meta($cmcd_id, 'cmc_single_settings_coin_be', true);
                    $coin_ow = get_post_meta($cmcd_id, 'cmc_single_settings_coin_ow', true);
                    $coin_wp = get_post_meta($cmcd_id, 'cmc_single_settings_coin_wp', true);
                    $coin_yt = get_post_meta($cmcd_id, 'cmc_single_settings_coin_yt', true);
                    $coin_rd = get_post_meta($cmcd_id, 'cmc_single_settings_coin_rd', true);

                    $coin_gh = get_post_meta($cmcd_id, 'cmc_single_settings_coin_gh', true);
                    $coin_fb = get_post_meta($cmcd_id, 'cmc_single_settings_coin_fb', true);

                    $coin_twt = get_post_meta($cmcd_id, 'cmc_single_settings_coin_twt', true);
                    $coin_redt = get_post_meta($cmcd_id, 'cmc_single_settings_coin_redt', true);

                }
                /* Restore original Post Data*/
                wp_reset_postdata();
                if (isset($coin_be) && !empty($coin_be)) {
                    $block_explorer = $coin_be;
                } else {
                    $block_explorer = $coin_data['block_explorer'];
                }
                if (isset($coin_ow) && !empty($coin_ow)) {
                    $website = $coin_ow;
                } else {
                    $website = $coin_data['website'];
                }
                if (isset($coin_wp) && !empty($coin_wp)) {
                    $whitepaper = $coin_wp;
                } else {
                    $whitepaper = $coin_data['whitepaper'];
                }
                if (isset($coin_yt) && !empty($coin_yt)) {
                    $youtube = $coin_yt;
                } else {
                    $youtube = $coin_data['youtube'];
                }
                if (isset($coin_rd) && !empty($coin_rd)) {
                    $announced = $coin_rd;
                } else {
                    $announced = $coin_data['announced'];
                }
                if (isset($coin_fb) && !empty($coin_fb)) {
                    $facebook = $coin_fb;
                } else {
                    $facebook = $coin_data['facebook'];
                }
                if (isset($coin_gh) && !empty($coin_gh)) {
                    $github = $coin_gh;
                } else {
                    $github = $coin_data['github'];
                }

                if (isset($coin_twt) && !empty($coin_twt)) {
                    $twitter = $coin_twt;
                } else {
                    $twitter = $coin_data['twitter'];
                }
                if (isset($coin_redt) && !empty($coin_redt)) {
                    $reddit = $coin_redt;
                } else {
                    $reddit = $coin_data['reddit'];
                }

            } else {
                if( is_array( $coin_data ) && !empty($coin_data) ){
                    extract($coin_data, EXTR_PREFIX_SAME, "dup");
                }
            }
            $output = '';
            $output .= '<div class="cmc-social-style1"><ul>';

            $output .= '<li><i class="cmc_icon-rank-1"></i> <span>' . sprintf(__('Rank %s', 'cmc'), $rank) . '</span></li>';

            if (isset($block_explorer) && $block_explorer != "N/A") {
                $output .= '<li><i class="cmc_icon-block-explorer"></i> <a target="_blank" href="' . $block_explorer . '" rel="nofollow">' . __('Block Explorer', 'cmc') . '</a></li>';
            };
            if (isset($website) && $website != "N/A") {
                $output .= '<li><i class="cmc_icon-website"></i> <a target="_blank"  href="' . $website . '" rel="nofollow">' . __('Official Website', 'cmc') . '</a></li>';
            };
            if (isset($whitepaper) && $whitepaper != "N/A") {
                $output .= '<li><i class="cmc_icon-whitepaper"></i> <a target="_blank" href="' . $whitepaper . '" rel="nofollow">' . __('White Paper', 'cmc') . '</a></li>';
            };
            if (isset($youtube) && $youtube != "N/A") {
                $output .= '<li><i class="cmc_icon-youtube"></i> <a target="_blank" href="' . $youtube . '" rel="nofollow">' . $coin_symbol .' '. __('YouTube', 'cmc') . '</a></li>';
            };
            /*if (isset($announced) && $announced != "N/A") {
                $output .= '<li><i class="cmc_icon-info"></i> ' . $announced . '</li>';
            };*/
            if (isset($github) && $github != "N/A") {
                $output .= '<li><i class="cmc_icon-github"></i> <a target="_blank" href="' . $github . '" rel="nofollow">' . $coin_symbol . ' ' . __('Github', 'cmc') . '</li></a>';
            };
            if (isset($reddit) && $reddit != "N/A") {
                $redit_url = "https://www.reddit.com/r/" . $reddit;
                $output .= '<li><i class="cmc_icon-reddit"></i> <a target="_blank" href="' . $redit_url . '" rel="nofollow">' . $coin_symbol . ' ' . __('Reddit', 'cmc') . '</a></li>';
            };
            if (isset($twitter) && $twitter != "N/A") {
                $output .= '<li><i class="cmc_icon-twitter"></i> <a target="_blank" href="' . $twitter . '" rel="nofollow">' . $coin_symbol . ' ' . __('Twitter', 'cmc') . '</a></li>';
            };
            if (isset($facebook) && $facebook != "N/A") {
                $output .= '<li><i class="cmc_icon-facebook"></i> <a target="_blank" href="' . $facebook . '" rel="nofollow">' . $coin_symbol . ' ' . __('Facebook', 'cmc') . '</a></li>';
            };

            $output .= '</ul>';

		    $output .='</div>';
            return $output;

        }
    }

    /*
    |--------------------------------------------------------------------------|
    | Registering all assets for coin single page                              |
    |--------------------------------------------------------------------------|
    */
    // common assets for all shortcodes
    public function cmc_single_assets()
    {
        wp_register_script('cmc-datatables', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js','jquery',CMC);
        wp_register_script('cmc-custom-fixed-col', CMC_URL . 'assets/js/tableHeadFixer.js', array('jquery', 'cmc-datatables'), CMC, true);

        wp_register_script('cmc-single-js', CMC_URL . 'assets/js/cmc-single.js', array('jquery', 'cmc-datatables'), CMC, true);
        //    wp_register_script('cmc-historical-tbl', CMC_URL . 'assets/js/cmc-historical-tbl.js', array('jquery','cmc-datatables'), false, true);
        wp_register_script('ccpw-lscache', CMC_URL . 'assets/js/lscache.min.js', array('jquery'), CMC, true);
        wp_register_script('crypto-numeral', CMC_URL . 'assets/js/numeral.min.js', array('jquery'), CMC, true);

        wp_register_style('cmc-tab-design-custom', CMC_URL . 'assets/css/cmc-tab-design-custom.min.css',null,CMC);

        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');
        if (is_page($single_page_id)) {
            wp_enqueue_style('cmc-bootstrap');
            if( $single_page_id == get_option('cmc-coin-advanced-single-page-id') ){
                wp_enqueue_style('cmc-tab-design-custom');
            }
            wp_enqueue_script('crypto-numeral');
            wp_enqueue_script('cmc-datatables');
            wp_enqueue_script('cmc-custom-fixed-col');
            wp_enqueue_script('cmc-historical-tbl');
            wp_enqueue_script('amcharts', 'https://www.amcharts.com/lib/3/amcharts.js', array('jquery'), false, true);
            wp_enqueue_script('amcharts-serial', 'https://www.amcharts.com/lib/3/serial.js', array('jquery'), false, true);
            wp_enqueue_script('amcharts-stock', 'https://www.amcharts.com/lib/3/amstock.js', array('jquery'), false, true);

            //        if (cmc_isMobileDevice() == 0) {
            wp_enqueue_script('ccc-socket', 'https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js', array('jquery'), false, true);
            //    }

            wp_enqueue_script('ccpw-lscache');
            wp_enqueue_script('cmc-single-js');
            wp_localize_script(
                'cmc-single-js',
                'data_object',
                array('ajax_url' => admin_url('admin-ajax.php'),
                'nonce'=>wp_create_nonce('cmc-ajax-nonce'),
                )
            );

        }

    }

    /*
    |--------------------------------------------------------------------------|
    | Facebook Comment Box Shortcode Handlers                                  |
    | Shortcode:-[coin-market-cap-comments]                                    |
    |--------------------------------------------------------------------------|
    |
    */
    public function cmc_comment_box_ad()
    {
        $output = '';
        global $wp;
        $page_url = home_url($wp->request, '/');

        global $post;
        $page_id = $post->ID;
        $single_page_id = cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-id');

        if (is_page($page_id) && $page_id == $single_page_id) {
            $fb_app_id = $this->titan_settings['cmc_fb_app_Id'];
            $app_id = $fb_app_id ? $fb_app_id : '1798381030436021';

            $output .= '<div class="fb-comments" data-connect-id="'.$app_id.'" data-href="' . $page_url . '" data-width="100%" data-numposts="10"></div>';

             $output .= '<div id="fb-root"><span class="cmc-comment-preloader" >'.__('Loading facebook comment(s)','cmc').'<img src="' . CMC_URL . 'images/chart-loading.svg"></span></div>';
	    }

        return $output;
    }

    /*
    |--------------------------------------------------------------------------|
    | extracting screen name from twitter url                                  |
    |--------------------------------------------------------------------------|
    */
    public function cmc_get_twitter_id_from_url($url)
    {
        if (preg_match("/^https?:\/\/(www\.)?twitter\.com\/(#!\/)?(?<name>[^\/]+)(\/\w+)*$/", $url, $regs)) {
            return $regs['name'];
        }
        return false;
    }

    /*
    |--------------------------------------------------------------------------|
    | coin affiliate links                                                     |
    |--------------------------------------------------------------------------|
    */
    public function cmc_affiliate_links_ad($atts=null, $content = null)
    {
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts);
        $output = '';
        if (get_query_var('coin_id')) {
            $coin_id = ucfirst(get_query_var('coin_id'));
            $coin_symbol = ucfirst(get_query_var('coin_symbol'));
            $coin_name = ucwords(str_replace('-', ' ', get_query_var('coin_id')));
            $affiliate_type = $this->titan_settings['choose_affiliate_type'];
            $buy_affiliate_link = "#";
            $sell_affiliate_link = "#";
            if ($affiliate_type == "changelly_aff_id") {
                $affiliate_id = '';
                $affiliate_id = $this->titan_settings['affiliate_id'];
                $buy_affiliate_link = sprintf('https://changelly.com/exchange/USD/%s/1?ref_id=%s', $coin_symbol, $affiliate_id);
                $sell_affiliate_link = sprintf('https://changelly.com/exchange/%s/BTC/1?ref_id=%s', $coin_symbol, $affiliate_id);
            } else if ($affiliate_type == "any_other_aff_id") {
                $other_affiliate_link = $this->titan_settings['other_affiliate_link'];
                if ($other_affiliate_link) {
                    $buy_affiliate_link = $other_affiliate_link;
                    $sell_affiliate_link = $other_affiliate_link;
                }
            }
            $output = '<span class="cmc_affiliate_links">
		<a target="_blank" class="cmc_buy" href="' . $buy_affiliate_link . '"><i class="cmc_icon-buy-coin" aria-hidden="true"></i> ' . __('Buy', 'cmc') . ' ' . $coin_name . '</a>
		<a target="_blank" class="cmc_sell" href="' . $sell_affiliate_link . '"><i class="cmc_icon-sell-coin" aria-hidden="true"></i>' . __('Sell', 'cmc') . ' ' . $coin_name . '</a>
		</span>';

        }
        return $output;

    }

    /*
    |-------------------------------------------------------------|
    |    Shortcode for coin details page (Advamced Design)        |
    |-------------------------------------------------------------|
    */
    public function cmc_shortcode_advanced_details_page_design()
    {

        // primary shortcode to be render before tabs
        $primary_data = array('cmc_coin_details_ad');

        // Array contains name of all Tabs
        $tab_name = array( __('Chart','cmc'), __('Calculator','cmc'), __('Historical Data','cmc'), __('Exchanges','cmc'), __('Twitter Feeds','cmc'),__('Comments','cmc') );

        $data = '';
        ob_start();
        foreach ($primary_data as $shortcode) {
            $data .= call_user_func( array($this, $shortcode) );
        }

        $data .= "<div id='cmc-tabbed-area'>"; // main DIV

        // Main Tab buttons
        $data .= "<ul class='cmc-tab-group'>
                    <li><a class='cmc-tabsBtn active' data-id='#cmc-container-chart'><i class='cmc_icon-chart-2'></i> " . $tab_name[0] . "</a></li>
					<li><a class='cmc-tabsBtn' data-id='#cmc-container-calc'><i class='cmc_icon-calculator'></i> " . $tab_name[1] . "</a></li>
                    <li><a class='cmc-tabsBtn' data-id='#cmc-container-history-data'><i class='cmc_icon-history'></i> " . $tab_name[2] . "</a></li>";

        if( class_exists('Crypto_Currency_Exchanges_List') ){
            $data .= "	<li><a class='cmc-tabsBtn' data-id='#cmc-container-exchanges'><i class='cmc_icon-chart'></i> " . $tab_name[3] . "</a></li>";
        }

		$data .= "	<li><a class='cmc-tabsBtn' data-id='#cmc-container-twitter-feeds'><i class='cmc_icon-twitter-2'></i> " . $tab_name[4] . "</a></li>
					<li><a class='cmc-tabsBtn' data-id='#cmc-container-facebook-comments'><i class='cmc_icon-comment'></i> " . $tab_name[5] . "</a></li>
                 </ul>";
        // end of Tabs

        $data .= "<div class='cmc-containers-group'>";

        $data .= "<div id='cmc-container-chart' class='cmc-data-container active'>";
        $data .= $this->cmc_chart_shortcode_ad();
        $data .= "</div>";

        $data .= "<div id='cmc-container-calc' class='cmc-data-container'>";
        $data .= do_shortcode('[cmc-calculator-ad]');
		$data .= "</div>";		

        $data .= "<div id='cmc-container-history-data' class='cmc-data-container'>";
        $data .= $this->cmc_historical_data_ad();
        $data .= "</div>";

        if( class_exists('Crypto_Currency_Exchanges_List') ){
            $data .= "<div id='cmc-container-exchanges' class='cmc-data-container'>";
            $data .= do_shortcode('[celp-coin-exchanges]');
            $data .= "</div>";
        }

        $data .= "<div id='cmc-container-twitter-feeds' class='cmc-data-container'>";
        if( function_exists( 'ctf_init' ) ){
            $data .= $this->cmc_twitter_feed_ad();
        }else{
            $data .= '<p>A Custom Twitter Feed plugin is required to make this section working!</p>';
        }
        $data .= "</div>";

        $data .= "<div id='cmc-container-facebook-comments' class='cmc-data-container'>";
        $data .= $this->cmc_comment_box_ad();
        $data .= "</div>";

        $data .= "</div>"; // end of cmc-container-group
        $data .= "</div>"; // end of main container

        ob_end_flush();

        return $data;
    }

    /*
    |-----------------------------------------------------------------------------------------------|
    |   Initialize titan settings and assign settings value to a private variable                   |
    |-----------------------------------------------------------------------------------------------|
    */
    function cmc_init_titan(){
        $cmc_titan = TitanFramework::getInstance('cmc_single_settings');
        $this->titan_settings = array('');
        $this->titan_settings['dynamic_desciption'] = $cmc_titan->getOption('dynamic_desciption');
        $this->titan_settings['dynamic_title'] = $cmc_titan->getOption('dynamic_title');
        $this->titan_settings['display_api_desc'] = $cmc_titan->getOption('display_api_desc');
        $this->titan_settings['s_enable_formatting'] = $cmc_titan->getOption('s_enable_formatting');
        $this->titan_settings['default_currency'] = $cmc_titan->getOption('default_currency');
        $this->titan_settings['chart_color'] = $cmc_titan->getOption('chart_color');
        $this->titan_settings['twitter_feed_type'] = $cmc_titan->getOption('twitter_feed_type');
        $this->titan_settings['display_changes24h_single'] = $cmc_titan->getOption('display_changes24h_single');
        $this->titan_settings['display_market_cap_single'] = $cmc_titan->getOption('display_market_cap_single');
        $this->titan_settings['display_Volume_24h_single'] = $cmc_titan->getOption('display_Volume_24h_single');
        $this->titan_settings['display_supply_single'] = $cmc_titan->getOption('display_supply_single');
        $this->titan_settings['cmc_fb_app_Id'] = $cmc_titan->getOption("cmc_fb_app_Id");
        $this->titan_settings['choose_affiliate_type'] =  $cmc_titan->getOption('choose_affiliate_type');
        $this->titan_settings['affiliate_id'] = $cmc_titan->getOption('affiliate_id');
        $this->titan_settings['other_affiliate_link'] = $cmc_titan->getOption('other_affiliate_link');
        $this->titan_settings['single_live_updates'] = $cmc_titan->getOption('single_live_updates');
    }

}