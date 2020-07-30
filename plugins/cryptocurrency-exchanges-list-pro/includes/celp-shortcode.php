<?php

	/**
	 * Exchanges list main shortcode
	 */

	function celp_shortcode( $atts, $content = null ) {

		// clear old exchange pair data
		celp_cleanOldData();

	$atts = shortcode_atts( array(
		'id'  => '',
		'class' => '',
		'alexa-rank'=>"yes",
		'bitcoin-price'=>"yes",
		'website-link'=>"yes"
	), $atts, 'celp' );
	if (false === ($cache = get_transient('celp-saved-ex'))) {
		celp_save_ex_data();
		$timing=7 * MINUTE_IN_SECONDS;
		set_transient('celp-saved-ex', date('H:s:i'),$timing);
		}
	
	$output='';
	$post_id=$atts['id'];
	$currency=celp_selected_currency();
	$currency_symbol = celp_fiat_cur_symbol( $currency['c_name'] );
	$currency_price	 = $currency['c_price'];

	$alexa_rank=$atts['alexa-rank'];
	$bitcoin_price=$atts['bitcoin-price'];
	$website_link=$atts['website-link'];
	$default_logo= CELP_URL . 'assets/logos/logo32/default-logo.png';
	celp_load_assets($page='exchanges_list');
	$per_page = celp_get_option('exchanges_per_page')? celp_get_option('exchanges_per_page'):10;
	$per_page = apply_filters( 'celp/exchanges_per_page', $per_page );

	// datatable translated text
	$celp_prev = __( 'Previous','celp');
		$celp_next = __( 'Next','celp');				
		$celp_show = __( 'Show','celp');
		$celp_search=__( 'Search','celp');
		$celp_entries = __( 'Entries','celp');
		
		$no_rs_found = __( 'No Exchange Found','celp');
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
		
		$slug = !empty(celp_get_option('exchange-page-slug')) ? celp_get_option('exchange-page-slug') : "exchange";
		$detail_page_url=esc_url( home_url($slug,'/') );	
	$output.='<!-- Cryptocurrency Exchanges List PRO  Version:-'.CELP_VERSION.' -->';

	$output.='<div class="celp_container" data-default-logo="'.$default_logo.'">';
	$output.='<table  data-per-page="'. $per_page .'" 
	id="celp_main_list"
	class="celp-datatable table table-striped table-bordered" 
	data-coin-symbol="'.$currency_symbol.'"
	data-coin-price="'.$currency_price.'"
	data-show-entries="'.$celp_show_entries.'"
	data-prev="'.$celp_prev.'" 
	data-next="'.$celp_next.'" 
	data-search="'.$celp_search.'"
	data-zero-records="'.$no_rs_found.'"
	data-showing_entries="'.$celp_showing_entries.'"
	data-filter_entries="'.$celp_filter_entries.'"
	data-loading_records="'.$celp_loading.'"
	>
	 <thead><tr>';
			
	$output.='<th data-classes="celp-rank" data-index="id" >'.__( '#', 'celp' ).'</th>';
	$output.='<th data-classes="celp-name exchange-name-logo" data-index="name" data-single-page-url="'.$detail_page_url.'"  class="all">'.__( 'Name', 'celp' ).'</th>';
	$output.='<th data-classes="celp-vol exchange-volume" data-index="volume_24h" >'.__( 'Volume (24H)', 'celp' ).'</th>';
	$output.='<th data-classes="celp-coins exchange-coins" data-index="coin_supports" class="all">'.__( 'Coins', 'celp' ).'</th>';
    $output.='<th data-classes="celp-trading_pairs exchange-pairs" data-index="trading_pairs">'.__( 'Trading Pairs', 'celp' ).'</th>';

	if($alexa_rank=="yes")
	{
	$output.='<th data-classes="celp-alexa_rank exchange-alexa" data-index="alexa_rank" class="all">'.__( 'Alexa Rank', 'celp' ).'</th>';
	}
	if($bitcoin_price=="yes")
	{
	$output.='<th data-classes="celp-btc-price exchange-btc-price" data-index="btc_price" class="all celp_bitcoin_price">'.__( 'Bitcoin Price', 'celp' ).'</th>';
	}
	if($website_link=="yes")
	{
	$output.='<th data-classes="celp-official-website exchange-website-link" data-index="official_website" class="all" data-orderable="false">'.__( 'Official Website', 'celp' ).'</th>';
	}
	$output.='</tr></thead>';
	$output.='</table>
	<!-- You must provide credits to API data providers according to their API use terms otherwise data access will be blocked by API providers - CoinExchangePrice.com -->
	<div class="api-credits"><a href="https://coinexchangeprice.com" rel="follow" target="_blank">Data by Coin Exchange Price</a></div>
	</div>';
	return $output;
	}



	
/*
|--------------------------------------------------------------------------
| CELP list server side processing ajax callback
|--------------------------------------------------------------------------
 */
function celp_get_ex_list_data(){
	$start_point    =0;
	$data_length    =237;
	$celpEx=new CELP_Exchanges;
	$order_col_name = 'volume_24h';
	$order_type ='DESC';
	$i=1;
	$all_exchanges=$celpEx->get_exchanges(array("number"=>$data_length,'offset'=> $start_point,
	'orderby' => $order_col_name,
	'order' => $order_type,
	'type'=>'on-coin'
	  ));
	$ex_array=array();
	$all_affilates=get_all_affilate_links();

	  if($all_exchanges){
		
		foreach($all_exchanges as $index=> $exchange){
			$ex_data=(array)$exchange;
			if($ex_data['fees'] == 'no') {
				$new_exchanges = array();
				$item = $all_exchanges[$index];
				unset($all_exchanges[$index]);
				array_push($all_exchanges, $item); 
			}
		}
		foreach($all_exchanges as $index=> $exchange){
			$ex_data=(array)$exchange;
		
			$extra_data=json_decode($ex_data['extra_data']);
			$e_id=$ex_data['ex_id'];
			$local_logo= CELP_PATH . 'assets/logos/logo32/' . $e_id . '.png';
			if (file_exists($local_logo)) {
			$e_logo = CELP_URL . 'assets/logos/logo32/' . $e_id . '.png';
			}else{
				$e_logo = 'https://res.cloudinary.com/coolplugins/image/upload/exchanges-logo/32x32/' . $e_id . '.png';
			}
			$ex_data['logo']=$e_logo;
			if(isset($extra_data->alexa_rank)&& $extra_data->alexa_rank!="N/A" ){
				$ex_data['alexa_rank']=$extra_data->alexa_rank;
			}else{
				$ex_data['alexa_rank']='N/A';
			}
			if(is_array($all_affilates)&& isset($all_affilates[$e_id])){
				$ex_data['official_website']=$all_affilates[$e_id]['affiliate_link'];
			}
			else if(isset($extra_data->website)&& $extra_data->website!="N/A" ){
				$ex_data['official_website']=$extra_data->website;
			}else{
				$ex_data['official_website']='#';
			}
			if(isset($extra_data->twitter)&& $extra_data->twitter!="N/A" ){
				$ex_data['twitter']=$extra_data->twitter;
			}else{
				$ex_data['twitter']='#';
			}

			$ex_data['id']=$i;
			$ex_data['volume_24h']=$ex_data['volume_24h'];
			$ex_array[]=$ex_data;
			$i++;
		}
	  }
		$response['data'] =$ex_array;
		echo json_encode( $response );
	wp_die();
}
