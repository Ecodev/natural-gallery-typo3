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
class Tx_InfiniteScrollGallery_Domain_Repository_ImageRepository {

	/**
	 * Find images
	 *
	 * @param Tx_Extbase_MVC_Web_Request $request
	 * @param $limit
	 * @param array $contentObjectData
	 * @return array
	 */
	public function findAll(Tx_Extbase_MVC_Web_Request $request, $limit, $contentObjectData = array()) {

		$clause = $this->getClause();
		$sortBy = 'tx_dam.crdate DESC';
		$groupBy = '';

		$tag = $request->hasArgument('tag') ? $request->getArgument('tag') : 0;
		$defaultTagFilter = $contentObjectData['tx_infinitescrollgallery_defaulttagfilter'];
		$tag = $tag . ',' . $defaultTagFilter; // merge default tag with tag given as parameter
		$searchString = $request->hasArgument('search') ? $request->getArgument('search') : '';
		$clause = $this->getClause($tag, $searchString);
		/* @var $GLOBALS t3lib_DB */
		$images = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_dam', $clause, $groupBy, $sortBy, $limit);
		return $images;
	}

	/**
	 * Find images
	 *
	 * @param Tx_Extbase_MVC_Web_Request $request
	 * @param $limit
	 * @param array $contentObjectData
	 * @return array
	 */
	public function findStock(Tx_Extbase_MVC_Web_Request $request, $limit, $contentObjectData = array()) {

		$clause = $this->getClause();
		$sortBy = 'tx_dam.crdate DESC';
		$groupBy = '';

		$tag = $request->hasArgument('tag') ? $request->getArgument('tag') : 0;
		$defaultTagFilter = $contentObjectData['tx_infinitescrollgallery_defaulttagfilter'];
		$tag = $tag . ',' . $defaultTagFilter; // merge default tag with tag given as parameter
		$searchString = $request->hasArgument('search') ? $request->getArgument('search') : '';

		//$defaultTagFilter = $contentObjectData['tx_infinitescrollgallery_defaulttagfilter'];
		$clause = $this->getClause($tag, $searchString);

		/* @var $GLOBALS t3lib_DB */
		$images = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_dam', $clause, $groupBy, $sortBy, $limit);
		#$request = $GLOBALS['TYPO3_DB']->SELECTquery('*', 'tx_dam', $clause, $groupBy, $sortBy, $limit);
		#t3lib_utility_Debug::debug($request,'debug');
		return $images;
	}

	/**
	 * function count all images
	 * @param Tx_Extbase_MVC_Web_Request $request
	 * @param $limit
	 * @param array $contentObjectData
	 * @return number of images
	 */
	public function countImages(Tx_Extbase_MVC_Web_Request $request, $limit, $contentObjectData = array()) {

		$searchString = $request->hasArgument('search') ? $request->getArgument('search') : '';

		$tag = $request->hasArgument('tag') ? $request->getArgument('tag') : 0;
		$defaultTagFilter = $contentObjectData['tx_infinitescrollgallery_defaulttagfilter'];
		$tag = $tag . ',' . $defaultTagFilter; // merge default tag with tag given as parameter

		$clause = $this->getClause($tag, $searchString);
		$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('count(*) AS totalOfImages', 'tx_dam', $clause);

		return $record['totalOfImages'];
	}

	/**
	 * Get clause part
	 *
	 * @param string $tag a comma separated tags id to be potentially searched (e.g 1,2,3)
	 * @param string $searchString a search string to be potentially searched
	 * @return string the clause part
	 */
	protected function getClause($tag = 0, $searchString = '') {

		/* @var $localCObj tslib_cObj */
		static $defaultTag = 0;
		$localCObj = t3lib_div::makeInstance('tslib_cObj');
		$clause = 'media_type = 2 ' . $localCObj->enableFields('tx_dam');
		if ($searchString !== '') {
			$clause .= ' AND (title LIKE "%' . $searchString . '%" OR description LIKE "%' . $searchString . '%" OR author LIKE "%' . $searchString . '%" OR date_production LIKE "%' . $searchString . '%" OR comment LIKE "%' . $searchString . '%")';
		}

		$tags = explode(",", $tag);
		$tags = array_filter($tags);

		if (!empty($tags)) {
			foreach ($tags as $_tag) {
				$clause .= ' AND uid IN (SELECT uid_foreign FROM tx_tagpack_tags_relations_mm WHERE tablenames = "tx_dam" AND uid_local = ' . $_tag . ') ';
			}
		}

		return $clause;
	}

}
?>