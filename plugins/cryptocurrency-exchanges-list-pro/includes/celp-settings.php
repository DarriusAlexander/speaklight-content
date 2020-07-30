<?php
/**
 * This snippet has been updated to reflect the official supporting of options pages by CMB2
 * in version 2.2.5.
 *
 * If you are using the old version of the options-page registration,
 * it is recommended you swtich to this method.
 */
add_action( 'cmb2_admin_init', 'celp_register_settings' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function celp_register_settings() {

	/**
	 * Registers options page menu item and form.
	 */
	$cmb_options = new_cmb2_box( array(
		'id'           => 'celp_settings_page',
		'title'        => esc_html__( 'Settings', 'celp1' ),
		'object_types' => array( 'options-page' ),

		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */

		'option_key'      => 'celp_options', // The option key and admin menu page slug.
		// 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
		 'menu_title'      => esc_html__( 'Settings', 'celp1' ), // Falls back to 'title' (above).
		 'parent_slug'     => 'edit.php?post_type=celp', // Make options page a submenu item of the themes menu.
		'capability'      => 'manage_options', // Cap required to view options-page.
		// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
		// 'save_button'     => esc_html__( 'Save Settings', 'celp1' ), // The text for the options-page save button. Defaults to 'Save'.
	) );

	/*
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */

$cmb_options->add_field( array(
    'name'    =>  __('Select Currency','cmc' ),
    'desc'    => 'Select Currency for Bitcoin price in Exchange List',
    'id'      => 'fiat_currency',
    'type'    => 'select',
    'options' => array(
    	 'USD'   => 'USD',
        'GBP'   => 'GBP',
        'EUR'   => 'EUR',
        'INR'   => 'INR',
        'JPY'   => 'JPY',
        'CNY'   => 'CNY',
        'ILS'   => 'ILS',
        'KRW'   => 'KRW',
        'RUB'   => 'RUB',    
        'DKK'   => 'DKK',
        'PLN'   => 'PLN',
        'AUD'   => 'AUD',
        'BRL'   => 'BRL',
        'MXN'   => 'MXN',
        'SEK'   => 'SEK',
        'CAD'   => 'CAD',
        'HKD'   => 'HKD',
        'MYR'   => 'MYR',
        'SGD'   => 'SGD',
        'CHF'   => 'CHF',
        'HUF'   => 'HUF',
        'NOK'   => 'NOK',
        'THB'   => 'THB',
        'CLP'   => 'CLP',
        'IDR'   => 'IDR',
        'NZD'   => 'NZD',
        'TRY'   => 'TRY',
        'PHP'   => 'PHP',
        'TWD'   => 'TWD',
        'CZK'   => 'CZK',
        'PKR'   => 'PKR',
        'ZAR'   => 'ZAR',
    ),
    'default' => 'USD',
) );

	

	
	$cmb_options->add_field(array(
		'name' => __('Select Currency', 'cmc'),
		'desc' => 'Select Currency for Bitcoin price in Exchange List',
		'id' => 'fiat_currency',
		'type' => 'select',
		'options' => array(
			'USD' => 'USD',
			'GBP' => 'GBP',
			'EUR' => 'EUR',
			'INR' => 'INR',
			'JPY' => 'JPY',
			'CNY' => 'CNY',
			'ILS' => 'ILS',
			'KRW' => 'KRW',
			'RUB' => 'RUB',
			'DKK' => 'DKK',
			'PLN' => 'PLN',
			'AUD' => 'AUD',
			'BRL' => 'BRL',
			'MXN' => 'MXN',
			'SEK' => 'SEK',
			'CAD' => 'CAD',
			'HKD' => 'HKD',
			'MYR' => 'MYR',
			'SGD' => 'SGD',
			'CHF' => 'CHF',
			'HUF' => 'HUF',
			'NOK' => 'NOK',
			'THB' => 'THB',
			'CLP' => 'CLP',
			'IDR' => 'IDR',
			'NZD' => 'NZD',
			'TRY' => 'TRY',
			'PHP' => 'PHP',
			'TWD' => 'TWD',
			'CZK' => 'CZK',
			'PKR' => 'PKR',
			'ZAR' => 'ZAR',
		),
		'default' => 'USD',
	));

$cmb_options->add_field( array(
		'name' => __( 'Dynamic Title', 'celp1' ),
		'desc' => __( 'Placeholders:-[name]
It will also used as SEO title.
', 'celp1' ),
		'id'   => 'dynamic_title',
		'type' => 'text',
		'default' => '[name] Exchange Info, Markets & Trading Volume.',
	) );


$cmb_options->add_field( array(
		'name' => __( 'Dynamic Description', 'celp1' ),
		'desc' => __( 'Placeholders:- [name],[volume],[supported-currencies],[trading-pairs],[global-rank],[popular-country],[popular-country-rank] 
It will also used as SEO meta description.
', 'celp1' ),
		'id'   => 'dynamic_description',
		'type' => 'textarea',
		'default' => '[name] exchange 24 hours trading volume is [volume] This exchange supports [supported-currencies] crypto currencies and [trading-pairs]  market trading pairs. According to Alexa website traffic analysis this exchange website has [global-rank] rank worldwide. Its website is most popular in [popular-country] with a Alexa rank of  [popular-country-rank] ',
	) );
	$cmb_options->add_field(array(
		'name' => __('Exchanges Description From API', 'celp1'),
		'desc' => '',
		'id' => 'api_ex_desc',
		'default' => 'show',
		'type' => 'radio',
		'options' => array(
			'show' => __('Show', 'celp1'),
			'hide' => __('Hide', 'celp1'),

		),
	));

	$cmb_options->add_field(array(
		'name' => __('Exchanges Detail Page Slug
', 'celp1'),
		'desc' => __(
			'<p>This will update text in red color only:- http://coinmarketcap.coolplugins.net/<strong style="color:red">exchange</strong>/{dynamic}/ 
</p><p>Coin details page URL like:- http://coinmarketcap.coolplugins.net/exchange/binance/</p>
<strong>Important notice:- After Save Changes.Please Update your Permalink Settings</strong>
', 'celp1'),
		'id' => 'exchange-page-slug',
		'type' => 'text',
		'default' => 'exchange',
	));
	
	$cmb_options->add_field(array(
		'name' => __('Facebok APP ID', 'celp1'),
		'desc' =>'',
		'id' =>'celp_fb_id',
		'type' => 'text',
		'default' => '',
	));
	$url= home_url(
		'/wp-json/exchanges-lists/v1/generate-sitemap',
		'/'
	);
	$cmb_options->add_field(array(
		'name' => 'Exchanges Sitemap Link',
		'desc' => '<a target="_blank" href="'. $url .'">Click here to genearte Sitemap</a>',
		'type' => 'title',
		'id' => 'ex_sitemap'
	));
$cmb_options->add_field( array(
		'name' =>'<b style="color:red;">'.__( 'Please add this shortcode in any page/Post.', 'celp1' ).'</b>',
		'id'   => 'celp_mainlist_shortcode_documentation',
		'type' => 'title',
		) );
		
$cmb_options->add_field( array(
		'name' => '<b>'.__( 'Exchange Main List Shortcode', 'celp1' ).'</b>',
		'id'   => 'celp_list_shortcode',
		'type' => 'text',
		'default' => '[celp alexa-rank="yes" bitcoin-price="yes" website-link="yes"]',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );
	$cmb_options->add_field(array(
		'name' => __('Show Per Page settings', 'cmc'),
		'desc' => 'Show Per Page exchanges for Exchange Main List Shortcode',
		'id' => 'exchanges_per_page',
		'type' => 'select',
		'options' => array(
			10 => 10,
			25 => 25,
			50 => 50,
			100 => 100,
		),
		'default' => 10,
	));	

$cmb_options->add_field( array(
		'name' =>'<span style="color:red">'.__( 'Please add this shortcode in "cmc currency details" page of "Coin Marketcap & Prices" plugin', 'celp1' ).'</span>',
		'id'   => 'celp_cmc_shortcode_documentation',
		'type' => 'title',
		) );
		
$cmb_options->add_field( array(
		'name' => '<b>'.__( 'Exchange prices for coin market cap on single page', 'celp1' ).'</b>',
		//'desc' => '<span style="color:red">'.__( 'Please add this shortcode in "cmc currency details" page of "Coin Marketcap & Prices" plugin', 'celp1' ).'</span>',
		'id'   => 'celp_exchange_price_cmc',
		'type' => 'text',
		'default' => ' [celp-coin-exchanges] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );
	$cmb_options->add_field(array(
		'name' => __('Show Per Page Settings', 'cmc'),
		'desc' => 'Show Per Page Settings for Exchange prices for coin market cap on single page',
		'id' => 'coin_details_exchange_per_page',
		'type' => 'select',
		'options' => array(
			10 => 10,
			25 => 25,
			50 => 50,
			100 => 100,
		),
		'default' => 25,
	));
		

$cmb_options->add_field( array(
		'name' =>'<b style="color:red;">'.__( 'Please add these shortcodes in Exchange details page of Crypto Exchanges List PRO plugin', 'celp1' ).'</b>',

		'id'   => 'celp_singlepage_shortcode_documentation',
		'type' => 'title',
		) );
$cmb_options->add_field( array(
	'name' => __( 'Exchange Name Shortcode', 'celp1' ),
	'id'   => 'celp_exchange_name_shortcode',
	'type' => 'text',
	'default' => ' [celp-exchange-name] ',
	'attributes'  => array(
				'readonly' => 'readonly',
				//'disabled' => 'disabled',
			),
		) );
$cmb_options->add_field( array(
		'name' => __( 'Exchange Detail Shortcode', 'celp1' ),
		'id'   => 'celp_exchange_detail_shortcode',
		'type' => 'text',
		'default' => ' [celp-detail] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );
$cmb_options->add_field( array(
		'name' => __( 'Exchange Dynamic Title Shortcode', 'celp1' ),
		'id'   => 'celp_dynamic_title_documentation',
		'type' => 'text',
		'default' => ' [celp-dynamic-title] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );
$cmb_options->add_field( array(
		'name' => __( 'Exchange Custom Description Shortcode', 'celp1' ),
		'id'   => 'celp_custom_description_documentation',
		'type' => 'text',
		'default' => ' [celp-description] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );		


$cmb_options->add_field( array(
	'name' => __( 'Exchange Affiliate Link Shortcode', 'celp1' ),
	'id'   => 'celp_affiliate_link',
	'type' => 'text',
	'default' => ' [celp-affiliate-link] ',
	'attributes'  => array(
				'readonly' => 'readonly',
				//'disabled' => 'disabled',
			),
	) );		

$cmb_options->add_field( array(
		'name' => __( 'Exchange Dynamic Description Shortcode', 'celp1' ),
		'id'   => 'celp_dynamic_description_documentation',
		'type' => 'text',
		'default' => ' [celp-dynamic-description] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );		

		
$cmb_options->add_field( array(
		'name' => __( 'Exchange Currency Pairs Shortcode', 'celp1' ),
		'id'   => 'celp_currency_pairs_shortcode',
		'type' => 'text',
		'default' => ' [celp-currencies-pairs] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );

$cmb_options->add_field( array(
		'name' => __( 'Exchange Twitter Feed Shortcode', 'celp1' ),
		'id'   => 'celp_twitter_shortcode',
		'type' => 'text',
		'default' => ' [celp-twitter-feed] ',
		'attributes'  => array(
                    'readonly' => 'readonly',
                    //'disabled' => 'disabled',
                ),
		) );

	$cmb_options->add_field(array(
		'name' => __('Facebook Comment Box', 'celp1'),
		'id' => 'celp_fb_comments',
		'type' => 'text',
		'default' => ' [celp-comments] ',
		'attributes' => array(
			'readonly' => 'readonly',
                    //'disabled' => 'disabled',
		),
	));		

}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function celp_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'celp_options', $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'celp_options', $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}