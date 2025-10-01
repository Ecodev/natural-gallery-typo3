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

    protected ConnectionPool $connectionPool;
    
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @throws Exception
     * @throws DBALException
     */
    public function findByIdentifiers(array $identifiers): array|QueryResultInterface
    {
        if (empty($identifiers)) {
            return [];
        }

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->in('uid', $identifiers)
            );
        $result = $queryBuilder->executeQuery()->fetchAllAssociative();

        return $result ?? [];
    }

    public function findFileCategories($uid): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_category');
        $results = [];
        if ($uid){
            $query = $queryBuilder
                ->select('sys_category.uid', 'sys_category.title')
                ->from('sys_category')
                ->join(
                    'sys_category',
                    'sys_category_record_mm',
                    'mm',
                    'sys_category.uid = mm.uid_local'
                )
                ->where(
                    $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->createNamedParameter($uid, \TYPO3\CMS\Core\Database\Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('sys_file_metadata')),
                    $queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->createNamedParameter('categories'))
                );
            $results = $query->executeQuery()->fetchAllAssociative();

        }

        return $results;
    }

    protected function getQueryBuilder(): QueryBuilder
    {

        return   $this->connectionPool->getQueryBuilderForTable($this->tableName);
    }
}
