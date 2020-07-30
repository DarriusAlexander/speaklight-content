<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tbp
 * @subpackage Tbp/includes
 * @author     Themify <themify@themify.me>
 */
class Tbp {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	private $plugin_name;
	
	
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	private $version;

	public $active_theme;

    /**
     * Creates or returns an instance of this class.
     *
     * @return	A single instance of this class.
     */
    public static function get_instance() {
	    static $instance = null;
	   
	    if ( $instance === null ) {
		$instance = new self;
	    }
	    return $instance;
    }

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->version = current(get_file_data( TBP_DIR.'themify-builder-pro.php', array( 'Version') ));
		$this->plugin_name = 'tbp';
		
		$this->register_module();
		add_action('wp_loaded',array($this,'init'));
	}

	/**
	 * Set up plugin's action hooks
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->load_dependencies();
		$this->load_active_theme();
		$is_ajax = Tbp_Utils::isAjax();
		$is_admin = $is_ajax===true || is_admin();
		if($is_ajax===true || $is_admin===true || Tbp_Utils::isRest()){
		    $this->define_admin_hooks();
		}
		if($is_ajax===true || $is_admin===false){
		    $this->define_public_hooks();
		}	
	}

	public function register_module() {
		Themify_Builder_Model::register_directory( 'templates', TBP_DIR . 'templates' );
		Themify_Builder_Model::register_directory( 'modules', TBP_DIR . 'modules' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tbp_Loader. Orchestrates the hooks of the plugin.
	 * - Tbp_i18n. Defines internationalization functionality.
	 * - Tbp_Admin. Defines all hooks for the admin area.
	 * - Tbp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */

		/**
		 * The class responsible for various functions.
		 */
		require_once TBP_DIR. 'includes/class-tbp-utils.php';
		
		
		/**
		 * Handles Dynamic Content feature.
		 */
		require_once TBP_DIR. 'includes/class-tbp-dynamic-content.php';

		require_once TBP_DIR. 'includes/class-tbp-dynamic-query.php';

		require_once TBP_DIR. 'includes/tbp-maps-pro-integration.php';

		if(1 || is_admin()){
		    if(is_admin()){
			/**
			 * The class responsible for pointer functions.
			 */
			require_once TBP_DIR. 'admin/class-tbp-import-demo.php';
			Tbp_Import_Demo::get_instance();
			require_once TBP_DIR. 'admin/class-tbp-pointers.php';
		    }
		    /**
		     * The class responsible for themes functions.
		     */
		    require_once TBP_DIR. 'includes/class-tbp-themes.php';

		    /**
		     * The class responsible for templates functions.
		     */
		    require_once TBP_DIR. 'includes/class-tbp-templates.php';
		    /**
		     * The class responsible for defining all actions that occur in the admin area.
		     */
		    require_once TBP_DIR. 'admin/class-tbp-admin.php';
		}
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once TBP_DIR. 'public/class-tbp-public.php';

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		new Tbp_Admin();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		Tbp_Public::get_instance();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	public function load_active_theme() {
		self::register_cpt();
		$theme = Tbp_Utils::get_active_theme();

		if ( $theme ) {
			$this->active_theme = $theme;
		} else {
			$theme = new stdClass();
			$theme->post_name = '';
			$this->active_theme = $theme;
		}
	}

	
	
	public static function register_cpt(){
	    register_post_type('tbp_theme',
		    apply_filters( 'tbp_register_post_type_tbp_theme', array(
			    'labels' => array(
				    'name'               => __( 'Themes', 'themify' ),
				    'singular_name'      => __( 'Theme', 'themify' ),
				    'menu_name'          => _x( 'Themes', 'admin menu', 'themify' ),
				    'name_admin_bar'     => _x( 'Theme', 'add new on admin bar', 'themify' ),
				    'add_new'            => _x( 'Add New', 'theme', 'themify' ),
				    'add_new_item'       => __( 'Add New Theme', 'themify' ),
				    'new_item'           => __( 'New Theme', 'themify' ),
				    'edit_item'          => __( 'Edit Theme', 'themify' ),
				    'view_item'          => __( 'View Theme', 'themify' ),
				    'all_items'          => __( 'All Themes', 'themify' ),
				    'search_items'       => __( 'Search Themes', 'themify' ),
				    'parent_item_colon'  => __( 'Parent Themes:', 'themify' ),
				    'not_found'          => __( 'No themes found.', 'themify' ),
				    'not_found_in_trash' => __( 'No themes found in Trash.', 'themify' )
			    ),
			    'public'              => false,
			    'exclude_from_search' => true,
			    'publicly_queryable'  => false,
			    'show_ui'             => true,
			    'show_in_menu'        => false,
			    'query_var'           => true,
			    'rewrite'             => array( 'slug' => 'tbp-theme' ),
			    'capability_type'     => 'post',
			    'has_archive'         => true,
			    'hierarchical'        => false,
			    'menu_position'       => null,
			    'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' ),
			    'can_export'          => true,
			    'show_in_rest'		  => true
		    ))
	    );

	    register_post_type( 'tbp_template',
		    apply_filters( 'tbp_register_post_type_tbp_template', array(
			    'labels' => array(
				    'name'               => __( 'Templates', 'themify' ),
				    'singular_name'      => __( 'Template', 'themify' ),
				    'menu_name'          => _x( 'Templates', 'admin menu', 'themify' ),
				    'name_admin_bar'     => _x( 'Template', 'add new on admin bar', 'themify' ),
				    'add_new'            => _x( 'Add New', 'template', 'themify' ),
				    'add_new_item'       => __( 'Add New Template', 'themify' ),
				    'new_item'           => __( 'New Template', 'themify' ),
				    'edit_item'          => __( 'Edit Template', 'themify' ),
				    'view_item'          => __( 'View Template', 'themify' ),
				    'all_items'          => __( 'All Templates', 'themify' ),
				    'search_items'       => __( 'Search Templates', 'themify' ),
				    'parent_item_colon'  => __( 'Parent Templates:', 'themify' ),
				    'not_found'          => __( 'No templates found.', 'themify' ),
				    'not_found_in_trash' => __( 'No templates found in Trash.', 'themify' )
			    ),
			    'public'              => false,
			    'exclude_from_search' => true,
			    'publicly_queryable'  => current_user_can( 'manage_options' ),
			    'show_ui'             => true,
			    'show_in_menu'        => false,
			    'show_in_admin_bar'   => true,
			    'query_var'           => true,
			    'rewrite'             => array( 'slug' => 'tbp-template' ),
			    'capability_type'     => 'post',
			    'has_archive'         => false,
			    'hierarchical'        => false,
			    'menu_position'       => null,
			    'supports'            => array( 'title', 'thumbnail','revisions' ),
			    'can_export'          => true,
			    'show_in_rest'		  => true
		    ))
	    );
	}

}
