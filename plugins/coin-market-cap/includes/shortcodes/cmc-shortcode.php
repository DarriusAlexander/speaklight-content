<?php
class CMC_Shortcode
{

/*
|--------------------------------------------------------------------------
| Bootstraping CMC main list 
|--------------------------------------------------------------------------
*/
	function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'cmc_register_scripts'));
		add_shortcode('global-coin-market-cap', array($this, 'cmc_global_data'));
		//add_action('init',array($this,'cmc_save_api_data'));
		add_shortcode('coin-market-cap', array($this, 'cmc_shortcode'));
		add_shortcode('cmc-technical-analysis', array($this, 'cmc_technical_analysis'));

		add_action('wp_ajax_dt_get_coins_list', array($this, 'cmc_dt_get_coins_list'));
		add_action('wp_ajax_nopriv_dt_get_coins_list', array($this, 'cmc_dt_get_coins_list'));
	
		add_action('wp_ajax_cmc_ajax_search', array($this, 'cmc_ajax_search'));
		add_action('wp_ajax_nopriv_cmc_ajax_search', array($this, 'cmc_ajax_search'));

		if (cmc_isMobileDevice() == 0) {
			add_action('wp_ajax_cmc_small_charts', array($this, 'cmc_small_chart_data'));
			add_action('wp_ajax_nopriv_cmc_small_charts', array($this, 'cmc_small_chart_data'));
		}

		
	}	

	function cmc_save_api_data(){
		// run only if transient does not exists
		if (false === ($cache = get_transient('cmc-saved-data'))) {
			cmc_check_cache();
		}  
	}
/*
|--------------------------------------------------------------------------
| CMC list server side processing ajax callback
|--------------------------------------------------------------------------
 */
	function cmc_dt_get_coins_list(){
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'cmc-ajax-nonce' ) ){
        	die ('Please refresh window and check it again');
			}

		require(CMC_PATH.'includes/helpers/cmc-serverside-processing.php');
		get_ajax_data();
		wp_die();
	}

/*
|-----------------------------------------------------------------|
|	Shortcode for Technical Analysis  widget					  |
|-----------------------------------------------------------------|
*/
function cmc_technical_analysis($atts,$content=null){
	GLOBAL $post;
	$atts =  shortcode_atts(array(
		'autosize'=>'false',
		'interval'=>'1m',
		'width'=>'425',
		'height'=>'450',
		'theme'=>'light',
		'interval-tabs'=>'true',
		'locale'=>'en',
		'transparent'=>'true',
	), $atts, 'cmc');

	$availabel_coins = array("BTC","ETH","EOS","BCH","XRP","LTC","BSV","USD","BTG","LEO","NEO","ZEC","IOTA","ETP","OMG","ETC","XMR","DASH","AMPL","ZRX","SAN","TRX","GOT","XTZ","XLM","DAI","QTUM","EDO","EURS","USD","C","BTT","KAN","YEED","DGX","BCI","GEN","BAT","PASS","MGO","XD","ATOM","ODE","XCHF","DATA","ZIL","LYM","GTX","MKR","RIF","XVG","INT","RBTC","WAX","WLO","DGB","UFR","VEE","VET","YOYOW","SNT","LOOM","AION","WBTC","TUSD","","HOT","AUC","IMP","ENJ","SEN","REP","VLD","SEE","SWM","BOX","OMNI","CLO","AID","AVT","ANT","BBN","CNN","TKN","ZCN","ATM","RLC","IQX","PAX","ESS","ZBT","QASH","GNT","MTN","MANA","POLY","MLN","CND","NIO","KNC","BFT","ABYSS","DRGN","WTC","POA","ALGO","AGI","ELF","BNT","FSN","DADI","ONL","DTH","UTK","MAN","VSYS","UTNP","GUSD","","PNK","RRT","FUN","MITH","IOST","RDN","GNO","STORJ","TRIO","TNB","ORS","EURt","CBT","LRC","AST","RCN","SPANK","RTE","XRA","REQ","FOA","DTA","PAI","USD","K","WPR","SNGLS","OKB","CTXC","CS","NCASH","DUSK");

	$symbol = null==get_query_var('coin_symbol')?'BTC':strtoupper( get_query_var('coin_symbol') );
	$interval = $atts['interval']==''?'1m':$atts['interval'];
	$autosize = $atts['autosize']==''?'true':$atts['autosize'];
	$transparent_bg = $atts['transparent']==''?'true':$atts['transparent'];
	$width = $atts['width']==''?'425':$atts['width'];
	$height = $atts['height']==''?'450':$atts['height'];
	$theme = $atts['theme']==''?'light':$atts['theme'];
	$locale = $atts['locale']==''?'en':$atts['locale'];
    $interval_tab = $atts['interval-tabs']==''?'true':$atts['interval-tabs'];
    $apply_autosize = '';
	if( $autosize =='true' ){
		$width='100%';
        $height='100%';
        $apply_autosize = 'autosize';
	}

	
	$html ='<!---------- CMC Version:-'. CMC  .' By Cool Plugins Team-------------->';
	$html .='<!-- TradingView Widget BEGIN -->
	<div class="tradingview-widget-container '.$apply_autosize.'" id="ccpw-analysis-widget-'.$post->ID.'">';

	if( in_array($symbol,$availabel_coins) ){
	  $html .='<div class="tradingview-widget-container__widget"></div>
	  <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/NASDAQ-AAPL/technicals/" rel="noopener" target="_blank"><span class="blue-text">Technical Analysis for AAPL</span></a> by TradingView</div>
	  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js" async>
	  {
	  "showIntervalTabs": '.$interval_tab.',
	  "width": "'.$width.'",
	  "colorTheme": "'.$theme.'",
	  "isTransparent": '.$transparent_bg.',
	  "locale": "'.$locale.'",
	  "symbol": "BITFINEX:'.$symbol.'USD",
	  "interval": "'.$interval.'",
	  "height": "'.$height.'"
	}
	  </script>';
	}else{
		$html .= '<div class="cmc_no_response">'.__('No technical data available for this coin').'</div>';
	}

	  $html .='</div>
	<!-- TradingView Widget END -->';

	return $html;
}

/*
|--------------------------------------------------------------------------
| CMC list main shortcode for coin market cap table. 
|--------------------------------------------------------------------------
 */	
function cmc_shortcode($atts, $content = null)
	{

		$atts = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'info' => true,
			'paging' => false,
			'scrollx' => true,
			'ordering' => true,
			'searching' => false,
		), $atts, 'cmc');
	
	if (false === ($cache = get_transient('cmc-saved-coindata'))) {
				$rs=save_cmc_coins_data();		
				$timing=5 * MINUTE_IN_SECONDS;
				set_transient('cmc-saved-coindata', date('H:s:i'),$timing);
				
			}
			
	if(false === ($cache = get_transient('cmc-saved-weeklydata'))){
			//grabing coin weekly price data(historical)for small charts
			$rs=save_cmc_historical_data();
			$timing=12 * HOUR_IN_SECONDS;
			set_transient('cmc-saved-weeklydata', date('H:s:i'), $timing);
		
	}
	$this->cmc_load_assets();
		$total_found =1830 ;
		$start_at = 0;
		$start_point =0;
		$data_length =10;
		$db = new CMC_Coins;	
		$cmc_link_array = array();
		$post_id = $atts['id'];
		// Initialize Titan
		$cmc_titan = TitanFramework::getInstance('cmc_single_settings');
		$show_coins = $cmc_titan->getOption('show_currencies', $post_id);
		$load_coins = $cmc_titan->getOption('load_currencies', $post_id);
		$real_currency = $cmc_titan->getOption('old_currency', $post_id);
		$old_currency = $real_currency ? $real_currency : "USD";
		// for currency dropdown
		$currencies_price_list = cmc_usd_conversions('all');
		$selected_currency_rate = cmc_usd_conversions($old_currency);
		$currency_symbol = cmc_old_cur_symbol($old_currency);
		$single_default_currency = $cmc_titan->getOption('default_currency', $post_id);
		$pagination = $show_coins ? $show_coins : 50;
	
		$display_supply = $cmc_titan->getOption('display_supply', $post_id);
		$display_Volume_24h = $cmc_titan->getOption('display_Volume_24h', $post_id);
		$display_market_cap = $cmc_titan->getOption('display_market_cap', $post_id);
		$display_chart = $cmc_titan->getOption('coin_price_chart', $post_id);
		$cmc_small_charts = $cmc_titan->getOption('cmc_chart_type', $post_id);
		
		$live_updates_cls = '';
		$live_updates = $cmc_titan->getOption('live_updates', $post_id);
		if ($live_updates) {
			$live_updates_cls = 'cmc_live_updates';
		} else {
			$live_updates_cls = '';
		}
		$enable_formatting = $cmc_titan->getOption('enable_formatting', $post_id);
		$single_page_type = $cmc_titan->getOption('single_page_type', $post_id);
		$link_in_newtab=$single_page_type?$single_page_type:0;
		$single_page_slug = cmc_get_page_slug();
		$cmc_data_attributes = '';
	//	$cmc_data_attributes .= 'data-pageLength="' . $pagination . '"';
		$cmc_coins_page = (get_query_var('page') ? get_query_var('page') : 1);
		$default_logo=(string) CMC_URL.'assets/coins-logos/default-logo.png';
		$bitcoin_price = cmc_btc_price();
		$c_json = currencies_json();
	
$html = '';
$html.='<!---------- CMC Version:-'. CMC  .' By Cool Plugins Team-------------->';
$html .= '<div id="cryptocurency-market-cap-wrapper" data-default-logo="'.$default_logo.'">';	

$html.='<script id="cmc_curr_list" type="application/json">'.$c_json.'</script>';
$html .= '<div class="cmc_price_conversion">
<select id="cmc_usd_conversion_box" class="cmc_conversions">';
			$currencies_price_list['BTC'] = $bitcoin_price;
			foreach ($currencies_price_list as $name => $price) {
				$csymbol = cmc_old_cur_symbol($name);
				if ($name == $old_currency) {

				$html .='<option selected="selected" data-currency-symbol="' . $csymbol . '" data-currency-rate="' . $price . '"  value="' . $name . '" >' . $name . '</option>';
				} else {
				$html .='<option data-currency-symbol="' . $csymbol . '" data-currency-rate="' . $price . '"  value="' . $name . '">' . $name . '</option>';
				}
			}
			unset($currencies_price_list['BTC']);
$html .= '</select></div>';
$cmc_prev_coins= __('Previous','cmc');
$cmc_next_coins= __('Next','cmc');
$coin_loading_lbl= __('Loading...','cmc');
$cmc_no_data= __('No Coin Found','cmc');
$cmc_no_fav_data = __('No Favourite Coin','cmc');
 $html .= coin_search($old_currency,$single_default_currency,$single_page_slug);

		$coin_url = home_url($single_page_slug ,'/') ;
		if ($old_currency == $single_default_currency) {
			$url_type="default";
		} else {
			$url_type = "custom";
		}
//	$html.='<div class="top-scroll-wrapper"><div class="top-scroll"></div></div>';
	$html.='<div class="cmc-fav cmc_icon-star-empty" id="cmc_toggel_fav" title="'.__('Show/Hide Watch List','cmc').'"></div>';
	$html .= '<table id="cmc_coinslist" data-loadinglbl="'.$coin_loading_lbl.'" data-number-formating="'.$enable_formatting.'" data-pagination="'. $pagination .'" data-total-coins="'.$load_coins.'" data-currency-symbol="'.$currency_symbol. '"
	data-prev-coins="'.$cmc_prev_coins.'" data-zero-fav-records="'.$cmc_no_fav_data.'" data-zero-records="'.$cmc_no_data.'" data-next-coins="'.$cmc_next_coins.'"
	data-currency-rate="'.$selected_currency_rate.'" data-old-currency="'.$old_currency.'"
	class="'.$live_updates_cls.'  cmc-datatable table table-striped table-bordered" 
	width="100%" data-watch-title="'.__('Add to watch list','cmc').'" data-unwatch-title="'.__('Remove from watch list','cmc').'"
    >';
		$preloader_url = CMC_URL . 'images/chart-loading.svg';
		$html .= '<thead data-preloader="'.$preloader_url.'">
		<tr>
		<th data-classes="cmc-rank" data-index="rank" class="desktop">'. __('#', 'cmc'). '</th>
		<th data-link-in-newtab="'.$link_in_newtab.'" data-single-url="'. $coin_url .'" data-url-type="'. $url_type .'"  data-classes="cmc-name" data-index="name" class="all">'.__('Name', 'cmc') . '</th>';	
		$html .= '<th data-classes="cmc-price" data-index="price" class="all">'.__('Price', 'cmc').'</th>';
		$html .= '<th data-classes="cmc-live-ch cmc-changes" data-index="percent_change_24h">'.__('Changes ', 'cmc') . ' <span class="badge  badge-default">' . __('24H ', 'cmc') . '</span></th>';
		
		if ($display_market_cap == true) {
		$html .= '<th data-classes="cmc-market-cap" data-sort-default data-index="market_cap">'.__('Market Cap', 'cmc') .'</th>';
		}
	if ($display_Volume_24h== true) {
		$html .= '<th data-classes="cmc-vol" data-index="volume">' . __('Volume ', 'cmc') . '<span class="badge  badge-default">' . __('24H', 'cmc') . '</span></th>';
		}
	if ($display_supply == true) {
		$html .= '<th data-classes="cmc-supply"  data-index="supply">' . __('Available Supply', 'cmc') . '</th>';
	}	
	if ($display_chart == true) {
			$period = '7d';
			$points = 0;
			if ($old_currency == "USD") {
				$currency_price = 1;
			} else {
				$currency_price = $selected_currency_rate;
			}
			$no_data_lbl = __('No Graphical Data', 'cmc');
			$chart_fill = "true";

		$html .= '<th data-sort-method="none" id="cmc_weekly_charts_head" data-classes="cmc-charts"  data-index="weekly_chart"  data-orderable="false"
			data-msz="' . $no_data_lbl . '"
			data-period="' . $period . '"
			data-points="' . $points . '"
			data-currency-symbol="' . $currency_symbol . '"
			data-currency-price="' . $currency_price . '"
			data-chart-fill="' . $chart_fill . '"
		>'
		.__('Price Graph ', 'cmc') . __('<span class="badge badge-default">(7D)</span>','cmc2').'</th>';
	
		}

	$html .= '</tr></thead>
	<tbody></tbody><tfoot>
</tfoot></table></div>';

	return $html;
}




/*
|--------------------------------------------------------------------------
| CMC Global Info shortcode handler
|--------------------------------------------------------------------------
*/
function cmc_global_data($atts, $content = null)
{

	$atts = shortcode_atts(array(
		'id' => '',
		'currency' => 'USD',
		'formatted' => true
	), $atts);

	wp_register_style('cmc-global-style', false);
	wp_enqueue_style('cmc-global-style');

	$cmc_g_styles = '/* Global Market Cap Data */
		.cmc_global_data {
			display:inline-block;
			margin-bottom:5px;
			width:100%;
		}
		.cmc_global_data ul {
		    list-style: none;
		    margin: 0;
		    padding: 0;
		    display: inline-block;
		    width: 100%;
		}
		.cmc_global_data ul li {
		    display: inline-block;
		    margin-right: 20px;
			font-size:14px;
			margin-bottom: 5px;
		}
		.cmc_global_data ul li .global_d_lbl {
			font-weight: bold;
		    background: #f9f9f9;
		    padding: 4px;
		    color: #3c3c3c;
		    border: 1px solid #e7e7e7;
		    margin-right: 5px;
		}
		.cmc_global_data ul li .global_data {
		    font-size: 13px;
			white-space:nowrap;
			display:inline-block;
		}
		/* Global Market Cap Data END */ ';

	wp_add_inline_style('cmc-global-style', $cmc_g_styles);

	$output = '';
	$old_currency = $atts['currency'] ? $atts['currency'] : 'USD';
	$currency_symbol = cmc_old_cur_symbol($old_currency);
	$fiat_currency_rate= cmc_usd_conversions($old_currency);
	$global_data = (array)cmc_get_global_data();
		if (is_array($global_data)&& count($global_data)>0) {
		if(isset($global_data['market_cap_percentage']->btc)){
		$bitcoin_percentage_of_market_cap = number_format($global_data['market_cap_percentage']->btc,'2','.','');
		}
		$output .= '<div class="cmc_global_data"><ul>';
		if(isset( $global_data['total_market_cap']) && isset( $global_data['total_volume'])){
			if ($old_currency == "USD") {
				$market_cap= $global_data['total_market_cap'];
				$volume= $global_data['total_volume'];
			}  else {
				$market_cap = $global_data['total_market_cap'] * $fiat_currency_rate;
				$volume = $global_data['total_volume'] * $fiat_currency_rate;
			}
		}	

		if (isset($market_cap)) {
			if ($atts['formatted'] == "true") {
				$mci_html = $currency_symbol . cmc_format_coin_values($market_cap);
			} else {
				$mci_html = $currency_symbol . format_number($market_cap);
			}
			$output .= '<li><span class="global_d_lbl">' . __('Market Cap:', 'cmc') . '</span><span class="global_data"> ' . $mci_html . '</span></li>';
		}

		if (isset($volume)) {
			if ($atts['formatted'] == "true") {
				$vci_html = $currency_symbol . cmc_format_coin_values($volume);
			} else {
				$vci_html = $currency_symbol . format_number($volume);
			}
			$output .= '<li><span class="global_d_lbl">' . __('24h Vol:', 'cmc') . '</span><span class="global_data"> ' . $vci_html . '</span></li>';
		}
		$output .= '<li><span class="global_d_lbl">' . __('BTC Dominance: ', 'cmc') . '</span><span class="global_data">' . $bitcoin_percentage_of_market_cap . '%</span></li>';

		$output .= '</ul></div>';
	}
	return $output;
}


/*
|--------------------------------------------------------------------------
|Register scripts and styles
|--------------------------------------------------------------------------
*/
public function cmc_register_scripts()
{
	if (!is_admin()) {

		if (!wp_script_is('jquery', 'done')) {
			wp_enqueue_script('jquery');
		}
		wp_register_script('cmc-datatables', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',null,CMC);
		wp_register_script('bootstrapcdn', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',null,CMC);
		
		wp_register_style('cmc-icons', CMC_URL . 'assets/css/cmc-icons.min.css',null,CMC);
		wp_register_style('cmc-custom', CMC_URL . 'assets/css/cmc-custom.css',null,CMC);
		wp_register_style('cmc-bootstrap', CMC_URL . 'assets/css/bootstrap.min.css',null,CMC);	

		wp_register_script('crypto-numeral',CMC_URL . 'assets/js/numeral.min.js', array('jquery'), CMC);
		wp_register_script('cmc-custom-fixed-col', CMC_URL . 'assets/js/tableHeadFixer.js', array('jquery', 'cmc-datatables'), CMC, true);

		wp_register_script('cmc-js', CMC_URL . 'assets/js/cmc-main-table.min.js', array('jquery', 'cmc-datatables'), CMC, true);
		wp_register_script('cmc-typeahead', CMC_URL . 'assets/js/typeahead.bundle.min.js', array('jquery'), CMC, true);
		wp_register_script('cmc-handlebars', CMC_URL . 'assets/js/handlebars-v4.0.11.js', array('jquery'), CMC, true);
		wp_register_script('cmc-chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js',null,CMC);
		//wp_register_script('cmc-numeraljs', '//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js');
		wp_register_script('cmc-small-charts', CMC_URL . 'assets/js/small-charts.js', array('jquery', 'cmc-chartjs'), CMC, true);
		wp_localize_script(
			'cmc-js',
			'data_object',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'=>wp_create_nonce('cmc-ajax-nonce'),
				'cmc_plugin_url' => CMC_URL
				)
		);
		
		$dynamic_css = cmc_dynamic_style();
		wp_add_inline_style('cmc-custom', $dynamic_css);

		wp_register_script('cmc-table-sort', CMC_URL . 'assets/js/tablesort.min.js', array('jquery'), CMC, true);
		//wp_register_script('cmc-lscache', CMC_URL . 'assets/js/lscache.min.js', array('jquery'), false, true);

		
		//loading globally for fast rendering
		wp_enqueue_style('cmc-bootstrap');
		wp_enqueue_style('cmc-custom');
		wp_enqueue_style('cmc-icons');
	}
}
/*
|--------------------------------------------------------------------------
| get plugin settings
|--------------------------------------------------------------------------
*/
	function cmc_get_settings($post_id, $index)
	{
		if ($post_id && $index) {
			// Initialize Titan
			$cmc_titan = TitanFramework::getInstance('cmc_single_settings');

			$val = $cmc_titan->getOption($index, $post_id);
			if ($val) {
				return true;
			} else {
				return false;
			}
		}
	}

/*
|--------------------------------------------------------------------------
| Loading required assets for coin single page
|--------------------------------------------------------------------------
*/
function cmc_load_assets(){

	//wp_enqueue_script('cmc-lscache');
	wp_enqueue_script('bootstrapcdn');
	wp_enqueue_script('crypto-numeral');

	wp_enqueue_script('cmc-typeahead');
	wp_enqueue_script('cmc-handlebars');
	wp_enqueue_script('cmc-chartjs');
	wp_enqueue_script('cmc-small-charts');

	wp_enqueue_script('cmc-custom-fixed-col');
	wp_enqueue_script('cmc-table-sort');
	//wp_enqueue_script('cmc-numeraljs');
	wp_enqueue_script('ccpw-lscache');
	wp_enqueue_script('cmc-js');
	
	wp_enqueue_script('ccc-socket', 'https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js', array('jquery'), CMC, true);
	wp_enqueue_script('ccc_stream', CMC_URL . 'assets/js/cmc-stream.min.js', null, CMC, true);


}


function cmc_ajax_search(){
	
	$single_page_slug = cmc_get_page_slug();
	$all_coins = cmc_coin_list_data();

	if (is_array($all_coins) && count($all_coins) > 0) {

		foreach ($all_coins as $id=>$coin) {
			$coin_id =$id;
			$coin_symbol = $coin['symbol'];
			$name = $coin['name'] . " " . $coin_symbol . "";
			$coin_logo = coin_logo_url($coin_id, $size = 32);
				$coin_url = home_url($single_page_slug . '/' . $coin_symbol . '/' . $coin_id);
			$cmc_link_array[] = array("link" => $coin_url, "name" => $name, "symbol" => $coin_symbol, "logo" => $coin_logo);
		}
		$search_links = json_encode($cmc_link_array, JSON_UNESCAPED_SLASHES);
		
		die( $search_links );
	}
}
}