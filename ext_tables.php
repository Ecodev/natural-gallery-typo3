<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Infinite Scroll Gallery'
);

// Add new columns to tt_content
t3lib_div::loadTCA('tt_content');

$tempColumns = array(
	'tx_infinitescrollgallery_enablemoreloading' => array (
          'exclude' => 0,
          'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_enablemoreloading',
          'config' => Array (
               'type' => "check",
          )
     ),
	'tx_infinitescrollgallery_limit' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_limit',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '20',
		)
	),
	'tx_infinitescrollgallery_thumbnailmaximumwidth' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_thumbnailmaximumwidth',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '180',
		)
	),
	'tx_infinitescrollgallery_thumbnailmaximumheight' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_thumbnailmaximumheight',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '150',
		)
	),
	'tx_infinitescrollgallery_imagemaximumwidth' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_imagemaximumwidth',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '700',
		)
	),
	'tx_infinitescrollgallery_imagemaximumheight' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_imagemaximumheight',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '600',
		)
	),
	'tx_infinitescrollgallery_defaulttagfilter' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_defaulttagfilter',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '0',
		)
	),
	'tx_infinitescrollgallery_tagcategory' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tagcategory',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '0',
		)
	),
	'tx_infinitescrollgallery_tagpid' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tagpid',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '0',
		)
	),
	'tx_infinitescrollgallery_orderby' => array (
          'exclude' => 0,
          'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_orderby',
		'config' => array (
                'default' => 'tx_dam.crdate DESC',
                'type' => 'select',
                'items' => array (

					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.tstampasc', 'tx_dam.tstamp ASC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.tstampdesc', 'tx_dam.tstamp DESC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.crdateasc', 'tx_dam.crdate ASC'),
                    array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.crdatedesc', 'tx_dam.crdate DESC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.sortingasc', 'tx_dam.sorting ASC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.sortingdesc', 'tx_dam.sorting DESC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.titleasc', 'tx_dam.title ASC'),
					array ('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tx_dam.titledesc', 'tx_dam.title DESC'),
                ),
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
         )
     ),
);
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);

$TCA['tt_content']['types']['list']['subtypes_addlist']['infinitescrollgallery_pi1'] = 'tx_infinitescrollgallery_enablemoreloading, tx_infinitescrollgallery_limit, tx_infinitescrollgallery_thumbnailmaximumwidth, tx_infinitescrollgallery_thumbnailmaximumheight, tx_infinitescrollgallery_imagemaximumwidth, tx_infinitescrollgallery_imagemaximumheight, tx_infinitescrollgallery_defaulttagfilter, tx_infinitescrollgallery_tagcategory, tx_infinitescrollgallery_tagpid, tx_infinitescrollgallery_orderby';


// temporary lines: prevent in case tx_dam is not loaded
if (!isset($TCA['tx_dam'])) {
	$TCA['tx_dam'] = array(
		'ctrl' => array(
			'title' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_dam',
			'label' => 'title',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'type' => 'media_type',
			#		'sortby' => 'sorting',
			'default_sortby' => 'ORDER BY title',
			'delete' => 'deleted',

			'enablecolumns' => array(
				'disabled' => 'hidden',
			),
			#'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tx_dam.php',
		),
	);
}

if (TYPO3_MODE == "BE") {
	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_infinitescrollgallery_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Backend/Wizicon.php';
}
?>
