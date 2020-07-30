<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tbp
 * @subpackage Tbp/public
 * @author     Themify <themify@themify.me>
 */
class Tbp_Public {

    private static $_locations = array();
    private static $taxonomies = array();
    public static $is_page = false;
    public static $is_archive = false;
    public static $is_single = false;
    public static $is_singular = false;
    public static $is_404 = false;
    public static $is_front_page = false;
    public static $is_home = false;
    public static $is_attachemnt = false;
    public static $is_search = false;
    public static $is_category = false;
    public static $is_tag = false;
    public static $is_author = false;
    public static $is_date = false;
    public static $is_tax = false;
    public static $is_post_type_archive = false;
    private static $currentQuery = null;
    private static $originalFile = null;
    public static $isTemplatePage = false;
    public static $hasShopTemplate = false;

    /**
     * Creates or returns an instance of this class.
     *
     * @return	A single instance of this class.
     */
    public static function get_instance() {
	static $instance = null;
	if ($instance === null) {
	    $instance = new self;
	}
	return $instance;
    }

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct() {
	add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'),9);
	add_action('template_include', array(__CLASS__, 'template_include'), 15);
	add_action('tbp_render_the_content', array(__CLASS__, 'render_content_page'));
	add_action('template_redirect', array(__CLASS__, 'set_rules'));
	add_action('pre_get_posts', array(__CLASS__, 'set_archive_per_page'));
	if (themify_is_woocommerce_active()) {
	    // Adding cart icon and shopdock markup to the woocommerce fragments
	    add_filter('woocommerce_add_to_cart_fragments', array(__CLASS__, 'tbp_add_to_cart_fragments'));
	}
	if(Themify_Builder_Model::is_frontend_editor_page()){
	    add_filter('themify_module_categories', array('Tbp_Utils', 'module_categories'));
	    add_filter('themify_builder_ajax_front_vars', array('Tbp_Utils', 'localize_predesigned_templates'));
	    add_filter('themify_load_predesigned_templates', array('Tbp_Utils', 'load_predesigned_templates'), 10);
	    add_filter('themify_builder_admin_bar_is_available', array(__CLASS__, 'is_available'));
	}
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public static function enqueue_scripts() {

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
	$instance = Tbp::get_instance();
	$plugin_name = $instance->get_plugin_name();
	$v = $instance->get_version();
	self::enque_styles($plugin_name, TBP_URL . 'public/css/tbp-style.css', null, $v, 'all');
	wp_enqueue_script($plugin_name, themify_enque(TBP_URL . 'public/js/tbp-script.js'), array('themify-main-script'), $v, true);

	if (themify_is_woocommerce_active()) {
	    self::enque_styles($plugin_name . '-woo', TBP_URL . 'public/css/tbp-woocommerce.css', null, $v, 'all');
	}
	foreach(self::$_locations as $loc){
		Themify_Builder_Stylesheet::enqueue_stylesheet(false,$loc);
	}
	if(Themify_Builder_Model::is_front_builder_activate()){
	    wp_enqueue_script($plugin_name.'-types', themify_enque(TBP_URL . 'admin/js/tbp-active.js'), array('themify-builder-common-js'), $v, true);
	    $data = array(
			'edit'=>__('Edit Template','themify'),
			'cssUrl'=>themify_enque(TBP_URL . 'admin/css/tbp-active.css'),
			'v'=>$v,
			'isArchive'=>self::$is_archive
	    );
	    if(self::$isTemplatePage===false){
		$type=$id=null;
		$query_object = self::$currentQuery;
		if(self::$is_archive===true){
		    if(self::$is_post_type_archive===true){
			$id=$query_object->name;
			$type='archive';
		    }
		    elseif(self::$is_search===true){
			$id=get_search_query();
			$type='search';
		    }
		    elseif(!empty($query_object)){
			$type=$query_object->taxonomy;
			$id=$query_object->term_id;
		    }
		}
		elseif(self::$is_singular===true){
		    $id=$query_object->ID;
		    $type=$query_object->post_type;
		}
		elseif(self::$is_404===true){
		    $type='404';
		    $id=Themify_Builder::$builder_active_id;
		}
		elseif(self::$is_author===true){
		    $type='author';
		    $id=  get_the_author_meta('ID');
		}
		if(!empty($id)){
		    $data['id']=$id;
		    $data['type']=$type;
		}
	    }
	    wp_localize_script($plugin_name.'-types', 'tbp_local', $data);
	    add_filter('tb_toolbar_module',array('Tbp_Public','add_class'));
	}
    }

    public static function get_header($name) {
	remove_action('get_header', array(__CLASS__, 'get_header'),1,1);
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	    <head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php if (!current_theme_supports('title-tag')) : ?>
	    	<title>
			<?php echo wp_get_document_title(); ?>
	    	</title>
		<?php endif; ?>
		<?php wp_head(); ?>
	    </head>
	    <body <?php body_class(); ?>>
		<?php
		themify_body_start();
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		}
		themify_header_before();
		self::render_location('header');
		themify_header_after();
		remove_all_actions('wp_head');
		$templates = array();
		$name = (string) $name;
		if ('' !== $name) {
		    $templates[] = "header-{$name}.php";
		}
		$templates[] = 'header.php';
		ob_start();
		locate_template($templates, true);
		ob_get_clean();
	    }

    public static function get_footer($name) {
		remove_action('get_footer', array(__CLASS__, 'get_footer'),1,1);
		themify_footer_before();
		self::render_location('footer');
		themify_footer_after();
		wp_footer();
		themify_body_end(); 
		?>
	    </body>
	</html>
	<?php
	remove_all_actions('wp_footer');
	$templates = array();
	$name = (string) $name;
	if ('' !== $name) {
	    $templates[] = "footer-{$name}.php";
	}
	$templates[] = 'footer.php';
	ob_start();
	locate_template($templates, true);
	ob_get_clean();
    }

    private static function render_template($post_id, $location) {
	if ( $template = self::get_template_post( $post_id ) ) {
	    global $ThemifyBuilder;
	    $tag = $location === 'header' || $location === 'footer' ? $location : 'div';
	    $id = $tag === 'div' ? 'content' : $location;
		$classes = array( 'tbp_template' );

		if ( $location === 'product_single' ) {
			$classes[] = 'product';
			if (function_exists('woocommerce_output_all_notices')) {
				echo '<div class="pagewidth">';
				woocommerce_output_all_notices();
				echo '</div>';
			}
		}

	    do_action('tbp_before_render_builder', $template->ID, $location);
	    $title = $template->post_title;
	    if($location==='archive' || $location==='product_archive'){
		Tbp_Utils::disable_ptb_loop();
	    }
	    echo sprintf('<!-- Builder Pro Template Start: %s -->', $title), '<' . $tag . ' id="tbp_' . $id . '" class="' . join( ' ', $classes ) . '">',
		$ThemifyBuilder->get_builder_output( $template->ID ),
		sprintf('<!-- Builder Pro Template End: %s -->', $title), '</' . $tag . '>';
	    do_action('tbp_after_render_builder', $template->ID, $location);
	}
    }

	public static function get_template_post( $post_id ) {
		if ( $template = get_post( $post_id ) ) {

			/* get WPML's translated template */
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				$translated_template_id = apply_filters( 'wpml_object_id', $post_id, $template->post_type, true );
				if ( $translated_template = get_post( $translated_template_id ) ) {
					return $translated_template;
				}
			}

			return $template;
		}

		return false;
	}

    public static function render_location($location) {
	if (isset(self::$_locations[$location])) {
	    self::render_template(self::$_locations[$location], $location);
	}
    }

    private static function collect_display_conditions() {
	$instance = Tbp::get_instance();
	$conditions = array();
	if (isset($instance->active_theme)) {
	    $args = array(
		'post_type' => Tbp_Templates::$post_type,
		'posts_per_page' => 50,
		'order' => 'ASC',
		'ptb_disable'=>true,
		'nopaging' => true,
		'no_found_rows'=>true,
		'ignore_sticky_posts'=>true,
		'meta_query' => array(
		    array(
			'key' => 'tbp_associated_theme',
			'value' => $instance->active_theme->post_name,
		    )
		)
	    );
		$templates = get_posts($args);
	    if ($templates) {
		foreach ($templates as $template) {
		    $condition = Tbp_Utils::get_template_conditions($template->ID);

		    if ($condition) {
			$list_conditions = array();
			foreach ($condition as $c) {
			    $list_conditions[$c['type']][] = $c;
			}

			$conditions[$template->ID] = $list_conditions;
		    }
		}
	    }
	}
	return $conditions;
    }

    private static function set_condition_tags() {


	self::$is_404 = is_404();
	if (self::$is_404 === false) {

	    self::$is_page = is_page();
	    self::$is_attachemnt = self::$is_page === false && is_attachment();
	    self::$is_single = self::$is_page === false && self::$is_attachemnt === false && is_single();
	    self::$is_singular = self::$is_page === true || self::$is_attachemnt === true || self::$is_single === true;

	    if (self::$is_singular === false) {

		self::$is_home = is_home();

		if (self::$is_home === false) {

		    self::$is_category = is_category();

		    if (self::$is_category === false) {

			self::$is_tag = is_tag();

			if (self::$is_tag === false) {

			    self::$is_tax = is_tax();

			    if (self::$is_tax === false) {

				self::$is_search = is_search();

				if (self::$is_search === false) {

				    self::$is_author = is_author();

				    if (self::$is_author === false) {

					self::$is_post_type_archive = is_post_type_archive();

					if (self::$is_post_type_archive === false) {

					    self::$is_date = is_date();
					}
				    }
				}
			    }
			}
		    }
		}
		self::$is_archive = self::$is_category === true || self::$is_tag === true || self::$is_tax === true || self::$is_home === true || self::$is_author === true || self::$is_date === true || self::$is_search === true || self::$is_post_type_archive === true || is_archive();
	    } else {
		self::$isTemplatePage = is_singular(Tbp_Templates::$post_type);
		self::$is_front_page = self::$is_page === true && is_front_page();
	    }
	}
	self::$currentQuery = get_queried_object();
    }

    private static function checking_display_rules() {
	if (!empty(self::$_locations) || (self::$is_archive===false && self::$is_page===false && is_singular('tglobal_style'))) {
	    return;
	}
	self::set_condition_tags();
	if (self::$isTemplatePage === true) {
	    $id=get_the_ID();
	    $template_type = get_post_meta($id, 'tbp_template_type', true);
	    if ($template_type) {
		self::$_locations[$template_type] = $id;
	    }
	    if(($template_type==='product_single' || $template_type==='product_archive') && themify_is_woocommerce_active()){
		add_filter('themify_builder_body_class', array(__CLASS__,'add_wc_to_body'));
	    }
	}
	else{
	    $conditions = self::collect_display_conditions();
	    // Cached the taxonomy lists
	    $tax = Tbp_Utils::get_taxonomies();
	    foreach ($tax as $slug => $v) {
		self::$taxonomies[$slug] = true;
	    }
	    $tax = null;
	    $currentPostType=!empty(self::$currentQuery->post_type)?self::$currentQuery->post_type:null;
		if(self::$is_404===true || self::$is_page===true){
			$currentPostType='page';
		}
	    elseif(self::$is_archive===true && empty($currentPostType)){
		if(self::$is_category === true || self::$is_tag === true || self::$is_tax === true){
			$tax = self::$currentQuery===null?false:get_taxonomy(self::$currentQuery->taxonomy); 
			if($tax===false){// WP doesn't recognized 404 page when taxonomy/term doesn't exist
			    self::$is_404=true;
			    $currentPostType='page';
			    self::$is_archive=self::$is_category=self::$is_tag=self::$is_tax=false;
			}
			else{
			    $currentPostType=$tax->object_type;
			}
		}
		elseif(self::$is_post_type_archive===true){
			$currentPostType = self::$currentQuery->name;
		}
		else{
			$currentPostType='post';
		}
		
	    }	
	    $isArray = is_array($currentPostType);
	    foreach ($conditions as $id => $condition_type) {

		if (isset($condition_type['exclude']) || isset($condition_type['include'])) {
		    $location = get_post_meta($id, 'tbp_template_type', true);
		    if((self::$is_archive===false && ($location==='archive' || $location==='product_archive')) || (self::$is_singular===false && ($location==='single' || $location==='product_single')) || ($location==='page' && self::$is_page===false && self::$is_404===false)){
				continue;
		    }
		    // Include conditions
		    if (isset($condition_type['include'])) {
			foreach ($condition_type['include'] as $condition) {
			    $post_type = Tbp_Utils::get_post_type($location, $condition);

			    if($post_type==='any' || (($isArray===true && self::check_intersect($currentPostType,$post_type)===true)|| ($isArray===false && in_array($currentPostType,$post_type,true)))){
				$view = self::get_condition_settings($id, $location, $condition);
				if ($view!==false && self::is_current_view($view)) {
				    self::$_locations[$location][$id] = $id;
				    if ($location !== 'header' && $location !== 'footer') {
						add_filter('themify_builder_bar_menu_toogle_on_post_id', array(__CLASS__, 'filter_themify_builder_bar_menu'));
				    }
				    break;
				}
			    }
			}
			unset($condition_type['include']);
		    }
		    // Exclude conditions
		    if (isset($condition_type['exclude'])) {
			foreach ($condition_type['exclude'] as $condition) {
			    $post_type = Tbp_Utils::get_post_type($location, $condition);
			    if($post_type==='any' || (($isArray===true && self::check_intersect($currentPostType,$post_type)===true)|| ($isArray===false && in_array($currentPostType,$post_type,true)))){
				$view = self::get_condition_settings($id, $location, $condition);
				if ($view!==false && self::is_current_view($view)) {
				    unset(self::$_locations[$location][$id]);
				    break;
				}
			    }
			}
			unset($condition_type['exclude']);
		    }
		}
	    }
	    // Check if one location has multiple template, then choose first one to display
	    if(!empty(self::$_locations)){
	        foreach (self::$_locations as $location=>$detail){
	            if(is_array(self::$_locations[$location])){
		            self::$_locations[$location]=reset(self::$_locations[$location]);
                }
            }
        }
	}
	if(isset(self::$_locations['product_archive'])){
		unset(self::$_locations['archive']);
	}
	if(isset(self::$_locations['product_single']) || isset(self::$_locations['page'])){
		unset(self::$_locations['single']);
	}

	self::set_location();
    }

    public function filter_themify_builder_bar_menu($id) {
	return get_post($id);
    }

    private static function get_condition_settings($id, $location, $condition) {

	$query = isset($condition['query']) ? $condition['query'] : '';
	$detail = $condition['detail'];
	$general = $condition['general'];
	if ($location === 'header' || $location === 'footer') {
	    $location = $general;
	    $data = $query;
	} else {
	    $data = $general;
	}
	if (($location === 'product_archive' || $location === 'product_single') && !themify_is_woocommerce_active()) {
	    return false;
	}
	$views = array($location => array());
	switch ($location) {
	    case 'general':
		$views[$location]['all'] = 'all';
		break;
	    case 'single':
	    case 'archive':
	    case 'product_archive':
		if ($data === 'all') {
		    $views[$location][$data] = 'all';
		} elseif (($location === 'archive' || $location === 'product_archive') && strpos($data, 'all_') === 0) {
		    $p = str_replace('all_', '', $data);
		    if (post_type_exists($p)) {
			$views[$location][$p] = 'all';
		    }
		} else {
		    $views[$location][$data] = $detail;
		}

		break;

	    default:
		$views[$location][$data] = $detail;

		break;
	}
	return $views;
    }

    private static function set_location() {
	if (self::$isTemplatePage === true || isset(self::$_locations['header'])) {
	    add_action('get_header', array(__CLASS__, 'get_header'),1,1);
	}
	if (self::$isTemplatePage === true || isset(self::$_locations['footer'])) {
	    add_action('get_footer', array(__CLASS__, 'get_footer'),1,1);
	}
    }

    private static function check_intersect($current, $posts_types) {
	foreach ($posts_types as $v) {
	    if (in_array($v, $current, true)) {
		return true;
	    }
	}
	return false;
    }

    private static function is_current_view($view) {
	if (!empty($view)) {
	    $query_object = self::$currentQuery;
	    foreach ($view as $type => $val) {
		switch ($type) {
		    case 'general':
			return true;
			break;
		    case 'page':
			if (self::$is_page === true || self::$is_404 === true) {
				foreach ($val as $k => $v) {
				if ($k === 'is_404') {
				    if (self::$is_404 === true) {
					    return true;
				    }
				} 
				elseif($k==='is_front'){
				    return self::$is_front_page===true;
				}
				elseif (self::$is_page === true) {
				    if ($k === 'child_of') {
					if($query_object->post_parent !== 0){
					    if($v === 'all'){
						    return true;
					    }
					    $parents = get_post_ancestors($query_object);
					    foreach ($parents as $p) {
						$parent = get_post($p);
						if (in_array($parent->post_name, $v, true)) {
							return true;
						}
					    }
					}
				    }
				    elseif ($v === 'all' || in_array($query_object->post_name, $v, true)) {
					return true;
				    }	
				}
			    }
			}
			break;
		    case 'single':
			if (self::$is_singular === true || self::$is_404 === true) {
			    foreach ($val as $k => $v) {
				if ($k === 'all' || ($v === 'all' && post_type_exists($k))) {
				    return true;
				}
				if (self::$is_404 === false) {
				    if (isset(self::$taxonomies[$k])) {
					if (($v === 'all' && has_term('', $k)) || ($v !== 'all' && is_array($v) && has_term($v, $k))) {
					    return true;
					}
				    } elseif ($k === 'is_attachment') {
					if (self::$is_attachemnt === true && ($v === 'all' || in_array($query_object->ID, $v))) {
					    return true;
					}
				    } elseif ($k === 'page' || $k === 'child_of' || $k === 'is_front') {
					if (self::$is_page === true) {
					    return self::is_current_view(array('page' => $val));
					}
				    } elseif (is_singular($k) && post_type_exists($k)) {
					if ($v === 'all' || in_array($query_object->post_name, $v, true)) {
					    return true;
					}
				    }
				} elseif ($k === 'is_404') {
				    return true;
				}
			    }
			}
			break;
		    case 'archive':
			if (self::$is_archive === true) {
			    foreach ($val as $k => $v) {
				if ($k === 'all' || ($v === 'all' && post_type_exists($k))) {
				    return true;
				}
				if (isset(self::$taxonomies[$k])) {
				    if (self::$is_category === true || self::$is_tax === true || self::$is_tag === true) {
					if ($k === $query_object->taxonomy && ($v === 'all' || in_array($query_object->slug, $v, true))) {
					    return true;
					}
				    }
				} elseif ($k === 'is_date' || $k === 'is_search') {
				    if ((self::$is_date === true && $k === 'is_date') || (self::$is_search === true && $k === 'is_search')) {
					return true;
				    }
				} elseif ($k === 'is_author') {
				    if (self::$is_author === true) {
					if ($v === 'all') {
					    return true;
					}
					$author = get_user_by('slug', get_query_var('author_name'));
					if (!empty($author) && in_array($author->ID, $v)) {
					    return true;
					}
				    }
				}
			    }
			}
			break;
		    case 'product_single':
			if (self::$is_singular === true && themify_is_woocommerce_active() && is_product()) {
			    foreach ($val as $k => $v) {
				if (isset(self::$taxonomies[$k])) {
				    if (($v === 'all') || ($v !== 'all' && is_array($v) && has_term($v, $k))) {
					return true;
				    }
				} elseif ($v === 'all' || in_array($query_object->post_name, $v, true)) {
				    return true;
				}
			    }
			}
			break;
		    case 'product_archive':
			if (self::$is_archive === true && themify_is_woocommerce_active() && (is_product_category() || is_product_tag() || is_shop())) {
			    foreach ($val as $k => $v) {
                    if ($k === 'shop') {
                        if(is_shop() === true){
							self::$hasShopTemplate = true;
                            return true;
                        }
                    }elseif ($v === 'all' && !self::$hasShopTemplate) {
                        return true;
                    }elseif (isset(self::$taxonomies[$k]) && ($v === 'all' || in_array($query_object->slug, $v, true))) {
                        return true;
                    }
			    }
			}
			break;
		}
	    }
	}
	return false;
    }

    public static function get_location($location = null) {
	return $location === NULL ? self::$_locations : (isset(self::$_locations[$location]) ? self::$_locations[$location] : null);
    }

    public static function template_include($template) {
	if(self::$is_404===true && Themify_Builder_Model::is_front_builder_activate()){
	    status_header(200);
	}
	self::$originalFile = $template;
	if (empty(self::$_locations)) {
	    return $template;
	}

	$template_layout_name = 'tbp-public-template.php';
	$template = locate_template(array(
	    $template_layout_name
	));
	if (!$template) {
	    $template = TBP_DIR . 'public/partials/' . $template_layout_name;
	}
	return $template;
    }

    public static function render_content_page() {
	$location = '';
	if (!empty(self::$_locations)) {
	    $items = self::$_locations;
	    unset($items['header'], $items['footer']);
	    if (!empty($items)) {
		$location = key($items);
	    }
	}
	if ('' === $location) {
	    if ($location !== 'header' && $location !== 'footer') {
		if (self::$is_singular !== true || self::$isTemplatePage === false) {
		    $is_theme = themify_is_themify_theme();
		    if ($is_theme === true) {
			echo '<div id="pagewrap" class="hfeed site"><div id="body" class="clearfix">';
		    }

		    load_template(self::$originalFile);

		    if ($is_theme === true) {
			echo '</div></div>';
		    }
		}
	    }
	} else {
	    self::render_location($location);
	}
    }

    /**
     * Fix number of posts displayed in archive pages according to template options
     * Required for the Archive Post module
     *
     * @since 1.0
     */
    public static function set_archive_per_page($query) {
	if ($query->is_main_query() && $query->is_archive()) {
	    /* populate self::$_locations before "template_redirect" hook */
	    self::set_rules();
	    $archive_template = self::get_location('archive');
	    if (empty($archive_template)) {
		$archive_template = self::get_location('product_archive');
	    }
	    if (!empty($archive_template)) {
		$query->set('posts_per_page', 1);
	    }
	}
    }

    public static function set_rules() {
	remove_action('pre_get_posts', array(__CLASS__, 'set_archive_per_page'));
	remove_action('template_redirect', array(__CLASS__, 'set_rules'));
	self::checking_display_rules();
    }

    /**
     * Add cart total and shopdock cart to the WC Fragments
     * @param array $fragments
     * @return array
     */
    public static function tbp_add_to_cart_fragments($fragments) {
	$fragments['.tbp_shopdock'] = Themify_Builder_Component_Base::retrieve_template('wc/shopdock.php', array(), '', '', false);
	$total = WC()->cart->get_cart_contents_count();
	$cl = $total > 0 ? 'tbp_cart_count' : 'tbp_cart_count tbp_cart_empty';
	$fragments['.tbp_cart_icon_container .tbp_cart_count'] = sprintf('<span class="%s">%s</span>', $cl, $total);
	return $fragments;
    }

    public static function add_wc_to_body($cl) {
	$cl[] = 'woocommerce woocommerce-page';
	if (isset(self::$_locations['product_single'])) {
		if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
			wp_enqueue_script('zoom');
		}
		if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
			wp_enqueue_script('flexslider');
		}
		if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
			wp_enqueue_script('photoswipe-ui-default');
			wp_enqueue_style('photoswipe-default-skin');
			add_action( 'wp_footer', 'woocommerce_photoswipe' );
		}
	    wp_enqueue_script('wc-single-product');
	}
	return $cl;
    }
    
    public static function is_available($isAvailable){
	remove_filter('themify_builder_admin_bar_is_available', array(__CLASS__, 'is_available'));
	add_filter('themify_builder_admin_bar_menu', array(__CLASS__, 'add_to_admin_bar'),10,2);
	return true;
    }

    public static function add_to_admin_bar($args,$isAvailable) {
	remove_filter('themify_builder_admin_bar_menu', array(__CLASS__, 'add_to_admin_bar'),10,2);
	if(self::$isTemplatePage===FALSE && !empty(self::$_locations)){
	    $pid = Tbp_Templates::$post_type.'-dropdown';
	    $args[] = array('parent' => 'themify_builder', 'title' => __('Edit Templates', 'themify'), 'id' => $pid, 'href' => '#', 'meta' => array('class' => 'tbp_admin_bar_templates'));
	    //out by order header, condition archive,footer
	    $locations = array();
	    $_locations = self::$_locations;
	    unset($_locations['header'],$_locations['footer']);
	    if(isset(self::$_locations['header'])){
		$locations[]=self::$_locations['header'];
	    }
	    if(!empty($_locations)){
		$locations[]=current($_locations);
	    }
	    if(isset(self::$_locations['footer'])){
		$locations[]=self::$_locations['footer'];
	    }
	    foreach ( $locations as $v ) {
			if ( $template_post = self::get_template_post( $v ) ) {
				$title = '<span data-id="' . $template_post->ID . '"></span>'.get_the_title( $template_post->ID );
				$args[] = array('parent' => $pid, 'id' => $template_post->ID,  'title'=>'<a href="#" class="js-turn-on-builder">'.$title.'</a>');
			}
	    }
	    $locations=$_locations=null;
	}
	$args[] = array('parent' => 'themify_builder', 'title' => __('Pro Themes', 'themify'), 'id' => Tbp_Themes::$post_type, 'href' => admin_url('admin.php?page=' . Tbp_Themes::$post_type), 'meta' => array('class' => 'tbp_admin_bar', 'target' => '_self'));
	$args[] = array('parent' => 'themify_builder', 'title' => __('Pro Templates', 'themify'), 'id' => Tbp_Templates::$post_type, 'href' => admin_url('edit.php?post_type=' . Tbp_Templates::$post_type), 'meta' => array('class' => 'tbp_admin_bar', 'target' => '_self'));
	return $args;
    }
    
    
    public static function add_class($cl){
	if(Themify_Builder::$builder_active_id){
	    $cl.=' tbp_edit_'.get_post_meta(Themify_Builder::$builder_active_id, 'tbp_template_type', true);
	}
	return $cl;
    }
    
    /*Temprorary code 21.09.19*/
    private static function  enque_styles($handle, $src = '', $deps = array(), $ver = false, $media = 'all'){
	if ( function_exists( 'themify_enque_style' ) ){
	    themify_enque_style($handle, $src, $deps, $ver, $media);
	}
	else{
	    wp_enqueue_style($handle, themify_enque($src), $deps, $ver, $media);
	}
    }
}
