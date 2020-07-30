<?php
/*
|--------------------------------------------------------------------------
|  grabing bitcoin price for conversion dropdown
|--------------------------------------------------------------------------
 */	
function cmc_btc_price(){
	if (false === ($cache = get_transient('cmc_btc_price'))) {
		$cmcDB = new CMC_Coins;
		$coin_data = $cmcDB->get_coins(array('coin_id' =>'bitcoin'));
		$btc_price='';
		if(!empty($coin_data[0]->price)){
		$btc_price = $coin_data[0]->price;
		set_transient('cmc_btc_price', $btc_price, 4 * MINUTE_IN_SECONDS);
		return $btc_price;
		}
	}else{
		return $btc_price = get_transient('cmc_btc_price');
	}
}

/*
|--------------------------------------------------------------------------
|  creating coins list for later use
|--------------------------------------------------------------------------
 */	
function cmc_coin_list_data()
{
	if (false === ($cache = get_transient('coins_listdata'))) {
	$cmcDB = new CMC_Coins;
	$coin_data = $cmcDB->get_coins_listdata(array('number' =>CMC_LOAD_COINS,'orderby' =>'market_cap',
	'order' =>'DESC'));
	$coin_list=array();
	foreach($coin_data  as $index=> $coin){
		$coin_id= $coin->coin_id;
		$coin_list[$coin->coin_id]=array("name"=>$coin->name,'price'=>$coin->price,'symbol'=>$coin->symbol);	
	}
	set_transient('coins_listdata', $coin_list, 5 * MINUTE_IN_SECONDS);
	return $coin_list;
	}else{
		return $coin_list = get_transient('coins_listdata');
	}
}

/*-----------------------------------------------------------------------|
|	 		Fetch all coin description created at admin dashboard	 	 |
| 				  This function only create a transient.				 |
|------------------------------------------------------------------------|
*/
function get_all_custom_cmc_description(){

	if( false === ($check = get_transient( 'cmc-custom-coin-des' ) ) ) {
		$custom_description = array(
			'post_type'=>'cmc-description',
			'posts_per_page'=>'-1'
		);

		$exists = new WP_Query( $custom_description );
		$already_exists = array();
		while( $exists->have_posts() ){
			$exists->the_post();
			$coin_id =  get_post_meta( get_the_ID(), 'cmc_single_settings_des_coin_name', true );
			$already_exists[] = $coin_id;
		}
		wp_reset_postdata();
		set_transient( 'cmc-custom-coin-des', $already_exists, 24 * HOUR_IN_SECONDS );
		return $already_exists;
	}else{
		return get_transient( 'cmc-custom-coin-des' );
	}

}

/*-----------------------------------------------------------------------|
|		 		Fetch all existing coin id via server				 	 |
|------------------------------------------------------------------------|
*/


function cmc_coin_arr(){

	if (false === ($cache = get_transient('coins_arr'))) {
		$api_url = CMC_API_ENDPOINT."coins/all?weekly=false&info=false";
		$request = wp_remote_get($api_url,array('timeout' => 120));
		if (is_wp_error($request)) {
			//error_log( $request );
			return false; // Bail early
		}
		$body = wp_remote_retrieve_body($request);
		$coins_data = json_decode($body);
		$coin_list=array();
		foreach($coins_data->data  as $index=> $coin){
			$coin_id= $coin->coin_id;
			$coin_list[$coin_id]=$coin->name;	
		}
		set_transient('coins_arr', $coin_list, 48 * HOUR_IN_SECONDS);
		return $coin_list;
		}else{
			return $coin_list = get_transient('coins_arr');
		}
}
/*
|--------------------------------------------------------------------------
| Fetching Historical data of mentioned coin
|--------------------------------------------------------------------------
 */			
		function cmc_historical_coins_arr($coin_id){
		$historical_coin_list= get_transient($coin_id.'-histo-data');
				 $historical_c_list=array();
				 if( empty($historical_coin_list) || $historical_coin_list==="" ) {
				   	 	$request = wp_remote_get( 'https://api.coingecko.com/api/v3/coins/'.$coin_id.'/market_chart?vs_currency=usd&days=365'.$coin_id,array('timeout'=> 120));
				  	if( is_wp_error( $request ) ) {
							return false; // Bail early
						}
						$body = wp_remote_retrieve_body( $request );
						$historical_coinsdata = json_decode( $body );
						if( ! empty( $historical_coinsdata ) ) {
						set_transient($coin_id.'-histo-data', $historical_coinsdata, 12*HOUR_IN_SECONDS);
						 $historical_coin_list=$historical_coinsdata;
						}
					}
						if(!empty($historical_coin_list )) {
						return $historical_coin_list;
						}
		}

	
/*
|--------------------------------------------------------------------------
| Coin single page main full chart
|--------------------------------------------------------------------------
 */	
		function coin_chart_data_json($coin_id,$type){
			$coin_d_arr=array();
			$historical_all_data = cmc_historical_coins_arr($coin_id);
			if (!empty($historical_all_data)) {
				$count = count($historical_all_data->prices);
					for ($i = 0; $i < $count; $i++) {
						$at_time= $historical_all_data->prices[$i][0];
						$coin_price= $historical_all_data->prices[$i][1];
						$coin_vol= $historical_all_data->total_volumes[$i][1];
						if($type=="chart"){
						 $coin_d_arr[]=array('date'=> $at_time, 'value'=>$coin_price,'volume'=>$coin_vol);
						}else{
						 $coin_market_cap = $historical_all_data->market_caps[$i][1];
						 $coin_d_arr[] = array('date' => $at_time, 'value' => $coin_price, 'volume' => $coin_vol,'market_cap'=>$coin_market_cap);	
						}
					}

					return $coin_d_arr;
				}
		}	


/*
|--------------------------------------------------------------------------
| coin market global data
|--------------------------------------------------------------------------
 */	
	
	function cmc_get_global_data(){

		if (false === ($cache = get_transient('cmc-global-data'))) {
	   	 	$request = wp_remote_get( CMC_API_ENDPOINT.'global-data' );
			if( is_wp_error( $request ) ) {
				//error_log($request);
				return false; // Bail early
			}
			$body = wp_remote_retrieve_body( $request );
			$global_data = json_decode( $body );
			if( ! empty( $global_data ) ) {
			 set_transient('cmc-global-data', $global_data, 15 * MINUTE_IN_SECONDS);
			 }
		 }else{
			$global_data = get_transient('cmc-global-data');
		 }
			return $global_data;
		}
/*
|--------------------------------------------------------------------------
| Helper funciton for formatting large values in billion/million
|--------------------------------------------------------------------------
 */	

	
   function cmc_format_coin_values($value, $precision = 2) {
	    if ($value < 1000000) {
	        // Anything less than a million
	        $formated_str = number_format($value);
	    } else if ($value < 1000000000) {
			// Anything less than a billion
	        $formated_str = number_format($value / 1000000, $precision) . '  M';
		   
			if(has_filter('cmc_change_format_text')) {
            $formated_str = apply_filters('cmc_change_format_text', $formated_str);
            }
			
	        
	    } else {
	        // At least a billion
	       $formated_str= number_format($value / 1000000000, $precision) . '  B';
	    
		   if(has_filter('cmc_change_format_text')) {
           $formated_str = apply_filters('cmc_change_format_text', $formated_str);
           }
		
		}

    return $formated_str;
    }
/*
|--------------------------------------------------------------------------
| Basic price formatter
|--------------------------------------------------------------------------
 */	
	function format_number($n){

	if($n >= 25){
	return	$formatted = number_format($n, 2, '.', ',');
	}
	else if($n >= 0.50 && $n < 25){
	return	$formatted = number_format($n, 3, '.', ',');
	}
	else if($n >= 0.01 && $n < 0.50){
	return	$formatted = number_format($n, 4, '.', ',');
	}
	else if($n >= 0.001 && $n < 0.01){
	return	$formatted = number_format($n, 5, '.', ',');
	}
	else if($n >= 0.0001 && $n < 0.001){
	return	$formatted = number_format($n, 6, '.', ',');
	}
	else{
	return	$formatted = number_format($n, 8, '.', ',');
    }
	}
/*
|--------------------------------------------------------------------------
| getting titan settings
|--------------------------------------------------------------------------
 */	

	function cmc_get_settings($post_id,$index){
		if($post_id && $index){
		$val=get_post_meta($post_id,$index,true);
		if($val){
			return true;
			}else{
				return false;
			}
		}
	}


/*
|--------------------------------------------------------------------------
| generating coin logo URL based upon coin id
|--------------------------------------------------------------------------
 */	
	
	function coin_logo_url($coin_id,$size=32){
	$logo_html='';
	$coin_logo_info=array();
	$upload = wp_upload_dir(); // Set upload folder
	$upload_dir = $upload['basedir'] . '/cmc/coins/small-icons/';
	$upload_url = $upload['baseurl'] . '/cmc/coins/small-icons/'.$coin_id.'.png';
	$coin_png = $upload_dir . $coin_id . '.png';
	$coin_svg=CMC_PATH.'/assets/coins-logos/'.$coin_id.'.svg';
	$coin_svg_url=CMC_URL.'/assets/coins-logos/'.$coin_id.'.svg';
	
	if (file_exists($coin_svg)) {
		$coin_logo_info['logo']=$coin_svg_url;
		$coin_logo_info['local']=true;
		return $coin_logo_info;
	}else if(file_exists($coin_png)){
		$coin_logo_info['logo']=$upload_url;
		$coin_logo_info['local']=true;
		return $coin_logo_info;

		}
		else {
		if($size==32){
		$index="32x32";
		}else{
		$index="128x128";
		}
		
		$coin_icon='https://res.cloudinary.com/coinmarketcap/image/upload/cryptocurrency/'.$index.'/'.$coin_id. '.png';
		$coin_logo_info['logo']=$coin_icon;
		$coin_logo_info['local']=false;
		return $coin_logo_info;

	}
}

/*
|--------------------------------------------------------------------------
| generating coin logo URL based upon coin id
|--------------------------------------------------------------------------
 */	
function coin_list_logo($coin_id, $size = 32)
{
	$logo_html = '';
	$coin_logo_info = array();
	$upload = wp_upload_dir(); // Set upload folder
	$upload_dir = $upload['basedir'] . '/cmc/coins/small-icons/';
	$upload_url = $upload['baseurl'] . '/cmc/coins/small-icons/';
	$coin_svg = CMC_PATH . '/assets/coins-logos/' . $coin_id . '.svg';
	$coin_png = $upload_dir . $coin_id . '.png';
	if (file_exists($coin_svg)) {
		return $logo_path= CMC_URL . 'assets/coins-logos/'.$coin_id . '.svg';
	} else if (file_exists($coin_png)) {
		return $logo_path =  $upload_url . $coin_id . '.png';
	} else {
	  $index = "32x32";
	 $coin_icon ='https://res.cloudinary.com/coinmarketcap/image/upload/cryptocurrency/' . $index . '/' . $coin_id . '.png';
		return $coin_icon;
	}
}

/*
|--------------------------------------------------------------------------
| generating coin logo URL based upon coin id
|--------------------------------------------------------------------------
 */	
	function cmc_coin_single_logo($coin_id,$size=128){
		$upload = wp_upload_dir(); // Set upload folder
		$upload_dir = $upload['basedir'] . '/cmc/coins/large-icons/';
		$upload_url = $upload['baseurl'] . '/cmc/coins/large-icons/';
		$logo_html='';
		$coin_png = $upload_dir . $coin_id . '.png';
		$coin_svg=CMC_PATH.'/assets/coins-logos/'.$coin_id.'.svg';
		$size= $size==''?128:$size;
		if (file_exists($coin_svg)) {
			$coin_svg=CMC_URL.'assets/coins-logos/'.$coin_id.'.svg';
			$logo_html='<img style="width:'.$size.'px;" id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_svg.'">';
		}else if (file_exists($coin_png)) {
			return $logo_html =  '<img style="width:'.$size.'px;" id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$upload_url . $coin_id . '.png">';
		}else{
			$index="128x128";
			$coin_icon='https://res.cloudinary.com/coinmarketcap/image/upload/cryptocurrency/'.$index.'/'.$coin_id. '.png';
			$logo_html='<img id="'.$coin_id.'" alt="'.$coin_id.'" src="'.$coin_icon.'" onerror="this.src = \'https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/'.$index.'/default-logo.png\';">';
		}
		return $logo_html;
	}

/*
|--------------------------------------------------------------------------
| Fiat  currencies symbol
|--------------------------------------------------------------------------
 */	

	function cmc_old_cur_symbol($name){
		 $cc = strtoupper($name);
		    $currency = array(
			"USD" => "&#36;" , //U.S. Dollar
			"JMD" => "J&#36", //Jamaican Dollars
		    "AUD" => "&#36;" , //Australian Dollar
		    "BRL" => "R&#36;" , //Brazilian Real
		    "CAD" => "C&#36;" , //Canadian Dollar
		    "CZK" => "K&#269;" , //Czech Koruna
		    "DKK" => "kr" , //Danish Krone
		    "EUR" => "&euro;" , //Euro
		    "HKD" => "&dollar;" , //Hong Kong Dollar
		    "HUF" => "Ft" , //Hungarian Forint
		    "ILS" => "&#x20aa;" , //Israeli New Sheqel
		    
			"INR" => "&#8377;", //Indian Rupee
		    "JPY" => "&yen;" , //Japanese Yen 
		    "MYR" => "RM" , //Malaysian Ringgit 
		    "MXN" => "&#36;" , //Mexican Peso
		    "NOK" => "kr" , //Norwegian Krone
		    "NZD" => "&#36;" , //New Zealand Dollar
		    "PHP" => "&#x20b1;" , //Philippine Peso
		    "PLN" => "&#122;&#322;" ,//Polish Zloty
		    "GBP" => "&pound;" , //Pound Sterling
		    "SEK" => "kr" , //Swedish Krona
		    
			"CHF" => "Fr " , //Swiss Franc
		    "TWD" => "NT&#36;" , //Taiwan New Dollar 
		    "THB" => "&#3647;" , //Thai Baht
		    "TRY" => "&#8378;", //Turkish Lira
		    
			"CNY" => "&yen;" , //China Yuan Renminbi
			'KRW'   => "&#8361;", //Korea (South) Won
			'RUB'   => "&#8381;", //Russia Ruble
			'SGD'   => "S&dollar;",  //Singapore Dollar
			'CLP'   => "&dollar;", //Chile Peso
			'IDR'   => "Rp ", //Indonesia Rupiah
			'PKR'   => "₨ ", //Pakistan Rupee
			'ZAR'   => "R ", //South Africa Rand
			'BTC'=>'&#579;'
			);
		    
		    if(array_key_exists($cc, $currency)){
		        return $currency[$cc];
		    }
	}
/*
|--------------------------------------------------------------------------
| Fiat  currencies codes
|--------------------------------------------------------------------------
 */	

	function currencies_json(){

		 $currency = array(
			"USD" => "&#36;" , //U.S. Dollar,
			"JMD" => "J&#36", //Jamaican Dollars
		    "AUD" => "&#36;" , //Australian Dollar
		    "BRL" => "R&#36;" , //Brazilian Real
		    "CAD" => "C&#36;" , //Canadian Dollar
		    "CZK" => "K&#269;" , //Czech Koruna
		    "DKK" => "kr" , //Danish Krone
		    "EUR" => "&euro;" , //Euro
		    "HKD" => "&dollar;" , //Hong Kong Dollar
		    "HUF" => "Ft" , //Hungarian Forint
		    "ILS" => "&#x20aa;" , //Israeli New Sheqel
		    
			"INR" => "&#8377;", //Indian Rupee
		    "JPY" => "&yen;" , //Japanese Yen 
		    "MYR" => "RM" , //Malaysian Ringgit 
		    "MXN" => "&#36;" , //Mexican Peso
		    "NOK" => "kr" , //Norwegian Krone
		    "NZD" => "&#36;" , //New Zealand Dollar
		    "PHP" => "&#x20b1;" , //Philippine Peso
		    "PLN" => "&#122;&#322;" ,//Polish Zloty
		    "GBP" => "&pound;" , //Pound Sterling
		    "SEK" => "kr" , //Swedish Krona
		    
			"CHF" => "Fr " , //Swiss Franc
		    "TWD" => "NT&#36;" , //Taiwan New Dollar 
		    "THB" => "&#3647;" , //Thai Baht
		    "TRY" => "&#8378;", //Turkish Lira
		    
			"CNY" => "&yen;" , //China Yuan Renminbi
			'KRW'   => "&#8361;", //Korea (South) Won
			'RUB'   => "&#8381;", //Russia Ruble
			'SGD'   => "S&dollar;",  //Singapore Dollar
			'CLP'   => "&dollar;", //Chile Peso
			'IDR'   => "Rp ", //Indonesia Rupiah
			'PKR'   => "₨ ", //Pakistan Rupee
			'ZAR'   => "R ", //South Africa Rand
			'BTC' => '&#579;'
		    );
		return json_encode($currency);
	}
	
/*
|--------------------------------------------------------------------------
| objectToArray conversion helper function
|--------------------------------------------------------------------------
 */	

  function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }
		
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }

/*
|--------------------------------------------------------------------------
| Detect mobile devices
|--------------------------------------------------------------------------
 */	
	function cmc_isMobileDevice()
	{
	
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}	

/*
|--------------------------------------------------------------------------
| Register scripts and styles
  add cdn and change js file functions
|--------------------------------------------------------------------------
 */		
	 function cmc_register_scripts() {
		if ( ! is_admin() ) {

			if( ! wp_script_is( 'jquery', 'done' ) ){
                wp_enqueue_script( 'jquery' );
            }
			wp_register_style( 'cmc-icons',CMC_URL.'assets/css/cmc-icons.min.css',null,CMC );
			wp_register_style( 'cmc-custom',CMC_URL.'assets/css/cmc-custom.min.css',null,CMC );
			wp_register_style( 'cmc-bootstrap',CMC_URL.'assets/css/bootstrap.min.css',null,CMC );
			
			wp_register_script('cmc-datatables', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',null,CMC);
			wp_register_script('crypto-numeral', CMC_URL .'assets/js/numeral.min.js', array('jquery'),CMC, true);
			
		}
	}	

/*
|--------------------------------------------------------------------------
| USD conversion helper function
|--------------------------------------------------------------------------
 */	

	function cmc_usd_conversions($currency){
 
		$conversions= get_transient('cmc_usd_conversions');
		if( empty($conversions) || $conversions==="" ) {
		   	 	$request = wp_remote_get(CMC_API_ENDPOINT. 'exchange-rates');
		  	if( is_wp_error( $request ) ) {
					return false;
				}
				$currency_ids = array("USD","AUD","BRL","CAD","CZK","DKK", "EUR","HKD","HUF","ILS","INR" ,"JPY" ,"MYR","MXN", "NOK","NZD","PHP" ,"PLN","GBP" ,"SEK","CHF","TWD","THB" ,"TRY","CNY","KRW","RUB", "SGD","CLP", "IDR","PKR", "ZAR","JMD" );
				$body = wp_remote_retrieve_body( $request );
				$conversion_data= json_decode( $body );
				if(isset($conversion_data->rates)){
				$conversion_data=(array)$conversion_data->rates;
				}else{
					$conversion_data=array();
				}
				if(is_array($conversion_data) && count($conversion_data)>0) {
					foreach($conversion_data as $key=> $currency_price){
							if(in_array($key,$currency_ids)){
								$conversions[$key]=$currency_price;
							}
					}	
				uksort($conversions, function($key1, $key2) use ($currency_ids) {
				    return (array_search($key1, $currency_ids) > array_search($key2, $currency_ids));
				});
			
				set_transient('cmc_usd_conversions',$conversions, 3 * HOUR_IN_SECONDS);
				}
			}

			if($currency=="all"){
				
				return $conversions;

			}else{
				if(isset($conversions[$currency])){
					return $conversions[$currency];
				}
			}
	}


/*
|--------------------------------------------------------------------------
| coin single page dynamic slug
|--------------------------------------------------------------------------
 */	

function cmc_get_page_slug(){
	
		if(get_option('cmc-single-page-slug')){
			return $slug= get_option('cmc-single-page-slug');
		}else{
			return $slug="currencies";
		}
}

/*
|--------------------------------------------------------------------------
| custom description formatting
|--------------------------------------------------------------------------
 */	
function cmc_get_wysiwyg_output($meta_key, $post_id = 0)
{
	global $wp_embed;

	$post_id = $post_id ? $post_id : get_the_id();

	$content = get_post_meta($post_id, $meta_key, 1);
	$content = $wp_embed->autoembed($content);
	$content = $wp_embed->run_shortcode($content);
	$content = wpautop($content);
	$content = do_shortcode($content);

	return $content;
}

/*
|--------------------------------------------------------------------------
| Integrating titan dynamic styles
|--------------------------------------------------------------------------
 */
 function cmc_dynamic_style(){
 		$cmc_dynamic_css='';
  	 	$cmc_titan = TitanFramework::getInstance( 'cmc_single_settings' );
 		return	$cmc_dynamic_css =$cmc_titan->getOption('cmc_dynamic_css');
   }

	/*
	|----------------------------------------------
	|	Get list of coin details pages
	|----------------------------------------------
	*/
	function cmc_get_coins_detail_pages(){
			$pages = array();
			$regular_coin_page  = get_option('cmc-coin-single-page-id');
			$advanced_coin_page = get_option('cmc-coin-advanced-single-page-id');

			if( $regular_coin_page != false && get_post_status($regular_coin_page) == 'publish' ){
				$pages[$regular_coin_page] = 'Regular Clean Design';
			}
			if( $advanced_coin_page != false && get_post_status($regular_coin_page) == 'publish' ){
				$pages[$advanced_coin_page] = 'Advanced Tab Design';
			}

			return $pages;
	}

	function cmc_get_coins_details_page_id(){
		$dynamic = get_option('cmc-coin-single-page-selected-design');
		$fresh_install		= get_option('CMC_FRESH_INSTALLATION');
	
		if( ( $fresh_install !== CMC || $fresh_install == false ) && $dynamic==false){
			$dynamic = get_option( 'cmc-coin-single-page-id' );
			update_option('cmc-coin-single-page-selected-design',$dynamic);
		}else if( ( $fresh_install === CMC && $dynamic == false ) && $dynamic==false){
			$dynamic = get_option( 'cmc-coin-advanced-single-page-id' );
			update_option('cmc-coin-single-page-selected-design',$dynamic);
		}

		return $dynamic;
	}

	function cmc_update_coin_ids($coin_id){
 	 $excluded=	array(
			"0xbtc"=>"oxbitcoin",
			"1337coin"=>"1337",
			"300-token"=>"300token",
			"ab-chain-rtb"=>"ab-chain",
			"ace"=>"tokenstars-ace",
			"acre"=>"acrecoin",
			"advanced-internet-blocks"=>"advanced-internet-block",
			"adx-net"=>"adex",
			"agrello-delta"=>"agrello",
			"aidoc"=>"ai-doctor",
			"airbloc"=>"airbloc-protocol",
			"akuya-coin"=>"akuyacoin",
			"alchemint-standards"=>"alchemint",
			"algorand"=>"algorand",
			"alphabitcoinfund"=>"alphabit",
			"altcoin-alt"=>"altcoin",
			"amlt"=>"coinfirm-amlt",
			"amo-coin"=>"amo",
			"apollo-currency"=>"apollo",
			"arbitrage"=>"arbitraging",
			"atc-coin"=>"atccoin",
			"attention-token-of-media"=>"atmchain",
			"b2bx"=>"b2b",
			"bhpcash"=>"bhpc",
			"bigbom"=>"bigbom-eco",
			"binance-coin"=>"binancecoin",
			"bit-tube"=>"bittube",
			"bitblocks"=>"bitblocks-project",
			"bitcapitalvendor"=>"bcv",
			"bitcny"=>"bitCNY",
			"bitcoin-sv"=>"bitcoin-cash-sv",
			"bitcoin-token"=>"bitcointoken",
			"bitcoinfast"=>"bitcoin-fast",
			"bitkan"=>"kan",
			"bitnation"=>"pangea",
			"bitrewards"=>"bitrewards-token",
			"bitscreener-token"=>"bitscreener",
			"bitshares-music"=>"muse",
			"bittorrent"=>"bittorrent-2",
			"blackmoon"=>"blackmoon-crypto",
			"blockmason"=>"blockmason-credit-protocol",
			"blockmesh"=>"blockmesh-2",
			"bloomtoken"=>"bloom",
			"blue-whale-token"=>"blue-whale",
			"bobs-repair"=>"bobs_repair",
			"boscoin"=>"boscoin-2",
			"bowhead"=>"bowhead-health",
			"brahmaos"=>"bioritmai",
			"brat"=>"brother",
			"brokernekonetwork"=>"broker-neko-network",
			"bt2-cst"=>"bt2",
			"bytecoin-bcn"=>"bytecoin",
			"c20"=>"crypto20",
			"c2c-system"=>"ctc",
			"cabbage"=>"cabbage-unit",
			"callisto-network"=>"callisto",
			"cartaxi-token"=>"cartaxi",
			"cedex-coin"=>"cedex",
			"ceek-vr"=>"ceek",
			"clipper-coin"=>"clipper-coin-capital",
			"coin"=>"coino",
			"colossusxt"=>"colossuscoinxt",
			"colu-local-network"=>"colu",
			"commerceblock"=>"commerceblock-token",
			"cdx-network"=>"commodity-ad-network",
			"compound-coin"=>"compound",
			"comsa-eth"=>"comsa",
			"coni"=>"coinbene-token",
			"cononchain"=>"canonchain",
			"constellation"=>"constellation-labs",
			"content-neutrality-network"=>"cnn",
			"cottoncoin"=>"cotton",
			"data-exchange"=>"databroker-dao",
			"datarius-credit"=>"datarius-cryptobank",
			"dav-coin"=>"dav",
			"decent-bet"=>"decentbet",
			"delta-chain"=>"deltachain",
			"denarius-dnr"=>"denarius",
			"digitex-futures"=>"digitex-futures-exchange",
			"digix-gold-token"=>"digix-gold",
			"docademic"=>"medical-token-currency",
			"doubloon"=>"boat",
			"dragon-coins"=>"dragon-coin",
			"dutch-coin"=>"dutchcoin",
			"dxchain-token"=>"dxchain",
			"dystem"=>"dsystem",
			"e-gulden"=>"electronicgulden",
			"eboostcoin"=>"eboost",
			"ebtcnew"=>"ebitcoin",
			"eccoin"=>"ecc",
			"edu-coin"=>"educoin",
			"elcoin-el"=>"elcoin",
			"electrifyasia"=>"electrify-asia",
			"eligma-token"=>"eligma",
			"emerald"=>"emerald-crypto",
			"endor-protocol"=>"endor",
			"energitoken"=>"energi-token",
			"enigma-project"=>"enigma",
			"enjin-coin"=>"enjincoin",
			"eplus-coin"=>"epluscoin",
			"escoro"=>"escroco",
			"ether-zero"=>"etherzero",
			"ethereum-blue"=>"blue",
			"ethereum-monero"=>"exmr-monero",
			"ethereumcash"=>"ethereum-cash",
			"experience-points"=>"xp",
			"experience-token"=>"exchain",
			"external-token"=>"eternal-token",
			"faceter"=>"face",
			"fantasygold"=>"fantasy-gold",
			"fintrux-network"=>"fintrux",
			"firstblood"=>"first-blood",
			"fluz-fluz"=>"fluzfluz",
			"folmcoin"=>"folm",
			"food"=>"foodcoin",
			"fox-trading"=>"fox-trading-token",
			"friends"=>"friendz",
			"fundtoken"=>"fundfantasy",
			"fundyourselfnow"=>"fund-yourself-now",
			"fusion"=>"fsn",
			"gamechain"=>"gamechain-system",
			"gems-protocol"=>"gems-2",
			"get-protocol"=>"get-token",
			"giant-coin"=>"giant",
			"global-cryptocurrency"=>"thegcccoin",
			"globalboost-y"=>"globalboost",
			"gnosis-gno"=>"gnosis",
			"golem-network-tokens"=>"golem",
			"graft"=>"graft-blockchain",
			"gridcoin"=>"gridcoin-research",
			"guess"=>"peerguess",
			"guppy"=>"matchpool",
			"harmonycoin-hmc"=>"harmonycoin",
			"haven-protocol"=>"haven",
			"heat-ledger"=>"heat",
			"hempcoin"=>"hempcoin-thc",
			"hero"=>"hero-token",
			"heronode"=>"hero-node",
			"hive-project"=>"hive",
			"hodl-bucks"=>"hodlbucks",
			"holo"=>"holotoken",
			"html-coin"=>"htmlcoin",
			"hybrid-block"=>"hybridblock",
			"hydrogen"=>"hydro",
			"ico-openledger"=>"openledger",
			"idol-coin"=>"idolcoin",
			"imbrex"=>"rex",
			"indorse-token"=>"indorse",
			"insanecoin-insn"=>"insanecoin",
			"intelligent-trading-foundation"=>"intelligent-trading-tech",
			"internationalcryptox"=>"international-cryptox",
			"ip-exchange"=>"ip-sharing-exchange",
			"ixledger"=>"insurex",
			"jesus-coin"=>"jesuscoin",
			"jibrel-network"=>"jibrel",
			"karma-eos"=>"karma-coin",
			"kora-network-token"=>"kora-network",
			"level-up"=>"play2live",
			"library-credit"=>"lbry-credits",
			"lobstex"=>"lobstex-coin",
			"local-coin-swap"=>"localcoinswap",
			"loki"=>"loki-network",
			"luna-coin"=>"lunacoin",
			"luna-stars"=>"meetluna",
			"massgrid"=>"masssgrid",
			"maximine-coin"=>"maximine",
			"mco"=>"monaco",
			"medical-chain"=>"medicalchain",
			"mediccoin"=>"medic-coin",
			"medx"=>"mediblocx",
			"metaverse"=>"metaverse-etp",
			"monero-classic"=>"monero-classic-xmc",
			"more-coin"=>"legends-room",
			"mybit"=>"mybit-token",
			"myriad"=>"myriadcoin",
			"nam-coin"=>"nam-token",
			"napoleonx"=>"napoleon-x",
			"nebulas-token"=>"nebulas",
			"nectar"=>"nectar-token",
			"neo-gold"=>"neogold",
			"nimiq-nim"=>"nimiq-2",
			"nix"=>"nix-platform",
			"oax"=>"openanx",
			"oneledger"=>"one-ledger",
			"ongsocial"=>"ong-social",
			"opcoinx"=>"over-powered-coin",
			"origami"=>"origami-network",
			"ormeus-coin"=>"ormeuscoin",
			"ors-group"=>"orsgroup-io",
			"own"=>"chainium",
			"oyster"=>"oyster-pearl",
			"pandacoin-pnd"=>"pandacoin",
			"pascal-coin"=>"pascalcoin",
			"paycoin2"=>"paycoin",
			"peerplays-ppy"=>"peerplays",
			"pepe-cash"=>"pepecash",
			"philosopher-stones"=>"philosopherstone",
			"policypal-network"=>"policypal",
			"quant"=>"quant-network",
			"quarkchain"=>"quark-chain",
			"raiden-network-token"=>"raiden-network",
			"rebl"=>"rebellious",
			"record"=>"record-farm",
			"restart-energy-mwat"=>"restart-energy",
			"rlc"=>"iexec-rlc",
			"rock"=>"rock-token",
			"rrcoin"=>"rrchain",
			"russian-mining-coin"=>"russian-miner-coin",
			"ryo-currency"=>"ryo",
			"safe-trade-coin"=>"safetradecoin",
			"santiment"=>"santiment-network-token",
			"scorum-coins"=>"scorum",
			"scroll"=>"scroll-token",
			"scryinfo"=>"scry-info",
			"seal-network"=>"seal",
			"securecloudcoin"=>"secure-cloud-coin",
			"sentinel"=>"sentinel-group",
			"sharder"=>"sharder-protocol",
			"sharpe-platform-token"=>"sharpe-capital",
			"shield-xsh"=>"shield",
			"shivom"=>"project-shivom",
			"signals-network"=>"signals",
			"six-domain-chain"=>"sixdomainchain",
			"socialcoin-socc"=>"socialcoin",
			"spectre-dividend"=>"spectre-dividend-token",
			"spectre-utility"=>"spectre-utility-token",
			"stealth"=>"stealthcoin",
			"student-coin"=>"bitjob",
			"supernet-unity"=>"supernet",
			"swarm-fund"=>"swarm",
			"target-coin"=>"targetcoin",
			"tgame"=>"truegame",
			"thore-cash"=>"thorecash",
			"thrive-token"=>"thrive",
			"tiesdb"=>"ties-network",
			"tokenstars"=>"tokenstars-team",
			"trackr"=>"crypto-insight",
			"travala"=>"concierge-io",
			"trident"=>"trident-group",
			"truechain"=>"true-chain",
			"trueusd"=>"true-usd",
			"trust"=>"wetrust",
			"ubique-chain-of-things"=>"ucot",
			"ultra-salescoud"=>"ultra-salescloud",
			"ultranote-coin"=>"ultra-note",
			"uniform-fiscal-object"=>"ufocoin",
			"usechain-token"=>"usechain",
			"uttoken"=>"united-traders-token",
			"vector"=>"vectorai",
			"view"=>"viewly",
			"vipstar-coin"=>"vipstarcoin",
			"vivid-coin"=>"vivid",
			"voisecom"=>"voise",
			"vsync-vsx"=>"vsync",
			"wabnetwork"=>"wab-network",
			"wavebase"=>"peoplewave",
			"wetoken"=>"worldwifi",
			"wi-coin"=>"wicoin",
			"win-coin"=>"wincoin",
			"women"=>"womencoin",
			"wys-token"=>"wysker",
			"x-coin"=>"xcoin",
			"x8x-token"=>"x8-project",
			"xinfin-network"=>"xdce-crowd-sale",
			"xovbank"=>"xov",
			"xtrd"=>"xtrade",
			"yolocash"=>"yolo-cash",
			"you-coin"=>"you-chain",
			"yuki"=>"yuki-coin"
			);

			if(array_key_exists($coin_id,$excluded)!=true){
				return false;
			var_dump($coin_id."ok");
			}
			if(array_key_exists($coin_id,$excluded) && isset($excluded[$coin_id])){
				return $excluded[$coin_id];
				var_dump($coin_id."excluded");
			}
	}