<?php
/**
 * @package Addon Library
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ADDON_LIBRARY_INC') or die('Restricted access');

class UniteCreatorDialogParam extends UniteCreatorDialogParamWork{
	
	
	/**
	 * modify param text, function for override
	 */
	protected function modifyParamText($paramType, $paramText){
		
		switch($paramType){
			case self::PARAM_POST:
				//$paramText = __("Article", ADDONLIBRARY_TEXTDOMAIN);
			break;
		}
		
		return($paramText);
	}
	
	
	/**
	 * init main params, add platform related param
	 */
	public function initMainParams(){
		
		parent::initMainParams();
		
		$this->arrParams[] = self::PARAM_POSTS_LIST;
		
	}
	
	
}