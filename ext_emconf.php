<?php

########################################################################
# Extension Manager/Repository config file for ext: "dam_index"
#
# Auto generated 29-09-2007 00:47
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Media>Indexing',
	'description' => 'Provides a Media submodule for mass indexing of files.',
	'category' => 'module',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'René Fritz',
	'author_email' => 'r.fritz@colorcube.de',
	'author_company' => 'Colorcube - digital media lab',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.2.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '3.5.0-0.0.0',
			'php' => '3.0.0-0.0.0',
			'dam' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:21:"ext_conf_template.txt";s:4:"a5f0";s:12:"ext_icon.gif";s:4:"6103";s:14:"ext_tables.php";s:4:"6d8f";s:41:"modfunc_index/class.tx_damindex_index.php";s:4:"2f87";s:27:"modfunc_index/locallang.php";s:4:"8953";s:31:"lib/class.tx_damindex_rules.php";s:4:"fec2";s:28:"lib/locallang_indexrules.php";s:4:"785d";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"348d";s:14:"mod1/index.php";s:4:"8926";s:18:"mod1/locallang.php";s:4:"c5f7";s:22:"mod1/locallang_mod.php";s:4:"8606";s:19:"mod1/moduleicon.gif";s:4:"4cbf";s:14:"doc/manual.sxw";s:4:"ec3d";}',
);

?>