<?php

defined('ADDON_LIBRARY_INC') or die;

class UniteCreatorAddonViewProvider extends UniteCreatorAddonView{
	
	
	/**
	 * get thumb sizes
	 */
	protected function getThumbSizes(){
		
		$arrThumbSizes = UniteFunctionsWPUC::getArrThumbSizes();
		
		unset($arrThumbSizes["medium"]);
		
		return($arrThumbSizes);
	}
	
	
}