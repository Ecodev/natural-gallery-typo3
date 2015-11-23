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

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * Repository for querying content element
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository {

	/**
	 * Initialize Repository
	 */
	public function initializeObject() {

		/** @var Typo3QuerySettings $querySettings */
		$querySettings = $this->objectManager->get(Typo3QuerySettings::class);
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);
	}

	/**
	 * @param array $identifiers
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	public function findByIdentifiers(array $identifiers) {
		$query = $this->createQuery();
		$query->matching($query->in('uid', $identifiers));
		return $query->execute();
	}
}