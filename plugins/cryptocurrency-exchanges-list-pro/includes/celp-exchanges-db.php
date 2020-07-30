<?php

class CELP_Exchanges
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
		$this->table_name = $wpdb->base_prefix . 'celp_exchanges';
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
			'id' => '%d',
			'ex_id' => '%s',
			'name' => '%s',
			'volume_24h' => '%f',
			'coin_supports' => '%d',
			'trading_pairs'=>'%d',
			'btc_price' =>'%f',
			'btc_volume' =>'%f',
			'extra_data'=>'%s',
			'updated' => '%s',
			'about' =>'%s',
			'exchange_status' => '%s'
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
			'volume_24h' => '',
			'coin_supports' => '',
			'trading_pairs'=>'',
			'btc_price' =>'',
			'btc_volume' =>'',
			'extra_data' =>'',
			'about' =>'',
			'exchange_status' => 'enable',
			'last_updated' => date('Y-m-d H:i:s'),
		);
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

		$sql = "CREATE TABLE " . $this->table_name . " (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		ex_id varchar(200) NOT NULL,
		name varchar(250) NOT NULL,
		volume_24h decimal(24,2),
		fees varchar(5),
		coin_supports bigint(10) NOT NULL,
		trading_pairs bigint(10) NOT NULL,
		btc_price decimal(20,6),
		updated varchar(250) NOT NULL,
		btc_volume decimal(20,6),
		extra_data longtext NOT NULL,
		about longtext,
		exchange_status varchar(20) NOT NULL DEFAULT 'enable',
		last_updated TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
		PRIMARY KEY (id),
		UNIQUE (ex_id)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta($sql);

		update_option($this->table_name . '_db_version', $this->version);
	}

	function celp_ex_insert($ex_data){
		
	return	$this->wp_insert_rows($ex_data,$this->table_name , true ,'ex_id');
	} 

	/**
	 * Retrieve exchanges from the database
	 *
	 * @access  public
	 * @since   1.0
	 * @param   array $args
	 * @param   bool  $count  Return only the total number of results found (optional)
	 */
	public function get_exchanges($args = array(), $count = false)
	{

		global $wpdb;

		$defaults = array(
			'number' => 20,
			'offset' => 0,
			'ex_id' =>'',
			'orderby' => 'volume_24h',
			'order' => 'DESC',
			'exchange_status' => 'enable',
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

		if (empty($where)) {
			$where .= " WHERE";
		} else {
			$where .= " AND";
		}

		$where .= " `exchange_status`= '{$args['exchange_status']}' ";

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

		return $results;

	}

	function get_all_exchanges(){
		GLOBAL $wpdb;
		
		$results = $wpdb->get_results("SELECT ex_id,name FROM {$this->table_name}");

		return $results;
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
function wp_insert_rows($row_arrays = array(), $wp_table_name, $update = false, $primary_key = null) {
	global $wpdb;
	$floatCols=array('btc_price','btc_volume','volume_24h');
	$wp_table_name = esc_sql($wp_table_name);
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