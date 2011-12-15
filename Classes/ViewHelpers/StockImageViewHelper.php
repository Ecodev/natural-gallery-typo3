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
 * View helper for rendering script
 *
 * = Examples =
 */
class Tx_InfiniteScrollGallery_ViewHelpers_StockImageViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Generate a JSON array of images suiting xoyview
	 *
	 * @param array $images containing images
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return string
	 */
	public function render($images, $maxWidth, $maxHeight) {

		// CObj
		$localCObj = t3lib_div::makeInstance('tslib_cObj');
		#$localCObj->start(array(), '');
		
		$output = array();
		foreach ($images as $image) {
			
			// Generating thumbnails
			$configThumbnail = array();
			$configThumbnail['file'] = 'fileadmin/user_upload/resources/' . $image['file_name'];
			$configThumbnail['file.']['maxW'] = $maxWidth; 
			$configThumbnail['file.']['maxH'] = $maxHeight; 
			$htmlThumbnail = $localCObj->IMG_RESOURCE($configThumbnail);
			
			$output[] = array(
				'media' => array(
					'src' => $localCObj->IMG_RESOURCE($configThumbnail),
					'title' => $image['title'],
				),
			);
		}
		return json_encode($output);
	}
}
?>