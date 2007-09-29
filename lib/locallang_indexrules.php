<?php
$LOCAL_LANG = Array (
	'default' => Array (
		'recursive.title' => 'Index sub folders',
		
		'folderAsCat.title' => 'Categorize files from folder names',
		'folderAsCat.desc' => 'Categories will be attached to files if a category has the same name as the files folder.',
		'folderAsCat.use_fuzzy' => 'Use fuzzy folder/category comparison.',
		
		'dryRun.title' => 'Dry run',
		'dryRun.desc' => 'Do not actually insert any data into the DAM; just print what would happen.',
		
		'doReindexing.title' => 'Reindexing',
		'doReindexing.desc' => 'Reindex files and update meta data.',
		'doReindexing.use_overwriteEmptyFields' => 'Files will be reindexed. Only meta data will be get which is still missing.',
		'doReindexing.use_reindexPreserve' => 'Files will be reindexed. Old meta data will be used if now new is available. Will overwrite data!',
		'doReindexing.overwriteEmptyFields' => 'Only get meta data which is still missing.',
		'doReindexing.reindexPreserve' => 'Overwrite current meta data with data from file. Will overwrite the meta data but preserve those where\'s no data available from the file itself.',

		'devel.title' => 'Delete all index data previously (for testing)',
	),
	'de' => Array (
		'recursive.title' => 'Unterverzeichnisse mit einbeziehen',
		
		'folderAsCat.title' => 'Verzeichnisse als Kategorien verwenden',
		'folderAsCat.desc' => 'Kategorien werden gesetzt falls die Datei in einem gleichnamigen Verzeichnis liegt.',
		'folderAsCat.use_fuzzy' => 'Verzeichnisnamen auf Kategorienamen �bertragen.',
		
		'dryRun.title' => 'Testlauf',
		'dryRun.desc' => 'Es werden keine Daten zum DAM hinzugef�gt sondern nur gezeigt was passieren w�rde',
		
		'doReindexing.title' => 'Neu-Indizierung',
		'doReindexing.desc' => 'Metadaten f�r bereits indizierte Dateien neu einlesen.',
		'doReindexing.use_overwriteEmptyFields' => 'Metadaten f�r bereits indizierte Dateien werden neu eingelesen. Nur fehlende Daten werden erg�nzt',
		'doReindexing.use_reindexPreserve' => 'Aktuelle Metadaten �berschreiben. Alte Daten werden verwendet falls keine neuen zur Verf�gung stehen.',
		'doReindexing.overwriteEmptyFields' => 'Nur Metadaten einf�gen welche bisher noch nicht zur Verf�gung stand.',
		'doReindexing.reindexPreserve' => 'Aktuelle Metadaten �berschreiben. Alte Daten werden verwendet falls keine neuen zur Verf�gung stehen.',
	),

);
?>