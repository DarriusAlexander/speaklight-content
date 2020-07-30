<?php 

	/*
	Crypto Price calculator
	*/
	function cmc_calculator($atts, $content = null){
	 	$atts = shortcode_atts( array(
		'id'  => '',
		), $atts);
	 wp_enqueue_script( 'cmc-select2-js','https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery'), CMC, true );
	 wp_enqueue_style( 'cmc-select2-css','https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',null,CMC);
	 wp_register_script('cmc-calculator', CMC_URL . 'assets/js/cmc-calcuator.js', array( 'jquery','cmc-select2-js'), CMC, true );
	 wp_enqueue_script('crypto-numeral');
	 wp_enqueue_script('cmc-calculator');

		 $cmc_styles='
		/*------------ START CALCULATOR STYLE -----------*/
		.select2 {
			width: 100%!important;
		}
		.cmc_calculator {
			display: inline-block;
			width: 100%;
			border: 1px solid #e7e7e7;
			padding: 10px;
			margin: 10px auto 20px;
			box-shadow: inset 0px 0px 8px 0px #cecece;
		}
		.cmc_calculator_block {
		    display: inline-block;
		    position: relative;
		    width: 32%;
		    margin-right: 1%;
		}
		.cmc_calculator_block span.cal_lbl {
		    font-size: 10px;
			background: #3a3a3a;
			color: #fff;
			padding: 1px 4px;
			font-weight: bold;
			z-index: 99;
			margin-bottom: 0;
			display: inline-block;
		}
		.cmc_calculator input#cmc_amount {
		    background: #e3e3e396;
		    border: 1px solid #dfdfdf;
		    color: #222;
		    font-size: 16px;
		    display: inline-block;
		    width: 100%;
		    padding: 8px;
		    border-radius: 2px;
			line-height:28px;
		}
		.cmc_calculator select#cmc_currencies_list, .cmc_calculator select#cmc_crypto_list, .cmc_calculator .select2-container .selection .select2-selection {
		    border: 1px solid #dfdfdf;
		    color: #222;
		    font-size: 16px;
		    display: inline-block;
		    width: 100%;
		    padding: 8px;
		    border-radius: 2px;
			background: #e3e3e396;
			height:auto;
			line-height:28px;
		}
		.cmc_calculator .select2-container--default .select2-selection--single .select2-selection__arrow {
			top:10px;
		}
		.cmc_calculator h2 {
		    margin: 10px auto 25px;
		    font-size: 24px;
		    padding: 0;
		    font-weight: bold;
		}
		.cmc_calculator h2 .cmc_rs_lbl, .cmc_calculator h2 .cmc_cal_rs, .cmc_calculator h2 div.equalsto {
		    display: inline-block;
		    white-space: nowrap;
		}
		.cmc_calculator h2 div.equalsto {
			margin:0 20px;
			font-size:28px;
		}
		.widget .cmc_calculator_block:first-child {
			width: 100%;
		    margin: 0 0 20px;
		}
		.widget .cmc_calculator_block {
			width: 49%;
		    margin-right: 1%;
		}
		.widget .cmc_calculator h2, .widget .cmc_calculator h2 div.equalsto {
		    font-size: 16px;
		}
		.widget .cmc_calculator h2 div.equalsto {
			margin:0 5px;
		}';


  		wp_add_inline_style( 'cmc-select2-css', $cmc_styles );
		$cmc_titan =TitanFramework::getInstance( 'cmc_single_settings' );
		$single_default_currency =$cmc_titan->getOption('default_currency');
		$single_page_currency =trim(get_query_var('currency'))!=null?trim(get_query_var('currency')):$single_default_currency;
  		$coin_id=(string) trim(get_query_var('coin_id'));
		$fiat_currency=$single_page_currency?$single_page_currency:'USD';
		$coin_list=cmc_coin_list_data();
		$currencies_list=(array)cmc_usd_conversions('all');
		$fiat_c_rate = cmc_usd_conversions($fiat_currency);
		 $output='';
		 $output.='<div class="cmc_calculator">';
		 $output.='<div class="cmc_calculator_block"><span class="cal_lbl">'.__('Enter Amount','cmc').'</span><input id="cmc_amount" value="10" type="number" name="amount" class="cmc_calculate_price"></div>';
		$output.='<div class="cmc_calculator_block"><span class="cal_lbl">'.__('Base Currency','cmc').'</span>
		<select class="cmc_calculate_price" id="cmc_crypto_list">';
			  if(is_array($coin_list)){
			  foreach($coin_list as $id=> $coin){
			  	if(get_query_var('coin_id')!=null && trim(get_query_var('coin_id'))== $id){
			  		$output.='<option selected="selected" value='.$coin['price'].'>'. $coin['name'].'('.$coin['symbol'].')'.'</option>';
			 		 }else{
			 		 $output.='<option value='.$coin['price'].'>'. $coin['name'].'('.$coin['symbol'].')'.'</option>';	
			 		 }
				 }
				}
		 $output.='</select></div>';
		 $output.='<div class="cmc_calculator_block"><span class="cal_lbl">'.__('Convert To','cmc').'</span><select data-default-currency="'.$single_page_currency.'" class="cmc_calculate_price" id="cmc_currencies_list">';

		  $output.='<optgroup label="'.__('Currencies','cmc').'">';
			  if(is_array($currencies_list)){
			  foreach($currencies_list as $name=> $price){
			  	if($name==$single_page_currency)
			  	{
					$output.='<option selected="selected" value='.$price.'>'.$name.'</option>';
			  	}else{
			  		$output.='<option value='.$price.'>'.$name.'</option>';
			  	}
			  
				 }
				}
			$output.='</optgroup><optgroup label="'.__('Crypto Currencies','cmc2').'">';
			if (is_array($coin_list)) {
				foreach ($coin_list as $index => $coin) {
					$output .= '<option value=' . $coin['price'] . '>' . $coin['name'].'('.$coin['symbol'].')' . '</option>';
				}
			}
		$output.=' </optgroup></select></div>';

	 if(isset($coin_list[$coin_id])){
		 $coin_symbol=get_query_var('coin_symbol');
		 $coin_name="10 ". $coin_list[$coin_id]['name'].' ('.$coin_symbol.')';
		$price=($coin_list[$coin_id]['price'] * 10)*$fiat_c_rate;
	  	 $coin_price=format_number($price).$single_page_currency;

		}else{
	$coin_symbol=trim(get_query_var('coin_id'))?get_query_var('coin_id'):"BTC";
		$coin_id=$coin_id ?$coin_id:__("Bitcoin",'cmc');
		$coin_name=sprintf("0 %s (%s)",ucfirst($coin_id),$coin_symbol);
		$coin_price=__('0 '. $single_page_currency,'cmc');
		}

	  $output.='<h2><div class="cmc_rs_lbl">'.$coin_name.'</div>'; 
	  $output.='<div class="equalsto">=</div><div class="cmc_cal_rs">'.$coin_price.'</div></h2>';
		  $output.='</div>';
	return $output;
	}
	