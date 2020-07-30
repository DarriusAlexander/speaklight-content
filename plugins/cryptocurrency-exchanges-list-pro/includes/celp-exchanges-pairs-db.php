<?php

class CELP_Exchanges_Pairs
{

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   1.0
	 */
	public function __construct()
	{

		global $wpdb;

		$this->table_name = $wpdb->base_prefix . 'celp_exchanges_pairs';
		$this->primary_key = 'id';
		$this->version = '1.0';

	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 * @since   1.0
	 */
	public function get_columns()
	{
		return array(
			'ex_id' => '%s',
			'name' => '%s',
			'pair' => '%s',
			'coin_id' => '%s',
			'base_symbol' => '%s',
			'target_symbol' => '%s',
			'price_usd' => '%f',
			'price_target' => '%f',
			'price_btc' => '%f',
			'volume_usd' => '%f',
			'volume_base' => '%f',
			'volume_btc' => '%f',
			'updated' =>'%s',
			'type' =>'%s',
		);
	}
	/**
	 * Get default column values
	 *
	 * @access  public
	 * @since   1.0
	 */
	public function get_column_defaults()
	{
		return array(
			'ex_id' => '',
			'name' => '',
			'volumne_24h' => '',
			'price' =>'',
			'last_updated' => date('Y-m-d H:i:s'),
		);
	}

	/**
	* Remove old exchanges pair data older than the specified duration;
	*/
	public function clear_old_exchanges_pair(){
		GLOBAL $wpdb;
		$query		=	"DELETE FROM $this->table_name WHERE last_updated < NOW() - 172800";		
		return $wpdb->query( $query );
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 * @since   1.0
	 */
	public function create_table()
	{

		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
		ex_id varchar(200) NOT NULL,
		name varchar(250) NOT NULL,
		pair varchar(100) NOT NULL,
		coin_id varchar(100) NOT NULL,
		base_symbol varchar(100) NOT NULL,
		target_symbol varchar(100) NOT NULL,
		coin_name varchar(100) NOT NULL,
		price_usd decimal(20,6),
		price_target decimal(20,6),
		price_btc decimal(20,6),
		volume_usd decimal(20,6),
		volume_base decimal(20,6),
		volume_btc decimal(20,6),
		updated varchar(250) NOT NULL,
		last_updated TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
		type varchar(100) NOT NULL,
		PRIMARY KEY (ex_id,pair)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
		dbDelta($sql);
		update_option($this->table_name . '_db_version', $this->version);
	}

	function celp_ex_insert($ex_data, $truncate=false){
		
	return 	$this->wp_insert_rows($ex_data,$this->table_name , true ,'ex_id', $truncate);
	} 

	/**
	 *  Retrive specific exchange details by exchange id
	 */
	public function get_exchange_coin_pairs( $ex_id ){
		$response = $this->get_pairs( array('ex_id'=>$ex_id,'number'=>'-1','type'=>'on-exchange'), false );
		if( gettype($response) == 'array' ){
			return $data= celpobjectToArray($response);
		}else{
			return false;
		}
	}

	/**
	 *  Retrive specific exchange details by coin id
	 */
	public function get_exchange_coin_price( $coin_id ){
		$response = $this->get_pairs( array('coin_id'=>$coin_id,'number'=>'-1','orderby'=>'volume_usd','order'=>'DESC'), false );

		if( gettype($response) == 'array' ){
			return $data= celpobjectToArray($response);
		}else{
			return false;
		}

	}

	/**
	 * Retrieve exchanges from the database
	 *
	 * @access  public
	 * @since   1.0
	 * @param   array $args
	 * @param   bool  $count  Return only the total number of results found (optional)
	 */
	public function get_pairs($args = array(), $count = false)
	{

		global $wpdb;

		$defaults = array(
			'number' => 20,
			'offset' => 0,
			'ex_id' =>'',
			'orderby' => 'volume_usd',
			'order' => 'DESC',
			'name' => '',
			'coin_id' => '',
			'type'=>'',
		);

		$args = wp_parse_args($args, $defaults);

		if ($args['number'] < 1) {
			$args['number'] = 999999999999;
		}

		$where = '';

	// specific referrals
		if (!empty($args['id'])) {

			if (is_array($args['id'])) {
				$order_ids = implode(',', $args['id']);
			} else {
				$order_ids = intval($args['id']);
			}

			$where .= "WHERE `id` IN( {$order_ids} ) ";

		}

		if (!empty($args['coin_id'])) {

			if (empty($where)) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}

			if (is_array($args['coin_id'])) {
				$where .= " `coin_id` IN('" . implode("','", $args['coin_id']) . "') ";
			} else {
				$where .= " `coin_id` = '" . $args['coin_id'] . "' ";
			}

		}

		if (!empty($args['name'])) {

			if (empty($where)) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}

			if (is_array($args['name'])) {
				$where .= " `name` IN('" . implode("','", $args['name']) . "') ";
			} else {
				$where .= " `name` = '" . strtoupper($args['name']) . "' ";
			}

		}
		if (!empty($args['type'])) {
			if (empty($where)) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}
			if (is_array($args['type'])) {
				$where .= " `type` IN('" . implode("','", $args['type']) . "') ";
			} else {
				$where .= " `type` = '" . strtoupper($args['type']) . "' ";
			}
		}

		if (!empty($args['ex_id'])) {

			if (empty($where)) {
				$where .= " WHERE";
			} else {
				$where .= " AND";
			}

			if (is_array($args['ex_id'])) {
				$where .= " `ex_id` IN('" . implode("','", $args['ex_id']) . "') ";
			} else {
				$where .= " `ex_id` = '" . $args['ex_id'] . "' ";
			}

		}


		$args['orderby'] = !array_key_exists($args['orderby'], $this->get_columns()) ? $this->primary_key : $args['orderby'];

		if ('total' === $args['orderby']) {
			$args['orderby'] = 'total+0';
		} else if ('subtotal' === $args['orderby']) {
			$args['orderby'] = 'subtotal+0';
		}

		$cache_key = (true === $count) ? md5('celp_ex_count' . serialize($args)) : md5('celp_ex_count' . serialize($args));

		$results = wp_cache_get($cache_key, 'exchanges');

		if (false === $results) {

			if (true === $count) {

				$results = absint($wpdb->get_var("SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};"));

			} else {

				$results = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
						absint($args['offset']),
						absint($args['number'])
					)
				);

			}

			wp_cache_set($cache_key, $results, 'exchanges', 3600);

		}

		if( $results != null ){
			return $results;
		}else{
			return 'false';
		}

	}

	/**
	 *  A method for inserting multiple rows into the specified table
	 *  Updated to include the ability to Update existing rows by primary key
	 *  
	 *  Usage Example for insert: 
	 *
	 *  $insert_arrays = array();
	 *  foreach($assets as $asset) {
	 *  $time = current_time( 'mysql' );
	 *  $insert_arrays[] = array(
	 *  'type' => "multiple_row_insert",
	 *  'status' => 1,
	 *  'name'=>$asset,
	 *  'added_date' => $time,
	 *  'last_update' => $time);
	 *
	 *  }
	 *
	 *
	 *  wp_insert_rows($insert_arrays, $wpdb->tablename);
	 *
	 *  Usage Example for update:
	 *
	 *  wp_insert_rows($insert_arrays, $wpdb->tablename, true, "primary_column");
	 *
	 *
	 * @param array $row_arrays
	 * @param string $wp_table_name
	 * @param boolean $update
	 * @param string $primary_key
	 * @return false|int
	 *
	 */
	function wp_insert_rows($row_arrays = array(), $wp_table_name, $update = true, $primary_key = null, $truncate) {
		
		global $wpdb;
		$floatCols=array('price_usd','price_target','price_btc','volume_usd','volume_base');
		$wp_table_name = esc_sql($wp_table_name);

		if( $truncate == true ){
			$wpdb->query("TRUNCATE TABLE ".$wp_table_name);
		}

		// Setup arrays for Actual Values, and Placeholders
		$values        = array();
		$place_holders = array();
		$query         = "";
		$query_columns = "";
	
		$query .= "INSERT INTO `{$wp_table_name}` (";
		foreach ($row_arrays as $count => $row_array) {
			foreach ($row_array as $key => $value) {
				if ($count == 0) {
					if ($query_columns) {
						$query_columns .= ", " . $key . "";
					} else {
						$query_columns .= "" . $key . "";
					}
				}
				
				$values[] = $value;
				$symbol = "%s";
				if (is_numeric($value)) {
					if (is_float($value)) {
						$symbol = "%f";
					} else {
						$symbol = "%d";
					}
				}
				if(in_array( $key,$floatCols)){
					$symbol = "%f";
				}
				if (isset($place_holders[$count])) {
					$place_holders[$count] .= ", '$symbol'";
				} else {
					$place_holders[$count] = "( '$symbol'";
				}
			}
			// mind closing the GAP
			$place_holders[$count] .= ")";
		}
		
		$query .= " $query_columns ) VALUES ";
		
		$query .= implode(', ', $place_holders);
		
		if ($update) {
			$update = " ON DUPLICATE KEY UPDATE $primary_key=VALUES( $primary_key ),";
			$cnt    = 0;
			foreach ($row_arrays[0] as $key => $value) {
				if ($cnt == 0) {
					$update .= "$key=VALUES($key)";
					$cnt = 1;
				} else {
					$update .= ", $key=VALUES($key)";
				}
			}
			$query .= $update;
		}
		
		$sql = $wpdb->prepare($query, $values);
		
		if ($wpdb->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
}