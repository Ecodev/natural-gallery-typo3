<?php

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
 * View helper for rendering styles
 *
 * = Examples =
 */
class Tx_InfiniteScrollGallery_ViewHelpers_StyleViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Inject CSS file in header or code. See examples in ScriptViewHelper
	 * the pragma is identical - only the output wrapper tags are different.
	 *
	 * @param string $file
	 * @param bool $cache If true, file(s) is cached
	 * @param bool $concat If true, files are concatenated (makes sense if $file is array)
	 * @param bool $compress If true, files are compressed using JSPacker
	 * @return string
	 */
	public function render($file=NULL, $cache=FALSE, $concat=FALSE, $compress=FALSE) {

		/* @var $pagerender t3lib_pagerenderer */
		$pagerender = $GLOBALS['TSFE']->getPageRenderer();

		if ($file === NULL) {
			$content = $this->renderChildren();
			$pagerender->addCssInlineBlock(md5($content), $content, $compress, $forceOnTop);
		} else if (is_array($file)) {
			$files = $file;
			foreach ($files as $file) {
				$file = $this->resolvePath($file);
				$pagerender->addCssFile($file);
			}
		} else {
			$file = $this->resolvePath($file);
			$pagerender->addCssFile($file);
		}
		return NULL;
	}

	/**
	 * Resolves the path
	 *
	 * @param string $filename
	 * @return string
	 */
	protected function resolvePath($filename) {
		if (substr($filename, 0, 4) == 'EXT:') { // extension
			list($extKey, $local) = explode('/', substr($filename, 4), 2);
			$filename = '';
			if (strcmp($extKey, '') && t3lib_extMgm::isLoaded($extKey) && strcmp($local, '')) {
				$filename = t3lib_extMgm::siteRelPath($extKey) . $local;
			}
		}
		return htmlspecialchars($filename);
	}

}

?>
