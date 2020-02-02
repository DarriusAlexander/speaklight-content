<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


class UniteCreatorFilters{
	
	const FILTER_CLIENTSIDE_GENERAL_SETTINGS = "uc_get_client_general_settings";
	const FILTER_MODIFY_GENERAL_SETTINGS = "uc_modify_general_settings";
	const FILTER_MANAGER_MENU_SINGLE = "uc_manager_addons_menu_single";
	const FILTER_MANAGER_MENU_FIELD = "uc_manager_addons_menu_field";
	const FILTER_MANAGER_MENU_MULTIPLE = "uc_manager_addons_menu_multiple";
	const FILTER_MANAGER_MENU_CATEGORY = "uc_manager_addons_menu_category";
	const FILTER_MANAGER_ADDONS_PLUGINS = "uc_manager_addons_plugins";
	const FILTER_MANAGER_ADDON_ADDHTML = "addon_library_manager_addon_addhtml";
	
	const FILTER_ADMIN_AJAX_ACTION = "addon_library_ajax_action";
	const FILTER_ADMIN_VIEW_FILEPATH = "addon_library_admin_view_filepath";
	const FILTER_MODIFY_URL_VIEW = "addon_library_modify_url_view";
	const FILTER_LAYOUTS_ACTIONS_COL_WIDTH = "addon_library_layouts_actions_colwidth";	
	const FILTER_EXPORT_ADDON_DATA = "addon_library_export_addon_data";
	const FILTER_EXPORT_CAT_TITLE = "addon_library_export_cat_title";
	const FILTER_MODIFY_SETTING_BEFORE_DRAW = "addon_library_modify_setting_before_draw";
	
	const ACTION_VALIDATE_GENERAL_SETTINGS = "uc_validate_general_settings";
	const ACTION_MANAGER_ITEM_BUTTONS1 = "uc_manager_action_item_buttons1";
	const ACTION_MANAGER_ITEM_BUTTONS2 = "uc_manager_action_item_buttons2";
	const ACTION_MANAGER_ITEM_BUTTONS3 = "uc_manager_action_item_buttons3";
	const ACTION_EDIT_ADDON_EXTRA_BUTTONS = "addon_library_addon_edit_extra_buttons";
	const ACTION_EDIT_GLOBALS = "addon_library_edit_globals";
	const ACTION_BOTTOM_PLUGIN_VERSION = "addon_library_bottom_plugin_version";
	const ACTION_ADD_ADMIN_SCRIPTS = "addon_library_add_admin_scripts";
	const ACTION_ADD_LAYOUT_TOOLBAR_BUTTON = "addon_library_add_layout_toolbar_button";
	const ACTION_ADD_ADDONS_TOOLBAR_BUTTON = "addon_library_add_addons_toolbar_button";	
	const ACTION_LAYOUT_EDIT_HTML = "addon_library_layout_edit_html";
	const ACTION_MODIFY_ADDONS_MANAGER = "addon_library_modify_addons_manager";
	const ACTION_LAYOUTS_LIST_ACTIONS = "addon_library_layouts_list_actions";
	const ACTION_EDIT_ADDON_ADDSETTINGS = "addon_library_edit_addon_addsettings";
	
}