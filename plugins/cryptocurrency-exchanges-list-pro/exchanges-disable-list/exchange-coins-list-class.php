<?php
Class ExchangeListPage{
    
    function __construct(){
        if(is_admin()){
			
			$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '' ;
			$page = isset($_GET['page']) ? $_GET['page'] : '' ;
			if( !isset( $_SESSION ) ){
				session_start();
			}
			if($post_type != 'celp' || $page != 'celp-ex-list') {
				$_SESSION['celp-ex-search'] = '';
			}

            require_once CELP_PATH . 'exchanges-disable-list/exchange-display-table.php';
			add_action('admin_menu', array($this, 'celp_add_menu' ) );
			add_filter('set-screen-option', array( $this, 'celp_save_screen_options'), 15, 3);
            add_action('wp_ajax_celp_edit_ex_to_list',array($this,'celp_edit_ex_to_list'));
            add_action('wp_ajax_celp_disable_ex_from_mainlist',array($this,'celp_disable_ex_from_mainlist'));	
		}
		// run at admin dashboard only
        add_action( 'admin_enqueue_scripts', array($this,'celp_coinList_admin_styles'));
    }

    function celp_coinList_admin_styles(){

		$menu = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$sub_menu = isset( $_GET['page'] ) ? $_GET['page'] : '';

        if( $menu == "celp" && $sub_menu == "celp-ex-list" ){
            wp_register_script( 'celp-ex-list-script', CELP_URL . 'exchanges-disable-list/js/exchange-list-script.js', array('jquery-core'), CELP_VERSION, true );
            wp_enqueue_script( 'celp-ex-list-script' );
            wp_register_style( 'celp-ex-list-style', CELP_URL . 'exchanges-disable-list/css/exchange-list-style.css');
            wp_enqueue_style( 'celp-ex-list-style' );
        }
    }
    
    function celp_add_menu(){
		$hook = add_submenu_page( 'edit.php?post_type=celp', 'Exchanges List', 'Exchanges List', 'manage_options', 'celp-ex-list', array($this,'celp_ex_list_page'), 2 );
		add_action( "load-".$hook, array( $this, 'celp_add_options' ) ); 		
	}

	function celp_ex_list_page(){
		$list = new celp_list_table();
		$list->prepare_items();
		$list->display();
	}

	function celp_add_options(){

		$option = 'per_page';
 
		$args = array(
			'label' => __('Number of exchanges per page','celp'),
			'default' => 10,
			'option' => 'results_per_page'
		);
		
		add_screen_option( $option, $args );
		// create columns field for screen options
		new celp_list_table;
	}
	
	function celp_save_screen_options($status, $option, $value) {
		if( $option == "results_per_page" ){
			return $value;
		}
		return $status;
	}
	
    /*---------------------------------------------------|
	|	Check if a custom post exists for as exchange    |
	|----------------------------------------------------|
	*/
	function celp_post_exists_by_ex_id($ex_id)
	{
		$args_posts = array(
			'post_type' => 'celp',
			'post_status' => array('pending', 'draft', 'publish'),
			'meta_key' => 'custom_ex_id',
			'meta_value' => $ex_id,
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
	
     /*------------------------------------------------------|
	 |  		Handle Edit exchange action through AJAX	 |	
	 |-------------------------------------------------------|
	 */
     function celp_edit_ex_to_list(){
        		
		if ( !isset( $_POST[ 'edit_exec_nonce' ] ) || !wp_verify_nonce( $_POST[ 'edit_exec_nonce' ], 'celp_edit_exc_nonce')){
			wp_die();
		}
		$id = filter_var($_POST['ex_id'], FILTER_SANITIZE_STRING);
		$name = filter_var($_POST['ex_name'], FILTER_SANITIZE_STRING);		
		$post_exists = $this->celp_post_exists_by_ex_id($id);

		if($post_exists){
			$post_id= $post_exists;
		}else{		
            $post_data = array(
                'post_title'	=>	$name,
                'post_type'		=>	'celp',
                'post_status'	=>	'publish',
                'post_author'	=> get_current_user_id()
            );
			$post_id = wp_insert_post( $post_data );
			if (!is_wp_error($post_id)) {
				$ex_data = celp_get_exchange_by_id($id);
				
				error_log( json_encode( $ex_data ) );

				if( isset( $sex_data->ex_id ) ){
					update_post_meta($post_id,'custom_ex_id', $ex_data->ex_id );
					if( isset( $ex_data->about ) && !empty( $ex_data->about ) ){
						update_post_meta($post_id,'custom_description', $ex_data->about );
					}
				}else if( isset( $ex_data[0] ) && isset( $ex_data[0]->ex_id )  ){
					update_post_meta($post_id,'custom_ex_id', $ex_data[0]->ex_id );
					if( isset( $ex_data[0] ) && !empty( $ex_data[0]->about ) ){
						update_post_meta($post_id,'custom_description', $ex_data[0]->about );
					}
				}else if( isset( $ex_data['ex_id']) ){
					update_post_meta($post_id,'custom_ex_id', $ex_data['ex_id']);					
					if( isset( $ex_data['about'] ) && !empty( $ex_data['about'] ) ){
						update_post_meta($post_id,'custom_description', $ex_data['about'] );
					}
				}


			}else{
				die( json_encode($post_id) );
			}
	    }
		
		if (!is_wp_error($post_id)) {
			$ex_url=	admin_url("post.php?post=". $post_id ."&action=edit", '/' );			
			$data=array('status'=>'success','url'=>$ex_url);
			echo json_encode($data);
		}else{
			$data = array('status' => 'error', 'log' => $post_id);
			echo json_encode($data);
		} 
		exit();
    }

	/*--------------------------------------------------------|
	|			Handle the disable exchange action			  |
	|---------------------------------------------------------|
	*/
    function celp_disable_ex_from_mainlist(){
		
		if (!isset( $_POST[ 'celp_disable_ex_nonce' ] ) || !wp_verify_nonce( $_POST[ 'celp_disable_ex_nonce' ], 'celp_disable_ex_nonce')){
			die( json_encode( array('response'=>'401','message'=>'Nounce can not be verified!') ) );
		}

		global $wpdb;
		
		$ex_id = filter_var($_POST['ex_id'], FILTER_SANITIZE_STRING);
		$ex_status = $_POST['btn_action'];
		$table_name = $wpdb->base_prefix.'celp_exchanges';
		$execute = $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET exchange_status = %s WHERE ex_id = %s", $ex_status, $ex_id ) );

	}

}
new ExchangeListPage();