<?php

defined('ADDON_LIBRARY_INC') or die;

class UniteCreatorLayoutsView{
	
	protected $showButtonsPanel = true;
	protected $showHeaderTitle = true;
	
	/**
	 * constructor
	 */
	public function __construct(){
		
	}
	
	
	/**
	 * put manage categories dialog
	 */
	public function putDialogCategories(){
		?>
			<div id="uc_dialog_add_category"  title="<?php _e("Manage Layout Categories", ADDONLIBRARY_TEXTDOMAIN)?>" style="display:none; height: 300px;" class="unite-inputs">
				
				<div class="unite-dialog-top">
				
					<input type="text" class="uc-catdialog-button-clearfilter" style="margin-bottom: 1px;">
					<a class='uc-catdialog-button-filter unite-button-secondary' href="javascript:void(0)"><?php _e("Filter", ADDONLIBRARY_TEXTDOMAIN)?></a>
					<a class='uc-catdialog-button-filter-clear unite-button-secondary' href="javascript:void(0)"><?php ?><?php _e("Clear Filter", ADDONLIBRARY_TEXTDOMAIN)?></a>
						
					<h3>
						<?php _e("List of categories (sort: ",ADDONLIBRARY_TEXTDOMAIN)?>
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="a-z">a-z</a>
						, 
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="z-a">z-a</a>
						):
					</h3>
				</div>
				
				<div id="list_layouts_cats"></div>
				
				<hr/>
				
					<?php _e("Add New Category", ADDONLIBRARY_TEXTDOMAIN)?>: 
					<input id="uc_dialog_add_category_catname" type="text" class="unite-input-regular" value="">
					
					<a id="uc_dialog_add_category_action" href="javascript:void(0)" class="unite-button-secondary" data-action="add_category"><?php _e("Create Category", ADDONLIBRARY_TEXTDOMAIN)?></a>
					
				<div>
				
				<br/>
				
					<button class="uc-button-set-category-tolayout unite-button-primary"><?php _e("Set Category To Layout")?></button>
					
				</div>
				<div id="uc_dialog_add_category_actions_wrapper" class="unite-dialog-actions">
					<div id="uc_dialog_add_category_loader" class="loader_text" style="display:none"><?php _e("Adding Category", ADDONLIBRARY_TEXTDOMAIN)?>...</div>
					<div id="uc_dialog_add_category_error" class="unite-dialog-error"  style="display:none"></div>
					<div id="uc_dialog_add_category_success" class="unite-dialog-success" style="display:none"><?php _e("Category Added", ADDONLIBRARY_TEXTDOMAIN)?></div>
				</div>
			</div>
			
			<div id="uc_layout_categories_message" title="<?php _e("Layout Categories Message", ADDONLIBRARY_TEXTDOMAIN)?>">
			</div>
		
		<?php 
	}
	
	
	/**
	 * put import addons dialog
	 */
	public function putDialogImportLayout(){
	
		$dialogTitle = __("Import Layouts",ADDONLIBRARY_TEXTDOMAIN);
		
		?>
		
			<div id="uc_dialog_import_layouts" class="unite-inputs" title="<?php echo $dialogTitle?>" style="display:none;">
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-inputs-label">
					<?php _e("Select layouts export file", ADDONLIBRARY_TEXTDOMAIN)?>:
				</div>
				
				<form id="dialog_import_layouts_form" name="form_import_layouts">
					<input id="dialog_import_layouts_file" type="file" name="import_layout">
							
				</form>	
				
				<div class="unite-inputs-sap-double"></div>
				
				<div class="unite-inputs-label" >
					<label for="dialog_import_layouts_file_overwrite">
						<?php _e("Overwrite Addons", ADDONLIBRARY_TEXTDOMAIN)?>:
					</label>
					<input type="checkbox" id="dialog_import_layouts_file_overwrite"></input>
				</div>
				
				
				<div class="unite-clear"></div>
				
				<?php 
					$prefix = "uc_dialog_import_layouts";
					$buttonTitle = __("Import Layouts", ADDONLIBRARY_TEXTDOMAIN);
					$loaderTitle = __("Uploading layouts file...", ADDONLIBRARY_TEXTDOMAIN);
					$successTitle = __("Layouts Added Successfully", ADDONLIBRARY_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
					
			</div>		
		
	<?php
	}
		
	
	/**
	* display layouts view
	 */
	public function display(){
		
		//table object
		$objTable = new UniteTableUC();
		
		$objLayouts = new UniteCreatorLayouts();
		$gridBuilder = new UniteCreatorGridBuilderProvider();
		
		$pagingOptions = $objTable->getPagingOptions();
		
		$response = $objLayouts->getArrLayoutsPaging($pagingOptions);
		
		$arrLayouts = $response["layouts"];
		$pagingData = $response["paging"];
		
		
		require HelperUC::getPathTemplate("layouts_list");		
	}
	
}

