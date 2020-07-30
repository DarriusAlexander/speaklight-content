<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 * @author     Themify <themify@themify.me>
 */
class Tbp_Admin {


	public $theme;

	public $template;
	
	public static $currentPage=null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {
		
		$is_ajax = Tbp_Utils::isAjax() || Tbp_Utils::isRest();
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 11 );
		if(isset($_REQUEST['post_type']) && $_REQUEST['post_type']===Tbp_Templates::$post_type){
		    self::$currentPage=Tbp_Templates::$post_type;
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page']===Tbp_Themes::$post_type){
		    self::$currentPage=Tbp_Themes::$post_type;
		}
		if(self::$currentPage!==null && $is_ajax===false){
		    add_action( 'admin_init', array( __CLASS__, 'register_scripts' ) );
		    add_action( 'admin_footer', array( __CLASS__, 'enqueue_scripts' ) );
		}
		add_filter('themify_module_categories', array('Tbp_Utils', 'module_categories'));
		if ( self::$currentPage == Tbp_Templates::$post_type || $is_ajax === true || ( isset($_REQUEST['post']) && 'post.php' === $GLOBALS['pagenow'] && Tbp_Templates::$post_type === get_post_type( $_REQUEST['post'] ) ) ) {
		    new Tbp_Templates();
		    add_action( 'admin_footer', array( __CLASS__, 'enqueue_scripts' ) );
		}
		if(self::$currentPage===Tbp_Themes::$post_type || $is_ajax===true){
		    new Tbp_Themes();
		    if($is_ajax===true){
			add_filter('themify_load_predesigned_templates',array('Tbp_Utils','load_predesigned_templates'),10);
		    }
		}
		add_filter('themify_builder_ajax_admin_vars',array('Tbp_Utils','localize_predesigned_templates'));
		add_action('themify_builder_admin_enqueue',array(__CLASS__,'load_js'));
	}
	
	public static function register_scripts(){
	    $plugin = Tbp::get_instance();
	    wp_register_script( $plugin->get_plugin_name().'-admin', themify_enque(TBP_URL. 'admin/js/tbp-admin.js'), array( 'jquery'), $plugin->get_version(), true );
		wp_localize_script( $plugin->get_plugin_name().'-admin', 'tbpAdminVars', array(
			'i18n' => array(
				'import' => __( 'Import Demo Content', 'tbp' ),
				'import_warning' => __( 'Warning: this will import the demo posts, pages, menus, etc. as per our demo. It may take a few minutes. You can erase demo on Pro Themes > Theme > Theme Details.', 'tbp' ),
			)
		) );
	}
	
	public static function load_js(){
	    $instance = Tbp::get_instance();
	    $plugin_name = $instance->get_plugin_name();
	    $v=$instance->get_version();
	    wp_enqueue_style($plugin_name.'-active', themify_enque(TBP_URL . 'admin/css/tbp-active.css'), null, $v, 'all');
	    wp_enqueue_script($plugin_name.'-types', themify_enque(TBP_URL . 'admin/js/tbp-active.js'), null, $v, true);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {
		
		/* do not load assets in the Customizer */
		if (is_customize_preview() ) {
			return;
		}
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tbp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tbp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		$plugin = Tbp::get_instance();
		$name=$plugin->get_plugin_name();
		$v = $plugin->get_version();
		if(current_user_can('upload_files') ) {
			wp_enqueue_media();
		}
		if(false === wp_script_is( 'themify-metabox','enqueued' )){
			wp_enqueue_style( 'themify-metabox' );
			wp_enqueue_script( 'themify-metabox' );
			wp_enqueue_script( 'themify-plupload' );
			do_action( 'themify_metabox_enqueue_assets' );
		}
		wp_enqueue_style( $name, themify_enque(TBP_URL . 'admin/css/tbp-admin.css'), array(), $v, 'all' );
		
		if ( ! wp_style_is( 'themify-icons' ) ) {
			wp_enqueue_style( 'themify-icons', themify_enque(THEMIFY_URI . '/themify-icons/themify-icons.css'), array(), THEMIFY_VERSION );
		}
		
		wp_enqueue_script('themify-main-script', themify_enque(THEMIFY_URI . '/js/main.js'), null, THEMIFY_VERSION, true);
		wp_enqueue_script($name.'-admin');
		$button=null;
		if(current_user_can( 'manage_options' )){
		    wp_enqueue_script( 'themify-plupload' );
		    $button = themify_get_uploader('tbp-themes-import', array(
				    'label'		=> __('Import', 'themify'),
				    'preset'	=> false,
				    'preview'   => false,
				    'tomedia'	=> false,
				    'topost'	=> '',
				    'fields'	=> '',
				    'featured'	=> '',
				    'message'	=> '',
				    'fallback'	=> '',
				    'dragfiles' => false,
				    'confirm'	=> false,
				    'medialib'	=> false,
				    'formats'	=> 'zip,txt',
				    'type'		=> '',
				    'action'    => self::$currentPage.'_plupload',
			    )
		    );
		}
		$labels = Themify_Builder::get_i18n();
		$labels['label']['browse_image'] = __('Add Image','themify');
		$ph_image = 'tbp_theme' === self::$currentPage ? 'theme' : 'template';
		wp_localize_script('themify-main-script', 'themifyBuilder', array(
		    'ajaxurl' => admin_url('admin-ajax.php'),
		    'includes_url' => includes_url(),
		    'meta_url' => THEMIFY_METABOX_URI,
		    'tb_load_nonce' => wp_create_nonce('tb_load_nonce'),
		    'import_nonce' => wp_create_nonce('themify_builder_import_filethemify-builder-plupload'),
		    'constructorUrl'=>themify_enque(THEMIFY_BUILDER_URI . '/js/themify-constructor.js'),
		    'builderToolbarUrl'=>themify_enque(THEMIFY_BUILDER_URI . '/css/toolbar.css'),
		    'builderCombineUrl'=>themify_enque(THEMIFY_BUILDER_URI . '/css/combine.css'),
		    'v'=>THEMIFY_VERSION,
		    'import_btn'=>$button,
		    'pageId'=>self::$currentPage,
		    'ph_image'=> TBP_URL  . '/admin/img/'.$ph_image.'-placeholder.png',
		    'labels'=>$labels['label']
		));
		$labels=null;
		wp_enqueue_script('themify-builder-simple-bar-js', THEMIFY_BUILDER_URI . '/js/simplebar.min.js', null,THEMIFY_VERSION, true);
		include( TBP_DIR . 'admin/partials/lightbox-tpl.php' );

		// Init Pointers
		new TBP_Pointers();
	}
	
	public function register_admin_menu() {
		    global $submenu;
			$menu_id = themify_is_themify_theme() ? 'themify' : 'themify-builder';
			if(empty($submenu[$menu_id])){
				return;
			}
			$label = '<span class="update-plugins"><span class="plugin-count" aria-hidden="true">PRO</span></span>';
		    add_submenu_page( $menu_id, esc_html__( 'Themes ', 'themify' ), sprintf(__( '%s Themes', 'themify' ),$label), 'edit_posts', Tbp_Themes::$post_type , array( 'Tbp_Themes', 'render_page' ) );
		    end($submenu[$menu_id]);
		    Tbp_Utils::move_array_index( $submenu[$menu_id], key($submenu[$menu_id]), 1 );
		    add_submenu_page( $menu_id, esc_html__( 'Templates', 'themify' ), sprintf(__( '%s Templates', 'themify' ),$label), 'edit_posts', 'edit.php?post_type='.Tbp_Templates::$post_type );
		    end($submenu[$menu_id]);
		    Tbp_Utils::move_array_index( $submenu[$menu_id], key($submenu[$menu_id]), 2 );
	}

}
