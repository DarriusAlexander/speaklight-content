<?php

/*
Coin based exchanges price for Coin market Cap Plugin
*/

function celp_coin_exchanges($atts, $content = null){
		$atts = shortcode_atts( array(
		'id'  => '',
		), $atts, 'celp-detail' );
		$output='';

		$currency_pairs=array();
		celp_load_assets($page='coin_single');

if(get_query_var('coin_id')){
	    $coin_id=(string) trim(get_query_var('coin_id'));
		$coin_symbol=(string) trim(get_query_var('coin_symbol'));
	
		if (false === ($cache = get_transient($coin_id.'-all-markets-saved'))) {
			$rs=celp_save_coins_ex_pairs($coin_id);
		
			if($rs){
			$timing=7 * MINUTE_IN_SECONDS;
			set_transient($coin_id.'-all-markets-saved', date('H:s:i'),$timing);
			}	
		}	
		$slug =!empty(celp_get_option('exchange-page-slug'))? celp_get_option('exchange-page-slug'):"exchange";
		$per_page = celp_get_option('coin_details_exchange_per_page') ? celp_get_option('coin_details_exchange_per_page') : 10;
		$celp_prev = __( 'Previous','celp');
		$celp_next = __( 'Next','celp');				
		$celp_show = __( 'Show','celp');
		$celp_search=__( 'Search','celp');
		$celp_entries = __( 'Entries','celp');
		$no_rs_found = __( 'No Exchange Found','celp');
		$ex_single_slug=esc_url( home_url($slug,'/') );
		$e_logo=CELP_URL .'assets/logos/logo32/default-logo.png';

		$celp_show_entries = sprintf("%s _MENU_ %s",$celp_show,$celp_entries);
		$output .='<div class="currecies-pairs" data-ex-default-logo="'.$e_logo.'">
			<table  data-per-page="'. $per_page .'" id="celp_coin_exchanges"
			 class="celp-datatable table table-striped table-bordered" 
			  data-show-entries="'.$celp_show_entries.'" 
			  data-prev="'.$celp_prev.'" data-next="'.$celp_next.'" 
			  data-search="'.$celp_search.'"
			  data-coin-symbol="'.$coin_symbol.'"
			  data-zero-records="'.$no_rs_found.'"
			  data-coin-id="'.$coin_id.'"
			  ><thead>
			<tr>
			<th data-classes="celp-id" data-index="id"  class="desktop">'.__( '#', 'celp' ).'</th>
			<th data-classes="exchange-name-logo" data-index="exchange_name" data-ex-single-slug="'.$ex_single_slug.'" class="">
			'.__( 'Exchange', 'celp' ).'</th>
			<th data-classes="celp-pair" data-index="pair" class="all">'.__( 'Pair', 'celp' ).'</th>
			<th data-classes="celp-price" data-index="price" class="all">'.__( 'Price', 'celp' ).'</th>
			<th data-classes="celp-volume" data-index="volume_24h" class="all">'.__( 'Volume (24h)', 'celp' ).'</th>
			<th data-orderable="false" data-classes="celp-updated" data-index="updated" class="all">'.__( 'Updated', 'celp' ).'</th>
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
function celp_get_coin_exchanges_handler(){
	$all_pairs=array();
	$celpExPairs=new CELP_Exchanges_Pairs;
	$coin_id=$_REQUEST['coin_id'];
	$exchange_list=$celpExPairs->get_exchange_coin_price($coin_id);
	$i=0;

	  if(is_array($exchange_list)){
		foreach($exchange_list as $index=> $pair){
			
			$Exchange = new CELP_Exchanges();
			// make sure the exchange is not disabled from backend
			$status =$exchange = $Exchange->get_exchanges( array( 'ex_id'=>$pair['ex_id'], 'exchange_status'=>'enable' ) );
			if( $status == false ){
				continue;
			}

			$i++;
			$pair_data['id']=$i;
			$pair_data['volume_24h']=$pair['volume_usd'];
			$pair_data['ex_id']=$pair['ex_id'];
			$e_id=$pair['ex_id'];
			$e_logo='';
			$local_logo= CELP_PATH . 'assets/logos/logo32/' . $e_id . '.png';
			if (file_exists($local_logo)) {
			$e_logo = CELP_URL . 'assets/logos/logo32/' . $e_id . '.png';
			}
	
			$pair_data['logo']=$e_logo;
			$pair_data['exchange_name']=$pair['name'];
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


function  generate_pair_list($exchange_data,$detail_page_url,$i,$currency){

	$e_id=$exchange_data['ex_id'];
	$local_logo =CELP_PATH.'assets/logos/logo32/'.$e_id.'.png';
	if (file_exists($local_logo)) {
		$e_logo = CELP_URL . 'assets/logos/logo32/' . $e_id . '.png';
	} else {
		$e_logo = 'https://res.cloudinary.com/coolplugins/image/upload/exchanges-logo/32x32/' . $e_id . '.png';

	}

	$e_name=ucfirst(str_replace("-","",$exchange_data['ex_id']));
	$pair=$exchange_data['currency_pairs']['pair'];

	$timestamp=get_timeago($exchange_data['currency_pairs']['timestamp']);
	$ex_detial_page=$detail_page_url.'/'.$e_id;

	$selected_currency = $currency['c_name'];
	$currency_price = $currency['c_price'];
	if ($selected_currency == "USD") {
	$price = celp_fiat_cur_symbol($selected_currency) .  celp_format_number($exchange_data['currency_pairs']['price']);
	$volume = celp_fiat_cur_symbol($selected_currency) .  celp_format_number($exchange_data['currency_pairs']['volume']);
	} else {
		$volusd = (float)$exchange_data['currency_pairs']['volume'];
		$vol_conversion = $volusd * $currency_price;
		$volume = celp_fiat_cur_symbol($selected_currency) . celp_format_number($vol_conversion);

		$usd_price = (float)$exchange_data['currency_pairs']['price'];
		$usd_price_conversion = $usd_price * $currency_price;
		$price = celp_fiat_cur_symbol($selected_currency) . celp_format_number($usd_price_conversion);
	}

	$output ='<tr>';
			$output .='<td data-order="'.$i.'">'.$i.'</td>';
			$output .='<td class="exchange-name-logo"><a title="'.$e_name.'" href="'.$ex_detial_page.'/"> <img onerror=this.src="'. CELP_URL . 'assets/logos/logo32/exchange-logo.png" alt="'.$e_name.'" src="'.$e_logo.'">'.$e_name.'</a></td>';
			$output .='<td>'.$pair.'</td>';
			$output .='<td data-order="'.$exchange_data['currency_pairs']['price'].'">'.$price.'</td>';
			$output .='<td data-order="'.$exchange_data['currency_pairs']['volume'].'">'.$volume.'</td>';
			$output .='<td>'.$timestamp.'</td>';
			$output .='</tr>';

	return $output;
}	
