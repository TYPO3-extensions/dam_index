<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2005 Ren� Fritz (r.fritz@colorcube.de)
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
 * Index rule plugins for the DAM.
 * Part of the DAM (digital asset management) extension.
 *
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   86: class tx_damindex_rule_recursive extends tx_dam_indexRuleBase 
 *   88:     function getTitle()	
 *
 *
 *  107: class tx_damindex_rule_folderAsCat extends tx_dam_indexRuleBase 
 *  109:     function getTitle()	
 *  119:     function getDescription()	
 *  129:     function getOptionsForm()	
 *  144:     function getOptionsInfo()	
 *  158:     function processMeta($meta)	
 *
 *
 *  193: class tx_damindex_rule_doReindexing extends tx_dam_indexRuleBase 
 *  195:     function getTitle()	
 *  205:     function getDescription()	
 *  215:     function getOptionsForm()	
 *  233:     function getOptionsInfo()	
 *  251:     function processMeta($meta, $absFile, $idxObj)	
 *
 *
 *  279: class tx_damindex_rule_dryRun extends tx_dam_indexRuleBase 
 *  281:     function getTitle()	
 *  291:     function getDescription()	
 *
 *
 *  308: class tx_damindex_rule_devel extends tx_dam_indexRuleBase 
 *  310:     function getTitle()	
 *  320:     function getDescription()	
 *  330:     function preIndexing()	
 *
 * TOTAL FUNCTIONS: 16
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_txdam.'lib/class.tx_dam_indexrulebase.php');




/**
 * Index rule plugin for the DAM
 * Recursive
 * 
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_recursive extends tx_dam_indexRuleBase {

	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:recursive.title');
	}

}

class tx_damindex_rules {
	// dummy for extmgm not to trow errors
}


/**
 * Index rule plugin for the DAM
 * Folder as category
 * 
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_folderAsCat extends tx_dam_indexRuleBase {

	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:folderAsCat.title');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:folderAsCat.desc');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getOptionsForm()	{
		global $LANG, $SOBE;

		$code = array();
		$code[1][1] = 	'<input type="hidden" name="data[rules][tx_damindex_rule_folderAsCat][fuzzy]" value="0" />'.
						'<input type="checkbox" name="data[rules][tx_damindex_rule_folderAsCat][fuzzy]"'.($this->setup['fuzzy']?' checked="checked"':'').' value="1" />&nbsp;';
		$code[1][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:folderAsCat.use_fuzzy');
		return $SOBE->doc->table($code);
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getOptionsInfo()	{
		global $LANG;
		if($this->setup['fuzzy']) {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:folderAsCat.use_fuzzy');
		}
		return $out;
	}

	/**
	 * [Describe function...]
	 * 
	 * @param	[type]		$meta: ...
	 * @return	[type]		...
	 */
	function processMeta($meta)	{
		$folder = basename(preg_replace('#/$#','',$meta['file_path']));
		if ($folder) {

			if($this->setup['fuzzy']) {
				$folder = str_replace ('_', ' ', $folder);
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_dam_cat', 'title LIKE "%'.$GLOBALS['TYPO3_DB']->quoteStr($folder, 'tx_dam_cat').'%"');
			} else {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_dam_cat', 'title="'.$GLOBALS['TYPO3_DB']->quoteStr($folder, 'tx_dam_cat').'"');
			}
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			if ($row['uid']) {
				$meta['fields']['category'].= ',tx_dam_cat_'.$row['uid'];
			}

		}
		return $meta;
	}

}




/**
 * Index rule plugin for the DAM
 * Reindexing
 * 
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_doReindexing extends tx_dam_indexRuleBase {

	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.title');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.desc');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getOptionsForm()	{
		global $LANG, $SOBE;

		$code = array();
		$code[1][1] = 	'<input type="hidden" name="data[rules][tx_damindex_rule_doReindexing][mode]" value="0" />'.
						'<input type="radio" name="data[rules][tx_damindex_rule_doReindexing][mode]"'.(($this->setup['mode']==1)?' checked="checked"':'').' value="1" />&nbsp;';
		$code[1][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.overwriteEmptyFields');

		$code[2][1] = 	'<input type="radio" name="data[rules][tx_damindex_rule_doReindexing][mode]"'.(($this->setup['mode']==2)?' checked="checked"':'').' value="2" />&nbsp;';
		$code[2][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.reindexPreserve');
		return $SOBE->doc->table($code);
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getOptionsInfo()	{
		global $LANG;
		if ($this->setup['mode']=='1') {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.use_overwriteEmptyFields');
		} elseif ($this->setup['mode']=='2') {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:doReindexing.use_reindexPreserve');
		}
		return $out;
	}

	/**
	 * [Describe function...]
	 * 
	 * @param	[type]		$meta: ...
	 * @param	[type]		$absFile: ...
	 * @param	[type]		$idxObj: ...
	 * @return	[type]		...
	 */
	function processMeta($meta, $absFile, $idxObj)	{
		if (is_array($meta['row']))	{

				// this is the new file info ...
			$file_mtime = $meta['fields']['file_mtime'];
			$file_ctime = $meta['fields']['file_ctime'];
			$file_inode = $meta['fields']['file_inode'];
			$file_size = $meta['fields']['file_size'];

			if ($this->setup['mode']=='1') {
					// overwrite empty fields
				$meta['fields'] = t3lib_div::array_merge_recursive_overrule($meta['fields'],$meta['row'], FALSE, FALSE);
			} elseif ($this->setup['mode']=='2') {
					// preserve old data if new is empty
				$meta['fields'] = t3lib_div::array_merge_recursive_overrule($meta['row'],$meta['fields'], FALSE, FALSE);
			}

#TODO use $idxObj->getFileNodeInfo() here?
				// no matter what the mode is the new file info (esp. mtime) should be renewed
			$meta['fields']['file_mtime'] = $file_mtime;
			$meta['fields']['file_ctime'] = $file_ctime;
			$meta['fields']['file_inode'] = $file_inode;
			$meta['fields']['file_size'] = $file_size;
#TODO compute new checksum here?
		}

		return $meta;
	}

}




/**
 * Index rule plugin for the DAM
 * Dry run
 * 
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_dryRun extends tx_dam_indexRuleBase {

	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:dryRun.title');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:dryRun.desc');
	}
}



/**
 * Index rule plugin for the DAM
 * Devel
 * 
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_devel extends tx_dam_indexRuleBase {

	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:devel.title');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.php:devel.desc');
	}

	/**
	 * [Describe function...]
	 * 
	 * @return	[type]		...
	 */
	function preIndexing()	{
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_dam', '');
		$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_dam_metypes_avail', '');
	}

}

//if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/lib/class.tx_damindex_rules.php'])	{
//	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_index/lib/class.tx_damindex_rules.php']);
//}

?>