<?php

/*
|--------------------------------------------------------------------------
| Fetching and saving all coin data
|--------------------------------------------------------------------------
 */	

	function save_cmc_coins_data(){
		$coins_data=array();
		//set_time_limit(120);
		$api_url = CMC_API_ENDPOINT."coins/all?weekly=false&info=false";
		$request = wp_remote_get($api_url,array('timeout' => 120));
		if (is_wp_error($request)) {
			//error_log( $request );
			return false; // Bail early
		}
		$body = wp_remote_retrieve_body($request);
		$coins_data = json_decode($body);
		$cmcDB = new CMC_Coins;
		if($coins_data){	
			$first_part=array_slice($coins_data->data,0,900);
			$second_part=array_slice($coins_data->data,901,CMC_LOAD_COINS);
			$coins1 = objectToArray($first_part);
			$coins2 = objectToArray($second_part); 
			global $wpdb;
		//	update_option('cmc-saving-time', time() );
		//	$truncate = $wpdb->query('TRUNCATE TABLE ' .  $wpdb->base_prefix . 'cmc_coins');
			$cmcDB->create_table();
			$rs=$cmcDB->cmc_insert($coins1);
			$rs=$cmcDB->cmc_insert($coins2);
		
			return $rs;
			}
	}


/*
|--------------------------------------------------------------------------
| saving coins weekly data
|--------------------------------------------------------------------------
 */
function save_cmc_historical_data()
{
	$cmcMetaDB = new CMC_Coins_Meta;
	$api_url = CMC_API_ENDPOINT . "coins/weeklydata";
	$request = wp_remote_get($api_url, array('timeout' => 120));
	if (is_wp_error($request)) {
		//error_log( $request );
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body($request);
	$coindata = json_decode($body);
	if ($coindata) {
	
	$arr_data=(array)$coindata->data;
	$first_part=array_slice($arr_data,0,600);
	$second_part=array_slice($arr_data,601,1200);
	$third_part=array_slice($arr_data,1201,CMC_LOAD_COINS);
	//update_option('cmc-charts-saving-time', time() );
	$histo_data1 = objectToArray($first_part);
	$histo_data2 = objectToArray($second_part);
	$histo_data3 = objectToArray($third_part);
	$rs= $cmcMetaDB->cmc_weekly_data_insert($histo_data1);
	$rs= $cmcMetaDB->cmc_weekly_data_insert($histo_data2);
	$rs= $cmcMetaDB->cmc_weekly_data_insert($histo_data3);
	
	return $rs;
	}
}

/*
|--------------------------------------------------------------------------
| saving coins extra data
|--------------------------------------------------------------------------
 */
function save_cmc_extra_data()
{
	$cmcMetaDB = new CMC_Coins_Meta;
	$api_url = CMC_API_ENDPOINT . "coins/info?desc=false";
	$request = wp_remote_get($api_url, array('timeout' => 120));
	if (is_wp_error($request)) {
		//error_log( $request );
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body($request);
	$coindata = json_decode($body);
	if ($coindata) {
	$arr_data=(array)$coindata->data;
	$first_part=array_slice($arr_data,0,900);
	$second_part=array_slice($arr_data,901,CMC_LOAD_COINS);
	$extra_data1 = objectToArray($first_part);
	$extra_data2 = objectToArray($second_part);
	
	$rs= $cmcMetaDB->cmc_extra_meta_insert($extra_data1);
	$rs= $cmcMetaDB->cmc_extra_meta_insert($extra_data2);
	return $rs;
	}
}

/*
|--------------------------------------------------------------------------
| saving coins desc data
|--------------------------------------------------------------------------
 */
function save_coin_desc_data()
{
	$cmcMetaDB = new CMC_Coins_Meta;
	$api_url = CMC_API_ENDPOINT . "coins/info?extra=false";
	$request = wp_remote_get($api_url, array('timeout' => 120));
	if (is_wp_error($request)) {
		//error_log( $request );
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body($request);
	$coindata = json_decode($body);
	if ($coindata) {
		$arr_data=(array)$coindata->data;
		$first_part=array_slice($arr_data,0,900);
		$second_part=array_slice($arr_data,900,CMC_LOAD_COINS);
		$desc_data1 = objectToArray($first_part);
		$desc_data2 = objectToArray($second_part);
	$rs= $cmcMetaDB->cmc_desc_insert($desc_data1);
	$rs= $cmcMetaDB->cmc_desc_insert($desc_data2);
	return $rs;
	}
}

/*
|--------------------------------------------------------------------------
| getting single coin details
|--------------------------------------------------------------------------
 */		
function cmc_get_coin_details($coin_id){
		$cmcDB = new CMC_Coins;
		$coin_data =$cmcDB->get_coins(array('coin_id'=> $coin_id));
	if(is_array($coin_data)&& isset($coin_data[0])){
		 $coin_data= objectToArray($coin_data[0]);
		return $coin_data;
		}else{
			return false;
		}
	
}

/*
|--------------------------------------------------------------------------
| getting coin meta
|--------------------------------------------------------------------------
 */	
function cmc_get_coin_meta($coin_id)
{

	$cmcMetaDB = new CMC_Coins_Meta;
	//if ($cmcMetaDB->coin_exists_by_id($coin_id) == true) {
		$coin_data = $cmcMetaDB->get_coins_meta_data(array('coin_id' => $coin_id));
		if(is_array($coin_data)&& isset($coin_data[0]->extra_data)){
			return unserialize($coin_data[0]->extra_data);
		
	} else {
		return false;
	}

}

/*
|--------------------------------------------------------------------------
| getting coin single page description 
|--------------------------------------------------------------------------
 */
function cmc_get_coin_desc($coin_id)
{
	$cmcMetaDB = new CMC_Coins_Meta;
	if ($cmcMetaDB->coin_exists_by_id($coin_id) == true) {
		$coin_data = $cmcMetaDB->get_coins_desc(array('coin_id' => $coin_id));
		if (isset($coin_data[0]->description)) {
			return $coin_data[0]->description;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


/*
|--------------------------------------------------------------------------
| fetching top gainer/ losers
|--------------------------------------------------------------------------
 */
function cmc_get_top_coins($type = "gainers",$show_coins=10){
	$cmcDB = new CMC_Coins;
	if($type== "gainers"){
		$order_type='DESC';
	}else{
		$order_type = 'ASC';
	}
	$coindata = $cmcDB->get_top_changers_coins(array(
		"number" => $show_coins,'orderby' =>'percent_change_24h',
		'order' => $order_type,
		'volume'=>50000
	));
	if(is_array($coindata) && count($coindata)>0){
		return $coindata;
	}else{
		return false;
	}
}

function coin_search($old_currency, $single_default_currency, 
$single_page_slug)
{
	$html='';
	$search_links='';
	$search = __('search', 'cmc');
	$no_result= __('Unable to find any result', 'cmc');
	$html .= '<div data-slug="'.$single_page_slug.'"  data-currency="' . $old_currency . '"  data-no-result="'.$no_result.'" class="cmc_search" id="custom-templates">
  		<input class="typeahead" type="text" placeholder="' . $search . '">
		</div>';
	return $html;
}