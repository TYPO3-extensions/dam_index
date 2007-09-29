<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2004 René Fritz (r.fritz@colorcube.de)
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
 * Module extension (addition to function menu) 'Index' for the 'Media>Index' module..
 * Part of the DAM (digital asset management) extension.
 *
 * @author	René Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   76: class tx_damindex_index extends t3lib_extobjbase 
 *   96:     function modMenu()    
 *  124:     function head() 
 *  161:     function main()	
 *  264:     function moduleContent()    
 *  367:     function showProgress() 
 *
 *              SECTION: Rendering general output
 *  469:     function getStepsBar($currentStep,$lastStep=4) 
 *
 *              SECTION: Rendering the forms etc
 *  520:     function getPresetForm ($rec,$fixedFields,$langKeyDesc) 
 *  589:     function showPresetData ($rec,$fixedFields) 
 *  638:     function doIndex() 
 *
 *              SECTION: this and that
 *  728:     function saveSettings() 
 *  746:     function getBrowseableFolderList ($path, $folderParam) 
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */




require_once(PATH_t3lib.'class.t3lib_extobjbase.php');

require_once(PATH_txdam.'lib/class.tx_dam_filelist.php');
require_once(PATH_txdam.'lib/class.tx_dam_indexing.php');

/**
 * Module 'Media>Index>Index'
 * Module 'Media>File>Index'
 * 
 * @author	René Fritz <r.fritz@colorcube.de>
 */
class tx_damindex_index extends t3lib_extobjbase {


	/**
	 * basic-file-functions object
	 */
	var $basicFF;
	
	/**
	 * indexing object
	 */
	var $index;
	
	

	/**
	 * Function menu initialization
	 * 
	 * @return	array		Menu array
	 */
	function modMenu()    {
		global $LANG;
		
		return array (
			'tx_damindex_index_func' => array (
				'index' => $LANG->getLL('tx_damindex_index.func_index'),
				'info' => $LANG->getLL('tx_damindex_index.func_info'),
			),
		);

	}


	/**
	 * Do some init things and aet some styles in HTML header
	 * 
	 * @return	void		
	 */
	function head() {
		global $SOBE, $LANG, $TYPO3_CONF_VARS, $FILEMOUNTS;

		//
		// doc and header init
		//

		$this->pObj->doc->form = '<form action=""'.t3lib_div::linkThisScript($this->pObj->addParams).'"" method="POST" enctype="multipart/form-data" name="editform" autocomplete="off">';
		#TODO ??? onSubmit="return TBE_EDITOR_checkSubmit(1);" 
		$this->pObj->doc->form.= '<input type="hidden" name="SET[tx_dam_folder]" value="'.$this->pObj->path.'" />';


		//
		// Init gui items and ...
		//
		
		$this->pObj->guiItems_registerFunc('getOptions', 'footer');


		//
		// Init indexing object
		//	
			
		$this->index = t3lib_div::makeInstance('tx_dam_indexing');
		$this->index->init();
		
		
			// initialize indexing setup
		$this->processIndexSetup();
	}	
	

	/**
	 * Main function
	 * 
	 * @return	string		HTML output
	 */
	function main()	{
		global $SOBE, $BE_USER, $LANG, $BACK_PATH;
		
		$content = '';
		
		$this->cmdIcons['funcMenu'] = '&nbsp;&nbsp;&nbsp;'.t3lib_BEfunc::getFuncMenu($this->pObj->addParams,'SET[tx_damindex_index_func]',$SOBE->MOD_SETTINGS['tx_damindex_index_func'],$SOBE->MOD_MENU['tx_damindex_index_func']);
		
		#$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], TRUE, $this->cmdIcons);
		#$content.= $this->pObj->doc->section('',$this->pObj->doc->funcMenu('',$this->cmdIcons['funcMenu']));


			// Render content:
		$content.= $this->moduleContent();

		return $content;
	}





	/**
	 * Generates the module content
	 * 
	 * @return	string		HTML content
	 */
	function moduleContent()    {
		global $SOBE, $BE_USER, $LANG, $BACK_PATH, $FILEMOUNTS;
	
		$content = '';
		
		$data = t3lib_div::_GP('data');

		$func = (string)$SOBE->MOD_SETTINGS['tx_damindex_index_func'];
		if ($step = t3lib_div::_GP('indexStep')) {
			$step = max(1,key($step));
			$func = 'index'.$step;
		}
		if (t3lib_div::_GP('indexStart')) {
			$func = 'indexStart';
		}


		$cnBgColor = t3lib_div::modifyHTMLcolor($this->pObj->doc->bgColor3,-5,-5,-5);
		
		#TODO
		$this->pObj->doc->lgBgColor5 = t3lib_div::modifyHTMLcolor($this->pObj->doc->bgColor5,25,25,25);

		switch($func)    {
			
			//
			// select start folder
			//			
			
			case 'index':
			case 'index1':
				$step=1;
				
				$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], TRUE, $this->cmdIcons);
				$content.= $this->pObj->doc->spacer(10);
		
				$store = t3lib_div::makeInstance('t3lib_modSettings');
				$store->init('tx_damindex');
				$store->setStoreList('tx_damindex_indexSetup');
				$store->processStoreControl();
				
				if ($code = $store->getStoreControl('load,remove')) {
					$this->content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.choose_preset'),$code,0,1);
					$this->content.=$this->pObj->doc->spacer(10);
				}
				
				$content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.index_begin'),$this->getStepsBar($step,$lastStep=4),0,1);
				$content.= $this->pObj->doc->section('',$LANG->getLL('tx_damindex_index.choose_start_folder'),0,1);
				$content.= $this->pObj->doc->spacer(10);


				$content.= $this->getBrowseableFolderList($this->pObj->path, 'SET[tx_dam_folder]');
			break;


			//
			// select indexing options
			//			
			
			case 'index2':
				$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], FALSE, $this->cmdIcons);
				$content.= $this->pObj->doc->spacer(10);
				
				$content.= $this->pObj->doc->section($LANG->getLL('options'),$this->getStepsBar($step,$lastStep=4),0,1);
				$content.= $this->pObj->doc->spacer(5);
				
				$code = '<table border="0" cellspacing="0" cellpadding="4" width="100%">'.$this->index->getIndexingOptionsForm().'</table>';
				$content.= $this->pObj->doc->section($LANG->getLL('options').':',$code,1,0);

			break;


			//
			// field predefinition
			//			
			
			case 'index3':
				$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], FALSE, $this->cmdIcons);
				$content.= $this->pObj->doc->spacer(10);
				
				$content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.index_fields_preset'),$this->getStepsBar($step,$lastStep=4),0,1);
				$content.= $LANG->getLL('tx_damindex_index.preset_desc');
				$content.= $this->pObj->doc->spacer(10);

				$rec=array_merge($this->index->dataPreset,$this->index->dataPostset);
				$fixedFields=array_keys($this->index->dataPostset);
				$code = '<table border="0" cellpadding="4" width="100%"><tr>
					<td bgcolor="'.$cnBgColor.'">'.$this->getPresetForm($rec,$fixedFields,'tx_damindex_index.fixed_desc').'</td>
					</tr></table>';
				$content.= $this->pObj->doc->section('',$code,0,1);
			break;


			//
			// setup summary
			//			
			
			case 'index4':
			
			
					// JavaScript
				$this->pObj->doc->JScode = $this->pObj->doc->wrapScriptTags('
					function showProgress() {
					
						if(document.all) {
							document.all.stepsFormButtons.style.visibility = "hidden";
							document.all.summaryInfoDiv.style.visibility = "hidden";
							document.all.progressInfoDiv.style.visibility = "visible";
						} else {
							document.getElementById("stepsFormButtons").style.visibility = "hidden";
							document.getElementById("summaryInfoDiv").style.visibility = "hidden";
							document.getElementById("progressInfoDiv").style.visibility = "visible";
						}
					}
				');			
			
		
				$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], FALSE, $this->cmdIcons);
				$content.= $this->pObj->doc->spacer(10);
				
				$content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.setup_summary'),$this->getStepsBar($step,$lastStep=4),0,1);

				$content.= '<div id="progressInfoDiv" style="visibility:hidden"><img src="'.$BACK_PATH.PATH_txdam_rel.'i/progress_ani.gif" border="0" align="absmiddle">Indexing in progress. Please wait ...</div>';
				$content.= '<div id="summaryInfoDiv">';
				$content.= '<strong>Set Options:</strong><table border="0" cellspacing="0" cellpadding="4" width="100%">'.$this->index->getIndexingOptionsInfo().'</table>';

				$content.= $this->pObj->doc->spacer(10);

				$rec=array_merge($this->index->dataPreset,$this->index->dataPostset);
				$fixedFields=array_keys($this->index->dataPostset);
				$content.= '<strong>Meta data preset:</strong><br /><table border="0" cellpadding="4" width="100%"><tr><td bgcolor="'.$cnBgColor.'">'.
								$this->showPresetData($rec,$fixedFields).
								'</td></tr></table>';

				$content.= $this->pObj->doc->spacer(10);

//				$store = t3lib_div::makeInstance('t3lib_modSettings');
//				$store->init('tx_damindex');
//				$store->setStoreList('tx_damindex_indexSetup');
//				$store->processStoreControl();
//#TODO nicht zu sehen:
//				if ($code = $store->getStoreControl('save')) {
//					$this->content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.store_preset'),$code,0,1);
//					$this->content.=$this->pObj->doc->spacer(10);
//				}
				$content.= '</div>';
				$content.= $this->pObj->doc->spacer(10);

			break;


			case 'indexStart':
				$this->cmdIcons['funcMenu'] = $this->pObj->btn_back(array('indexStep[1]'=>1));
				$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], FALSE, $this->cmdIcons);
				$content.= $this->pObj->doc->spacer(10);

				$content.= $this->pObj->doc->section($LANG->getLL('tx_damindex_index.indexed_files'),'',0,1);
				$content.= $this->doIndex();

			break;


			//
			// services info
			//			
			
			case 'info':
				$content.= $this->pObj->doc->section('',$this->pObj->doc->funcMenu('',$this->cmdIcons['funcMenu']));
				
				$code='';

				require_once (PATH_txdam.'lib/class.tx_dam_svlist.php');
				$list = t3lib_div::makeInstance('tx_dam_svlist');
				$list->pObj = &$this->pObj;

				$code.= 'Indexing needs the help of some services to extract meta data or read text content from the files.<br /><br />Used service types are:<br />';
				$code.= '<strong>metaExtract</strong> - get meta data from files.<br />';
				$code.= '<strong>textExtract</strong> - get text content out of files.<br />';
				$code.= '<strong>textLang</strong> - detect the language of text content.<br />';

				$code.= $list->serviceTypeList_loaded();

				$code.= 'The "External" column shows which external programs are needed. If a service is not available, it might be the case that the program is not installed or can\'t be executed.<br />';

				$content.= $this->pObj->doc->section('Available services for indexing:',$code,0,1);
			break;
		}

#		 $content.= '<br /><span style="margin-left:20px;"><input type="submit" name="indexStep['.$step.']" value="update" /></span><br />';

		return $content;
	}


	


	/*******************************************************
	 *
	 * Rendering the forms etc
	 *
	 *******************************************************/
	 
	 	 
	/**
	 * Returns the form to preset values
	 * 
	 * @param	array		preset record data
	 * @param	array		fields which are preset as fixed fields
	 * @param	[type]		$langKeyDesc: ...
	 * @return	string		
	 * @params  string
	 */
	function getPresetForm ($rec,$fixedFields,$langKeyDesc) {
		global $LANG, $BACK_PATH, $TCA;

		$content = '';
		$editForm = '';

		if(!is_array($rec)) $rec = array();
		if(!is_array($fixedFields)) $fixedFields = array();

			// fake table - to be safe
		t3lib_div::loadTCA('tx_dam');
		$TCA['tx_dam_simpleforms'] = $TCA['tx_dam'];

		require_once (PATH_txdam.'lib/class.tx_dam_simpleforms.php');
		$form = t3lib_div::makeInstance('tx_dam_simpleForms');
		$form->initDefaultBEmode();
		$form->removeRequired($TCA['tx_dam_simpleforms']);
		$form->removeMM($TCA['tx_dam_simpleforms']);
		$form->tx_dam_fixedFields=$fixedFields;
		
		require_once (PATH_t3lib.'class.t3lib_transferdata.php');
		$processData = t3lib_div::makeInstance('t3lib_transferData');
		$rec = $processData->renderRecordRaw('tx_dam_simpleforms', $rec['uid'], $rec['pid'], $rec);
		$rec['uid'] = 1;
		$rec['pid'] = 0;
		$rec['media_type'] = 0;


		$columnsOnly=$TCA['tx_dam_simpleforms']['txdamInterface']['index_fieldList'];

		if ($columnsOnly)	{
			$editForm.= $form->getListedFields('tx_dam_simpleforms',$rec,$columnsOnly);
		} else {
			$editForm.= $form->getMainFields('tx_dam_simpleforms',$rec);
		}

			// add message for checkboxes
		$editForm='<tr bgcolor="'.$this->pObj->doc->bgColor4.'">
				<td nowrap="nowrap" valign="middle">'.
				'<img src="clear.gif" width="7" height="10" alt="" />'.
				'<img src="'.$BACK_PATH.'gfx/pil2down.gif" width="12" height="7" vspace="2" alt="" />'.
				'<img src="clear.gif" width="10" height="10" alt="" /></td>
				<td valign="top">'.$LANG->getLL($langKeyDesc).'</td>
			</tr>
			<tr>
				<td colspan="2"><img src="clear.gif" width="1" height="8" alt="" /></td>
			</tr>'.
			$editForm;


		$editForm=$form->wrapTotal($editForm,$rec,'tx_dam_simpleforms');

		$this->pObj->doc->JScode.= '
		'.$form->printNeededJSFunctions_top();
		$content.= $editForm.$form->printNeededJSFunctions();

		unset($TCA['tx_dam_simpleforms']);

		return $content;
	}


	/**
	 * Returns the non-editable form of preset values
	 * 
	 * @param	array		preset record data
	 * @param	array		fields which are preset as fixed fields
	 * @return	string		
	 */
	function showPresetData ($rec,$fixedFields) {
		global $LANG, $BACK_PATH, $TCA;

		$content = '';

		if(!is_array($rec)) $rec = array();

			// fake table - to be safe
		t3lib_div::loadTCA('tx_dam');
		$TCA['tx_dam_simpleforms'] = $TCA['tx_dam'];
		
		require_once (PATH_txdam.'lib/class.tx_dam_simpleforms.php');
		$form = t3lib_div::makeInstance('tx_dam_simpleForms');
		$form->initDefaultBEmode();
		$form->prependFormFieldNames='ignore';
		$form->removeRequired($TCA['tx_dam_simpleforms']);
		$form->removeMM($TCA['tx_dam_simpleforms']);
		$form->setNonEditable($TCA['tx_dam_simpleforms']);
		$form->tx_dam_fixedFields=$fixedFields;

		require_once (PATH_t3lib.'class.t3lib_transferdata.php');
		$processData = t3lib_div::makeInstance('t3lib_transferData');
		$rec = $processData->renderRecordRaw('tx_dam_simpleforms', $rec['uid'], $rec['pid'], $rec);
		$rec['uid'] = 1;
		$rec['pid'] = 0;
		$rec['media_type'] = 0;

		$columnsOnly=$TCA['tx_dam']['txdamInterface']['index_fieldList'];

		if ($columnsOnly)	{
			$content.= $form->getListedFields('tx_dam_simpleforms', $rec, $columnsOnly);
		} else {
			$content.= $form->getMainFields('tx_dam_simpleforms', $rec);
		}

		$content = $form->wrapTotal($content, $rec, 'tx_dam_simpleforms');

		unset($TCA['tx_dam_simpleforms']);

		return $content;
	}


	/**
	 * Do the file indexing
	 * Read files from a directory index them and output a result table
	 * 
	 * @return	string		HTML content
	 */
	function doIndex() {
		global $LANG;
		
		$content = '';

		$this->index->setDryRun($this->index->ruleConf['tx_damindex_rule_dryRun']['enabled']);

		set_time_limit(60*5);

		$code='';
		$ctable=array();
		$irow=1;
		# $ctable[$irow][] = 'Title';
		$ctable[$irow][] = 'File';
		$ctable[$irow][] = 'Path';
		$ctable[$irow][] = 'Type';
		$ctable[$irow][] = 'Text Excerpt';

		$files = $this->index->getFilesInDir($this->pObj->path, $this->index->ruleConf['tx_damindex_rule_recursive']['enabled']);

		if (count($files)) {

			$this->index->collectMeta = TRUE;
			$this->index->enableReindexing($this->index->ruleConf['tx_damindex_rule_doReindexing']['enabled']);
			$this->index->indexFiles($files, $this->pObj->defaultPid);

			foreach($this->index->meta as $id => $val) {
				$meta = $val['fields'];
				if(is_array($meta)) {
					# $ctable[++$irow][] = t3lib_div::fixed_lgd_cs($meta['title'],30);
					$ctable[++$irow][] = t3lib_div::fixed_lgd_cs($meta['file_name'],25);
					$ctable[$irow][] = t3lib_div::fixed_lgd_cs($meta['file_path'],-20);
					$ctable[$irow][] = tx_dam_div::fileIcon ($meta['file_type'],$meta['media_type'],$tparams='align="top"').'&nbsp;'.$meta['file_type'];
					$ctable[$irow][] = t3lib_div::fixed_lgd_cs($meta['abstract'],18);
				}
			}

			if ($this->index->stat['totalCount'] > 0) {
				$this->pObj->doc->tableLayout = Array (
					'table' => Array('<table cellpadding="3" cellspacing="1" border="0" width="100%">','</table>'),
					'0' => Array (
						'tr' => Array('<tr bgcolor="'.$this->pObj->doc->bgColor5.'">','</tr>'),
						'defCol' => Array('<td valign="top" nowrap="nowrap"><strong>','</strong></td>')
					),
					'defRowOdd' => Array (
						'defCol' => Array('<td valign="top" nowrap="nowrap" bgcolor="'.$this->pObj->doc->bgColor4.'">','</td>'),
						'3' => Array('<td valign="top" bgcolor="'.$this->pObj->doc->bgColor4.'">','</td>'),
						'4' => Array('<td valign="top" bgcolor="'.$this->pObj->doc->bgColor4.'">','</td>')
					),
					'defRowEven' => Array (
						'defCol' => Array('<td valign="top" nowrap="nowrap" bgcolor="'.$this->pObj->doc->bgColor3.'">','</td>'),
						'3' => Array('<td valign="top" bgcolor="'.$this->pObj->doc->bgColor3.'">','</td>'),
						'4' => Array('<td valign="top" bgcolor="'.$this->pObj->doc->bgColor3.'">','</td>')
					)
				);
				if ($this->index->ruleConf['tx_damindex_rule_dryRun']['enabled']) {
					$code.= '<div><strong class="diff-r">'.$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:dryRun.title').'!</strong></div>';
				}
				$code.= '<br />'.$this->index->stat['totalCount'].' files indexed';
				$code.= ' in '.max(1,ceil($this->index->stat['totalTime']/1000)).' sec.';
				$code.= $this->pObj->doc->table($ctable);
				$content.= $this->pObj->doc->section('',$code,0,1);
			} else {
				$content.= $this->pObj->doc->section('','<br />'.'There are no <b>new</b> files to index',0,1);
			}
		} else {
			$content.= $this->pObj->doc->section('','<br />'.'There are no files to index',0,1);

		}
		return $content;
	}





	/*******************************************************
	 *
	 * GUI
	 *
	 *******************************************************/
	 
	 
	 
	/**
	 * Returns HTML of a box with a step counter and "back" and "next" buttons
	 * 
	 * @param	integer		current step (begins with 1)
	 * @param	integer		last step
	 * @return	string		
	 */
	function getStepsBar($currentStep,$lastStep=4) {
		global $LANG;

		$bgcolor = t3lib_div::modifyHTMLcolor($this->pObj->doc->bgColor,-15,-15,-15);
		$nrcolor = t3lib_div::modifyHTMLcolor($bgcolor,30,30,30);

		$content='';
		$buttons='';

		for ($i = 1; $i <= $lastStep; $i++) {
			$color = ($i == $currentStep) ? '#000' : $nrcolor ;
			$content.= '<span style="margin-left:5px; margin-right:5px; color:'.$color.';">'.$i.'</span>';
		}
		$content = '<span style="margin-left:50px; margin-right:25px; vertical-align:middle; font-family:Verdana,Arial,Helvetica; font-size:22px; font-weight:bold;">'.$content.'</span>';

		if($currentStep > 1) {
			$buttons.= '<input type="submit" name="indexStep['.($currentStep-1).']" value="'.$LANG->getLL('tx_damindex_index.back').'" style="margin-right:10px;" />';
		}
if($currentStep==3){
$onClick='return TBE_EDITOR_checkSubmit(1);';
$onClick=' onclick="'.htmlspecialchars($onClick).'"';
}		
		if($currentStep < $lastStep) {
			$buttons.= '<input type="submit" name="indexStep['.($currentStep+1).']"'.$onClick.' value="'.$LANG->getLL('tx_damindex_index.next').'" />';
		} else {
			$onClick='showProgress();return true;';
			$buttons.= '<input type="submit" name="indexStart" value="'.$LANG->getLL('tx_damindex_index.start').'" onclick="'.htmlspecialchars($onClick).'" style="font-weight:bold;background-color: #6b6;" />';
		}
		$content.= '<span id="stepsFormButtons" style="margin-left:25px;vertical-align:middle;">'.$buttons.'</span>';

		return '<div style="padding:4px; background:'.$bgcolor.';">'.$content.'</div><br />';
	}


	/***************************************
	 *
	 *	 this and that
	 *
	 ***************************************/

	/**
	 * Processes the submitted data for the indexing setup
	 * 
	 * @return	void
	 */
	function processIndexSetup()	{
		global $SOBE, $BE_USER, $LANG, $BACK_PATH;
		
			// get stored indexing setup from last page view or last session
		$storedSetup = unserialize($SOBE->MOD_SETTINGS['tx_damindex_indexSetup']);
		if(is_array($storedSetup['ruleConf'])) {
			$this->index->ruleConf = t3lib_div::array_merge_recursive_overrule($this->index->ruleConf, $storedSetup['ruleConf']);
		}
		if(is_array($storedSetup['dataPreset'])) {
			$this->index->dataPreset = t3lib_div::array_merge_recursive_overrule($this->index->dataPreset, $storedSetup['dataPreset']);
		}
		if(is_array($storedSetup['dataPostset'])) {
			$this->index->dataPostset = t3lib_div::array_merge_recursive_overrule($this->index->dataPostset, $storedSetup['dataPostset']);
		}


			// merging values to the current indexing setup
		$data = t3lib_div::_POST('data');

		if (is_array($data['rules'])) {
			$this->index->mergeRuleConf($data['rules']);
		}
			

			// preset form
		if (is_array($data['tx_dam_simpleforms'][1])) {

				// get which fields are fixed
			$fixedFieldsArr = t3lib_div::_POST('data_fixedFields');
			$fixedFields=array();
			if (is_array($fixedFieldsArr['tx_dam_simpleforms'][1])) {
				foreach($fixedFieldsArr['tx_dam_simpleforms'][1] as $field => $isFixed) {
					if($isFixed) $fixedFields[] = $field;
				}
			}

				// split data to preset and fixed
			foreach($data['tx_dam_simpleforms'][1] as $field => $value) {
				if(in_array($field,$fixedFields)) {
					$this->index->dataPostset[$field] = $value;
				} else {
					$this->index->dataPreset[$field] = $value;
				}
			}
		}
		#debug($this->index->ruleConf, 'ruleConf', __LINE__, __FILE__);
		#debug($this->index->dataPreset, 'dataPreset', __LINE__, __FILE__);
		#debug($this->index->dataPostset, 'dataPostset', __LINE__, __FILE__);

		$this->saveSettings();
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function saveSettings() {
		global $SOBE;
		
		
		$setup = array(
			'ruleConf' => $this->index->ruleConf,
			'dataPreset' => $this->index->dataPreset,
			'dataPostset' => $this->index->dataPostset,
			);
		
		$newSettings = array(
			'tx_damindex_indexSetup' => serialize($setup),
			'tx_dam_folder' => $this->pObj->path,
		);
		$SOBE->MOD_SETTINGS = t3lib_BEfunc::getModuleData($SOBE->MOD_MENU, $newSettings, $SOBE->MCONF['name'], $SOBE->modMenu_type, $SOBE->modMenu_dontValidateList, $SOBE->modMenu_setDefaultList);
	}


	/**
	 * Creates a browsable file/folder list
	 * 
	 * @param	string		Path
	 * @param	string		Path
	 * @return	string		Output
	 */
	function getBrowseableFolderList ($path, $folderParam) {
		global $LANG, $FILEMOUNTS;
		
		$content = '';
		
		$filelist = t3lib_div::makeInstance('tx_dam_fileList');
		$filelist->folderParam = $folderParam;
		#$content.= $this->pObj->getPathInfoHeaderBar($this->pObj->path, $FILEMOUNTS[$this->pObj->fmountID], TRUE, '', 'up,refresh');
		$content.= $filelist->getBrowseableFolderList($path, TRUE);
		
		$cnBgColor = t3lib_div::modifyHTMLcolor($this->pObj->doc->bgColor3,-5,-5,-5);
		$content = '<div style="width:100%;background-color:'.$cnBgColor.'">'.$content.'</div>';
		return $content;
	}	


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/modfunc_index/class.tx_damindex_index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/modfunc_index/class.tx_damindex_index.php']);
}

?>