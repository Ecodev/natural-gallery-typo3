<?php
namespace Fab\InfiniteScrollGallery\Domain\Repository;

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

/**
 * Repository for querying content element
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