<?php
namespace TYPO3\CMS\InfiniteScrollGallery\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Fabien Udriot
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * View helper for translating
 *
 * = Examples =
 * @todo remove me!
 */
class TranslateViewHelper extends \Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Initialize
	 */
	public function initialize() {
		if (!is_object($GLOBALS['TSFE']->language)) {

			// TypoScript configuration
			$configuration = $GLOBALS['TSFE']->tmpl->setup['config.'];

			$language = $configuration['language'] == '' ? 'default' : $configuration['language'];

			// Initialize language object
			$GLOBALS['TSFE']->language = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Language');
			$GLOBALS['TSFE']->language->lang = $language;
			$GLOBALS['TSFE']->language->charSet = 'utf-8';
			$GLOBALS['TSFE']->language->includeLLFile('EXT:infinite_scroll_gallery/Resources/Private/Language/locallang.xlf');
		}
	}

	/**
	 * Translate a key
	 *
	 * @param string $key
	 * @return string
	 */
	public function render($key) {

		// TypoScript configuration
		$configuration = $GLOBALS['TSFE']->tmpl->setup['config.'];

		$language = $configuration['language'] == '' ? 'default' : $configuration['language'];

		$alternativeLabels = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_infinitescrollgallery.'];

		if(!empty($alternativeLabels['_LOCALLANG.'][$language . '.'][$key])) {
			$label = $alternativeLabels['_LOCALLANG.'][$language . '.'][$key];
		}
		else {
			$label = $GLOBALS['TSFE']->language->getLL($key);
		}

		#plugin.tx_infinitescrollgallery._LOCALLANG.default.filter_by_tag = asdf
		return  $label;
	}
}
?>