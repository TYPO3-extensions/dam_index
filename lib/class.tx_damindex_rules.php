<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2006 Rene Fritz (r.fritz@colorcube.de)
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
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   79: class tx_damindex_rules
 *
 *
 *   93: class tx_damindex_rule_recursive extends tx_dam_indexRuleBase
 *  100:     function getTitle()
 *
 *
 *  116: class tx_damindex_rule_folderAsCat extends tx_dam_indexRuleBase
 *  123:     function getTitle()
 *  133:     function getDescription()
 *  143:     function getOptionsForm()
 *  159:     function getOptionsInfo()
 *  174:     function processMeta($meta)
 *
 *
 *  207: class tx_damindex_rule_doReindexing extends tx_dam_indexRuleBase
 *  214:     function getTitle()
 *  224:     function getDescription()
 *  234:     function getOptionsForm()
 *  253:     function getOptionsInfo()
 *  270:     function processMeta($meta, $absFile, $idxObj)
 *
 *
 *  313: class tx_damindex_rule_dryRun extends tx_dam_indexRuleBase
 *  320:     function getTitle()
 *  330:     function getDescription()
 *
 *
 *  346: class tx_damindex_rule_devel extends tx_dam_indexRuleBase
 *  353:     function getTitle()
 *  363:     function getDescription()
 *  374:     function preIndexing()
 *
 * TOTAL FUNCTIONS: 16
 * (This index is automatically created/updated by the script "update-class-index")
 *
 */

require_once(PATH_txdam.'lib/class.tx_dam_indexrulebase.php');



class tx_damindex_rules {
	// dummy for extmgm not to throw errors
}



/**
 * Index rule plugin for the DAM
 * Recursive
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_recursive extends tx_dam_indexRuleBase {

	/**
	 * Returns the title of the index rule
	 *
	 * @return	string	Title
	 */
	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:recursive.title');
	}

}


/**
 * Index rule plugin for the DAM
 * Folder as category
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_folderAsCat extends tx_dam_indexRuleBase {

	/**
	 * Returns the title of the index rule
	 *
	 * @return	string	Title
	 */
	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:folderAsCat.title');
	}

	/**
	 * Returns the description of the index rule
	 *
	 * @return	string	Description
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:folderAsCat.desc');
	}

	/**
	 * Returns the options form
	 *
	 * @return	string	HTML content
	 */
	function getOptionsForm()	{
		global $LANG;

		$code = array();
		$code[1][1] = 	'<input type="hidden" name="data[rules][tx_damindex_rule_folderAsCat][fuzzy]" value="0" />'.
						'<input type="checkbox" name="data[rules][tx_damindex_rule_folderAsCat][fuzzy]"'.($this->setup['fuzzy']?' checked="checked"':'').' value="1" />&nbsp;';
		$code[1][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:folderAsCat.use_fuzzy');
		return $GLOBALS['SOBE']->doc->table($code);
	}

	/**
	 * Returns some information what options are selected.
	 * This is for user feedback.
	 *
	 * @return	string	HTML content
	 */
	function getOptionsInfo()	{
		global $LANG;
		if($this->setup['fuzzy']) {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:folderAsCat.use_fuzzy');
		}
		return $out;
	}

	/**
	 * For processing the meta data BEFORE the index is written
	 *
	 * @param	array		$meta Meta data array
	 * @param	string		$absFile Filename
	 * @return	array Processed meta data array
	 */
	function processMeta($meta)	{
		$folder = basename(preg_replace('#/$#','',$meta['file_path']));
		if ($folder) {

			if($this->setup['fuzzy']) {
				$folder = str_replace ('_', ' ', $folder);
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_dam_cat', 'title LIKE '.$GLOBALS['TYPO3_DB']->fullQuoteStr('%'.$folder.'%', 'tx_dam_cat'));
			} else {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tx_dam_cat', 'title='.$GLOBALS['TYPO3_DB']->fullQuoteStr($folder, 'tx_dam_cat'));
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
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_doReindexing extends tx_dam_indexRuleBase {

	/**
	 * Returns the title of the index rule
	 *
	 * @return	string	Title
	 */
	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.title');
	}

	/**
	 * Returns the description of the index rule
	 *
	 * @return	string	Description
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.desc');
	}

	/**
	 * Returns the options form
	 *
	 * @return	string	HTML content
	 */
	function getOptionsForm()	{
		global $LANG;

		$code = array();
		$code[1][1] = 	'<input type="hidden" name="data[rules][tx_damindex_rule_doReindexing][mode]" value="0" />'.
						'<input type="radio" name="data[rules][tx_damindex_rule_doReindexing][mode]"'.(($this->setup['mode']==1)?' checked="checked"':'').' value="1" />&nbsp;';
		$code[1][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.overwriteEmptyFields');

		$code[2][1] = 	'<input type="radio" name="data[rules][tx_damindex_rule_doReindexing][mode]"'.(($this->setup['mode']==2)?' checked="checked"':'').' value="2" />&nbsp;';
		$code[2][2] = $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.reindexPreserve');
		return $GLOBALS['SOBE']->doc->table($code);
	}

	/**
	 * Returns some information what options are selected.
	 * This is for user feedback.
	 *
	 * @return	string	HTML content
	 */
	function getOptionsInfo()	{
		global $LANG;
		if ($this->setup['mode']=='1') {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.use_overwriteEmptyFields');
		} elseif ($this->setup['mode']=='2') {
			$out .= $this->getEnabledIcon().$LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:doReindexing.use_reindexPreserve');
		}
		return $out;
	}

	/**
	 * For processing the meta data BEFORE the index is written
	 *
	 * @param	array		$meta Meta data array
	 * @param	string		$absFile Filename
	 * @return	array Processed meta data array
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

// TODO use $idxObj->getFileNodeInfo() here?
				// no matter what the mode is the new file info (esp. mtime) should be renewed
			$meta['fields']['file_mtime'] = $file_mtime;
			$meta['fields']['file_ctime'] = $file_ctime;
			$meta['fields']['file_inode'] = $file_inode;
			$meta['fields']['file_size'] = $file_size;
// TODO compute new checksum here?
		}

		return $meta;
	}

}




/**
 * Index rule plugin for the DAM
 * Dry run
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_dryRun extends tx_dam_indexRuleBase {

	/**
	 * Returns the title of the index rule
	 *
	 * @return	string	Title
	 */
	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:dryRun.title');
	}

	/**
	 * Returns the description of the index rule
	 *
	 * @return	string	Description
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:dryRun.desc');
	}
}



/**
 * Index rule plugin for the DAM
 * Devel
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damindex_rule_devel extends tx_dam_indexRuleBase {

	/**
	 * Returns the title of the index rule
	 *
	 * @return	string	Title
	 */
	function getTitle()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:devel.title');
	}

	/**
	 * Returns the description of the index rule
	 *
	 * @return	string	Description
	 */
	function getDescription()	{
		global $LANG;
		return $LANG->sL('LLL:EXT:dam_index/lib/locallang_indexrules.xml:devel.desc');
	}

	/**
	 * Will be called before the indexing.
	 * Can be used to initialize things
	 *
	 * @return	void
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