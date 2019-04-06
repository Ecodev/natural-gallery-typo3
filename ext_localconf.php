<?php
defined('TYPO3_MODE') or die();

call_user_func(
    function () {

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

        // Register icons
        $icons = [
            'content-natural-gallery' => 'EXT:natural_gallery/Resources/Public/Images/NaturalGallery.png',
        ];
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        foreach ($icons as $identifier => $path) {
            $iconRegistry->registerIcon(
                $identifier, TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, ['source' => $path]
            );
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        tx_naturalgallery_templatebasedcontent {
                            iconIdentifier = content-natural-gallery
                            title = LLL:EXT:natural_gallery/Resources/Private/Language/locallang.xlf:wizard.title
                            description = LLL:EXT:natural_gallery/Resources/Private/Language/locallang.xlf:wizard.description
                            tt_content_defValues {
                                CType = list
                                list_type = naturalgallery_pi1
                            }
                        }
                    }
                }
            }'
        );
    }
);
