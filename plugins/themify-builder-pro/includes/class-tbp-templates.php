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
class Tbp_Templates{

	public static $post_type = 'tbp_template';
	
	private static $api_base = 'https://themify.me/demo/themes/builder-pro-themes/wp-json/wp/v2/tbp_template';
	
	private static $defaults=array();
	
	private static $Types = array();
	
	private static $Labels=array();

	public function __construct() {
		self::set_defaults();
		self::$Types = array(
		    'header' => __( 'Header', 'themify' ),
		    'footer' => __( 'Footer', 'themify' ),
		    'single' => __( 'Single', 'themify' ),
		    'archive'=>__( 'Archive', 'themify' ),
		    'page'=> __( 'Page','themify' )
		);
		self::$Labels=array(
			'general'=>__('Entire Site','themify'),
			'archive'=>__('Archives','themify'),
			'single'=>__('Singular','themify'),
			'page'=>__( 'All Pages', 'themify' ),
			'is_front'=>__( 'Homepage', 'themify' ),
			'is_404'=>__( '404 Page', 'themify' ),
			'child_of'=>__( 'Child of', 'themify' ),
			'is_date'=>__( 'Date Archive', 'themify' ),
			'is_author'=>__( 'Author Archive', 'themify' ),
			'is_search'=>__( 'Search Results', 'themify' ),
			'is_attachment'=>__( 'Media', 'themify' ),
			'shop'=> __( 'Shop Page', 'themify' ),
			'post'=>__( 'All Post', 'themify' ),
			'product'=>__( 'All Product', 'themify' ),
			'all_post'=>__( 'All Post Archives', 'themify' ),
			'all_product'=>__( 'All Product Archives', 'themify' ),
			'_single'=>array(
				'all'=>__( 'All Singular', 'themify' ),
				'product_cat'=>__( 'In Product Category', 'themify' ),
				'product_tag'=>__( 'In Product Tag', 'themify' ),
				'category'=>__( 'In Category', 'themify' ),
			    'post_tag'=>__( 'In Tag', 'themify' )
			),
			'_archive'=>array(
				'all'=>__( 'All Archives', 'themify' ),
				'category'=>__( 'Categories', 'themify' ),
				'post_tag'=>__( 'Tags', 'themify' ),
				'product_cat'=>__( 'Product categories', 'themify' ),
				'product_tag'=>__( 'Product tags', 'themify' )
			)

		);
		if(themify_is_woocommerce_active()){
		    self::$Types['product_single'] = __( 'Product Single','themify' );
		    self::$Types['product_archive'] = __( 'Product Archive','themify' );
		}
		self::$Types = apply_filters('tbp_template_types', self::$Types);
		// Templates
		add_action( 'admin_notices', array( $this, 'add_menu_tabs' ), 9 );
		add_filter( 'manage_edit-' . self::$post_type . '_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_' . self::$post_type . '_posts_custom_column', array( __CLASS__, 'manage_custom_column' ), 10, 2 );
		add_action( 'wp_ajax_'.self::$post_type.'_saving', array( $this, 'save_form' ) );
		add_action( 'wp_ajax_'.self::$post_type.'_get_item', array( $this, 'get_item_data' ) );
		add_action( 'wp_ajax_'.self::$post_type.'_plupload', array( $this, 'import_templates' ) );
		add_action( 'admin_init', array( __CLASS__, 'export_row' ) );
		add_filter( 'handle_bulk_actions-edit-tbp_template', array( __CLASS__, 'export_row_bulk' ), 10, 3);
		add_filter( 'bulk_actions-edit-tbp_template', array( $this, 'row_bulk_actions' ) );
		add_action( 'pre_get_posts', array( $this, 'filter_template_query' ) );
		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 11 );
		add_filter( 'themify_builder_layout_export_content', array( $this, 'filter_export_content' ), 10, 3);
		add_filter( 'themify_builder_check_row_bulk_import_button', array( $this, 'check_row_bulk_import_btn' ), 10, 2);
		add_action( 'themify_builder_layout_import_loop_set_data', array( $this, 'import_loop_set_data' ), 10, 3 );
		add_action( 'themify_builder_layout_import_loop_set_metadata', array( $this, 'import_loop_set_metadata' ), 10, 3 );
		add_filter( 'themify_builder_layout_import_type', array( $this, 'set_import_type' ), 10, 2 );
		add_filter( 'themify_builder_post_types_support', array( $this, 'register_builder_post_type_metabox' ) );
		add_filter( 'themify_post_types', array( $this, 'extend_post_types' ) );
		add_filter( 'themify_metabox/fields/themify-meta-boxes', array( $this, 'themify_skip_post_options_metabox' ), 99, 2);
		add_action( 'wp_ajax_tbp_load_data', array( $this, 'load_data' ) );
		add_filter( 'views_edit-tbp_template', array( $this, 'views_edit_template' ) );
		add_filter('themify_builder_post_types_support', array(__CLASS__,'exclude_post_type'));
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_fields' ) );
		add_filter( 'rest_tbp_template_query', array( __CLASS__, 'rest_post_type_query' ), 10, 2 );
		
		add_action( 'admin_footer', array( $this, 'enqueue_scripts' ) );
		global $pagenow;
		if(is_admin() && $pagenow === 'post.php' && !empty( $_GET['post'] ) && get_post_type($_GET['post']) === self::$post_type){
			add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar' ),999 );
        }
	}

	private static function set_defaults(){
		self::$defaults = array(
			'tbp_template_name'            => __( 'New Template', 'themify'),
			'tbp_template_type'            => 'archive',
			'tbp_associated_theme'=>'',
			'tbp_custom_css'=>'',
			'tbp_template_conditions'=>array(),
		);
    }

	public function add_menu_tabs() {
		global $pagenow;

		if( isset( $_GET['post_type'] ) && $_GET['post_type'] === self::$post_type && $pagenow === 'edit.php' ) {
			$tabs = array_merge(array('active' => __( 'Active', 'themify' )),self::$Types);
			$current_tab = ( empty( $_GET['tab'] ) ) ? 'active' : sanitize_text_field( urldecode( $_GET['tab'] ) ); ?>
			
			<div class="notice tbp_notice_template">
				<h2 class="nav-tab-wrapper tbp_nav_tab_wrapper">
				    <?php foreach ( $tabs as $name => $label ):?>
					<a href="<?php echo admin_url( 'edit.php?post_type=' . self::$post_type . '&tab=' . $name )?>" class="nav-tab<?php if( $current_tab === $name ):?> nav-tab-active<?php endif;?>"><?php echo $label?></a>
				    <?php endforeach;?>
				</h2>
			</div>
	<?php
		}
	}

	/**
	 * Post Type Custom column.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $columns 
	 * @return array
	 */
	public function edit_columns( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __('Title', 'themify'),
			'type'      => __('Type', 'themify'),
			'status'	=> __( 'Status', 'themify' ),
			'theme'		=> __( 'Theme', 'themify' ),
			'condition'	=> __( 'Conditions', 'themify' ),
			'author'	=> __( 'Author', 'themify' ),
			'date'      => __('Date', 'themify')
		);
	}

	/**
	 * Manage Post Type Custom Columns.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $column 
	 * @param int $post_id 
	 */
	public static function manage_custom_column( $column, $post_id  ) {
		switch ( $column ) {
			case 'type':
				echo get_post_meta( $post_id, 'tbp_template_type', true );
			break;

			case 'status':
				global $post;
				echo $post->post_status;
			break;

			case 'theme':
				echo get_post_meta( $post_id, 'tbp_associated_theme', true );
			break;

			case 'condition':
				$records = Tbp_Utils::get_template_conditions( $post_id );
				$type=get_post_meta( $post_id, 'tbp_template_type', true );
				$key='';
				if($type==='product_archive'){
					$type='archive';
				}
				elseif($type==='product_single'){
					$type='single';
				}
				if($type==='archive' || $type==='single'){
					$key='_'.$type;
				}	
				foreach( $records as $record ) {
				    $output  = array();
				    foreach ( $record as $k=>$v ) {
						$val=$v;
						if($k!=='type' && $k!=='detail'){
							if(isset(self::$Labels[$v])){
								$val=self::$Labels[$v];
							}
							else{
								if($type==='header' || $type==='footer'){
									$key='_'.$record['general'];
								}
								if(isset(self::$Labels[$key][$v])){
									$val=self::$Labels[$key][$v];
								}
								elseif(strpos($v,'all_')===0){
									$val=ucfirst(str_replace('all_','',$v));
								}
							}
						}
						elseif($k==='detail' && ($record['general']==='general' || $record['general']==='all' || $record['general']==='is_404' || $record['general']==='is_front' || $record['general']==='is_search' || $record['general']==='is_date' || strpos($record['general'],'all_')===0 || (isset($record['query']) && ($record['query']==='all' || strpos($record['query'],'all_')===0)))){
								continue;
						}
						$output[] = is_array( $val )?implode (', ', $val ):$val;
				    }
				    echo '<p>' , implode(' > ', $output ) , '</p>';
				}
			break;
		}
	}

	/**
	 * Post Row Actions
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $actions 
	 * @return array
	 */
	public function post_row_actions( $actions ) {
		global $post;
		if ( self::$post_type === $post->post_type ) {
			$actions['edit'] = sprintf( '<a href="#" class="tbp_lightbox_edit" data-post-id="%d">%s</a>', 
				$post->ID, 
				__('Options', 'themify') 
			);
			$actions['backend'] = sprintf( '<a href="%s">%s</a>', admin_url( 'post.php?post=' . $post->ID . '&action=edit' ), __('Backend', 'themify') );
			
			if ( isset( $actions['themify-builder'] ) ) {
				unset( $actions['themify-builder'] );
				$builder_link = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( get_permalink( $post->ID ) . '#builder_active' ), __('Frontend', 'themify' ));
				$actions['themify-builder'] = $builder_link;
			}
			$actions['tbp-template-export'] = sprintf( '<a href="%s">%s</a>', wp_nonce_url( admin_url( 'edit.php?post_type='.Tbp_Templates::$post_type.'&post=' . $post->ID . '&action='.Tbp_Templates::$post_type.'_export' ), 'tbp_template_export' ), __('Export', 'themify') );

			if ( isset( $actions['trash'] ) ) {
				$actions['tbp-trash'] = $actions['trash'];
			}
			unset($actions['trash'], $actions['view'] );
		}
		return $actions;
	}

	/**
	 * Template form fields.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function get_options() {
		$themes = TBP_Utils::get_exist_themes();
		$themesSelect = array();
		if(!empty($themes)){
		    foreach($themes as $t){
			$themesSelect[$t->post_name]=$t->post_title;
		    }
		}
		
		$instance = Tbp::get_instance();
		$post_types = Themify_Builder_Model::get_public_post_types(false);
		$selectPostTypes = $post_types;
		unset($themes,$selectPostTypes['product'],$selectPostTypes[self::$post_type],$selectPostTypes['tbp_theme'],$selectPostTypes['tglobal_style'],$selectPostTypes['tb_cf']);
		

		$single_tax_arr=$archive_tax_arr = array();
		foreach ( $post_types as $post_type => $label ) {
		    if ( $post_type !== 'post' && $post_type !== 'page' ) {
				$post_type_object = get_post_type_object( $post_type );

				$single_tax_arr[ $post_type ] = array(
					'label'=>  $post_type_object->labels->singular_name,
					'id'=>$post_type,
					'options' => array(
						$post_type => sprintf( 'All %s', $post_type_object->labels->singular_name )
					)
				);
				if ( $post_type_object->has_archive ) {
					$archive_tax_arr[ $post_type ] = array(
						'label'=>  $post_type_object->labels->name,
						'id'=>$post_type,
						'options'=>array(
							'all_' . $post_type => sprintf( 'All %s Archives', $label )
						)
					);
				}

				$post_type_taxonomies = wp_filter_object_list( get_object_taxonomies( $post_type, 'objects' ), array(
					'public' => true,
					'show_in_nav_menus' => true
				));

				if ( empty($post_type_taxonomies)  || is_wp_error( $post_type_taxonomies ) ) {
					continue;
				}

				foreach ( $post_type_taxonomies as $slug => $object ) {
					$single_tax_arr[ $post_type ]['options'][ $slug ] = sprintf( 'In %s', $object->labels->singular_name );
					$archive_tax_arr[ $post_type ]['options'][ $slug ] = $object->label;
				}
		    }
		}
		$post_type_taxonomies =  $post_types=null;
		$conditiions=array();
		foreach(self::$Types as $type=>$v){
		    $method = 'get_options_'.$type;
		    $conditiions[$type] =  $this->$method($single_tax_arr,$archive_tax_arr);
		}
		
		$args = array(
		    array(
			'id'=>'tbp_template_name',
			'label' => __('Name', 'themify'),
			'type'=>'text',
			'control'=>FALSE
		    ),
		    array(
			'id'=>'tbp_associated_theme',
			'label' => __( 'Associated Theme', 'themify' ),
			'options'=>$themesSelect,
			'default'=> $instance->active_theme->post_name,
			'control'=>FALSE,
			'type'=>'select',
			'help' => __( 'Select the theme which you want this template to associated. Templates are used base on the activated theme.', 'themify' )
		    ),
		    array(
			'id' => 'tbp_template_type',
			'type'=> 'tbp_type',
			'label'=> __('Type', 'themify'),
			'options' => self::$Types
		    ),
		    array(
			'id'=>'tbp_template_conditions',
			'type'=>'condition',
			'label'=>__('Display Conditions', 'themify' ),
			'options'=>$conditiions
		    ),
		);
		if ( '' === $instance->active_theme->post_name ) {
			$no_activated_theme_args = array(
				'id' => 'tbp_no_theme_activated',
				'type' => 'message',
				'comment' => sprintf('<h3>%s</h3><p>%s</p>', esc_html__( 'No Theme Activated', 'themify' ), __( "You don't have a Pro Theme activated. Please create or activate a <a href='" . admin_url('admin.php?page=tbp_theme') . "'>Pro Theme</a> First.", 'themify' )),
				'theme_page_url' => admin_url('admin.php?page='.Tbp_Themes::$post_type)
			);
			array_unshift( $args, $no_activated_theme_args );
		}
		return apply_filters( 'tbp_post_type_tbp_template_fields', $args);
	}
	
	
	private function get_options_header(array $single,array $archive){
	    $singleData=$this->get_options_single($single, $archive,true);
	    $pageData=$this->get_options_page($single, $archive);
	    array_unshift($singleData['optgroup'],array('id'=>'page','label'=>self::$Types['page'],'options'=>$pageData));
	    $pageData=null;
	    $args = array(
			'general'=>array(
			    'label'=>self::$Labels['general']
			),
			'archive'=>array(
			    'label'=>self::$Labels['archive'],
			    'options'=>$this->get_options_archive($single, $archive,true)
			),
			'single'=>array(
			    'label'=>self::$Labels['single'],
			    'options'=>$singleData
			));
	    return $args;
	}
	
	private function get_options_footer(array $single,array $archive){
	    return $this->get_options_header($single,$archive);
	}
	
	
	private function get_options_single(array $single,array $archive,$isHeader=null){
	    $args = array(
		'all'=>array(
		    'label'=>self::$Labels['_single']['all']
		),
		'is_attachment'=>array(
		    'label'=>self::$Labels['is_attachment'],
		    'has_query'=>true
		),
		'optgroup'=>array(
		    array(
			'label'=>__( 'Post', 'themify' ),
			'id'=>'post',
			'options'=>array(
			    'post'=>self::$Labels['post'],
			    'category'=>self::$Labels['_single']['category'],
			    'post_tag'=>self::$Labels['_single']['post_tag']
			)
		    )
		)
	    );
	    if(!empty($single)){
		if($isHeader===null){
		    unset($single['product']);
		    $args['optgroup'][0]['selected']='post';
		}
		foreach($single as $s){
		    $args['optgroup'][]=$s;
		}
	    }
	    return $args;
	}
	private function get_options_archive(array $single,array $archive,$isHeader=null){
	    $args = array(
		'all'=>array(
		    'label'=>self::$Labels['_archive']['all']
		),
		'is_date'=>array(
		    'label'=>self::$Labels['is_date']
		),
		'is_front'=>array(
		    'label'=>__( 'Homepage Latest posts', 'themify' )
		),
		'is_author'=>array(
		    'label'=>self::$Labels['is_author'],
		    'has_query'=>true
		),
		'is_search'=>array(
		    'label'=>self::$Labels['is_search']
		),
		'optgroup'=>array(
		    array(
			'label'=>__( 'Posts', 'themify' ),
			'id'=>'post',
			'options'=>array(
			    'all_post'=>self::$Labels['all_post'],
			    'category' => self::$Labels['_archive']['category'],
			    'post_tag'=>self::$Labels['_archive']['post_tag'],
			)
		    )
		)
	    );
	    if(get_option( 'show_on_front')!=='posts'){
		unset($args['is_front']);
	    }
	    if(!empty($archive)){
		if($isHeader===null){
		    unset($archive['product']);
		    $args['optgroup'][0]['selected']='all_post';
		}
		foreach($archive as $s){
		    $args['optgroup'][]=$s;
		}
	    }
	    return $args;
	}
	private function get_options_page(array $single,array $archive){
	    $args =array(
		'page'=>array(
		    'label'=>self::$Labels['page'],
		    'has_query'=>true
		),
		'is_front'=>array(
		    'label'=>self::$Labels['is_front'],
		    'has_query'=>false
		),
		'is_404'=>array(
		    'label'=>self::$Labels['is_404'],
		    'has_query'=>false
		),
		'child_of'=>array(
		    'label'=>self::$Labels['child_of'],
		    'has_query'=>true
		)
	    );
	    if(get_option( 'show_on_front')!=='page'){
		    unset($args['is_front']);
	    }
	    return $args;
	}
	private function get_options_product_single(array $single,array $archive){
	    return array(
			'product'=>array(
				'label'=>self::$Labels['product'],
				'has_query'=>true
			),
			'product_cat'=>array(
				'label'=>self::$Labels['_single']['product_cat'],
				'has_query'=>true
			),
			'product_tag'=>array(
				'label'=>self::$Labels['_single']['product_tag'],
				'has_query'=>true
			)
	    );
	}
	private function get_options_product_archive(array $single,array $archive){
	    return array(
		'all_product'=>array(
		    'label'=>self::$Labels['all_product']
		),
		'shop'=>array(
		    'label'=>self::$Labels['shop']
		),
		'product_cat'=>array(
		    'label'=>self::$Labels['_archive']['product_cat'],
		    'has_query'=>true
		),
		'product_tag'=>array(
		    'label'=>self::$Labels['_archive']['product_tag'],
			'has_query'=>true
		)
	    );
	}


	/**
	 * Saving form data.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $post_data 
	 */
	public function save_form() {
		if(!empty($_POST['type']) && $_POST['type']===self::$post_type){
		    check_ajax_referer('tb_load_nonce', 'tb_load_nonce');
		    $id = !empty($_POST['id'])?(int)$_POST['id']:null;
		    $response = self::save($_POST['data'], $id);
		    echo json_encode($response);
		}
		die;
	}
	
	public static function save($post_data,$id){
	    $post_data = wp_parse_args( $post_data, self::$defaults );
	    $post_status = !empty($post_data['is_draft'])?'draft':'publish';
	    $return = array();
	    $args = array(
		'post_title'  => sanitize_text_field( $post_data['tbp_template_name'] ),
		'post_type'   => self::$post_type,
		'menu_order'  => !empty($post_data['menu_order'])?$post_data['menu_order']:0
	    );

	    $instance = Tbp::get_instance();
	    if($id){
		$args['ID']=$id;
		unset($args['post_type']);
		wp_update_post( $args );
	    }
	    else{
		$args['post_status'] = $post_status;
		$args['post_content']='';
		$args['post_name']=sanitize_title( $instance->active_theme->post_name . ' ' . $args['post_title'] );
		$id = wp_insert_post( $args );
		if(! is_wp_error( $id )){
		    if (isset($post_data['import']) && 'blank' !== $post_data['import'] && '' !== $post_data['import'] ) {
			    $remote_url = self::getTemplateTypeUrl(array('slug'=>$post_data['import']));
			    $request = wp_remote_get( $remote_url ); 
			    if ( ! is_wp_error( $request ) ) {
				    $response = json_decode( wp_remote_retrieve_body( $request ), true );
				    if ( !empty($response) && !empty($response[0]['template_builder_content']) ) {
					    $builder_content = json_decode( $response[0]['template_builder_content'], true );
					    $GLOBALS['ThemifyBuilder_Data_Manager']->save_data( $builder_content, $id);
						// Import attached GS
						If(!empty($response[0]['tbp_template_gs'])){
							Themify_Global_Styles::builder_import($response[0]['tbp_template_gs']);
						}
				    }
			    }
		    }
		}
		else{
		    $id=null;
		}
	    }
	    if ( $id ) { 
		    foreach( self::$defaults as $key => $value ) {
			if ( $key!=='tbp_template_name' ){
			    if( !empty($post_data[ $key ])){
				update_post_meta( $id,$key, $post_data[ $key ] );
			    }
			    else{
				delete_post_meta($id, $key);
			    }
			}
		    }
		    // Update associated theme
		    $asc_theme_meta = isset( $post_data['theme'] ) ? $post_data['theme'] : $instance->active_theme->post_name;
		    update_post_meta( $id, 'tbp_associated_theme', $asc_theme_meta );
		    if(empty($args['ID'])){
			$callback_uri = get_permalink( $id ) . '#builder_active';
			if ( 'draft' === $post_status ) {
			    $callback_uri = add_query_arg( 'preview', true, $callback_uri );
			}
			$return['redirect'] = $callback_uri;
		    }
		    $return['id']=$id;
		    return $return;
	    }
	    return false;
	}

	public function filter_template_query( $query ) {  
		if (self::$post_type === $query->get('post_type') && $query->is_main_query() ) {
			
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'active';
			$status = isset( $_GET['post_status'] ) ? $_GET['post_status'] : 'active';
			$meta_query = (array) $query->get('meta_query');

			if ( 'active' === $tab || 'active' === $status ) {
				$instance = $plugin = Tbp::get_instance();
				$meta_query[] = array(
				    'key' => 'tbp_associated_theme',
				    'value' => $instance->active_theme->post_name
			    );
			}

			if ( 'active' !== $tab ) {
				$meta_query[] = array(
					'key' => 'tbp_template_type',
					'value' => $tab
				);
			}

			$query->set( 'meta_query', $meta_query );
			if ( 'active' === $status ) {
				$query->set( 'post_status', 'publish' );
			} elseif ( 'all' === $status ) {
				$query->set( 'post_status', 'any' );
			}
		}
		return $query;
	}

	/**
	 * Filter export field content
	 */
	public function filter_export_content( $fields, $id, $post_type ) {
		if ( self::$post_type !== $post_type ) return $fields;

		foreach( self::$defaults as $key => $value ) {
			if ($key!=='tbp_template_name'){
			    $fields[ $key ] = get_post_meta( $id, $key, true );
			}
		}
		return $fields;
	}

	/**
	 * Check display condition import button
	 */
	public function check_row_bulk_import_btn( $boolean, $post_type ) {
		return $boolean && self::$post_type !== $post_type;
	}

	/**
	 * Hook set data import per post
	 */
	public function import_loop_set_data( $new_id, $data, $type ) {
		if ( self::$post_type !== $type ) return;

		$this->update_template_metadata( $new_id, $data );

		// set active theme
		update_post_meta( $new_id, 'tbp_associated_theme', $this->plugin->active_theme->post_name );
	}

	/**
	 * Hook set data import per post
	 */
	public function import_loop_set_metadata( $new_id, $data, $type ) {
		if ( self::$post_type !== $type ) return;

		$this->update_template_metadata( $new_id, $data );
	}

	public function update_template_metadata( $new_id, $data ) {
		foreach( self::$defaults as $key => $value ) {
			if ( isset( $data[ $key ] ) && $key!=='tbp_template_name' ) {
				update_post_meta( $new_id, $key, $data[ $key ] );
			}
		}	
	}

	public function set_import_type( $label, $type ) {
		if ( self::$post_type === $type ){
			$label = 'Templates';
		}
		
		return $label;
	}

	public static function filter_export_template_data( $data, $id ) {
	    if(empty(self::$defaults)){
		self::set_defaults();
	    }
	    foreach( self::$defaults as $key => $meta_value ) {
		    if ( $key!=='tbp_template_name' ){
				$v=get_post_meta( $id, $key, true );
				if(!empty($v)){
					$data[ $key ] = $v;
				}
		    }
		}
		return $data;
	}

	public function register_builder_post_type_metabox( $post_types ) {
		$post_types[] = self::$post_type;

		return $post_types;
	}

	/**
	 * Includes this custom post to array of cpts managed by Themify
	 * 
	 * @access public
	 * @param Array $types
	 * @return Array
	 */
	public function extend_post_types( $types ) {
		$cpts = array( self::$post_type );
		return array_merge( $types, $cpts );
	}

	public function themify_skip_post_options_metabox( $metaboxes, $post_type ) {
		
		if ( self::$post_type === $post_type && $metaboxes[0]['id'] === 'tbp_template-options' ) {
			unset( $metaboxes[0] );
		}
		return $metaboxes;
	}


	public static function register_rest_fields() {
		register_rest_field( self::$post_type, 'template_type', array(
				'get_callback'    => array( __CLASS__, 'get_template_type_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_image_thumbnail', array(
				'get_callback'    => array( __CLASS__, 'get_template_thumbnail_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_image_full', array(
				'get_callback'    => array( __CLASS__, 'get_template_img_full_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'template_builder_content', array(
				'get_callback'    => array( __CLASS__, 'get_builder_content_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_template_options', array(
				'get_callback'    => array( __CLASS__, 'get_template_options_cb'),
				'schema'          => null,
			)
		);

		register_rest_field( self::$post_type, 'tbp_template_gs', array(
				'get_callback'    => array( __CLASS__, 'get_template_gs'),
				'schema'          => null,
			)
		);
	}

	public static function get_template_type_cb( $data ) {
		$id = is_numeric($data)?$data:$data['id'];
		return get_post_meta( $id, 'tbp_template_type', true );
	}

	public static function get_template_thumbnail_cb( $data,$size='thumbnail') {
		if( $data['featured_media'] ){
			$img = wp_get_attachment_image_src( $data['featured_media'],$size );
			return $img[0];
		}
		return false;
	}

	public static function get_template_img_full_cb( $data ) {
		return self::get_template_thumbnail_cb($data,'large');
	}

	public static function get_builder_content_cb( $data ) {
		global $ThemifyBuilder;
        $builder_data = $ThemifyBuilder->get_builder_data( $data['id'] );
        return json_encode( $builder_data );
	}

	public static function rest_post_type_query( $args, $request ) {
	  
		if ( ! empty( $request['template_type'] ) ) {
			$args['meta_query'][] = array(
				array(
					'key' => 'tbp_template_type',
					'value' => sanitize_text_field( $request['template_type'] )
				)
			);
		}

		if ( ! empty( $request['associated_theme'] ) ) {
			$args['meta_query'][] = array(
				array(
					'key' => 'tbp_associated_theme',
					'value' => sanitize_text_field( $request['associated_theme'] )
				)
			);
		}

		return $args;
	}

	public static function get_template_options_cb( $data ) {
		$return = array();
		foreach( self::$defaults as $meta_key => $meta_value ) {
		    $return[ $meta_key ] = get_post_meta( $data['id'], $meta_key, true );
		}

		return $return;
	}

	public static function get_template_gs( $data ) {
		$used_gs = Themify_Global_Styles::used_global_styles($data['id']);
		foreach ($used_gs as $key=>$post){
			if(isset($post['type'])){
				if ( 'row' === $post['type'] ) {
					if(isset($used_gs[$key]['data'][0]['styling'])){
						$used_gs[$key]['data'] = $used_gs[$key]['data'][0]['styling'];
					}
					
				} 
				elseif ( 'column' === $post['type'] ) {
					if(isset($used_gs[$key]['data'][0]['cols'])){
						$used_gs[$key]['data'] = $used_gs[$key]['data'][0]['cols'][0]['styling'];
					}
				} 
				elseif(isset($used_gs[$key]['data'][0]['cols'][0]['modules'][0])){
						$used_gs[$key]['data'] = $used_gs[$key]['data'][0]['cols'][0]['modules'][0]['mod_settings'];
					
				}
			}
        }
		return $used_gs;
	}
	
	/**
	 * Load_data action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return json
	 */
	public function load_data() {
		// Check ajax referer
		check_ajax_referer('tb_load_nonce', 'tb_load_nonce');
		$type = isset( $_POST['type'] ) ? $_POST['type'] : 'post';
		$page = isset($_POST['p'])?(int)$_POST['p']:1;
		$limit = isset($_POST['limit'])?(int)$_POST['limit']:20;
		$s = isset( $_POST['s'] ) ? $_POST['s'] : '';
		if($page<=0){
		    $page=1;
		}
		$count = 0;
		$result=array();
		switch ($type) {
			case 'is_author':
				$query_params = array(
					'who' => 'authors',
					'has_published_posts' => true,
					'paged'=>$page,
					'number'=>$limit,
					's' => $s,
					'fields' => array(
						'ID',
						'display_name'
					)
				);
				$query = new WP_User_Query( $query_params );
				if(!is_wp_error($query)){
				    $count=$query->total_users;
				    $query = $query->get_results();
				    foreach ( $query as $author ) {
					$result[$author->ID] = $author->display_name;
				    }
				}
			    break;
			default:
				$args = array(
				    'post_type'      => $type,
				    'posts_per_page' => $limit,
				    's' => $s,
				    'cache_results'=>false,
				    'ignore_sticky_posts'=>true,
				    'paged'=>$page,
				    'update_post_meta_cache'=>false,
				    'update_post_term_cache'=>false
				);
				$is_taxonomy=FALSE;
				if($type==='child_of' || $type==='page'){
				    $args['post_type']='page';
				    if($type==='child_of'){
					$args['post_parent']=0;
					$args['hierarchical']=false;
				    }
				    if('page' === $type){
				        $args['order'] = 'ASC';
				        $args['orderby'] = 'title';
				    }
				}
				elseif($type==='is_attachment'){
				    $args['post_type']='attachment';
				    $args['post_status']='inherit';
				}
				elseif($type==='category' || $type==='post_tag' || taxonomy_exists($type)){
				    $args =  array(
					'taxonomy' => $type,
					'update_term_meta_cache'=>false,
					'hide_empty' => true,
					'pad_counts'=>true,
					'number'=>$limit,
					'search' => $s
				    );
				    if($page>1){
					$args['offset']=($page-1)*$limit;
				    }
				    $query = get_terms($args);
				    $count=  wp_count_terms($type,$args);
				    $is_taxonomy=true;
				}
				$result=array();
				if($is_taxonomy===false){
				    $query = new WP_Query( $args );
				}
				if ( !empty($query) && !is_wp_error($query) ) {
				    if($is_taxonomy===false){
					$count=$query->found_posts;
					$query=$query->posts;
				    }
				    foreach ( $query as $post ) {
					$key = $type==='is_attachment'?$post->ID:($is_taxonomy===true?$post->slug:$post->post_name);
					$result[$key] = $is_taxonomy===true?$post->name.' <span class="tbp_pagination_post_count">('.$post->count.')</span>':$post->post_title;
				    }
					
				}
				break;
		}
		$data_json = array(
		    'data'=>$result,
		    'limit'=>$limit,
		    'count'=>$count
		);
		die(json_encode( $data_json ));
	}
	
	public function get_item_data(){
	    
	    // Check ajax referer
	    check_ajax_referer('tb_load_nonce', 'tb_load_nonce');
	    if(!empty($_POST['id'])){
		$id = (int)$_POST['id'];
		$data = array();
		$get_post = get_post($id);
		if(!empty($get_post) && $get_post->post_type===self::$post_type){
		    $data['tbp_template_name'] = html_entity_decode($get_post->post_title);
		    foreach(self::$defaults as $k=>$v){
			if($k!=='tbp_template_name'){
			    $item= get_post_meta( $id, $k, true );
			    if(!empty($item)){
				$data[$k] =$item;
			    }
			}
		    }
		}
		echo json_encode($data);
	    }
	    die;
	}
	
	
	public static function getTemplateTypeUrl($args=array()){
	    return self::$api_base.'?'.http_build_query($args);
	}


	public function enqueue_scripts(){
		$localize = array(
		    'options'=>$this->get_options(),
		    'add_template' =>__( 'New Template', 'themify' ),
		    'edit_template' => __( 'Edit Template', 'themify' ),
		    'add_conition'=>__('Add Condition','themify'),
		    'select' =>__( 'Select', 'themify' ),
		    'include'=>__('Include','themify'),
		    'exclude'=>__('Exclude','themify'),
		    'all' => __( 'All', 'themify' ),
		    'blank'=>__('Blank','themify'),
		    'import'=>__('Import Template','themify'),
		    'publishBtn'=>__('Publish', 'themify'),
		    'draftBtn'=>__('Save Draft', 'themify'),
		    'next'=>__('Next', 'themify'),
		    'api_base'=> self::getTemplateTypeUrl(array('template_type'=>''))
	    );
	    wp_localize_script( 'tbp-admin', '_tbp_app', $localize );
	}

	public function views_edit_template( $views ) {
		global $current_screen;
		switch ( $current_screen->id ) {
			case 'edit-tbp_template':
				//$views = Tbp_Utils::manipulate_views_count( 'tbp_template', $views );

				$views['all'] = $this->get_edit_link( array(
					'post_status' => 'all'
				), esc_html__( 'All', 'themify' ), $this->check_current_link('all') );

				$views['publish'] = $this->get_edit_link( array(
					'post_status' => 'publish'
				), esc_html__( 'Published', 'themify' ), $this->check_current_link('publish') );

				if ( isset( $views['draft'] ) ) {
					$views['draft'] = $this->get_edit_link( array(
						'post_status' => 'draft'
					), esc_html__( 'Draft', 'themify' ), $this->check_current_link('draft'));
				}

				if ( isset( $views['trash'] ) ) {
					$views['trash'] = $this->get_edit_link( array(
						'post_status' => 'trash'
					), esc_html__( 'Trash', 'themify' ), $this->check_current_link('trash'));
				}

				$views = array_merge( array(
					'active' => $this->get_edit_link( array( 'post_status' => 'active' ), esc_html__( 'Active', 'themify' ), $this->check_current_link('active') ) 
				), $views );
			break;
		}

		return $views;
	}

	protected function get_edit_link( $args, $label, $class = '' ) {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'active';
		$args['post_type'] = self::$post_type;
		$args['tab'] = $tab;
		$url = add_query_arg( $args, 'edit.php' );

		$class_html = $aria_current = '';
		if ( ! empty( $class ) ) {
			$class_html = sprintf(
				' class="%s"',
				esc_attr( $class )
			);

			if ( 'current' === $class ) {
				$aria_current = ' aria-current="page"';
			}
		}

		return sprintf(
			'<a href="%s"%s%s>%s</a>',
			esc_url( $url ),
			$class_html,
			$aria_current,
			$label
		);
	}

	private function check_current_link( $check ) {
		$status = isset( $_GET['post_status'] ) ? $_GET['post_status'] : 'active';
		return $status === $check ?'current':'';
	}
	
	public static function exclude_post_type($post_types){
	    unset($post_types[self::$post_type]);
	    $post_types[self::$post_type] = self::$post_type;
	    return $post_types;
	}
	
	/**
	 * Add custom link actions in templates rows bulk action
	 *
	 * @access public
	 * @param array $actions
	 * @return array
	 */
	public function row_bulk_actions( $actions ) {
		$actions['tbp-template-bulk-export'] = __( 'Export', 'themify');
		return $actions;
	}
	
	/**
	 * Import Templates.
	 *
	 * @access public
	 */
	public function import_templates() {
		$imgid = $_POST['imgid'];
		! empty( $_POST[ '_ajax_nonce' ] ) && check_ajax_referer($imgid . 'themify-plupload');
		/** Handle file upload storing file|url|type. @var Array */
		$file = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true, 'action' =>self::$post_type.'_plupload'));
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
			$isZip= 'zip' === $ext[1] || 'rar' === $ext[1];
			if( $isZip===true ) {
				unzip_file($file['file'], $base_path);
				if( $wp_filesystem->exists( $base_path . 'export_file.txt' ) ) {
					$data = $wp_filesystem->get_contents( $base_path . 'export_file.txt' );
				} 
			} elseif( $wp_filesystem->exists( $file['file'] ) ){
			    $data = $wp_filesystem->get_contents( $file['file'] );
			}
			if($data){
			    $data = is_serialized($data) ? maybe_unserialize($data) : json_decode($data,true);
			    $msg = $this->set_data( $data );
			    $data=null;
			    if($msg){
				$file['error'] = $msg;
			    }
			    elseif($isZip===true){
				// Check for importing attached GS data
				$gs_path = $base_path . 'builder_gs_data_export.txt';
				if($wp_filesystem->exists($gs_path)){
				    $gs_data = $wp_filesystem->get_contents($gs_path);
				    $gs_data = is_serialized($gs_data) ? maybe_unserialize($gs_data) : json_decode($gs_data);
				    Themify_Global_Styles::builder_import($gs_data);
				    $wp_filesystem->delete($gs_path);
				}
			    }
			    $wp_filesystem->delete($file['file']);
			    if($isZip===true){
				$wp_filesystem->delete($base_path . 'export_file.txt');
			    }
			}
			else{
			    $file['error'] = __('Data could not be loaded', 'themify');
			}
		}
		$file['type'] = $ext[1];
		// send the uploaded file url in response
		echo json_encode($file);
		exit;
	}
	
	
	private function set_data($data){
		$error = false;
		if(!isset($data['import']) || !isset($data['content']) || !is_array($data['content'])){
			$error = __('Incorrect Import File', 'themify');
		} else {
			if($data['import'] !== 'template'){
				$error = __('Failed to import. Unknown data.', 'themify');
			}
			if(!$error){
			    global $ThemifyBuilder_Data_Manager;
			    foreach($data['content'] as $post_data){
				unset($post_data['is_draft']);
				$return = self::save($post_data,null);
				if($return && !empty($post_data['builder_data'])){
				    if(is_string($post_data['builder_data'])){
					$post_data['builder_data']=json_decode($post_data['builder_data'],true);
				    }
				    $ThemifyBuilder_Data_Manager->save_data( $post_data['builder_data'], $return['id'] );
				}
			    }
			}
		}
		return $error;
	}
	
	/**
	 * Export Templates.
	 *
	 * @access public
	 */
	public static function export_row() {
		if ( isset( $_GET['action'] ) && 'tbp_template_export' === $_GET['action'] && wp_verify_nonce($_GET['_wpnonce'], 'tbp_template_export') ) {
			$postid = array((int) $_GET['post']);
			if(!self::export_row_bulk('', 'tbp-template-bulk-export' , $postid))
				wp_redirect( admin_url( 'edit.php?post_type='.self::$post_type ) );
			exit;
		}
	}
	/**
	 * Export Templates.
	 *
	 * @access public
	 */
	public static function export_row_bulk( $redirect_to, $action, $pIds ){
		if ( $action !== 'tbp-template-bulk-export' || empty( $pIds ) ) {
			return $redirect_to;
		}
		$data = array( 'content' => array(), 'import' => 'template' );
		$usedGS = array();
		global $ThemifyBuilder;  
		foreach ( $pIds as $pId ) {
			$meta = array(
				'tbp_template_name' => get_the_title( $pId ),
				'builder_data' => Themify_Builder_Import_Export::prepare_builder_data($ThemifyBuilder->get_builder_data($pId))
			);
			$meta = self::filter_export_template_data($meta,$pId);
			$data['content'][] = $meta;
			// Check for attached GS
			$usedGS+= Themify_Global_Styles::used_global_styles($pId);
		}
		if ( !function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;
		if ( class_exists( 'ZipArchive' ) ) {
			$datafile = 'export_file.txt';
			$wp_filesystem->put_contents( $datafile, json_encode( $data ) );
			$files_to_zip = array( $datafile );
			// Export used global styles
			if(!empty($usedGS)){
				foreach ($usedGS as $gsID=>$gsPost){
					unset($usedGS[$gsID]['id'],$usedGS[$gsID]['url']);
					$styling = Themify_Builder_Import_Export::prepare_builder_data($gsPost['data']);
					$styling = $styling[0];
					if($gsPost['type'] === 'row'){
						$styling = $styling['styling'];
					}elseif($gsPost['type'] === 'column'){
						$styling = $styling['cols'][0]['styling'];
					}else{
						$styling = $styling['cols'][0]['modules'][0]['mod_settings'];
					}
					$usedGS[$gsID]['data'] = $styling;
				}
				$gs_data = json_encode($usedGS);
				$gs_datafile = 'builder_gs_data_export.txt';
				$wp_filesystem->put_contents($gs_datafile, $gs_data, FS_CHMOD_FILE);
				$files_to_zip[] = $gs_datafile;
			}
			$fName='pro_template_'.$meta['tbp_associated_theme'].'_' . date( 'Y_m_d' );
			$file = $fName. '.zip';
			$result = themify_create_zip( $files_to_zip, $file, true );
		}
		if ( isset( $result ) && $result ) {
			if ( ( isset( $file ) ) && ( $wp_filesystem->exists( $file ) ) ) {
				ob_start();
				header( 'Pragma: public' );
				header( 'Expires: 0' );
				header( 'Content-type: application/force-download' );
				header( 'Content-Disposition: attachment; filename="' . $file . '"' );
				header( 'Content-Transfer-Encoding: Binary' );
				header( 'Content-length: ' . filesize( $file ) );
				header( 'Connection: close' );
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
			header( 'Content-Type: application/force-download' );
			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Cache-Control: private', false );
			header( 'Content-Disposition: attachment; filename="' . $fName . '.txt"' );
			header( 'Content-Transfer-Encoding: binary' );
			ob_clean();
			flush();
			echo json_encode( $data );
			exit();
		}
		return false;
	}

	public static function admin_bar($wp_admin_bar){
		$wp_admin_bar->add_node( array(
			'id'    => 'tbp_view_template',
			'title' => __('View Template','themify'),
			'href'  => get_post_permalink($_GET['post']),
		) );
    }
}