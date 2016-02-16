<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Fab.natural_gallery',
    'Pi1',
    array(
        'Gallery' => 'list',
    ),
    // non-cachable actions
    array(
        #'Gallery' => 'list',
    )
);
