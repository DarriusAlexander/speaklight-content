<?php
/**
 * Create response for datatable AJAX request
 */


function get_ajax_data(){

        $start_point    = $_REQUEST['start']!=null?$_REQUEST['start']:0;
        $current_page   = (int)$_REQUEST['draw']!=null?$_REQUEST['draw']:1;
        $fiat_currency = $_REQUEST['currency']!=null ? $_REQUEST['currency'] :'USD';
        $fiat_currency_rate = $_REQUEST['currencyRate']!=null? $_REQUEST['currencyRate'] : 1;
        $total_coins   = -1;
        if( isset($_REQUEST['totalCoins']) && !empty( $_REQUEST['totalCoins']) && is_numeric( $_REQUEST['totalCoins'] ) ){
          $total_coins = $_REQUEST['totalCoins'];
        }
        $fav_coins = ( isset($_REQUEST['coinID'])?$_REQUEST['coinID']:'NA');
        $data_length    = $_REQUEST['length']?$_REQUEST['length']:100;
        
        if( $total_coins!=-1 && $data_length>$total_coins ){
            $data_length  = $total_coins;
        }

        $i=$start_point+1;
        $coins_list=array();
		 
		// if ( false === ( $value = get_transient( 'top_coins' ) ) ) {
        $order_col_name = 'market_cap';
        $order_type ='DESC';
        $cmcDB = new CMC_Coins;
        $cmcMetaDB = new CMC_Coins_Meta;
        $coins_request_count=$data_length+$start_point;
   
        if( $fav_coins == 'NA' ){
          $coindata= $cmcDB->get_coins( array("number"=>$data_length,'offset'=> $start_point,'orderby' => $order_col_name,
          'order' => $order_type
            ));
         }else{
          $coindata= $cmcDB->get_coins( array("coin_id"=>$fav_coins,"number"=>$data_length,'offset'=> $start_point,'orderby' => $order_col_name,
          'order' => $order_type
            ));
        }
          $coin_ids=array();
          if($coindata){
            foreach($coindata as $coin){
                 $coin_ids[]= $coin->coin_id;
            }
        }
    $histo_data = $cmcMetaDB->get_coins_weeky_price(array(
        'coin_id'=>$coin_ids,"number"=>$data_length
        ));
    $coin_histo_arr = array();
    if (!empty($histo_data)) {
        foreach ($histo_data as $coin) {
            $coin_histo_arr[$coin->coin_id] = $coin->weekly_price_data;
        }
    }
		$response = array();
        $coins = array();
        $bitcoin_price = get_transient('cmc_btc_price');
        $coins_list=array();
        if($coindata){
        foreach($coindata as $coin){
                $coin = (array)$coin;
                $coin_id= $coin['coin_id'];
                $coins['logo'] = coin_list_logo($coin['coin_id'], $size = 32);
                $symbol= strtoupper($coin['symbol']);
                $coins['rank'] = $i;
                $coins['last_updated'] = $coin['last_updated'];
                $coins['coin_id'] = $coin['coin_id'];
                $coins['watch_list'] = $coin['coin_id'];
                $coins['symbol'] = $symbol; 
                $coins['name'] = strtoupper($coin['name']);
                $coins['usd_price'] = $coin['price'];
                $coins['usd_market_cap'] = $coin['market_cap'];
                $coins['usd_volume'] = $coin['total_volume'];
                if($fiat_currency=="USD"){
                    $coins['price'] = $coin['price'];
                    $coins['market_cap'] = $coin['market_cap'];
                    $coins['volume'] = $coin['total_volume'];
                     $c_price=$coin['price'];
                }else if ($fiat_currency == "BTC") {
                    $coins['price'] = $coin['price']/ $bitcoin_price;
                    $coins['market_cap'] = $coin['market_cap'] / $bitcoin_price;
                    $coins['volume'] = $coin['total_volume'] / $bitcoin_price;
               }else{
                    $coins['price'] = $coin['price']* $fiat_currency_rate;
                    $coins['market_cap'] = $coin['market_cap'] * $fiat_currency_rate;
                    $coins['volume'] = $coin['total_volume'] * $fiat_currency_rate;
                 }
                $coins['supply'] = $coin['circulating_supply'];
                $coins['percent_change_24h'] = number_format($coin['percent_change_24h'],2,'.','');
                if(isset($coin_histo_arr[$coin_id]) && $coin_histo_arr[$coin_id]!='N/A'){

                    $w_array= unserialize($coin_histo_arr[$coin_id]);
                        if(is_array($w_array)){
                        $w_array[]=$coins['usd_price'];
                        $coins['weekly_chart'] =json_encode($w_array);
                        }else{
                        $coins['weekly_chart'] ='false';
                        }


                 }else{
                  $coins['weekly_chart'] = 'false'; 
                 }
                 $i++;
                $coins_list[]= $coins; 

                if( $total_coins!=-1 && $i > $total_coins ){
                  break;
                }
                
            }
        }
          $coins_list2=array();
       /*
			set_transient( 'top_coins', $coins_list, 60 * MINUTE_IN_SECONDS);
		}else{
			$coins_list=get_transient('top_coins');
		} */
		//$coins_list=get_transient('top_coins');
		
          if( $total_coins == -1 ){
            $response = array("draw"=>$current_page,"recordsTotal"=>CMC_LOAD_COINS,"recordsFiltered"=> 1900,"data"=>$coins_list);
          }else{
            $response = array("draw"=>$current_page,"recordsTotal"=>CMC_LOAD_COINS,"recordsFiltered"=> $total_coins,"data"=>$coins_list);
          }
		echo json_encode( $response );
	exit();
}