<?php

defined('ADDON_LIBRARY_INC') or die('Restricted access');
	
   class UniteProviderAdminUC extends UniteCreatorAdmin{
   		
   		const SHOW_NOTICE = true;
   		const CURRENT_NOTICE = "Get access to more than +700 addons and +30 Layouts for WPBakery Page Builder <br> 50% OFF Cyber Monday - <a href='https://codecanyon.net/item/unlimited-addons-mega-bundle-for-visual-composer/19602316?ref=unitecms' target='_blank'>Get it now for just $11</a>";
   		const NOTICE_ID = "cyber_monday_discount";
   		
   		
   		const OPTION_ADMIN_NOTICE = "addon_library_admin_notices_dismissed";
   		
	   	private static $arrMenuPages = array();
	   	private static $arrSubMenuPages = array();
	   	private static $capability = "manage_options";
	   	
   		private $mainFilepath;
   		
	   	private static $t;
	   	
	   	const ACTION_ADMIN_MENU = "admin_menu";
	   	const ACTION_ADMIN_INIT = "admin_init";
	   	const ACTION_ADD_SCRIPTS = "admin_enqueue_scripts";
   		const ACTION_AFTER_SETUP_THEME = "after_setup_theme";
   		const ACTION_PRINT_SCRIPT = "admin_print_footer_scripts";
   		const ACTION_AFTER_SWITCH_THEME = "after_switch_theme";
   		
   		//install addons from this folder in the addon library itself on activate
   		const DIR_INSTALL_ADDONS = "addons_install";	
   		
   		const VIEW_ADDONS_VC = "addons_vc";
   		const VIEW_ADDONS_WP = "addons_wp";
   		const VIEW_ADDONS_ELEMENTOR = "addons_elementor";
   		
   		const ADDONSTYPE_VC = "vc";
   		const ADDONSTYPE_WP = "wp";
   		const ADDONSTYPE_ELEMENTOR = "elementor";
   		
   		
		/**
		 *
		 * the constructor
		 */
		public function __construct($mainFilepath){
			self::$t = $this;
			
			$this->mainFilepath = $mainFilepath;
			
			parent::__construct();
			
			$this->init();
						
		}		

				
			
		/**
		 * process activate event - install the db (with delta).
		 */
		public static function onActivate(){
			
			self::createTables();
						
			self::importCurrentThemeAddons();
			
			//import addons that comes in the addon library package
			self::importPackageAddons();
		}
		
				
		
		/**
		 * validate view if called single way
		 */
		public static function validateSingleView($view){
			
			switch($view){
				case GlobalsUC::VIEW_ADDONS_LIST:
					
					UniteFunctionsUC::throwError("Permission Denied to enter this view");
				break;
			}
			
		}

		/**
		 * after switch theme
		 */
		public static function afterSwitchTheme(){
			
			self::importCurrentThemeAddons();
		}
		
		
		/**
		 * do all actions on theme setup
		 */
		public static function onThemeSetup(){
						
			//UniteProviderFunctionsUC::integrateVisualComposer();
		}
		
		/**
		 * on before init
		 */
		public static function onBeforeVCInit(){
			
			UniteProviderFunctionsUC::integrateVisualComposer();
						
		}
		
		
		/**
		 *
		 * create the tables if not exists
		 */
		public static function createTables(){
			
			self::createTable(GlobalsUC::TABLE_ADDONS_NAME);
			self::createTable(GlobalsUC::TABLE_CATEGORIES_NAME);
		}
		
		
		/**
		 *
		 * craete tables
		 */
		public static function createTable($tableName){
		
			global $wpdb;
						
			//if table exists - don't create it.
			$tableRealName = $wpdb->prefix.$tableName;
			if(UniteFunctionsWPUC::isDBTableExists($tableRealName))
				return(false);
			
			$charset_collate = $wpdb->get_charset_collate();
			
			switch($tableName){
				case GlobalsUC::TABLE_LAYOUTS_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255) NOT NULL,
					layout_data mediumtext,					
					ordering int not NULL,
					catid int not NULL,
					layout_type varchar(60),
					relate_id int not NULL,
					parent_id int not NULL,
					params text NOT NULL,
					PRIMARY KEY (id)
					)$charset_collate;";
				break;
				case GlobalsUC::TABLE_CATEGORIES_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255) NOT NULL,
					alias varchar(255),
					ordering int not NULL,
					params text NOT NULL,
					type tinytext,
					parent_id int(9),
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				
				case GlobalsUC::TABLE_ADDONS_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255),
					name varchar(128),
					alias varchar(128),
					addontype varchar(128),
					description text,
					ordering int not NULL,
					templates text,
					config text,
					catid int,
					is_active tinyint,
					test_slot1 text,	
					test_slot2 text,	
					test_slot3 text,
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				default:
					UniteFunctionsMeg::throwError("table: $tableName not found");
				break;
			}
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		/**
		 *
		 * add ajax back end callback, on some action to some function.
		 */
		protected static function addActionAjax($ajaxAction, $eventFunction){
			self::addAction('wp_ajax_'.GlobalsUC::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
			self::addAction('wp_ajax_nopriv_'.GlobalsUC::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
		}
		
		
		/**
		 *
		 * register the "onActivate" event
		 */
		protected function addEvent_onActivate($eventFunc = "onActivate"){
			
			register_activation_hook( $this->mainFilepath, array(self::$t, $eventFunc) );
		}
		
		
		/**
		 *
		 * add menu page
		 */
		protected static function addMenuPage($title,$pageFunctionName,$icon=null){
			self::$arrMenuPages[] = array("title"=>$title,"pageFunction"=>$pageFunctionName,"icon"=>$icon);
		}
		
		/**
		 *
		 * add sub menu page
		 */
		protected static function addSubMenuPage($slug,$title,$pageFunctionName){
			self::$arrSubMenuPages[] = array("slug"=>$slug,"title"=>$title,"pageFunction"=>$pageFunctionName);
		}
		
		/**
		 * add admin menus from the list.
		 */
		public static function addAdminMenu(){
						
			//return(false);
			foreach(self::$arrMenuPages as $menu){
				$title = $menu["title"];
				$pageFunctionName = $menu["pageFunction"];
				$icon = UniteFunctionsUC::getVal($menu, "icon");
				
				add_menu_page( $title, $title, self::$capability, GlobalsUC::PLUGIN_NAME, array(self::$t, $pageFunctionName), $icon );
			}
		
			foreach(self::$arrSubMenuPages as $key=>$submenu){
		
				$title = $submenu["title"];
				$pageFunctionName = $submenu["pageFunction"];
		
				$slug = GlobalsUC::PLUGIN_NAME."_".$submenu["slug"];
		
				if($key == 0)
					$slug = GlobalsUC::PLUGIN_NAME;
		
				add_submenu_page(GlobalsUC::PLUGIN_NAME, $title, $title, 'manage_options', $slug, array(self::$t, $pageFunctionName) );
			}
		
		}
		
		
		/**
		 *
		 * tells if the the current plugin opened is this plugin or not
		 * in the admin side.
		 */
		private function isInsidePlugin(){
			$page = UniteFunctionsUC::getGetVar("page","",UniteFunctionsUC::SANITIZE_KEY);
			
			if($page == GlobalsUC::PLUGIN_NAME || strpos($page, GlobalsUC::PLUGIN_NAME."_") !== false)
				return(true);
		
			return(false);
		}
		
				
		/**
		 *
		 * add some wordpress action
		 */
		protected static function addAction($action,$eventFunction){
			
			add_action( $action, array(self::$t, $eventFunction) );
		}
		
		
		/**
		 * add local filter
		 */
		protected function addLocalFilter($tag, $func, $priority=10, $arguments=1){
			
			add_filter($tag, array($this, $func), $priority, $arguments);
		}
		
		
		/**
		 *
		 * validate admin permissions, if no pemissions - exit
		 */
		protected static function validateAdminPermissions(){
			
			if(UniteFunctionsWPUC::isAdminPermissions() == false){
				echo "access denied, no admin permissions";
				return(false);
			}
			
		}
		
		
		/**
		 * get addons view by type
		 */
		public static function getUrlViewAddonsByType($type){
			
			switch($type){
				case self::ADDONSTYPE_VC:
					return HelperUC::getViewUrl(self::VIEW_ADDONS_VC);
				break;
				case self::ADDONSTYPE_WP:
					return HelperUC::getViewUrl(self::VIEW_ADDONS_WP);
				break;
				case self::ADDONSTYPE_ELEMENTOR:
					return HelperUC::getViewUrl(self::VIEW_ADDONS_ELEMENTOR);
				break;
				default:
					UniteFunctionsUC::throwError("Wrong addons type: $type");
				break;
			}
			
		}
		
		
		/**
		 *
		 * admin main page function.
		 */
		public static function adminPages(){
			
			if(is_multisite())
				self::createTables();
			
			parent::adminPages();
			
		}
		
		/**
		 * add all pages scripts
		 */
		public static function addAllPagesScripts(){
			
			$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
			UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
			
			HelperUC::addScriptAbsoluteUrl(GlobalsUC::$url_provider."assets/provider_admin.js", "provider_admin_js");
			
			HelperUC::addScript("admin","unitecreator_admin");
			
		}
		
		
		/**
		 * add outside plugin scripts
		 */
		public static function onAddOutsideScripts(){
			
			try{
			
			//add outside scripts, only on posts or pages page
			$isPostsPage = UniteFunctionsWPUC::isAdminPostsPage();
			
			if($isPostsPage == false){
				
				self::addAllPagesScripts();
				return(false);
			}
			
			UniteVcIntegrateUC::onAddOutsideScripts();
			
			}catch(Exception $e){
				
				HelperHtmlUC::outputException($e);
				
			}
		}

		
		/**
		 * print custom scripts
		 */
		public static function onPrintFooterScripts(){

			HelperProviderUC::onPrintFooterScripts();
			
		}
		
		private static function a_________IMPORT_ADDONS________(){}
		
		
		/**
		 * install addosn from some path
		 */
		private static function installAddonsFromPath($pathAddons, $addonsType = null){
			
			if(empty($addonsType))
				$addonsType = self::ADDONSTYPE_VC;
			
			if(is_dir($pathAddons) == false)
				return(false);
			
			$exporter = new UniteCreatorExporter();
			$exporter->setMustImportAddonType($addonsType);
			$exporter->importAddonsFromFolder($pathAddons);
			
		}
		
		/**
		 * import current theme addons
		 */
		private static function importCurrentThemeAddons(){
			
			$pathCurrentTheme = get_template_directory()."/";
			
			$dirAddons = apply_filters("uc_path_theme_addons", GlobalsUC::DIR_THEME_ADDONS);
			
			$pathAddons = $pathCurrentTheme.$dirAddons."/";
			
			self::installAddonsFromPath($pathAddons);
		}
		
		
		/**
		 * import package addons
		 */
		private static function importPackageAddons(){
			
			$pathAddons = GlobalsUC::$pathPlugin.self::DIR_INSTALL_ADDONS."/";
			
			if(is_dir($pathAddons) == false)
				return(false);
			
			$imported = false;
			
			//install vc addons
			$pathAddonsVC = $pathAddons.self::ADDONSTYPE_VC."/";
			if(is_dir($pathAddonsVC)){
				self::installAddonsFromPath($pathAddonsVC, self::ADDONSTYPE_VC);
				$imported = true;
			}
			
			//install wp addons
			$pathAddonsWP = $pathAddons.self::ADDONSTYPE_WP."/";
			if(is_dir($pathAddonsWP)){
				self::installAddonsFromPath($pathAddonsWP, self::ADDONSTYPE_WP);
				$imported = true;
			}
			
			
			return($imported);
		}
		
		private static function a_________OTHERS________(){}
		
		
		/**
		 * return if creator plugin exists
		 */
		protected function isCreatorPluginExists(){
			$arrPlugins = get_plugins();
			
			$pluginName = "addon_library_creator/addon_library_creator.php";
			if(isset($arrPlugins[$pluginName]) == false)
				return(false);
			
			$isActive = is_plugin_active($pluginName);
			
			return($isActive);
						
		}
		
		
	
		
		/**
		 * modify addons manager
		 */
		public static function modifyAddonsManager($objManager){
			
			$addonsView = HelperUC::getGeneralSetting("manager_addons_view");
			
			if($addonsView == "info")
				$objManager->setViewType(UniteCreatorManagerAddons::VIEW_TYPE_INFO);
			
		}
		
		
		/**
		 * modify addons manager
		 */
		public static function validateGeneralSettings($arrValues){
			
			$vcFolder = UniteFunctionsUC::getVal($arrValues, "vc_folder");
			UniteFunctionsUC::validateNotEmpty($vcFolder, "visual composer folder");
			
		}
		
		
		/**
		 * after update plugin
		 * install package addons, then redirect to dashboard
		 */
		private function onAfterUpdatePlugin(){
			
			$isImported = self::importPackageAddons();
			if($isImported == false)
				return(false);
			
			//redirect to main view
			$urlRedirect = $linkBack = HelperUC::getViewUrl_Default();
			
			dmp("addons installed, redirecting...");
			echo "<script>location.href='$urlRedirect'</script>";
			exit();
			
		}
		
		
		/**
		 * run provider action if exists - only if inside plugin
		 */
		private function runProviderAction(){
			
			$action = UniteFunctionsUC::getGetVar("provider_action", "", UniteFunctionsUC::SANITIZE_KEY);
			if(empty($action))
				return(false);
			
			switch($action){
				case "run_after_update":
					$this->onAfterUpdatePlugin();
				break;
			}
			
		}
		
		
		/**
		 * dismiss admin notice
		 */
		private function dissmissAdminNotice($data){
			
			$noticeID = UniteFunctionsUC::getVal($data, "noticeid");
			if(empty($noticeID))
				return(false);
						
			$arrOptions = get_option(self::OPTION_ADMIN_NOTICE);
			
			if(isset($arrOptions[$noticeID]))
				return(false);
				
			$arrOptions[$noticeID] = self::OPTION_ADMIN_NOTICE;
			
			update_option(self::OPTION_ADMIN_NOTICE, $arrOptions);
		}
		
		
		/**
		 * on ajax action
		 */
		public function onAdminAjaxAction($found, $action, $data){
			
			switch($action){
				case "dismiss_admin_notice":
					
					$this->dissmissAdminNotice($data);
					
					HelperUC::ajaxResponseSuccess("Message Dismissed");
					
				break;
				default:
					return(false);
				break;
			}
			
			return(true);
		}
		
		
		/**
		 * add admin notices
		 */
		public static function addAdminNotices(){
			
			if(self::SHOW_NOTICE == false)
				return(false);
			
			$noticeID = self::NOTICE_ID;
			
			//if dismissed don't show;
			$arrDismissedOptions = get_option(self::OPTION_ADMIN_NOTICE);
			if(isset($arrDismissedOptions[$noticeID]))
				return(false);
			
		?>
		    <div id="addon_library_admin_notice" data-noticeid='<?php echo $noticeID?>' class="notice notice-warning is-dismissible" style="display:none">
		        <p><?php _e( self::CURRENT_NOTICE, ADDONLIBRARY_TEXTDOMAIN ); ?></p>
		    </div>
		    <?php
			
		}
		
		
		/**
		 * 
		 * init function
		 */
		protected function init(){
			
			$isCreatorExists = self::isCreatorPluginExists();
			$isLayoutEditorExists = HelperProviderUC::isLayoutEditorExists();
			
			parent::init();
			
			HelperProviderUC::globalInit();
			
			//set permission:
						
			//HelperUC::printGeneralSettings();
			
			$permission = HelperUC::getGeneralSetting("edit_permission");
			if($permission == "editor")
				self::$capability = "edit_posts";
			
			
			$urlMenuIcon = GlobalsUC::$url_provider."assets/images/icon_menu.png";
			
			self::addMenuPage('Addon Library', "adminPages", $urlMenuIcon);
			
			$arrSubmenuPages = array();
			
			self::addSubMenuPage(self::VIEW_ADDONS_VC, __('Addons for Visual Composer',ADDONLIBRARY_TEXTDOMAIN), "adminPages");
			
			if($isLayoutEditorExists)
				self::addSubMenuPage(self::VIEW_ADDONS_WP, __('Addons for WordPress',ADDONLIBRARY_TEXTDOMAIN), "adminPages");

				
			if(defined("ENABLE_ADDON_LIBRARY_ELEMENTOR"))
				self::addSubMenuPage(self::VIEW_ADDONS_ELEMENTOR, __('Addons for Elementor',ADDONLIBRARY_TEXTDOMAIN), "adminPages");
			
			
			/*
			if($isLayoutEditorExists)
				self::addSubMenuPage(self::VIEW_ADDONS_WP, __('Addons for Elementor',ADDONLIBRARY_TEXTDOMAIN), "adminPages");
			*/
				
			if($isCreatorExists == true)
				self::addSubMenuPage("assets", __('Assets Manager',ADDONLIBRARY_TEXTDOMAIN), "adminPages");
			
			self::addSubMenuPage("settings", __('General Settings',ADDONLIBRARY_TEXTDOMAIN), "adminPages");
			
			
			//add internal hook for adding a menu in arrMenus
			self::addAction(self::ACTION_ADMIN_MENU, "addAdminMenu");
			
			//if not inside plugin don't continue
			if($this->isInsidePlugin() == true){
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
			}else{	
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddOutsideScripts");
			}
			
			self::addAction(self::ACTION_PRINT_SCRIPT, "onPrintFooterScripts");
			
			//self::addAction(self::ACTION_AFTER_SETUP_THEME, "onThemeSetup");
			
			self::addAction("vc_before_init", "onBeforeVCInit");
			
			self::addAction(self::ACTION_AFTER_SWITCH_THEME, "afterSwitchTheme");
			
			$this->addEvent_onActivate();
			
			self::addActionAjax("ajax_action", "onAjaxAction");
			
			//addon library actions
			self::addAction(UniteCreatorFilters::ACTION_MODIFY_ADDONS_MANAGER, "modifyAddonsManager");
			self::addAction(UniteCreatorFilters::ACTION_VALIDATE_GENERAL_SETTINGS, "validateGeneralSettings");
			
			//run provider action if exists (like after update)
			if($this->isInsidePlugin()) 
				$this->runProviderAction();
			
			self::addAction("admin_notices", "addAdminNotices");
			
			self::addLocalFilter(UniteCreatorFilters::FILTER_ADMIN_AJAX_ACTION, "onAdminAjaxAction",10, 3);
			
	}

		
		
	}

?>