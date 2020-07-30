<?php

	/**
	 * Register scripts and styles
	 */
	 //add cdn and change js file functions
	  function celp_register_scripts() {
		if ( ! is_admin() ) {

			if( ! wp_script_is( 'jquery', 'done' ) ){
				wp_enqueue_script( 'jquery' );
			}
			wp_register_style( 'celp-styles',CELP_URL.'assets/css/celp-styles.min.css', null, CELP_VERSION );	
			wp_register_style( 'cmc-icons',CELP_URL.'assets/css/cmc-icons.min.css', null, CELP_VERSION );
			wp_register_style( 'cmc-bootstrap',CELP_URL.'assets/css/bootstrap.min.css', null, CELP_VERSION );
		
			wp_register_script( 'cmc-datatables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', null, CELP_VERSION );
			wp_register_script('numeraljs',CELP_URL.'assets/js/numeral.min.js', array('jquery'), CELP_VERSION ,false);
			wp_register_script( 'celp-dt-absolute','https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js',  array('jquery','cmc-datatables'), CELP_VERSION );
			wp_register_script('cmc-custom-fixed-col', CELP_URL . 'assets/js/tableHeadFixer.js', array('jquery', 'cmc-datatables'), CELP_VERSION, true);
			wp_register_script( 'celp-ex-list', CELP_URL . 'assets/js/celp-ex-list.min.js', array('jquery','cmc-datatables'), CELP_VERSION, false );
			wp_register_script( 'celp-ex-pairs', CELP_URL . 'assets/js/celp-ex-pairs.min.js', array('jquery','cmc-datatables'), CELP_VERSION, false );
			wp_register_script( 'celp-coin-ex-list', CELP_URL . 'assets/js/celp-coin-ex-list.min.js', array('jquery','cmc-datatables'), CELP_VERSION, false );
		
		}	
	}

	function celp_cleanOldData(){
		$cache_name = 'celp-cache-cleaner';
		$cache		= get_transient( $cache_name );
		if( $cache === false ){
			$exchanges_pair = new CELP_Exchanges_Pairs();
			$exchanges_pair->clear_old_exchanges_pair();
			set_transient( $cache_name , date('Y-m-d H:i:s'), 3 * DAY_IN_SECONDS );
		}
	}

function celp_load_assets($page=''){
	// Loading only required css and js files
	//loading assets

	wp_enqueue_style('celp-styles');
	wp_enqueue_style('cmc-icons');
	wp_enqueue_style('cmc-bootstrap');
	
	wp_enqueue_script( 'cmc-datatables');
	wp_enqueue_script('cmc-custom-fixed-col');
	wp_enqueue_script('numeraljs');

	if($page=="coin_single"){
		wp_enqueue_script('celp-coin-ex-list');
		wp_enqueue_script('celp-ex-pairs');
		wp_localize_script(
			'celp-coin-ex-list',
			'ajax_object',
			array('ajax_url' => admin_url('admin-ajax.php'))
		);
	}else if($page=="ex_single"){
		wp_enqueue_script('celp-ex-pairs');
		wp_localize_script(
			'celp-ex-pairs',
			'ajax_object',
			array('ajax_url' => admin_url('admin-ajax.php'))
		);
	}else{
		wp_enqueue_script('celp-dt-absolute');
		wp_enqueue_script('celp-ex-list');
		
		wp_localize_script(
			'celp-ex-list',
			'ajax_object',
			array('ajax_url' => admin_url('admin-ajax.php'))
		);
	}
}


function celp_save_ex_data()
{
	$celpEx = new CELP_Exchanges;
	$api_url ="https://api.coinexchangeprice.com/v1/exchanges/info";
	$request = wp_remote_get($api_url, array('timeout' =>120));
	if (is_wp_error($request)) {
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body($request);
	$ex_data = json_decode($body);
	if ($ex_data ) {
	$ex_list = celpobjectToArray($ex_data);
	$all_ex_data=array();
	$time = current_time( 'mysql' );
	$save_it=array();
	if(is_array($ex_list)){
		foreach($ex_list as $index=> $exchange){
			$exchange = celpobjectToArray($exchange);
		$save_it['ex_id']=$exchange['exchange_id'];
		$save_it['name']=$exchange['name'];
		$save_it['volume_24h']=$exchange['volume_usd'];
		$save_it['fees'] = $exchange['fees'];
		$save_it['coin_supports']=$exchange['currency_support'];
		$save_it['trading_pairs']=$exchange['trading_pairs'];
		$save_it['btc_price']=$exchange['btc_price'];
		$save_it['btc_volume']=$exchange['volume_btc'];
		$save_it['updated']= time();
		$save_it['extra_data']=json_encode(array('twitter'=>$exchange['twitter'],
									'website'=>$exchange['website'],
									'telegram'=>$exchange['telegram'],
									'blog'=>$exchange['blog'],
									'alexa_rank'=>$exchange['alexa_rank'],
									'country_rank'=>$exchange['country_rank'],
									'top_country'=>$exchange['top_country']
										), JSON_UNESCAPED_SLASHES );
		$save_it['about']=$exchange['description'];
		$all_ex_data[]=$save_it;
		}
		if(is_array($all_ex_data) && count($all_ex_data)>0){
			return	$rs=$celpEx->celp_ex_insert($all_ex_data);
			}
	}
	
   
	}
}

function celp_save_ex_pairs_data( $ex_id )
{
	$celpExPairs = new CELP_Exchanges_Pairs;
	$api_url ="https://api.coinexchangeprice.com/v1/markets/".$ex_id."/all";
	$request = wp_remote_get($api_url, array('timeout' =>120));
	if (is_wp_error($request)) {
		return false; // Bail early
	}

	$body = wp_remote_retrieve_body($request);
	$ex_data = json_decode($body);	
if ($ex_data) {
	$ex_data = celpobjectToArray($ex_data->$ex_id);
	$save_it['ex_id']=$ex_data['exchange_id'];
	$save_it['name']=$ex_data['exchange_name'];
	$all_ex_data=array();
	if(is_array($ex_data['currency_pairs'])){
foreach($ex_data['currency_pairs'] as $index=> $pair){
			$save_it['coin_id']=$pair['coin_id'];
			$save_it['base_symbol']=$pair['base_symbol'];
			$save_it['target_symbol']=$pair['target_symbol'];
			$save_it['coin_name']=$pair['coin_name'];
			$save_it['pair']=$pair['pair'];
			$save_it['price_usd']=$pair['price_usd'];
			$save_it['price_target']=$pair['price_target'];
			$save_it['price_btc']=$pair['price_btc'];
			$save_it['volume_usd']=$pair['volume_usd'];
			$save_it['volume_base']=$pair['volume_base'];
			$save_it['volume_btc']=$pair['volume_btc'];
			$save_it['updated']=$pair['timestamp'];
			$save_it['type']='on-exchange';
			$all_ex_data[]=$save_it;	
			}
			if(count($all_ex_data)>0){
			return	$rs=$celpExPairs->celp_ex_insert($all_ex_data);
			}
 		}
	}else{
		return false;
	}

 }

 function celp_save_coins_ex_pairs($coin_id )
 {
	 $celpExPairs = new CELP_Exchanges_Pairs;
	 $api_url ="https://api.coinexchangeprice.com/v1/markets/all/".$coin_id;
	 $request = wp_remote_get($api_url, array('timeout' =>120));
	 if (is_wp_error($request)) {
		 return false; // Bail early
	 }
	 $body = wp_remote_retrieve_body($request);
	 $ex_data = json_decode($body);
	 if ($ex_data) {
		$all_ex_data=array();
	 $ex_data = celpobjectToArray($ex_data);
	 foreach($ex_data as $ex_){
		 $save_it['ex_id']=$ex_['exchange_id'];
		 $save_it['name']=$ex_['exchange_name'];
			 foreach($ex_['currency_pairs'] as $index=> $exchange){
				 $save_it['pair']=$exchange['pair'];
				 $save_it['coin_id']=$exchange['coin_id'];
				 $save_it['base_symbol']=$exchange['base_symbol'];
				 $save_it['target_symbol']=$exchange['target_symbol'];
				 $save_it['coin_name']=$exchange['coin_name'];
				 $save_it['price_usd']=$exchange['price_usd'];
				 $save_it['price_target']=$exchange['price_target'];
				 $save_it['price_btc']=$exchange['price_btc'];
				 $save_it['volume_usd']=$exchange['volume_usd'];
				 $save_it['volume_base']=$exchange['volume_base'];
				 $save_it['volume_btc']=$exchange['volume_btc'];
				 $save_it['updated']=$exchange['timestamp'];
				 $save_it['type']='on-coin';
				 $all_ex_data[]=$save_it;	
			 }
	 }
		if(count($all_ex_data)>0){
		return $rs=$celpExPairs->celp_ex_insert($all_ex_data,false);
		}
	 }
  }
	function celp_get_all_exchanges(){
		$db_data = new CELP_Exchanges();
		$resluts = $db_data->get_all_exchanges() ;
		$arr = array();
		if( gettype($resluts) == 'array' ){
			foreach( $resluts as $key=>$index){
				$arr[] = $index;
			}
			return  $arr ;
		}else{
			return false;
		}
	}

	/**
	* Fetching Exchange id from coin market cap to display exchange list in setting panel
	*/    
        function celp_get_exchange_id_api_data(){
            $e_list=array();
            $e_list_api_response= celpobjecttoarray(celp_get_all_exchanges());
			//$e_list_api_response==array();
			
        if(!empty($e_list_api_response )&& is_array($e_list_api_response) ) {
                foreach( $e_list_api_response as $e_api_response=>$index ) {
                $e_list[$index['ex_id'] ] = $index['name'];

                }
                return $e_list;
             }else{
             	 return false;
             }
        }


// converting object to array
function celpobjectToArray($d) {
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

	function celp_format_number($number)
	{
	   $decimalplaces = 2;
	   $decimalcharacter = '.';
	   $thousandseparater = ',';
	   if($number< 0.50){
		  $decimalplaces =6;
		}
	   return number_format($number,$decimalplaces,$decimalcharacter,$thousandseparater);
	}

// get affilite links
function get_custom_content($ex_id){
		
		$args = array(
	    'post_type'  => 'celp',
	    'meta_query' => array(
	        array(
	            'key'   => 'custom_ex_id',
	            'value' =>$ex_id,
	        )
	    ));

		$content=array();
		$postslist = get_posts( $args );
		if ( $postslist ) {
	    foreach ( $postslist as $post ){
	        setup_postdata( $post );
			 $post_id=$post->ID;
			 $formatted_content= celp_get_wysiwyg_output('custom_description', $post_id);
	         $content['desc']= $formatted_content;
	         $content['affiliate_link']= get_post_meta($post_id,'affiliate_link',true);
	      }  

	      return $content;
	    }else{
	    	return false;
	    } 
 
	}

	// get all custom affilate links
	function get_all_affilate_links(){
		$args = array(
		'post_type'  => 'celp',
		'numberposts' => '-1'
	  	);
		$content=array();
		$postslist = get_posts( $args );
		if ( $postslist ) {
	    foreach ( $postslist as $post ){
	        setup_postdata( $post );
			 $post_id=$post->ID;
			 $ex_id= get_post_meta($post_id,'custom_ex_id',true);
	         $content[$ex_id]['affiliate_link']= get_post_meta($post_id,'affiliate_link',true);
	      }  
	      return $content;
	    }else{
	    	return false;
	    } 
	}


	function celp_get_wysiwyg_output($meta_key, $post_id = 0)
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

	/* USD conversions */

	function celp_usd_conversions($currency){
		$cmc_conversions= get_transient('cmc_usd_conversions');
		if(!empty($cmc_conversions) && $cmc_conversions!=null){
			return $cmc_conversions;
		} else{
		$conversions= get_transient('celp_usd_conversions');	
		if( empty($conversions) || $conversions==="" ) {
		   	 	$request = wp_remote_get("https://api-beta.coinexchangeprice.com/v1/exchange-rates");
		  	if( is_wp_error( $request ) ) {
					return false;
				}
				$currency_ids = array("USD","AUD","BRL","CAD","CZK","DKK", "EUR","HKD","HUF","ILS","INR" ,"JPY" ,"MYR","MXN", "NOK","NZD","PHP" ,"PLN","GBP" ,"SEK","CHF","TWD","THB" ,"TRY","CNY","KRW","RUB", "SGD","CLP", "IDR","PKR", "ZAR" );
				$body = wp_remote_retrieve_body( $request );
				$conversion_data= json_decode( $body );
			if(isset($conversion_data->rates)){
				$conversion_data=(array)$conversion_data->rates;
				if(is_array($conversion_data) && count($conversion_data)>0) {
					foreach($conversion_data as $key=> $currency_price){
							if(in_array($key,$currency_ids)){
								$conversions[$key]=$currency_price;
							}
					}	
				uksort($conversions, function($key1, $key2) use ($currency_ids) {
				    return (array_search($key1, $currency_ids) > array_search($key2, $currency_ids));
				});
				set_transient('celp_usd_conversions',$conversions, 2* HOUR_IN_SECONDS);
				}
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
	}


// currencies symbol
	function celp_fiat_cur_symbol($name){
		 $cc = strtoupper($name);
		    $currency = array(
		    "USD" => "&#36;" , //U.S. Dollar
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


// date formating
function get_timeago( $ptime )
{
	$estimate_time = time() - $ptime;

	if( $estimate_time < 1 )
	{
	return __('less than 1 second ago', 'celp');
	}

	$condition = array( 
	12 * 30 * 24 * 60 * 60  =>__('year ','celp'),
	24 * 60 * 60  =>__('day', 'celp'),
		60 * 60 + 60 * 60 => __('hours ago', 'celp'),
		60 * 60 => __('hour ago', 'celp'),
		60 + 60 => __('minutes ago', 'celp'),
		60 => __('minute ago', 'celp'),
		1 + 1 => __('seconds ago', 'celp'),
		1 => __('second ago', 'celp')

	);

	foreach( $condition as $secs => $str )
	{
	$d = $estimate_time / $secs;
	if( $d >= 1 )
	{
	$r = round( $d );
	return $r . ' ' . $str;
	}
	}
}

function celp_selected_currency(){
	$currency=array();
	if (celp_get_option('fiat_currency')) {
		$selected_currency = celp_get_option('fiat_currency');
		$all_prices = celp_usd_conversions('all');
		$currency_price = $all_prices[$selected_currency];
	} else {
		$selected_currency = "USD";
		$all_prices = celp_usd_conversions('all');
		$currency_price = $all_prices[$selected_currency];
	}

	$currency['c_name']= $selected_currency;
	$currency['c_price'] = $currency_price;
	return $currency;
}

/*--------------------------------------------------------------|
|  																|
|  Find exchange by id											|
|   check for both 'enable' and 'disable' exchanges 			|
|---------------------------------------------------------------|
*/
function celp_get_exchange_by_id( $ex_id =null ){
	$DB = new CELP_Exchanges();
	$exchange = $DB->get_exchanges( array( 'ex_id'=>$ex_id, 'exchange_status'=>'enable' ) );
	if( $exchange != null  ){
		return $exchange;
	}else{
		$exchange = $DB->get_exchanges( array( 'ex_id'=>$ex_id, 'exchange_status'=>'disable' ) );
		if( $exchange != null ){
			// return the exchange as a single array
			return $exchange;
		}
	}
}


/*-----------------------------------------------------------------------|
|    Fetch all custom exchange description created at admin dashboard	 |
|------------------------------------------------------------------------|
*/
function celp_fetch_custom_exchanges(){

		$custom_ex = array(
			'post_type'=>'celp',
			'posts_per_page'=>'-1'
		);
		$exists = new WP_Query( $custom_ex );
		$already_exists = array();
		while( $exists->have_posts() ){
			$exists->the_post();
			$ex_id =  get_post_meta( get_the_ID(), 'custom_ex_id', true );
			$already_exists[] = $ex_id;
		}
		wp_reset_postdata();
		return $already_exists;
}