<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Infinite Scroll Gallery'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/flexform.xml');


//$TCA['tt_content']['types']['list']['subtypes_addlist']['infinitescrollgallery_pi1'] =
//'tx_infinitescrollgallery_enablemoreloading, tx_infinitescrollgallery_enablevideo, tx_infinitescrollgallery_showfilters,   tx_infinitescrollgallery_limit, tx_infinitescrollgallery_thumbnailmaximumwidth, tx_infinitescrollgallery_thumbnailmaximumheight, tx_infinitescrollgallery_imagemaximumwidth, tx_infinitescrollgallery_imagemaximumheight, tx_infinitescrollgallery_defaulttagfilter, tx_infinitescrollgallery_tagcategory, tx_infinitescrollgallery_tagpid, tx_infinitescrollgallery_orderby';
//// temporary lines: prevent in case tx_dam is not loaded
//if (!isset($TCA['tx_dam'])) {
//	$TCA['tx_dam'] = array(
//		'ctrl' => array(
//			'title' => 'LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang_db.xml:tx_dam',
//			'label' => 'title',
//			'tstamp' => 'tstamp',
//			'crdate' => 'crdate',
//			'cruser_id' => 'cruser_id',
//			'type' => 'media_type',
//			#		'sortby' => 'sorting',
//			'default_sortby' => 'ORDER BY title',
//			'delete' => 'deleted',
//
//			'enablecolumns' => array(
//				'disabled' => 'hidden',
//			),
//		),
//	);
//}
//
//if (TYPO3_MODE == "BE") {
////	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_infinitescrollgallery_pi1_wizicon"] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath
////	($_EXTKEY) . 'Classes/Backend/Wizicon.php';
//}
?>
