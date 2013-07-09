<?php
namespace TYPO3\CMS\InfiniteScrollGallery\Domain\Repository;
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
 * Repository for querying content element
 *
 */
class ContentRepository {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseHandler;

	/**
	 * Constructor
	 */
	public function __construct(){
		$this->databaseHandler = $GLOBALS['TYPO3_DB'];
	}
	/**
	 * @param int $uid
	 * @return array|NULL
	 */
	public function findByUid($uid) {
		$record = $this->databaseHandler->exec_SELECTgetSingleRow('*', 'tt_content',
			'uid =' . $this->databaseHandler->fullQuoteStr($uid, 'tt_content'));


		return $record;
	}
}
?>