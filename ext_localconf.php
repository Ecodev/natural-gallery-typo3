<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Fab.infinite_scroll_gallery',
    'Pi1',
    array(
        'Gallery' => 'list', 'listAjax'
    ),
    // non-cacheable actions
    array(
        'Gallery' => 'listAjax',
    )
);
