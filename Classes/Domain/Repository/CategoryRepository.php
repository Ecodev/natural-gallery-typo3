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

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for querying content element
 */
class CategoryRepository
{

    /**
     * Initialize Repository
     */
    protected string $tableName = 'sys_category';

    /**
     * @throws Exception
     * @throws DBALException
     */
    public function findByIdentifiers(array $identifiers): array|QueryResultInterface
    {
        $result = null;
        if (!empty($identifiers)) {
            $queryBuilder = $this->getQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();
            $queryBuilder->select('*')
                ->from($this->tableName)
                ->where(
                    $queryBuilder->expr()->in('uid', $identifiers)
                );
            $result = $queryBuilder->execute()->fetchAllAssociative();
        }
        return $result;
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        return $connectionPool->getQueryBuilderForTable($this->tableName);
    }
}
