<?php
#namespace Fab\InfiniteScrollGallery\Backend;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class that adds the wizard icon.
 */
class tx_infinitescrollgallery_wizard
{

    /**
     * Processing the wizard items array
     *
     * @param array $wizardItems : The wizard items
     * @return array
     */
    function proc($wizardItems)
    {
        $wizardItems['plugins_tx_infinitescrollgallery_pi1'] = array(
            'icon' => ExtensionManagementUtility::extRelPath('infinite_scroll_gallery') . 'Resources/Public/Images/InfiniteScrollGallery.png',
            'title' => $this->getLanguageService()->sL('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang.xlf:wizard.title'),
            'description' => $this->getLanguageService()->sL('LLL:EXT:infinite_scroll_gallery/Resources/Private/Language/locallang.xlf:wizard.description'),
            'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=infinitescrollgallery_pi1'
        );

        return $wizardItems;
    }

    /**
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
