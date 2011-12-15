<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Infinite Scroll Gallery'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Infinite Scroll Gallery');


t3lib_extMgm::addLLrefForTCAdescr('tx_infinitescrollgallery_domain_model_gallery', 'EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_csh_tx_infinitescrollgallery_domain_model_gallery.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_infinitescrollgallery_domain_model_gallery');
$TCA['tx_infinitescrollgallery_domain_model_gallery'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_domain_model_gallery',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Gallery.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_infinitescrollgallery_domain_model_gallery.gif'
	),
);



// Add new columns to tt_content
t3lib_div::loadTCA('tt_content');

$tempColumns = array(
	'tx_infinitescrollgallery_limit' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_limit',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '10',
		)
	),
	'tx_infinitescrollgallery_thumbnailmaximumwidth' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_thumbnailmaximumwidth',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '100',
		)
	),
	'tx_infinitescrollgallery_thumbnailmaximumheight' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_thumbnailmaximumheight',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '100',
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
			'default' => '700',
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
			'default' => '2',
		)
	),
	'tx_infinitescrollgallery_tagpid' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_infinitescrollgallery_tagpid',
		'config' => array(
			'type' => 'input',
			'size' => '40',
			'max' => '256',
			'default' => '34',
		)
	),
);
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);

$TCA['tt_content']['types']['list']['subtypes_addlist']['infinitescrollgallery_pi1'] = 'tx_infinitescrollgallery_limit, tx_infinitescrollgallery_thumbnailmaximumwidth, tx_infinitescrollgallery_thumbnailmaximumheight, tx_infinitescrollgallery_imagemaximumwidth, tx_infinitescrollgallery_imagemaximumheight, tx_infinitescrollgallery_defaulttagfilter, tx_infinitescrollgallery_tagcategory, tx_infinitescrollgallery_tagpid';
?>
