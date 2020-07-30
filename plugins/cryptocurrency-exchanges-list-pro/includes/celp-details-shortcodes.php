<?php

class Celp_Details_Shortcodes
{


	function __construct() {

			// exchange detials shortcodes for single pages
			add_shortcode( 'celp-detail',array( $this,'celp_single_page_shortcode'));	
			add_shortcode( 'celp-currencies-pairs',array( $this,'celp_single_currency_pairs'));
			add_shortcode( 'celp-dynamic-title',array( $this,'celp_dynamic_title'));
			add_shortcode( 'celp-dynamic-description',array( $this,'celp_dynamic_description'));
			add_shortcode( 'celp-description',array( $this,'celp_description'));
			add_shortcode( 'celp-exchange-name',array( $this,'celp_exchange_name'));
			add_shortcode( 'celp-affiliate-link',array( $this,'celp_affiliate_link'));
			add_shortcode( 'celp-twitter-feed',array( $this,'celp_twitter_feed'));

			add_shortcode('celp-comments', array($this, 'celp_comment_box'));
		
			add_action('wp_ajax_celp_get_pairs_list', array($this,'celp_get_ex_pairs_list'));
			add_action('wp_ajax_nopriv_celp_get_pairs_list', array($this,'celp_get_ex_pairs_list'));
			
			if( !is_admin() ){
				add_filter( 'the_title', array($this,'celp_custom_page_title'), 10, 2 );
			}

			/* RankMath SEO Hooks */
			add_filter('rank_math/frontend/title', array($this, 'celp_ex_name_to_title') );
			add_filter( 'rank_math/frontend/description',array($this,'celp_open_graph_desc') );
			add_filter('rank_math/frontend/canonical', array($this, 'rankmath_exchange_canonical') );

			// filters and hooks for SEO title and description
			add_filter( 'pre_get_document_title',array($this,'celp_ex_name_to_title'), 10, 1);
			add_filter( 'wpseo_title',array( $this,'celp_ex_name_to_title'), 10, 1);
			add_action( 'wp_head',array( $this,'celp_generate_meta_desc'), 2);
			add_filter( 'wpseo_metadesc', array($this,'celp_open_graph_desc'),10,1);
			add_filter( 'wpseo_opengraph_desc',array($this,'celp_open_graph_desc'),10,1);
			add_action('wp', array($this,'celp_remove_canonical'));

		 }

		/*-------------------------------------------------------|
		|	  Generate Canonical URL for RankMath SEO Plugin	 | 
		|--------------------------------------------------------|
		*/
		 function rankmath_exchange_canonical(){

			$single_page_id= get_option('ex-single-page-id');
			if( !is_page($single_page_id) ){
				return;
			}

			$ex_id=(string) trim(get_query_var('exchange-id'));
			$slug = !empty(celp_get_option('exchange-page-slug')) ? celp_get_option('exchange-page-slug') : "exchange";
			$ex_url=esc_url( home_url($slug.'/'.$ex_id.'/','/') );
		
			echo'<link rel="canonical" href="'.$ex_url.'" />';
		 }
/*
Exchange single page shortcode for
representaion all data
*/

function celp_single_page_shortcode($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
		), $atts, 'celp-detail' );
	$output='';
	
	if (false === ($cache = get_transient('celp-saved-ex'))) {
		$rs=celp_save_ex_data();
			if($rs){
			$timing=7 * MINUTE_IN_SECONDS;
			set_transient('celp-saved-ex', date('H:s:i'),$timing);
			}
		}

	wp_enqueue_style('celp-styles');
if(get_query_var('exchange-id')){
		$ex_id=(string) trim(get_query_var('exchange-id'));
		$exchange_data=$this->celp_get_data($ex_id);
		if($exchange_data){
				$e_id=$exchange_data['ex_id'];
				$e_name=$exchange_data['name'];
				$e_currency_support=$exchange_data['coin_supports']?$exchange_data['coin_supports']:0;
				$e_trading_pairs=$exchange_data['trading_pairs']?$exchange_data['trading_pairs']:0;
				$e_exchange_volusd = $exchange_data['volume_24h'] ? $exchange_data['volume_24h'] : 0;
				$e_exchange_volbtc=$exchange_data['btc_volume']?celp_format_number($exchange_data['btc_volume']):0;
				$local_logo =CELP_PATH.'assets/logos/logo128/'.$e_id.'.png';
				$rank=$exchange_data['alexa_rank'];
				if (file_exists($local_logo)) {
					$e_logo = CELP_URL . 'assets/logos/logo128/' . $e_id . '.png';
				} else {
					$e_logo = 'https://res.cloudinary.com/coolplugins/image/upload/exchanges-logo/128x128/' . $e_id . '.png';

				}

				$currency = celp_selected_currency();
				$selected_currency = $currency['c_name'];
				$currency_price = $currency['c_price'];	

				if ($selected_currency == "USD") {
					$volume = celp_fiat_cur_symbol($selected_currency) . celp_format_number($e_exchange_volusd);
				} else {
					$volusd = (float)$e_exchange_volusd;
					$vol_conversion = $volusd * $currency_price;
					$volume = celp_fiat_cur_symbol($selected_currency) . celp_format_number($vol_conversion);
				}
			
				$vol_html=sprintf("%s (%s %s)",
					$volume,
					$e_exchange_volbtc,
				__('BTC','celp')
				);

			$output .='<div class="ex_details">';
			$output .='<div class="ex_logo_section"><div class="ex_logo_top"><img onerror=this.src="'. CELP_URL . 'assets/logos/logo128/exchange-logo.png" alt="'.$e_name.'" src="'.$e_logo.'">
			<br/><span class="ex_name"> '.$e_name.'</span></div>';
			$affi_con=get_custom_content($e_id);

			if(isset($affi_con['affiliate_link']) && !empty($affi_con['affiliate_link'])){
				$e_website=$affi_con['affiliate_link'];
				if(!empty($exchange_data['website'])){
					 $e_website_linkname = $exchange_data['website']?$exchange_data['website']:"#"; }
				else { $e_website_linkname = $e_website; }

			}else if(!empty($exchange_data['website'])){
				$e_website=$exchange_data['website']?$exchange_data['website']:"#";
				$e_website_linkname = $e_website;
			}
			$output .='<a target="_blank" rel="nofollow" href="'.$e_website.'" class="trading-button">'.__('Start Crypto Trading','celp').'</a></div>';
				
				$output .='<table><tbody>';
				$output .='<tr><th><i class="cmc_icon-exchange"></i> '.__('Exchange Name','celp').'</th><td>'.$e_name.'</td></tr>';
				$output .='<tr>
				<th><i class="cmc_icon-volume"></i></i> '.__('Volume (24H)','celp').'</th>
				<td class="exchange-volume">'. $volume .'<br>
				<span class="usd_btc">'.$e_exchange_volbtc.__(' BTC','celp').'</span></td>
				</tr>';
				$output .='<tr><th><i class="cmc_icon-bitcoin"></i> '.__('Coins Support','celp').'</th><td>'.$e_currency_support.'</td></tr>';
				$output .='<tr><th><i class="cmc_icon-chart"></i> '.__('Trading Pairs','celp').'</th><td>'.$e_trading_pairs.'</td></tr>';
				
			if(!empty($e_website)){

				$output .='<tr><th><i class="cmc_icon-website"></i> '.__('Website','celp').'</th>
				<td><a target="_blank" rel="nofollow" title="'.$e_name.'" href="'.$e_website.'">';
					if(strlen($e_website_linkname)>=32) 
						{
						$excerpt=	substr($e_website_linkname, 0, 32);
						 $output .= $excerpt .'...'; 
						}else{
						$output .=$e_website_linkname;
						}

					$output .= '</a></td></tr>';
				}
				if(!empty($exchange_data['twitter'])){
					$output .='<tr><th><i class="cmc_icon-twitter"></i></i> '.__('Twitter','celp').'</th><td><a target="_blank" rel="nofollow" title="'.$e_name.'" href="'.$exchange_data['twitter'].'">';
					if (strlen($exchange_data['twitter']) >= 32) {
						$excerpt = substr($exchange_data['twitter'], 0, 32);
						$output .= $excerpt . '...';
					} else {
						$output .=$exchange_data['twitter'];
					}
					$output .= '</a></td></tr>';
				}	
				if(!empty($exchange_data['telegram'])){
					$output .='<tr><th><i class="cmc_icon-telegram"></i> '.__('Telegram','celp').'</th><td><a target="_blank" rel="nofollow" title="'.$e_name.'" href="'.$exchange_data['telegram'].'">';
					if (strlen($exchange_data['telegram']) >= 32) {
						$excerpt = substr($exchange_data['telegram'], 0, 32);
						$output .= $excerpt . '...';
					} else {
						$output .= $exchange_data['telegram'];
					}
					$output .= '</a></td></tr>';	
				}	
				if(!empty($exchange_data['blog'])){
					$output .='<tr><th><i class="cmc_icon-edit"></i> '.__('Blog','celp').'</th><td><a target="_blank" rel="nofollow" title="'.$e_name.'" href="'.$exchange_data['blog'].'">';
				
					if (strlen($exchange_data['blog']) >= 32) {
						$excerpt = substr($exchange_data['blog'], 0, 32);
						$output .= $excerpt . '...';
					} else {
						$output .= $exchange_data['blog'];
					}
					$output .= '</a></td></tr>';	
				}
				
				if(!empty($exchange_data['alexa_rank'])){
					$output .='<tr><th><i class="cmc_icon-alexa"></i> '.__('Alexa Rank','celp').'</th><td>'.$exchange_data['alexa_rank'].'</td></tr>';
				}
				if(!empty($exchange_data['top_country'])){
					$output .='<tr><th><i class="cmc_icon-country"></i> '.__('Top Country','celp').'</th><td>'.$exchange_data['top_country'].'</td></tr>';
				}
		
			$output .='</tbody></table></div>';
			return $output;

		}else{
			return __('Something wrong with URL','celp');
		}

	}


}



// currencies pair shortcode for single page
function celp_single_currency_pairs($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
		), $atts, 'celp-detail' );
		$output='';
		$currency_pairs=array();
		celp_load_assets($page='ex_single');

if(get_query_var('exchange-id')){
		 $ex_id=(string) trim(get_query_var('exchange-id')); 	 
		 if (false === ($cache = get_transient($ex_id.'-all-pairs-saved'))) {
			$rs=celp_save_ex_pairs_data( $ex_id );
				if($rs){
				$timing=7 * MINUTE_IN_SECONDS;
				set_transient($ex_id.'-all-pairs-saved', date('H:s:i'),$timing);
				}
			}	

		$celp_prev = __( 'Previous','celp');
		$celp_next = __( 'Next','celp');				
		$celp_show = __( 'Show','celp');
		$celp_search=__( 'Search','celp');
		$celp_entries = __( 'Entries','celp');
		$no_rs_found = __( 'No Pair Found','celp');
		$default_logo= CELP_URL . 'assets/logos/logo32/default-logo.png';
		$celp_show_entries = sprintf("%s _MENU_ %s",$celp_show,$celp_entries);
		
		// showing START to END of TOTAL entries
		$celp_showing=__( 'Showing','celp');
		$celp_to=__( 'to','celp');
		$celp_of=__( 'of','celp');
		$celp_entry=__( 'entries','celp');
		$celp_showing_entries= sprintf("%s _START_ %s _END_ %s _TOTAL_ %s",$celp_showing,$celp_to,$celp_of,$celp_entry);

        // filtered from _MAX_ total entries
		$celp_filter=__( 'filtered from','celp');
		$celp_total_entries=__( 'total entries','celp');
		$celp_filter_entries= sprintf( "%s  _MAX_ %s",$celp_filter,$celp_total_entries);
    
		// Loading records preloader
		$celp_loading= __( 'Loading','celp');
		
		 // Initialize Titan
		 if(class_exists('TitanFramework')){
			$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );
			$cmc_slug =$cmc_titan->getOption('single-page-slug');
			 if( empty( $cmc_slug ) ){
					$cmc_slug='currencies';					
				}
		 	}else{
		 		$cmc_slug='currencies';
			}
			
			// Prevent from creating hyper link if CMC plugin is not active
			$coin_single_slug= 'false';
			
			if( class_exists( 'CoinMarketCap' ) ){	 
				 $coin_single_slug=esc_url( home_url($cmc_slug,'/') );
			}
		     
				$i=0;
				$currency = celp_selected_currency();
				$selected_currency = $currency['c_name'];
				$currency_symbol = celp_fiat_cur_symbol( $currency['c_name'] );
				$currency_price = $currency['c_price'];

		$output .='<div class="currecies-pairs" data-default-logo="'.$default_logo.'">
		<table id="celp_currency_pairs"
		 class="celp-datatable table table-striped table-bordered" 
		 data-per-page="25"
		 data-coin-symbol="'.$currency_symbol.'"
		 data-coin-price="'.$currency_price.'"
		 data-show-entries="'.$celp_show_entries.'" data-prev="'.$celp_prev.'" 
		 data-next="'.$celp_next.'" data-search="'.$celp_search.'" 
		 data-ex-id="'.$ex_id.'"
		 data-zero-records="'.$no_rs_found.'"
		 data-showing_entries="'.$celp_showing_entries.'"
		 data-filter_entries="'.$celp_filter_entries.'"
		 data-loading_records="'.$celp_loading.'"
		 ><thead>
		<tr>
			<th  data-classes="celp-rank" data-index="id">'.__( '#', 'celp' ).'</th>
			<th  data-classes="celp-coin_name" data-index="coin_name" data-coin-single-slug="'.$coin_single_slug.'">'.__( 'Currency', 'celp' ).'</th>
			<th  data-classes="celp-pair" data-index="pair">'.__( 'Pair', 'celp' ).'</th>
			<th  data-classes="celp-price" data-index="price">'.__( 'Price', 'celp' ).'</th>
			<th  data-classes="celp-volume_24h" data-index="volume_24h" class="all">'.__( 'Volume (24h)', 'celp' ).'</th>
			<th  data-classes="celp-updated" data-index="updated" class="all">'.__( 'Updated', 'celp' ).'</th>
		</tr></thead>';
		$output .='</table>
			<!-- You must provide credits to API data providers according to their API use terms otherwise data access will be blocked by API providers - CoinExchangePrice.com -->
			<div class="api-credits"><a href="https://coinexchangeprice.com" rel="follow" target="_blank">Data by Coin Exchange Price</a></div>
			</div>';	
		  return $output;	
		}else{
		return __('Something wrong with URL','celp');
	}
}	
	
/*
|--------------------------------------------------------------------------
| CELP list server side processing ajax callback
|--------------------------------------------------------------------------
 */
function celp_get_ex_pairs_list(){
	$all_pairs=array();
	$celpExPairs=new CELP_Exchanges_Pairs;
	$ex_id=$_REQUEST['ex_id'];
	$exchange_pairs=$celpExPairs->get_exchange_coin_pairs($ex_id);
	$i=0;

	  if(is_array($exchange_pairs)){
		foreach($exchange_pairs as $index=> $pair){

			// This is completely depends on CMC plugin
			if( class_exists( 'CMC_Coins' ) ){
				$CMC = new CMC_Coins();
				$coin = $CMC->get_coins( array('coin_id'=>$pair['coin_id'] ) );
				if( $coin == null || $coin == false ){
					continue;	// skip the current loop
				}
			}

			$i++;
			$pair_data['id']=$i;
			$pair_data['coin_id']=$pair['coin_id'];
			$pair_data['coin_symbol']=$pair['base_symbol'];
			$pair_data['coin_name']=$pair['coin_name'];
			$pair_data['volume_24h']=$pair['volume_usd'];
			$pair_data['ex_id']=$pair['ex_id'];

			$pair_data['price']=$pair['price_usd'];
			$pair_data['pair']=$pair['pair'];
			$pair_data['updated']=get_timeago($pair['updated']);
			$all_pairs[]=$pair_data;
		
		}
	  } 
		$response['data'] =$all_pairs;
		echo json_encode( $response );
	wp_die();
}



// creating dynamic page title 
function celp_custom_page_title( $title, $id = null ){
	  $single_page_id= get_option('ex-single-page-id');
	 if($id==$single_page_id){
	  		$title=$this->celp_create_ex_title();
		}
		return $title;
	}

function celp_dynamic_title($atts, $content = null){
	 $single_page_id= get_option('ex-single-page-id');
	 if(is_page($single_page_id)){
	  		$content=$this->celp_create_ex_title();
		}
		return $content;
}

// generating dynamic wp title 
function celp_ex_name_to_title( $ex_title) {
		global $post;
		if ($post == null) {
			return;
		}
	  $single_page_id= get_option('ex-single-page-id');
		 if($post->ID==$single_page_id){
		 	$ex_title=$this->celp_create_ex_title();
			}

		/* Return the title. */
		return $ex_title;
	}

// exchange name shortcode
function celp_exchange_name($atts, $content = null){
		$ex_title='';
		$ex_id=(string) trim(get_query_var('exchange-id'));
	 	$ex_title=ucfirst(str_replace('-',' ',$ex_id));

	 	return $ex_title;	
}
// generating dynamic wp-title and page titles
	function celp_create_ex_title(){
	
	$ex_id=(string) trim(get_query_var('exchange-id'));
	 		 $ex_title=ucfirst(str_replace('-',' ',$ex_id));	
	// $ex_vol=10;
	
	if(celp_get_option('dynamic_title')){
		$dynamic_title = celp_get_option('dynamic_title');
	}else{
		$dynamic_title = '[name] Exchange Info, Markets & Trading Volume';
	}
	$dynamic_array=array($ex_title);
	$placeholders=array('[name]');
	 $title_txt=str_replace($placeholders,$dynamic_array,$dynamic_title);

	 return $title_txt;
}

function celp_remove_canonical(){
	$single_page_id= get_option('ex-single-page-id');
	 if ( is_page($single_page_id) ) {
      add_filter( 'wpseo_canonical', '__return_false',  10, 1 );
    }
}
//creating dyanmic description 
function celp_create_ex_desc(){
	
if(get_query_var('exchange-id')){
			
			$ex_id=(string) trim(get_query_var('exchange-id'));
			$exchange_data=$this->celp_get_data($ex_id);
		
			if($exchange_data){
				$e_id=$exchange_data['ex_id'];
				$e_name=$exchange_data['name'];
				$e_currency_support=isset($exchange_data['coin_supports'])?$exchange_data['coin_supports']:0;
				$e_trading_pairs=isset($exchange_data['trading_pairs'])?$exchange_data['trading_pairs']:0;
				$e_exchange_volusd=isset($exchange_data['volume_24h']) ?$exchange_data['volume_24h']:0;
				$e_exchange_volbtc=isset($exchange_data['btc_volume'])?celp_format_number($exchange_data['btc_volume']):0;
				$rank=isset($exchange_data['alexa_rank'])?$exchange_data['alexa_rank']:"";
				$exchange_country_rank=isset($exchange_data['country_rank'])?$exchange_data['country_rank']:0;
				$exchange_top_country=isset($exchange_data['top_country'])?$exchange_data['top_country']:'';
				$currency = celp_selected_currency();
				$selected_currency = $currency['c_name'];
				$currency_price = $currency['c_price'];

				if ($selected_currency == "USD") {
					$volume = celp_fiat_cur_symbol($selected_currency) . celp_format_number($e_exchange_volusd);
				} else {
					$volusd = (float)$e_exchange_volusd;
					$vol_conversion = $volusd * $currency_price;
					$volume = celp_fiat_cur_symbol($selected_currency) . celp_format_number($vol_conversion);
				}

				$vol_html=sprintf("%s (%s %s)",
					$volume,
					$e_exchange_volbtc,
				__('BTC','celp')
				);

  	 $ex_title=ucfirst(str_replace('-',' ',$ex_id));	
	if(celp_get_option('dynamic_description')){	 
	$dynamic_desc=celp_get_option('dynamic_description');
	}else{
		$dynamic_desc = '[name] exchange 24 hours trading volume is [volume] This exchange supports [supported-currencies] crypto currencies and [trading-pairs]  market trading pairs. According to Alexa website traffic analysis this exchange website has [global-rank] rank worldwide. Its website is most popular in [popular-country]
		 with a Alexa rank of  [popular-country-rank] ';
	}

	$dynamic_array=array($ex_title,$vol_html,$e_currency_support,$e_trading_pairs,$rank,$exchange_top_country,$exchange_country_rank);
		$placeholders=array('[name]','[volume]','[supported-currencies]','[trading-pairs]','[global-rank]','[popular-country]','[popular-country-rank]');
	 $desc_txt=str_ireplace($placeholders,$dynamic_array,$dynamic_desc);

	 return $desc_txt;
	 }
		
	}
}

// generating description meta for SEO
function celp_generate_meta_desc(){
	$single_page_id= get_option('ex-single-page-id');
	 if(is_page($single_page_id) && !class_exists('RankMath')){
		if ( !defined( 'WPSEO_VERSION' ) ) {
			$desc=$this->celp_create_ex_desc();
			echo '<meta name="description" content="'.esc_html($desc). '" />';
		}
	$ex_id=(string) trim(get_query_var('exchange-id'));
	$slug = !empty(celp_get_option('exchange-page-slug')) ? celp_get_option('exchange-page-slug') : "exchange";
	$ex_url=esc_url( home_url($slug.'/'.$ex_id.'/','/') );

	echo'<link rel="canonical" href="'.$ex_url.'" />';
	}
}

function celp_open_graph_desc( $desc ) {
  	 $single_page_id= get_option('ex-single-page-id');
	 if(is_page($single_page_id)){
	 	return $desc=$this->celp_create_ex_desc();
	  	
		}
	  return $desc;
	}
// creating shortcode for generating dynamic description
function celp_dynamic_description($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
		), $atts, 'celp-dynamic-description' );
		
		$desc=$this->celp_create_ex_desc();
	return	$output ='<div class="celp_dynamic_description">'.$desc.'</div>';
	
}
	//facebook comment box for exchange detial page
	function celp_comment_box($atts, $content = null){
		$atts = shortcode_atts(array(
			'id' => '',
		), $atts);
		$output = '';
		global $wp;
		$page_url = home_url($wp->request, '/');

		global $post;
		$page_id = $post->ID;
		$single_page_id = get_option('ex-single-page-id');
		$celp_fb_id = celp_get_option('celp_fb_id');
	

		if (is_page($page_id) && $page_id == $single_page_id) {
			$app_id = $celp_fb_id ? $celp_fb_id : '1798381030436021';
			$output .= '<div class="fb-comments" data-href="' . $page_url . '" data-width="100%" data-numposts="10"></div>';

			$output .= '<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src ="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=' . $app_id . '&autoLogAppEvents=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));</script>';
		}

		return $output;

	}

	//celp exchange description
	function celp_description($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
		), $atts, 'celp-description' );
		
		$output='';		
		$desc='';
		$api_ex_desc=celp_get_option('api_ex_desc');
		$ex_id=(string) trim(get_query_var('exchange-id'));	
		$custom_content=get_custom_content($ex_id);

		if(isset($custom_content['desc']) && !empty($custom_content['desc'])){
			$desc=$custom_content['desc'];
		}else{
			$exchange_data=$this->celp_get_data($ex_id);
			$ex_about=$exchange_data['about']?$exchange_data['about']:'';
			if($api_ex_desc=='show'){
				$desc=$ex_about;
			}
		}
	
		$output.='<div class="celp_desc"><p>'.$desc.'</p></div>';
		return $output;
	}

	function celp_affiliate_link($atts, $content = null){
		$output='';		
		$link="#";
		$ex_id=(string) trim(get_query_var('exchange-id'));	
		$custom_content=get_custom_content($ex_id);
		if(isset($custom_content['affiliate_link']) && !empty($custom_content['affiliate_link'])){
			$link=$custom_content['affiliate_link'];
		}else{
			$exchange_data=$this->celp_get_data($ex_id);
			$exchange_website=$exchange_data['website']?$exchange_data['website']:'';
			$link=$exchange_website;
		}

		$output .='<span class="celp_affiliate_links">
		<a target="_blank" class="celp_trade_now" href="'. $link.'">'.__('Trade','celp').'</a>
		</span>';

		return $output;
	}

	//Shortcode for Twitter Feeds
	function celp_twitter_feed(){
		if(get_query_var('exchange-id')){
		$ex_id=(string) trim(get_query_var('exchange-id'));
			$exchange_data=$this->celp_get_data($ex_id);
	
	if(isset($exchange_data['twitter'])){
		 // pass it into a regular expression, and get the result
    $result = preg_match("/https?:\/\/(www\.)?twitter\.com\/(#!\/)?@?([^\/]*)/",$exchange_data['twitter'], $matches);
  
    if($result){
    	$screen_name=$matches[3];
    }else{
    	$screen_name=$ex_id;
    }
	$d=''; 
    	if($ex_id!='stellar-decentralized-exchange'){
			$d= do_shortcode( '[custom-twitter-feeds screenname="'.$screen_name.'"]' );
 		}

			return $d;
			}
	}	
	}

  function celp_get_data($ex_id){

	$ex_info=array();
	$celpEx=new CELP_Exchanges;
	$i=1;
	$exchange_data=$celpEx->get_exchanges(array("number"=>1,'ex_id' =>$ex_id,
	  ));
	
	if(is_array($exchange_data) && isset($exchange_data[0]))
		{
			$exchange=celpobjectToArray($exchange_data[0]);
			$extra_data=celpobjectToArray(json_decode($exchange['extra_data']));
			return  $ex_info=array_merge($exchange,$extra_data);
		}else{
			return false;
		}
	}

	
} // class end


