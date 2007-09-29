<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE')	{
		

	$tempSetup =  unserialize($_EXTCONF);		
		
	if ($tempSetup['add_media_file_indexing']) {	

		t3lib_extMgm::insertModuleFunction(
			'txdamM1_txdamfileM1',		
			'tx_damindex_index',
			t3lib_extMgm::extPath($_EXTKEY).'modfunc_index/class.tx_damindex_index.php',
			'LLL:EXT:dam_index/modfunc_index/locallang.php:tx_damindex_index.title'
		);
	}
	
	if ($tempSetup['add_media_indexing']) {
		t3lib_extMgm::addModule('txdamM1','txdamindexM1','',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
		
		t3lib_extMgm::insertModuleFunction(
			'txdamM1_txdamindexM1',		
			'tx_damindex_index',
			t3lib_extMgm::extPath($_EXTKEY).'modfunc_index/class.tx_damindex_index.php',
			'LLL:EXT:dam_index/modfunc_index/locallang.php:tx_damindex_index.title'
		);	
	}


	$TYPO3_CONF_VARS['EXTCONF']['dam']['indexRuleClasses']['tx_damindex_rule_recursive'] = 'EXT:dam_index/lib/class.tx_damindex_rules.php:&tx_damindex_rule_recursive';
	$TYPO3_CONF_VARS['EXTCONF']['dam']['indexRuleClasses']['tx_damindex_rule_folderAsCat'] = 'EXT:dam_index/lib/class.tx_damindex_rules.php:&tx_damindex_rule_folderAsCat';
	$TYPO3_CONF_VARS['EXTCONF']['dam']['indexRuleClasses']['tx_damindex_rule_doReindexing'] = 'EXT:dam_index/lib/class.tx_damindex_rules.php:&tx_damindex_rule_doReindexing';
	$TYPO3_CONF_VARS['EXTCONF']['dam']['indexRuleClasses']['tx_damindex_rule_dryRun'] = 'EXT:dam_index/lib/class.tx_damindex_rules.php:&tx_damindex_rule_dryRun';
#	$TYPO3_CONF_VARS['EXTCONF']['dam']['indexRuleClasses']['tx_damindex_rule_devel'] = 'EXT:dam_index/lib/class.tx_damindex_rules.php:&tx_damindex_rule_devel';

}

?>