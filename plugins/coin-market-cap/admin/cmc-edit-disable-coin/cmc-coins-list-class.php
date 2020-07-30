<?php
Class CoinsListPage{
    
    function __construct(){
        if(is_admin()){
			
			$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '' ;
			$page = isset($_GET['page']) ? $_GET['page'] : '' ;
			session_start();
			if($post_type != 'cmc' || $page != 'cmc-coins-list') {
				$_SESSION['cmc-coin-search'] = '';
			}

            require_once CMC_PLUGIN_DIR . 'admin/cmc-edit-disable-coin/cmc-display-table.php';
			add_action('admin_menu', array($this, 'cmc_add_menu' ) );
			add_filter('set-screen-option', array( $this, 'cmc_save_screen_options'), 15, 3);
            add_action('wp_ajax_edit_coin_to_list',array($this,'edit_coin_to_list'));
            add_action('wp_ajax_disable_coin_from_mainlist',array($this,'disable_coin_from_mainlist'));	
        }
        add_action( 'admin_enqueue_scripts', array($this,'cmc_coinList_admin_styles'));
    }

    function cmc_coinList_admin_styles(){
        if(admin_url('admin.php?page=cmc-coins-list')){
            wp_register_script( 'cmc-coins-list-script', CMC_URL.'admin/cmc-edit-disable-coin/js/cmc-coins-list-script.js', array('jquery-core'), CMC, true );
            wp_enqueue_script( 'cmc-coins-list-script' );
            wp_register_style( 'cmc-coins-list-style', CMC_URL.'admin/cmc-edit-disable-coin/css/cmc-coins-list-style.css');
            wp_enqueue_style( 'cmc-coins-list-style' );
        }
    }
    
    function cmc_add_menu(){
		//$hook = add_menu_page('Coins List', 'Coins List', 'manage_options', 'cmc-coins-list', array($this,'cmc_coin_list_page'), 'dashicons-chart-area', 20);
		$hook = add_submenu_page( 'edit.php?post_type=cmc', 'Coins List', 'Coins List', 'manage_options', 'cmc-coins-list', array($this,'cmc_coin_list_page') );
		add_action( "load-".$hook, array( $this, 'cmc_add_options' ) ); 		
	}

	function cmc_coin_list_page(){
		$list = new cmc_list_table();
		$list->prepare_items();
		$list->display();
	}

	function cmc_add_options(){

		$option = 'per_page';
 
		$args = array(
			'label' => __('Number of coins per page','cmc'),
			'default' => 10,
			'option' => 'results_per_page'
		);
		
		add_screen_option( $option, $args );
		// create columns field for screen options
		new cmc_list_table;
	}
	
	function cmc_save_screen_options($status, $option, $value) {
		if( $option == "results_per_page" ){
			return $value;
		}
		return $status;
	}
	
    /*-----------------------------------------------|
	|	Check if a custom post exists for as coin    |
	|------------------------------------------------|
	*/
	function coin_post_exists_by_coin_id($coin_id)
	{
		$args_posts = array(
			'post_type' => 'cmc-description',
			'post_status' => array('pending', 'draft', 'publish'),
			'meta_key' => 'cmc_single_settings_des_coin_name',
			'meta_value' => $coin_id,
			'posts_per_page' => 1,
		);
		// The Query
		$query1 = new WP_Query($args_posts);	
		// The Loop
		$post_id = null;
		if ($query1->have_posts()) {
			while ($query1->have_posts()) {
				$query1->the_post();
				$post_id = get_the_ID();
			}
		wp_reset_postdata();
			return $post_id;
		}else{
			return false;
		}
	}
	
     /*--------------------------------------------------|
	 |  		Handle Edit coin action through AJAX	 |	
	 |---------------------------------------------------|
	 */
     function edit_coin_to_list(){
        		
		if ( !isset( $_POST[ 'edit_coin_nonce' ] ) || !wp_verify_nonce( $_POST[ 'edit_coin_nonce' ], 'edit_coin_nonce')){
			wp_die();
		}
		$coin_id = filter_var($_POST['coin_id'], FILTER_SANITIZE_STRING);
		$coin_name = filter_var($_POST['coin_name'], FILTER_SANITIZE_STRING);		
		$post_exists = $this->coin_post_exists_by_coin_id($coin_id);


		if($post_exists){
			$post_id= $post_exists;
		}else{		
            $post_data = array(
                'post_title'	=>	$coin_name,
                'post_type'		=>	'cmc-description',
                'post_status'	=>	'publish',
                'post_author'	=> get_current_user_id()
            );
			$post_id = wp_insert_post( $post_data );
			if (!is_wp_error($post_id)) {				
				$api_desc = cmc_get_coin_desc($coin_id);
				$db_meta_data = cmc_get_coin_meta($coin_id);	
				update_post_meta($post_id, 'cmc_single_settings_des_coin_name', $coin_id);				
				update_post_meta($post_id,'cmc_single_settings_coin_be', $db_meta_data['block_explorer']);
				update_post_meta($post_id,'cmc_single_settings_coin_ow', $db_meta_data['website']);
				update_post_meta($post_id,'cmc_single_settings_coin_wp', $db_meta_data['whitepaper']);
				update_post_meta($post_id,'cmc_single_settings_coin_yt', $db_meta_data['youtube']);
				update_post_meta($post_id,'cmc_single_settings_coin_rd', $db_meta_data['announced']);
				update_post_meta($post_id,'cmc_single_settings_coin_gh', $db_meta_data['github']);
				update_post_meta($post_id,'cmc_single_settings_coin_fb', $db_meta_data['facebook']);
				update_post_meta($post_id,'cmc_single_settings_coin_twt', $db_meta_data['twitter']);
				update_post_meta($post_id,'cmc_single_settings_coin_redt', $db_meta_data['reddit']); 								
				update_post_meta($post_id, 'cmc_single_settings_coin_description_editor', $api_desc);
			}else{
				die( json_encode($post_id) );
			}
	    }
		
		if (!is_wp_error($post_id)) {
			$coin_url=	admin_url("post.php?post=". $post_id ."&action=edit", '/' );			
			$data=array('status'=>'success','url'=>$coin_url);
			echo json_encode($data);
		}else{
			$data = array('status' => 'error', 'log' => $post_id);
			echo json_encode($data);
		} 
		exit();
    }

	/*----------------------------------------------------|
	|			Handle the disable coin action			  |
	|-----------------------------------------------------|
	*/
    function disable_coin_from_mainlist(){
		
		if (!isset( $_POST[ 'disable_coin_nonce' ] ) || !wp_verify_nonce( $_POST[ 'disable_coin_nonce' ], 'disable_coin_nonce')){
			wp_die();
		}

		global $wpdb;
		
		$coin_id = filter_var($_POST['coin_id'], FILTER_SANITIZE_STRING);
		$coin_name = filter_var($_POST['coin_name'], FILTER_SANITIZE_STRING);
		$coin_status = $_POST['btn_action'];
		$table_name = $wpdb->base_prefix.'cmc_coins';
		$execute = $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET coin_status = %s WHERE coin_id = %s", $coin_status, $coin_id ) );
		
	}

}
new CoinsListPage();