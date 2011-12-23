<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Fabien Udriot <fabien.udriot@ecodev.ch>, Ecodev
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 ***************************************************************/

/**
 *
 *
 * @package infinite_scroll_gallery
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class Tx_InfiniteScrollGallery_Domain_Repository_TagRepository {

	/**
	 * Find tags
	 * @param array $contentObjectData
	 * @return array
	 */
	public function findAll($contentObjectData = array()) {

		/* @var $localCObj tslib_cObj */
		$localCObj = t3lib_div::makeInstance('tslib_cObj');

		$clause = '1 = 1 ' . $localCObj->enableFields('tx_tagpack_tags');
		if ($contentObjectData['tx_infinitescrollgallery_tagcategory'] > 0) {
			$clause .= ' AND category =  ' . $contentObjectData['tx_infinitescrollgallery_tagcategory'] . ' ';
		}
		if ($contentObjectData['tx_infinitescrollgallery_tagpid'] > 0) {
			$clause .= ' AND pid =  ' . $contentObjectData['tx_infinitescrollgallery_tagpid'] . ' ';
		}

		$sortBy = 'name ASC';
		$orderBy = '';

		$tags = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_tagpack_tags', $clause, $sortBy, $orderBy);
		#$request = $GLOBALS['TYPO3_DB']->SELECTquery('*', 'tx_tagpack_tags', $clause, $sortBy, $orderBy);
		#t3lib_utility_Debug::debug($request,'debug');
		foreach ($tags as $tag) {
			$outputTags[$tag['uid']] = $tag['name'];
		}
		return $outputTags;
	}

}
?>