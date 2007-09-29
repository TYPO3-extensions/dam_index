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
	'version' => '1.0.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Rene Fritz',
	'author_email' => 'r.fritz@colorcube.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'dam' => '',
			'php' => '3.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:15:{s:9:"ChangeLog";s:4:"7c40";s:21:"ext_conf_template.txt";s:4:"a5f0";s:12:"ext_icon.gif";s:4:"6103";s:14:"ext_tables.php";s:4:"b91d";s:41:"modfunc_index/class.tx_damindex_index.php";s:4:"6df3";s:27:"modfunc_index/locallang.xml";s:4:"18d0";s:31:"lib/class.tx_damindex_rules.php";s:4:"0209";s:28:"lib/locallang_indexrules.xml";s:4:"9705";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"42d3";s:14:"mod1/index.php";s:4:"47f8";s:18:"mod1/locallang.xml";s:4:"37b0";s:22:"mod1/locallang_mod.xml";s:4:"6cab";s:19:"mod1/moduleicon.gif";s:4:"4cbf";s:14:"doc/manual.sxw";s:4:"ec3d";}',
);

?>