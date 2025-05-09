<?php
namespace Fab\NaturalGallery\Domain\Repository;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for querying content element
 */
class CategoryRepository extends Repository {

    /**
     * Initialize Repository
     */
    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(FALSE);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param array $identifiers
     * @return QueryResultInterface|array[]|object[]
     * @throws InvalidQueryException
     */
    public function findByIdentifiers(array $identifiers): array|QueryResultInterface
    {
        $result = null;
        if (!empty($identifiers)) {
            $query = $this->createQuery();
            $query->matching($query->in('uid', $identifiers));
            $result = $query->execute();
        }
        return $result;
    }
}
