<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Fab.natural_gallery',
    'Pi1',
    'Natural Gallery'
);

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('natural_gallery');
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:natural_gallery/Configuration/FlexForm/NaturalGallery.xml');

if (TYPO3_MODE === "BE") {
	$TBE_MODULES_EXT["xMOD_db_new_content_el"]['addElClasses']['tx_naturalgallery_wizard'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('natural_gallery') . 'Classes/Backend/Wizard.php';
}
