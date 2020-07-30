<?php
/**
 * Plugin Name:Coins MarketCap
 * Description:Best cryptocurrency plugin to automatically create 2000+ crypto coins single pages with their price, historical price, chart, exchanges list and social-feed data.
 * Author:Cool Plugins 
 * Author URI:https://coolplugins.net/
 * Version:3.8
 * License: GPL2
 * Text Domain:cmc
 * Domain Path:languages
 **/
 /** @package Coin_Market_Cap
 *Copyright (C) 2016 CoolPlugins contact@coolplugins.net
 */

if (!defined('ABSPATH')) {
	exit();
}
	
define('CMC', '3.8');
define('CMC_PRO_FILE', __FILE__);
define('CMC_PATH', plugin_dir_path(CMC_PRO_FILE ));
define('CMC_PLUGIN_DIR',plugin_dir_path(CMC_PRO_FILE ));
define( 'CMC_URL',plugin_dir_url(CMC_PRO_FILE ));
define('CMC_LOAD_COINS',1900);

define('CMC_API_ENDPOINT',"https://api-beta.coinexchangeprice.com/v1/");
if( !defined('CMC_CSS_URL')) {
    define('CMC_CSS_URL', plugin_dir_url( __FILE__ ) . 'css');
}

/**
 * Class CoinMarketCap
 */
final class CoinMarketCap {

	/**
	 * Plugin instance.
	 *
	 *
	 * @access private
	 */
	private static $instance = null;
	public $shortcode_obj=null;

	/**
	 * Get plugin instance.
	 *
	 *
	 * @static
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @access private
	 */
	private function __construct() {
		//set_time_limit(120);
		register_activation_hook( CMC_PRO_FILE, array( $this, 'cmc_activate' ) );
		register_deactivation_hook( CMC_PRO_FILE, array( $this, 'cmc_deactivate' ) );
		// include all files
		$this->cmc_includes();
	
	   // run to verify plugin version in-case of update
	   add_action( 'init', array($this,'cmc_plugin_version_verify') );
	   add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
	   // creating settings panel
	    add_action( 'tf_create_options', array( $this,'cmc_createMyOptions'));
		// registering custom rewrite urls for coin single pge
		add_action('init', array($this, 'cmc_rewrite_rule'));
		add_filter( 'query_vars', array($this,'cmc_query_vars'));

		add_action('tf_save_admin_cmc_single_settings', array($this, 'cmc_after_titan_save'), 10, 3);
		add_filter( 'style_loader_tag', array( $this, 'cmc_css_preload') , 10, 4 );
		add_filter( 'script_loader_tag', array( $this, 'cmc_defer_scripts' ), 10, 3 );
		if(is_admin()){
			$this->onAdminInit();			
		}else{
			add_action('init',array($this,'cmc_grab_custom_slug'));
			add_action('template_redirect', array($this, 'cmc_single_page_redirection'));
		}
		
		$this->onInit();
		
	}

    
   
/*
|--------------------------------------------------------------------------
| On Admin Init register hooks
|--------------------------------------------------------------------------
*/
	function onAdminInit(){
			// adding custom js in admin side
			add_action( 'admin_enqueue_scripts', array($this,'cmc_admin_custom_js'));
			add_action( 'save_post', array( $this,'save_cmc_settings'),10, 3 );
			//add_action( 'admin_notices', array($this,'cmc_admin_notice_for_coins_logo'));
			add_action( 'admin_enqueue_scripts', array( $this,'cmc_remove_wp_colorpicker'),99);
			// integrate review notice
			new CMCReviewNotice();
	}
/*
|--------------------------------------------------------------------------
| on init create rest endpoint and set cron jobs
|--------------------------------------------------------------------------
*/
	function onInit(){
	  // rest api endpoint for sitemap generation 
	  add_action('rest_api_init', function () {
		register_rest_route('coin-market-cap/v1', 'sitemap.xml', array(
			'methods' => 'GET',
			'callback' => array('CMC_Sitemaps','cmc_generate_sitemap')
		));
		register_rest_route('coin-market-cap/v1', 'update-coin-meta', array(
			'methods' => 'GET',
			'callback' => array($this,'cmc_update_coin_meta')
	));
 });
		
		//initialize Cron Jobs
		add_filter('cron_schedules', array($this, 'cmc_cron_schedules')); 
		add_action('cmc_coins_autosave', array($this, 'do_this_5minutes_updates'));
		add_action('cmc_coins_weeklyprice_autosave', array($this, 'do_this_daily'));
		add_action('cmc_coins_meta_autosave', array($this, 'do_this_monthly'));
		add_action('cmc_coins_desc_autosave', array($this, 'cmc_save_this_monthly'));
		// disabling jetpack photon cache
		add_filter( 'jetpack_photon_skip_for_url',array( $this,'cmc_photon_only_allow_local'), 9, 4 );
	}

/*
|--------------------------------------------------------------------------
| defer CSS style
|--------------------------------------------------------------------------
*/
	function cmc_css_preload($html, $handle, $href, $media) {
		$preload_style = array(
			'cmc-global-style',
			'cmc-tab-design-custom',
			'cmc-bootstrap',
			'cmc-icons',
		);	
		if ( in_array( $handle, $preload_style ) ) {
		 $html = "<link rel='preload' as='style' onload='this.onload=null;this.rel=\"stylesheet\"' id='$handle' href='$href' type='text/css' media='all' />";
		 $html .= "<link rel='stylesheet' as='style' onload='this.onload=null;this.rel=\"stylesheet\"' id='$handle' href='$href' type='text/css' media='all' />";
		}
		return $html;
	}

/*
|--------------------------------------------------------------------------
| defer scripts 
|--------------------------------------------------------------------------
*/
	function cmc_defer_scripts( $tag, $handle, $src ) {
		// The handles of the enqueued scripts we want to defer
		$defer_scripts = array(
			'amcharts',
			'amcharts-stock',
			'amcharts-serial',
			'cmc-single-js'
		);

		// The handles of the enqueued scripts we want to async
		$async_scripts = array( 
			'cmc-bootstrap',
			'cmc-admin-custom-js',
			'ccc-socket',
			'ccc_stream',
			'cmc-custom',
		);
	
		if ( in_array( $handle, $async_scripts ) ) {
			return '<script src="' . $src . '" async="async" type="text/javascript"></script>' . "\n";
		}	
		if ( in_array( $handle, $defer_scripts ) ) {
			return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
		}	
		return $tag;
	}

/*
|--------------------------------------------------------------------------
|Load plugin function files here.
|--------------------------------------------------------------------------
*/
	public function cmc_includes()
	{
	require_once(CMC_PATH . '/admin/titan-framework/titan-framework-embedder.php');
	require_once(CMC_PATH . 'admin/settings/init-api.php');

	//include Coins List Page files
	require_once(CMC_PATH . '/admin/cmc-edit-disable-coin/cmc-coins-list-class.php');

	//includes DB files
	require_once(CMC_PATH . '/includes/db/cmc-db.php');
	require_once(CMC_PATH . '/includes/db/cmc-coins-db.php');
	require_once(CMC_PATH . '/includes/db/cmc-coins-meta-db.php');		
	// includes Helpers files
	require_once(CMC_PATH .	'/includes/cmc-functions.php');
	require_once(CMC_PATH . '/includes/cmc-helpers.php');
	
	require_once(CMC_PATH . '/includes/helpers/cmc-post-types.php');
	require_once(CMC_PATH . '/includes/helpers/cmc-create-sitemaps.php');
	require_once(CMC_PATH . '/includes/helpers/cmc-download-logos.php');
	
	if(is_admin()){
	require_once(CMC_PATH . '/includes/helpers/class.review-notice.php');
	}
	// include shortcodes
	require_once(CMC_PATH . '/includes/shortcodes/cmc-shortcode.php');
	require_once(CMC_PATH . '/includes/shortcodes/cmc-top-gl-shortcode.php');
	require_once(CMC_PATH . '/includes/shortcodes/cmc-advanced-single-shortcode.php');
	require_once(CMC_PATH . '/includes/shortcodes/cmc-single-shortcode.php');
	
	$this->shortcode_cmc=new CMC_Shortcode();
	$this->cmc_gainer_losers=new CMC_Top();
	$this->shortcode_cmc_single=new CMC_Single_Shortcode();
	$this->shortcode_cmc_advanced_single = new CMC_Advanced_Single_Shortcode();
	
	new CMC_Posttypes();
	new CMC_Sitemaps();
	new CMC_Download_logos();

	}

/*
|--------------------------------------------------------------------------
| Load Text domain
|--------------------------------------------------------------------------
*/	
	public function load_text_domain() {
		load_plugin_textdomain( 'cmc', false, basename(dirname(__FILE__)) . '/languages/');
	}

/*
|--------------------------------------------------------------------------
|generating rewrite rule on plugin init
|--------------------------------------------------------------------------
*/
 function cmc_rewrite_rule() {
		$page_id= cmc_get_coins_details_page_id(); //get_option('cmc-coin-single-page-selected-design');
		$single_page_slug=cmc_get_page_slug();
		add_rewrite_rule('^' . $single_page_slug . '/([^/]*)/([^/]*)/([^/]*)?$', 'index.php?page_id=' . $page_id . '&coin_symbol=$matches[1]&coin_id=$matches[2]
		 	 &currency=$matches[3]
		 	', 'top');
		add_rewrite_rule('^'.$single_page_slug . '/([^/]*)/([^/]*)/?$', 'index.php?page_id=' . $page_id . '&coin_symbol=$matches[1]&coin_id=$matches[2]
', 'top');
		
		}

/*
|--------------------------------------------------------------------------
| adding dyanmic rewrite rule after save changes in slug settings 	
|--------------------------------------------------------------------------
*/

	function cmc_dynamic_rewrite_rules($wp_rewrite)
	{
		$page_id = cmc_get_coins_details_page_id();//get_option('cmc-coin-single-page-selected-design');
		$single_page_slug = cmc_get_page_slug();
		$feed_rules = array(
			'^' . $single_page_slug . '/([^/]*)/([^/]*)/([^/]*)/?$' => 'index.php?page_id=' . $page_id . '&coin_symbol=$matches[1]&coin_id=$matches[2]
		 	 &currency=$matches[3]',
			'^' . $single_page_slug . '/([^/]*)/([^/]*)/?$' => 'index.php?page_id=' . $page_id . '&coin_symbol=$matches[1]&coin_id=$matches[2]',
		);
		$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
		return $wp_rewrite->rules;
	}
/*
|--------------------------------------------------------------------------
| adding query var for custom rewrite rules
|--------------------------------------------------------------------------
*/
function cmc_query_vars( $query_vars ){
			$query_vars[] = 'coin_symbol';
			$query_vars[] = 'coin_id';
			$query_vars[] ='currency';
			return $query_vars;
		}

/*
|--------------------------------------------------------------------------
| generating page with shortcode for coin single page
|--------------------------------------------------------------------------
*/		
function add_coin_details_page(){
		 	$post_data = array(
		    'post_title' => 'CMC Currency Details',
		    'post_type' => 'page',
			'post_content'=>'
				[cmc-dynamic-title]
				[cmc-dynamic-description]
				[cmc-affiliate-link]
				[coin-market-cap-details]
				[cmc-coin-extra-data]
				<h3 class="single-page-h3">Crypto Calculator</h3>
				[cmc-calculator]
				<h3 class="single-page-h3">Price Chart</h3>
				[cmc-chart]
				<h3 class="single-page-h3">More Info About Coin</h3>
				[coin-market-cap-description]
				<h3 class="single-page-h3">Historical Data</h3>
				[cmc-history]
				<h3 class="single-page-h3">Markets / Exchanges</h3>
				<div class="specialline">**You can show markets data only by installing "Cryptocurrency Exchanges List Pro" WordPress plugin with "Coin Market Cap &amp; Price" plugin.</div>
				[celp-coin-exchanges]
				<h3 class="single-page-h3">Technical Analysis</h3>
				[cmc-technical-analysis autosize="true" theme="light"]
				<h3 class="single-page-h3">Twitter News Feed</h3>
				[cmc-twitter-feed]
				<h3 class="single-page-h3">Submit Your Reviews</h3>
				[coin-market-cap-comments]',
		     'post_status'   => 'publish',
		      'post_author'  => get_current_user_id(),
			); 
		
			$single_page_id = get_option('cmc-coin-single-page-id');

			if('publish' === get_post_status( $single_page_id)){
			
			}else{
				$post_id = wp_insert_post( $post_data );
				update_option('cmc-coin-single-page-id',$post_id);
			}
			

			$post_data = array(
				'post_title'	=>	'CMC Currency Details (Advanced Design)',
				'post_type'		=>	'page',
				'post_content'	=>'
				[cmc-single-coin-details-advanced-design]
				',
				'post_status'	=>	'publish',
				'post_author'	=> get_current_user_id()
			);
			$single_page_id = get_option('cmc-coin-advanced-single-page-id');
			if('publish' === get_post_status( $single_page_id)){
			
			}else{
				$post_id = wp_insert_post( $post_data );
				update_option('cmc-coin-advanced-single-page-id',$post_id);
			}
 		}
/*
|--------------------------------------------------------------------------
| generating coins tables
|--------------------------------------------------------------------------
 */
 function cmc_create_table(){
	 			add_option('cmc_table_init','1');
				$cmc_db = new CMC_Coins;
				$cmc_details_db = new CMC_Coins_Meta;
				$cmc_db->create_table();
				$cmc_details_db->create_table();
				delete_option('cmc_table_init');
		 }
/*
|--------------------------------------------------------------------------
| deleting coins tables on plugin deactivation
|--------------------------------------------------------------------------
 */		 
		 function cmc_delete_table(){
			global $wpdb;
			$coin_table = $wpdb->prefix . 'cmc_coins';
			$coin_meta_table = $wpdb->prefix . 'cmc_coin_meta';
			$wpdb->query("DROP TABLE IF EXISTS " . $coin_table);
			$wpdb->query("DROP TABLE IF EXISTS " . $coin_meta_table);
		 }
/*
|--------------------------------------------------------------------------
|  Plugin settings panel
|--------------------------------------------------------------------------
*/		 
	function cmc_createMyOptions(){
		require_once CMC_PLUGIN_DIR .'/admin/settings/cmc-settings.php';
	}

	/*
	For ask for reviews code
	*/

	function cmc_installation_date(){
   	 	update_option('cmc_activation_time',strtotime("now"));
	}
 
/**
 * Save shortcode when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_cmc_settings( $post_id, $post, $update ) {
	// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		        return;
		// AJAX? Not used here
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		        return;
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) )
		        return;
		// Return if it's a post revision
		if ( false !== wp_is_post_revision( $post_id ) )
		        return;
    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

	if( $post_type == 'cmc-description' ){
		delete_transient( 'cmc-custom-coin-des' );
	}
    // If this isn't a 'book' post, don't update it.
    if ( "cmc" != $post_type ) return;
	    // - Update the post's metadata.
   		 update_option('cmc-post-id',$post_id);


	}

/*
|--------------------------------------------------------------------------
| attaching hook after titan settings save
|--------------------------------------------------------------------------
*/
	function cmc_after_titan_save($container, $activeTab, $options)
	{
		$cmc_titan = TitanFramework::getInstance('cmc_single_settings');
		$slug = $cmc_titan->getOption('single-page-slug');
		$details_page 	 = $cmc_titan->getOption('single-page-design-id');
	//	set_transient('cmc-single-page-slug', $slug,MINUTE_IN_SECONDS );
		update_option('cmc-single-page-slug', $slug);

			if( !isset( $details_page ) || $details_page == false ){
				$details_page = get_option( 'cmc-coin-single-page-id' );
			}
			update_option('cmc-coin-single-page-selected-design',$details_page);

	if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == "extra-settings") {
			add_filter('generate_rewrite_rules',array($this, 'cmc_dynamic_rewrite_rules'));
			flush_rewrite_rules();
		}

	}
/*
|--------------------------------------------------------------------------
| Get custom Slug
|--------------------------------------------------------------------------
*/
	function cmc_grab_custom_slug(){
		$cmc_titan = TitanFramework::getInstance('cmc_single_settings');
		$slug = $cmc_titan->getOption('single-page-slug');
		//set_transient('cmc-single-page-slug', $slug, MINUTE_IN_SECONDS);
		update_option('cmc-single-page-slug', $slug);
	}

/* 
|--------------------------------------------------------------------------
|  registering custom js for settings panel
|--------------------------------------------------------------------------
*/
	function cmc_admin_custom_js()
	{
		 $screen =(array) get_current_screen();
		
	    if (isset($screen['post_type']) && $screen['post_type']=="cmc") {     
	    // loading js
	    	wp_register_script( 'cmc-admin-custom-js', CMC_URL.'assets/js/cmc-admin-custom.js', array('jquery-core'), CMC, true );
			wp_enqueue_script( 'cmc-admin-custom-js' );		
		}


			wp_register_script( 'cmc-admin-custom-js', CMC_URL.'assets/js/cmc-admin-custom.js', array('jquery-core'), CMC, true );
			wp_enqueue_script( 'cmc-admin-custom-js' );		
			$already_created_desc = get_transient( 'cmc-custom-coin-des' );
			wp_localize_script( 'cmc-admin-custom-js', 'cmc_description', array( 'already_created'=> $already_created_desc ) );
		
	}

/*
|--------------------------------------------------------------------------
| generating sitemap 
|--------------------------------------------------------------------------
*/
	function cmc_update_coin_meta(){
			$rs=save_cmc_extra_data();
			update_option('cmc-coins-meta-saving-time', time() );
		//	if($rs){
			$timing=MONTH_IN_SECONDS;
			set_transient('cmc-saved-extradata', date('d/m H:s:i'),$timing);
			$response['coin-meta-data'] ='generated';
			//}

			//fetching coin full description 
			$rs2=save_coin_desc_data();
			update_option('cmc-coins-desc-saving-time', time() );
			if($rs2){
			$timing=MONTH_IN_SECONDS;
			set_transient('cmc-saved-desc', date('d/m H:s:i'), $timing);
			$response['coin-description-data'] ='generated';
			}
			$response['status'] ="success";

			echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			die();
			
	}

/*
|--------------------------------------------------------------------------
|  fixing conflict
|--------------------------------------------------------------------------
*/
	public function cmc_remove_wp_colorpicker()
	{
		wp_dequeue_script('wp-color-picker-alpha');
	}

/*
|--------------------------------------------------------------------------
|   on plugin activation hook adding page and flushing rewrite rules
|--------------------------------------------------------------------------
 */		
	
public function cmc_activate()
{
	$this->add_coin_details_page();
	$CMC_VERSION = get_option('CMC_PRO_VERSION');
	if( $CMC_VERSION === false ){
		update_option('CMC_FRESH_INSTALLATION',CMC);
	}
	$this->cmc_rewrite_rule();
	$this->cmc_create_table();
	$this->cmc_cron_job_init();
	$this->cmc_installation_date();
	flush_rewrite_rules();
    

}		

/*
|--------------------------------------------------------------------------
|  Check if plugin is just updated from older version to new
|--------------------------------------------------------------------------
*/	
public function cmc_plugin_version_verify( ) {
	
	$CMC_VERSION = get_option('CMC_PRO_VERSION');
	if( !isset($CMC_VERSION) || version_compare( $CMC_VERSION, CMC, '<' ) ){
		$this->cmc_activate();
		update_option('CMC_PRO_VERSION', CMC );
	}
	
	$CMC_UPDATE_TABLE = get_option('CMC_UPDATE_TABLE');
	
	if( $CMC_VERSION >= 3.7 && !$CMC_UPDATE_TABLE){
		delete_transient('cmc-saved-coindata');
		$this->cmc_create_table();
		update_option('CMC_UPDATE_TABLE', 'updated' );
	}

}	// end of cmc_plugin_version_verify()
			

/*
|--------------------------------------------------------------------------
|  Run when deactivate plugin.
|--------------------------------------------------------------------------
*/	
			public function cmc_deactivate()
			{
				if(!is_plugin_active( 'cryptocurrency-price-ticker-widget-pro/cryptocurrency-price-ticker-widget-pro.php' ) ) {
					$this->cmc_delete_table();
				}

				wp_clear_scheduled_hook('cmc_coins_autosave');
				wp_clear_scheduled_hook('cmc_coins_weeklyprice_autosave');
				wp_clear_scheduled_hook('cmc_coins_meta_autosave');
				wp_clear_scheduled_hook('cmc_coins_desc_autosave');
				delete_transient('cmc-saved-coindata');
				delete_transient('cmc-saved-weeklydata');
				delete_transient('cmc-saved-extradata');
				delete_transient('cmc-saved-desc');
				flush_rewrite_rules();
			}
/*
|--------------------------------------------------------------------------
|   on plugin activation hook adding page and flushing rewrite rules
|--------------------------------------------------------------------------
 */		
	public function cmc_cron_job_init(){

		if (!wp_next_scheduled('cmc_coins_autosave')) {
			wp_schedule_event(time(), '5min', 'cmc_coins_autosave');
		}
		if (!wp_next_scheduled('cmc_coins_weeklyprice_autosave')) {
			wp_schedule_event(time(), '12hour', 'cmc_coins_weeklyprice_autosave');
		}
		if (!wp_next_scheduled('cmc_coins_meta_autosave')) {
			wp_schedule_event(time(), 'monthly', 'cmc_coins_meta_autosave');
		}
		if (!wp_next_scheduled('cmc_coins_desc_autosave')) {
			wp_schedule_event(time(), 'monthly', 'cmc_coins_desc_autosave');
		}
	}
/*
|--------------------------------------------------------------------------
|  cron custom schedules
|--------------------------------------------------------------------------
 */
			function cmc_cron_schedules($schedules)
			{
				// 5 minute schedule for grabing all coins 
				if (!isset($schedules["5min"])) {
					$schedules["5min"] = array(
						'interval' => 5 * 60,
						'display' => __('Once every 5 minutes')
					);
				}
				if (!isset($schedules["12hour"])) {
					$schedules["12hour"] = array(
						'interval' =>43200,
						'display' => __('Once every 12 hours')
					);
				}
				if (!isset($schedules["monthly"])) {
				$schedules['monthly'] = array(
					'interval' => 2635200,
					'display' => __('Once a month')
				);
				}
				return $schedules;
			}
/*
|--------------------------------------------------------------------------
|  grabing coins data after 5 minute using cron
|--------------------------------------------------------------------------
 */

	function do_this_5minutes_updates()
	{
		$timing=5 * MINUTE_IN_SECONDS;
		//saving all coins data
		$rs=save_cmc_coins_data();
		if($rs){
		set_transient('cmc-saved-coindata', date('H:s:i'),$timing);
		set_transient('cmc-saved-in','cron',$timing);
		}
	
	}
/*
|--------------------------------------------------------------------------
| grabing coin weekly price data(historical)for small charts
|--------------------------------------------------------------------------
 */			
	function do_this_daily()
	{
		$rs=save_cmc_historical_data();
		$timing=12 * HOUR_IN_SECONDS;
		set_transient('cmc-saved-weeklydata', date('H:s:i'), $timing);
		
	}

/*
|--------------------------------------------------------------------------
| grabing coin soical links and extra info
|--------------------------------------------------------------------------
 */
		function do_this_monthly()
		{
			$rs=save_cmc_extra_data();
			$timing=MONTH_IN_SECONDS;
			set_transient('cmc-saved-extradata', date('d/m H:s:i'), $timing);
			
		}
		function cmc_save_this_monthly()
		{
			//fetching coin full description 
			$rs=save_coin_desc_data();
			$timing=MONTH_IN_SECONDS;
			set_transient('cmc-saved-desc', date('d/m H:s:i'), $timing);
				
		}
		/*
		function cmc_admin_notice_for_coins_logo(){

			$plugin_info = get_plugin_data( __FILE__ , true, true );
			if( get_option("cmc_download_icons")!= CMC ){
				printf(__('<style>.ctf_review_notice {display:none !Important;}</style><div class="cmc-review wrap" style="background: #ffffff !important;border-left: 4px solid #ffba00;padding: 15px !important;max-width: 860px;display: inline-block;border-radius: 4px;clear:both;-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);">
	<p style="display: inline;vertical-align: top;">New coins added! Please update coin logos, links, description and sitemap from <strong>%s >> Coin Details Settings >> Extra Settings</strong> page.</p></div>'),$plugin_info['Name'] );
			}

		} */

		/**
	 * Only use Photon for images belonging to our site.
	 * @param bool         $skip      Should Photon ignore that image.
	 * @param string       $image_url Image URL.
	 * @param array|string $args      Array of Photon arguments.
	 * @param string|null  $scheme    Image scheme. Default to null.
	 */
	function cmc_photon_only_allow_local( $skip, $image_url, $args, $scheme ) {
	    // Get the site URL, without any protocol.
	    $site_url = preg_replace( '~^(?:f|ht)tps?://~i', '', get_site_url() );
	 
	    /**
	     * If the image URL is from our site,
	     * return default value (false, unless another function overwrites).
	     * Otherwise, do not use Photon with it.
	     */
	    if ( strpos( $image_url, $site_url ) ) {
	        return $skip;
	    } else {
	        return true;
	    }
	}

	/*-----------------------------------------------------------------------------------|
	|																					 |
	|				The below function verify if the requested coin is enabled			 |
	|				If the coin is disabled, single page only shows 404 error		     |
	|------------------------------------------------------------------------------------|
	*/
	function cmc_single_page_redirection(){
		GLOBAL $post;
		$page_id = cmc_get_coins_details_page_id();

		if( isset( $post->ID ) && $post->ID != $page_id) return;

			$coin_id = get_query_var( 'coin_id' ) ;

			$db = new CMC_Coins();
			$r = !empty($coin_id)? $db->get_coins( array('coin_id'=> trim( $coin_id ) )) : null;
			
			if( $r == null ){
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				include( get_query_template( '404' ) );
				exit();
			}
		}
	
} // class end

	function CoinMarketCap() {
		return CoinMarketCap::get_instance();
	}
$GLOBALS['CoinMarketCap'] = CoinMarketCap();
