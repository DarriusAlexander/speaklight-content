<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
class Tbp_Themes{

	public static $post_type = 'tbp_theme';
	
	private static $authorLink='https://themify.me';
	
	private static $author='https://themify.me';
	
	private static $defaults=array();
	
	private static $api_base = 'https://themify.me/demo/themes/builder-pro-themes/wp-json/wp/v2/tbp_theme';

	public function __construct() {
		$plugin = Tbp::get_instance();
		self::$author = $plugin->get_plugin_name();
		add_action( 'wp_ajax_'.self::$post_type.'_saving', array( $this, 'save_form' ) );
		add_action( 'wp_ajax_'.self::$post_type.'_get_item', array( $this, 'get_item_data' ) );
		add_action( 'admin_init', array( $this, 'actions' ),15 );
		add_action( 'wp_ajax_'.self::$post_type.'_plupload', array( $this, 'import_theme_action' ) );
		add_action( 'delete_post', array( $this, 'delete_associated_templates' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_fields' ) );
		
		self::$defaults = array(
			'tbp_theme_name'        => __('New Theme', 'themify'),
			'tbp_theme_description' => '',
			'tbp_theme_version'     => '1.0.0',
			'tbp_theme_screenshot'  => '',
			'tbp_theme_screenshot_id' => '',
			'import' => ''
		);
		add_action( 'admin_footer', array( $this, 'enqueue_scripts' ) );
		add_filter( 'tbp_theme_export_templates_data', array( 'Tbp_Templates', 'filter_export_template_data' ), 10, 2);
	}
	
	public function get_options(){
	    $args = array(
		    array(
			'id'=>'tbp_theme_name',
			'label' => __('Theme Name', 'themify'),
			'type'=>'text',
			'control'=>false
		    )
		);
	    if(current_user_can('upload_files') ){
		$max_upload_size = (int) wp_max_upload_size() / ( 1024 * 1024 );
		$args[]=array(
			'id'=>'tbp_theme_screenshot',
			'label' => __('Thumbnail', 'themify'),
			'type'=>'tbp_image',
			'description'=>sprintf( __( 'Maximum upload file size: %d MB.', 'themify' ), $max_upload_size )
		    );
	    }
	    return apply_filters( 'tbp_theme_fields',$args);
	}

	public static function prepare_themes_for_js( $themes = null ) {
		$instance = TBP::get_instance();
		$current_theme = $instance->active_theme->post_name;

		/**
		 * Filter theme data before it is prepared for JavaScript.
		 *
		 * Passing a non-empty array will result in prepare_themes_for_js() returning
		 * early with that value instead.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $prepared_themes An associative array of theme data. Default empty array.
		 * @param null|array $themes          An array of tbp_theme objects to prepare, if any.
		 * @param string     $current_theme   The current theme slug.
		 */
		$prepared_themes = (array) apply_filters( 'pre_prepare_tbp_themes_for_js', array(), $themes, $current_theme );

		if ( ! empty( $prepared_themes ) ) {
			return $prepared_themes;
		}

		// Make sure the current theme is listed first.
		if ( '' !== $current_theme ){
			$prepared_themes[ $current_theme ] = array();
		}
		if ( null === $themes ) {
			$args = array(
				'post_type' => self::$post_type,
				'posts_per_page' => -1,
				'no_found_rows'=>true,
				'ignore_sticky_posts'=>true,
				'order' => 'DESC'
			);
			$query = new WP_Query( $args );
			$themes = $query->get_posts();
		}

		$updates = array();

		$url = menu_page_url(self::$post_type,false);
		$actions = array('activate','deactivate','export','delete');
		foreach ( $themes as $theme ) {
			$slug = $theme->post_name;
			$metadata = wp_parse_args( get_post_meta( $theme->ID, 'theme_info', true ), self::$defaults );
			$prepared_themes[ $slug ] = array(
				'id'           => $slug,
				'theme_id'     => $theme->ID,
				'name'         => $theme->post_title,
				'screenshot'   => array( get_the_post_thumbnail_url( $theme->ID ) ), // @todo multiple
				'description'  => $metadata['tbp_theme_description'],
				'author'       => self::$author,
				'authorAndUri' => sprintf( '<a href="%s">%s</a>', self::$authorLink, self::$author ),
				'version'      => $metadata['tbp_theme_version'],
				'tags'         => '',
				'parent'       => false,
				'active'       => $slug === $current_theme,
				'hasUpdate'    => isset( $updates[ $slug ] ),
				'update'       => false
			);
			$item_actions = array();
			foreach($actions as $act){
			    $item_actions[$act] = wp_nonce_url(add_query_arg(array('action'=>$act,'p'=>$theme->ID),$url), self::$post_type.'_nonce' );
			}
			$prepared_themes[ $slug ]['actions'] = $item_actions;
		}

		/**
		 * Filter the themes prepared for JavaScript.
		 *
		 * Could be useful for changing the order, which is by name by default.
		 *
		 * @since 1.0.0
		 *
		 * @param array $prepared_themes Array of themes.
		 */
		$prepared_themes = array_values(apply_filters( 'tbp_prepare_themes_for_js', $prepared_themes ));
		return array_filter( $prepared_themes );
	}

	public static function  render_page() {
	    include_once TBP_DIR . 'admin/partials/tbp-admin-theme-page.php';
	}


	/**
	 * Save form post data via Hooks
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $post_data 
	 */
	public function save_form( $post_data ) {
	    if(!empty($_POST['type']) && $_POST['type']===self::$post_type){
		check_ajax_referer('tb_load_nonce', 'tb_load_nonce');
		$resp = array();
		$post_data = $_POST['data'];
		$post_data = wp_parse_args( $post_data, self::$defaults );
		$post_status = !empty($post_data['is_draft'])?'draft':'publish';
		$isNew = empty($_POST['id']);
		$id = $isNew===false?(int)$_POST['id']:null;
		$args = array(
		    'post_title'  => sanitize_text_field( $post_data['tbp_theme_name'] ),
		    'post_type'   => self::$post_type,
		    'menu_order'  => !empty($post_data['menu_order'])?$post_data['menu_order']:0
		);
		if($id){
		    $args['ID']=$id;
		    $args['post_status'] = 'publish';
		    unset($args['post_type']);
		    wp_update_post( $args );
		}
		else{
		    $args['post_status'] = $post_status;
		    $args['post_name'] = str_replace('-', '_', sanitize_title( $args['post_title'] ) );
		    $id = wp_insert_post( $args );
		    if(! is_wp_error( $id )){
			if (isset($post_data['import']) && 'blank' !== $post_data['import'] && '' !== $post_data['import'] ) {
			    
			}
		    }
		    else{
			$id=null;
		    }
		}

		if ( $id ) {
			unset( $post_data['tbp_theme_name'] );
			if ( !empty($post_data['tbp_theme_screenshot']) && !empty($post_data['tbp_theme_screenshot_id'] )) {
				set_post_thumbnail( $id, $post_data['tbp_theme_screenshot_id'] );
			}
			if ( ! isset( $metainfo ) ){
			    update_post_meta( $id, 'theme_info', self::removeEmpty($post_data ));
			}
			// Return activate url
			$instance = Tbp::get_instance();
			if('publish' === $post_status && $instance->active_theme->ID!=$id){
			    $this->set_active_theme($id);
			    $resp['redirect'] = admin_url( 'admin.php?page=' . self::$post_type . '&status=activate&id=' . $id );
			}
			echo json_encode( $resp );
		}
		die;
	    }
	}
	
	
	private static function removeEmpty(array $arr){
	    foreach($arr as $k=>$v){
		if($v===''){
		    unset($arr[$k]);
		}
	    }
	    return $arr;
	}

	/**
	 * Activate/Deactivate Theme action.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function actions() {
		if(isset($_GET['p'], $_GET['action'],$_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::$post_type.'_nonce') ){
		    
		    $action = $_GET['action'];
		    $url = menu_page_url(self::$post_type,false);
		    $post_id = (int) $_GET['p'];
		    switch($action){
			case 'activate':
			case 'deactivate':
			    if($action==='deactivate'){
				$post_id=null;
			    }
			    $this->set_active_theme($post_id);
			    $url = add_query_arg( array( 'status' => $action ), $url );
			break;
			case 'export':
			    if(!$this->export_theme_bulk(array($post_id))){
				wp_redirect( admin_url( 'edit.php?post_type=' . self::$post_type ) );
			    }
			    exit;
			break;
			case 'delete':
			    wp_delete_post( $post_id, true ); 
			break;
		    }
		    wp_redirect($url);
		    exit;
		}
	}


	/**
	 * Activate TF Theme.
	 * 
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function set_active_theme( $post_id ){
		// Activate theme
		Tbp_Utils::set_active_theme( $post_id );
	}


	/**
	 * Expor Theme function
	 */
	private function export_theme_bulk( $pIds ) {
		global $ThemifyBuilder;

		$data = array('import' => 'Pro_Themes', 'content' => array());
		global $ThemifyBuilder;  
		foreach ( $pIds as $pId ) {
		    $theme = get_post( $pId );
		    if(empty($theme)){
				continue;
		    }
		    $data_themes = array(
			'title' => get_the_title( $theme ),
			'theme_info' => get_post_meta( $pId, 'theme_info', true ),
			'templates' => array()
		    );

		    $args = array(
			    'post_type' =>Tbp_Templates::$post_type,
			    'no_found_rows'=>true,
			    'ignore_sticky_posts'=>true,
			    'meta_query' => array(
				array(
					'key'     => 'tbp_associated_theme',
					'value' => $theme->post_name,
				)
			    )
			);
			$query = new WP_Query( $args );
			$templates = $query->get_posts();
			if ( $templates ) {
				$usedGS = array();
				foreach( $templates as $template ) {
					$builder_data = $ThemifyBuilder->get_builder_data( $template->ID );
					$builder_data = Themify_Builder_Import_Export::prepare_builder_data( $builder_data );
					$data_templates = array(
						'title' => get_the_title( $template->ID ),
						'settings' => $builder_data,
					);
					$data_templates['tbp_associated_theme'] = $theme->post_name;
					$data_themes['templates'][] = apply_filters( 'tbp_theme_export_templates_data', $data_templates, $template->ID );
					// Check for attached GS
					$usedGS +=Themify_Global_Styles::used_global_styles( $template->ID );
				}
			}

			$data['content'][] = $data_themes;
		}
		wp_reset_postdata();
		
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;
		$f = 'pro_theme_' . get_post_field( 'post_name', $pId ) . '_'.date('Y_m_d');
		if(class_exists('ZipArchive')){
			$datafile = 'export_file.txt';
			$wp_filesystem->put_contents( $datafile, json_encode( $data ) );
			$files_to_zip = array( $datafile );
			// Export used global styles
			if ( !empty( $usedGS ) ) {
				foreach ( $usedGS as $gsID => $gsPost ) {
					unset( $usedGS[ $gsID ]['id'],$usedGS[ $gsID ]['url'] );
					$styling = Themify_Builder_Import_Export::prepare_builder_data( $gsPost['data'] );
					$styling = $styling[0];
					if ( $gsPost['type'] === 'row' ) {
						$styling = $styling['styling'];
					} elseif ( $gsPost['type'] === 'column' ) {
						$styling = $styling['cols'][0]['styling'];
					} else {
						$styling = $styling['cols'][0]['modules'][0]['mod_settings'];
					}
					$usedGS[ $gsID ]['data'] = $styling;
				}
				$gs_data = json_encode( $usedGS );
				$gs_datafile = 'builder_gs_data_export.txt';
				$wp_filesystem->put_contents( $gs_datafile, $gs_data, FS_CHMOD_FILE );
				$files_to_zip[] = $gs_datafile;
			}
			$file = $f. '.zip';
			$result = themify_create_zip( $files_to_zip, $file, true );
		}
		if(!empty($result) ){
			if ( ( isset( $file ) ) && ( $wp_filesystem->exists( $file ) ) ) {
				ob_start();
				header('Pragma: public');
				header('Expires: 0');
				header('Content-type: application/force-download');
				header('Content-Disposition: attachment; filename="' . $file . '"');
				header('Content-Transfer-Encoding: Binary'); 
				header('Content-length: '.filesize($file));
				header('Connection: close');
				ob_clean();
				flush();
				echo $wp_filesystem->get_contents( $file );
				$wp_filesystem->delete( $datafile );
				$wp_filesystem->delete( $file );
				exit();
			} else {
				return false;
			}
		} else {
			if ( ini_get( 'zlib.output_compression' ) ) {
				ini_set( 'zlib.output_compression', 'Off' );
			}
			ob_start();
			header('Content-Type: application/force-download');
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private',false);
			header('Content-Disposition: attachment; filename="'.$f.'.txt"');
			header('Content-Transfer-Encoding: binary');
			ob_clean();
			flush();
			echo json_encode($data);
			exit();
		}

		return false;
	}

	public function import_theme_action() {
		$imgid = $_POST['imgid'];
		
		! empty( $_POST[ '_ajax_nonce' ] ) && check_ajax_referer($imgid . 'themify-plupload');

		/** Handle file upload storing file|url|type. @var Array */
		$file = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true,'action' =>self::$post_type.'_plupload'));

		// if $file returns error, return it and exit the function
		if (! empty( $file['error'] ) ) {
			echo json_encode($file);
			exit;
		}

		//let's see if it's an image, a zip file or something else
		$ext = explode('/', $file['type']);
		// Import routines
		if( 'zip' === $ext[1] || 'rar' === $ext[1] || 'plain' === $ext[1] ){

			$url = wp_nonce_url('edit.php');

			if (false === ($creds = request_filesystem_credentials($url) ) ) {
				return true;
			}
			if ( ! WP_Filesystem($creds) ) {
				request_filesystem_credentials($url, '', true);
				return true;
			}

			global $wp_filesystem;
			$base_path = wp_upload_dir();
			$base_path = trailingslashit( $base_path['path'] );
			$data=null;
			if( 'zip' === $ext[1] || 'rar' === $ext[1] ) {
				unzip_file($file['file'], $base_path);
				if( $wp_filesystem->exists( $base_path . 'export_file.txt' ) ) {
					$data = $wp_filesystem->get_contents( $base_path . 'export_file.txt' );
					$data = is_serialized($data) ? maybe_unserialize($data) : json_decode($data,true);
					// Check for importing attached GS data
					$gs_path = $base_path . 'builder_gs_data_export.txt';
					if ( $wp_filesystem->exists( $gs_path ) ) {
						$gs_data = $wp_filesystem->get_contents( $gs_path );
						$gs_data = is_serialized( $gs_data ) ? maybe_unserialize( $gs_data ) : json_decode( $gs_data );
						Themify_Global_Styles::builder_import( $gs_data );
						$gs_data=null;
						$wp_filesystem->delete( $gs_path );
					}
					$wp_filesystem->delete($base_path . 'export_file.txt');
				} 
			} elseif( $wp_filesystem->exists( $file['file'] ) ){
				$data = $wp_filesystem->get_contents( $file['file'] );
				$data = is_serialized($data) ? maybe_unserialize($data) : json_decode($data,true);
			}
			if($data){
				$result = $this->process_import( $data );
				if(!empty($result['err'])){
					$file['error'] = $result['err'];
				}
				if(!empty($result['id']) && 0 !== (int)$result['id']){
					$file['msg'] = __('Theme imported successfully. Would you like to activate the theme?','themify');
					$url = admin_url( 'admin.php?page=' . self::$post_type);
					$url = add_query_arg( array(
						'p' => $result['id'],
						'_wpnonce' => wp_create_nonce(self::$post_type.'_nonce'),
						'action' => 'activate'
					), $url );
					$file['active'] = $url;
				}

				$wp_filesystem->delete($file['file']);
			}
			else{
				$file['error'] = __('Data could not be loaded', 'themify');
			}
			
		}
		// set thumb to true to trigger themify_plupload_selected event
		$file['thumb'] = true;
		// send the uploaded file url in response
		echo json_encode($file);
		exit;
	}

	private function process_import($data){
		$error = false;

		if(!isset($data['import']) || !isset($data['content']) || !is_array($data['content'])){
			$error = __('Incorrect Import File', 'themify');
		} else {
			$error = 'Pro_Themes' !== $data['import'] ? __('Failed to import. Unknown data.', 'themify') : $error;
			if(!$error){
				global $ThemifyBuilder_Data_Manager;
				$uid=get_current_user_id();
				foreach($data['content'] as $psot){
					if ( Tbp_Utils::theme_post_exists( $psot['title'] ) ) {
						return esc_html__( 'Import failed. There is an existing theme with identical name as the import data.', 'themify' );
					} else {
						$new_theme_id = wp_insert_post(array(
							'post_status' => 'publish',
							'post_type' => self::$post_type,
							'post_author' => $uid,
							'post_title' => $psot['title']
						));

						if ( $new_theme_id ) {
							$result['id'] = $new_theme_id;
							update_post_meta( $new_theme_id, 'theme_info', $psot['theme_info'] );

							if ( isset( $psot['templates'] ) && is_array( $psot['templates'] ) ) {
								foreach( $psot['templates'] as $key => $template ) {
									$new_template_id = wp_insert_post(array(
										'post_status' => 'publish',
										'post_type' =>Tbp_Templates::$post_type,
										'post_author' => $uid,
										'post_title' => $template['title']
									));

									if ( $new_template_id ) {
										if ( ! empty( $template['settings'] ) ){
										    if(is_string($template['settings'])){
											$template['settings'] = json_decode( $template['settings'], true );
										    }
										    $ThemifyBuilder_Data_Manager->save_data( $template['settings'], $new_template_id );
										}
										do_action( 'themify_builder_layout_import_loop_set_metadata', $new_template_id, $template, Tbp_Templates::$post_type );
										$theme = get_post($new_theme_id);
										update_post_meta( $new_template_id, 'tbp_associated_theme', $theme->post_name );
									}
								}
							}
						}
					}
				}
			}
		}
		$result['err'] = $error;
		return $result;
	}

	/**
	 * Delete associated template and template part data.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param int $post_id 
	 */
	public function delete_associated_templates( $post_id ) {
		// If this is just a revision, don't send the email or  If this isn't a 'tbp_theme' post, don't update it.
		if ( wp_is_post_revision( $post_id ) || self::$post_type !== get_post_type( $post_id ))
			return;

		$theme = get_post( $post_id );
		$datas = Tbp_Utils::get_template_related_post_ids( $post_id, $theme->post_name );
		if ( !empty( $datas )) {
			foreach( $datas as $data ) {
			    if ( $post_id != $data ){
				wp_delete_post( $data, true );
			    }
			}
		}
	}

	public static function register_rest_fields() {
		register_rest_field( self::$post_type, 'tbp_image_thumbnail', array(
				'get_callback'    => array( __CLASS__, 'get_theme_thumbnail_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_image_full', array(
				'get_callback'    => array( __CLASS__, 'get_theme_img_full_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_theme_info', array(
				'get_callback'    => array( __CLASS__, 'get_theme_info_cb'),
				'schema'          => null,
			)
		);
	}

	public static function get_theme_thumbnail_cb( $data ) {
		if( !empty($data['featured_media'])){
			$img = wp_get_attachment_image_src( $data['featured_media'] );
			return $img[0];
		}
		return false;
	}

	public static function get_theme_img_full_cb( $data ) {
		if( !empty($data['featured_media']) ){
			$img = wp_get_attachment_image_src( $data['featured_media'], 'large' );
			return $img[0];
		}
		return false;
	}

	public static function get_theme_info_cb( $data ) {
		return get_post_meta( $data['id'], 'theme_info', true );
	}

	private function import_related_templates( $slug, $theme_slug ) {
		$remote_url=Tbp_Templates::getTemplateTypeUrl(array('associated_theme'=>$slug));
		$request = wp_remote_get( $remote_url );
		if ( ! is_wp_error( $request ) ) {
			$response = json_decode( wp_remote_retrieve_body( $request ), true );

			if ( is_array( $response ) && !empty( $response )) {
				foreach( $response as $template ) {

					// Create a new post
					$set_slug = sanitize_title( $theme_slug . ' ' . $template['title']['rendered'] );
					$my_post = array(
						'post_title'  => $template['title']['rendered'],
						'post_name' => $set_slug,
						'post_status' => $template['status'],
						'post_type'   => $template['type']
					);

					$template_options = $template['tbp_template_options'];
					$new_id = wp_insert_post( $my_post );

					if ( $new_id ) {

						// Import builder content
						$builder_content =!empty($template['template_builder_content'])?json_decode( $template['template_builder_content'], true ):array();
						$GLOBALS['ThemifyBuilder_Data_Manager']->save_data( $builder_content, $new_id );

						// Import template options
						$template_options = $template['tbp_template_options'];
						if ( is_array( $template_options ) && !empty( $template_options )) {
							foreach( $template_options as $meta_key => $val ) {
								update_post_meta( $new_id, $meta_key, $val );
							}
						}

						// Update associated theme
						update_post_meta( $new_id, 'tbp_associated_theme', $theme_slug );
						// Import attached GS
						If(!empty($template['tbp_template_gs'])){
							Themify_Global_Styles::builder_import($template['tbp_template_gs']);
						}
					}
				}
			}
		}
	}
	
	public function get_item_data(){
	    
	    // Check ajax referer
	    check_ajax_referer('tb_load_nonce', 'tb_load_nonce');
	    if(!empty($_POST['id'])){
		$id = (int)$_POST['id'];
		$get_post = get_post( $id );
		if(!empty($get_post)){
		    $data = get_post_meta( $id, 'theme_info', true );
		    $data['tbp_theme_name'] = $get_post->post_title;
		}
		else{
		    $data=array();
		}
		echo json_encode($data);
	    }
	    die;
	}
	
	
	public static function getTemplateTypeUrl($args=array()){
		return self::$api_base.'?'.http_build_query($args);
	}
	
	public function enqueue_scripts(){
	    
	    $instance = Tbp::get_instance();
	    $name=$instance->get_plugin_name();
	    wp_enqueue_script( $name, themify_enque(TBP_URL. 'admin/js/tbp-theme.js'), array( 'jquery'), $instance->get_version(), true );
	    $localize = array(
		    'options'=>$this->get_options(),
		    'add_template' =>__( 'New Theme', 'themify' ),
		    'edit_template' => __( 'Edit Theme', 'themify' ),
		    'blank'=>__('Blank','themify'),
		    'import'=>__('Import Theme','themify'),
		    'api_base'=>  self::$api_base,
		    'confirmDelete' => __( "Are you sure you want to delete this theme? All associated templates will be deleted as well.\n\nClick 'Cancel' to go back, 'OK' to confirm the delete.",'themify' ),
		    'publishBtn'=>__('Activate', 'themify'),
		    'active'=> empty($instance->active_theme->ID) ? false : $instance->active_theme->ID,
		    'next'=>__('Next', 'themify')
	    );
	    wp_localize_script( 'tbp-admin', '_tbp_app', $localize );
	}
}
