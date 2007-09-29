<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2005 René Fritz (r.fritz@colorcube.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Module 'Media>Index'
 * Part of the DAM (digital asset management) extension.
 *
 * @author	René Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam_index
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   74: class tx_damindex_module1 extends tx_dam_SCbase 
 *   94:     function init()	
 *  143:     function menuConfig()	
 *  158:     function main()	
 *  196:     function jumpToUrl(URL)	
 *  257:     function printContent()	
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */







unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');
require ($BACK_PATH.'template.php');

require_once(PATH_txdam.'lib/class.tx_dam_scbase.php');

$LANG->includeLLFile('EXT:dam_index/mod1/locallang.php');


$BE_USER->modAccess($MCONF,1);


require_once (PATH_t3lib.'class.t3lib_basicfilefunc.php');



/**
 * Script class for the DAM index module
 * 
 * @author	René Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam_file
 */
class tx_damindex_module1 extends tx_dam_SCbase {

	/**
	 * t3lib_basicFileFunctions object
	 */
	var $basicFF;



	/**
	 * Initializes the backend module
	 * 
	 * @return	void		
	 */
	function init()	{
		global $SOBE, $TYPO3_CONF_VARS, $FILEMOUNTS;

		parent::init();


		//
		// Init basic-file-functions object:
		//

		$this->basicFF = t3lib_div::makeInstance('t3lib_basicFileFunctions');
		$this->basicFF->init($FILEMOUNTS,$TYPO3_CONF_VARS['BE']['fileExtensions']);

	}



	/**
	 * Main function of the module. Write the content to $this->content
	 * 
	 * @return	void		
	 */
	function main()	{
		global $BE_USER, $LANG, $BACK_PATH, $FILEMOUNTS, $TYPO3_CONF_VARS;

		//
		// Initialize the template object
		//

		$this->doc = t3lib_div::makeInstance('mediumDoc');
		$this->doc->backPath = $BACK_PATH;
		$this->doc->docType = 'xhtml_trans';


		//
		// Validating the input path and checking access against the mounts of the user.
		//

		$this->path = $this->basicFF->is_directory(tx_dam_div::getAbsPath($this->path));
		$this->path = $this->path ? $this->path.'/' : '';
		$access = $this->path && ($this->fmountID = $this->basicFF->checkPathAgainstMounts($this->path));
		$this->path_mount = $FILEMOUNTS[$this->fmountID]['path'];

//debug($this->MOD_SETTINGS['tx_dam_folder'], 'tx_dam_folder');
//debug($this->path_mount, 'path_mount');
//debug($this->path, 'path');
//debug($FILEMOUNTS, '$FILEMOUNTS');
//debug($this->fmountID, 'fmountID');


		//
		// There was access to this file path, continue ...
		//

		if ($access)	{

			//
			// Output page header
			//

			$this->doc->form='<form action="'.htmlspecialchars(t3lib_div::linkThisScript($this->addParams)).'" method="POST" name="editform" enctype="'.$TYPO3_CONF_VARS['SYS']['form_enctype'].'">';

				// JavaScript
			$this->doc->JScodeArray['jumpToUrl'] = '
				var script_ended = 0;
				var changed = 0;

				function jumpToUrl(URL)	{
					document.location = URL;
				}
				';

			$this->doc->postCode.= $this->doc->wrapScriptTags('
				script_ended = 1;');


			$this->extObjHeader();

				// Draw the header.
			$this->content.= $this->doc->startPage($LANG->getLL('title'));
			$this->content.= $this->doc->header($LANG->getLL('title'));
			$this->content.= $this->doc->spacer(5);


			//
			// Output tabmenu if not a single function was forced
			//

			if (!$this->forcedFunction AND count($this->MOD_MENU['function'])>1) {
#TODO				$this->content.= $this->doc->section('',$this->doc->getTabMenu($this->addParams,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function']),0,1);
				$this->content.= $this->doc->section('',$this->getTabMenu($this->addParams,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function']),0,1);
			}

			//
			// Call submodule function
			//

			$this->extObjContent();


			//
			// output footer: search box, options, store control, ....
			//

			$this->content.= $this->doc->spacer(10);
			$this->content.= $this->guiItems_getOutput('footer');


			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
#TODO
				$this->content.= $this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
			}

			$this->content.= $this->doc->spacer(10);

		} else {
				// If no access or no path

			$this->content.= $this->doc->startPage($LANG->getLL('title'));
			$this->content.= $this->doc->header($LANG->getLL('title'));
			$this->content.= $this->doc->spacer(5);
#TODO
			$this->content.= $this->doc->spacer(10);
		}
	}


	/**
	 * Prints out the module HTML
	 * 
	 * @return	string		HTML
	 */
	function printContent()	{
		global $SOBE;

		$this->content.= $this->doc->middle();
		$this->content.= $this->doc->endPage();
		$this->content = $this->doc->insertStylesAndJS($this->content);
		echo $this->content;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_damindex_module1');
$SOBE->init();

// Include files?
reset($SOBE->include_once);
while(list(,$INC_FILE)=each($SOBE->include_once))	{include_once($INC_FILE);}
$SOBE->checkExtObj();	// Checking for first level external objects

// Repeat Include files! - if any files has been added by second-level extensions
reset($SOBE->include_once);
while(list(,$INC_FILE)=each($SOBE->include_once))	{include_once($INC_FILE);}
$SOBE->checkSubExtObj();	// Checking second level external objects


$SOBE->main();
$SOBE->printContent();

?>