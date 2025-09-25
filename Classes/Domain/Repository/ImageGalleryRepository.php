<?php
namespace Fab\NaturalGallery\Domain\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Fab\NaturalGallery\Persistence\Matcher;
use Fab\NaturalGallery\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;


class ImageGalleryRepository
{

    protected string $tableName = 'sys_file';

    protected array $settings;
    public function __construct(private \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
    {
    }

    public function getDefaultData(string $field):string
    {
        return ConfigurationUtility::getInstance()->get($field);
    }

    public function findByUid(int $uid): array
    {
        $query = $this->getQueryBuilder();
        $query
            ->select('*')
            ->from($this->tableName)
            ->where(
                $query->expr()->eq('uid', $uid)
            );

        $messages = $query->executeQuery()->fetchOne();

        return is_array($messages) ? $messages : [];
    }


    /**
     * @throws DBALException
     * @throws Exception
     */
    public function findByCategories(array $categories): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*')
            ->from($this->tableName)
            ->join(
                'sys_file',
                'sys_file_metadata',
                'metadata',
                'sys_file.uid = metadata.file'
            )
            ->join(
                'metadata',
                'sys_category_record_mm',
                'mm',
                'mm.uid_foreign = metadata.uid AND mm.tablenames = "sys_file_metadata" AND mm.fieldname = "categories"'
            );

        if (!empty($categories)) {
            $queryBuilder->where(
                $queryBuilder->expr()->in('mm.uid_local', $categories)
            );
        }

        $queryBuilder->addOrderBy('metadata.year', 'DESC')
            ->addOrderBy('metadata.title', 'ASC');

        return $queryBuilder
            ->executeQuery()
            ->fetchAllAssociative();

    }

    /**
     * @throws Exception
     * @throws DBALException
     */
    public function findByDemand(array|Matcher $demand = [], array $orderings = [], int $offset = 0, int $limit = 0): array
    {
        if (isset($demand['likes']) && $demand['likes'] instanceof Matcher) {
            $matcher = $demand['likes'];
            $inConditions = $matcher->getIn();
            $categoryConditions = $matcher->getEquals()[1]['operand'];

            if (!empty($inConditions) && isset($inConditions[0]['operand']) && is_array($inConditions[0]['operand'])) {
                $uids = array_map('intval', $inConditions[0]['operand']);
            }
        }
        
        $timestamp = time();
        $queryBuilder = $this->getQueryBuilder();
        
        $queryBuilder
            ->select('sys_file.*')
            ->from('sys_file')
            ->leftJoin(
                'sys_file',
                'sys_file_metadata',
                'metadata',
                'sys_file.uid = metadata.file'
            )
            ->leftJoin(
                'metadata',
                'sys_category_record_mm',
                'mm',
                'metadata.uid = mm.uid_foreign AND mm.tablenames = "sys_file_metadata" AND mm.fieldname = "categories"'
            )
            ->leftJoin(
                'mm',
                'sys_category',
                'category',
                'mm.uid_local = category.uid'
            )
            ->where(
                $queryBuilder->expr()->eq('sys_file.type', 2),
                $queryBuilder->expr()->in('sys_file.uid', (array)$uids),
                // Metadata conditions
                $queryBuilder->expr()->lte('metadata.t3ver_state', 0),
                $queryBuilder->expr()->eq('metadata.t3ver_wsid', 0),
                $queryBuilder->expr()->in('metadata.sys_language_uid', [0, -1]),
                // Category conditions
                $queryBuilder->expr()->eq('category.deleted', 0),
                $queryBuilder->expr()->lte('category.t3ver_state', 0),
                $queryBuilder->expr()->eq('category.t3ver_wsid', 0),
                $queryBuilder->expr()->eq('category.hidden', 0),
                $queryBuilder->expr()->lte('category.starttime', $timestamp),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('category.endtime', 0),
                    $queryBuilder->expr()->gt('category.endtime', $timestamp)
                ),
                $queryBuilder->expr()->in('category.sys_language_uid', [0, -1])
            )
            ->orderBy('sys_file.name', 'ASC');

        if ($categoryConditions) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('category.uid', (int)$categoryConditions)
            );
        }


        if (empty($orderings['*orderings'])) {
            foreach ($orderings['*orderings'] as $ordering => $direction) {
                $queryBuilder->addOrderBy($ordering, $direction);
                if ($this->hasForeignRelationIn($ordering)) {
                    $relationalField = $this->getForeignRelationFrom($ordering);
                    if ($demand instanceof Matcher) {
                        $demand->like($relationalField . '.uid', '');
                    }
                }
            }
        }
        if ($offset > 0) {
            $queryBuilder->setFirstResult($offset);
        }

        if ($limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        return  $queryBuilder->executeQuery()->fetchAllAssociative();


    }



    protected function hasForeignRelationIn($ordering): bool
    {
        return str_contains($ordering, '.');
    }


    protected function getForeignRelationFrom($ordering): string
    {
        $parts = explode('.', $ordering);
        return $parts[0];
    }
    /**
     * @throws DBALException
     * @throws Exception
     */
    public function findByUids(array $uids): array
    {
        $query = $this->getQueryBuilder();
        $query
            ->select('*')
            ->from($this->tableName)
            ->where($query->expr()->in('uid', $uids));

        return $query->executeQuery()->fetchAllAssociative();
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = $this->connectionPool;
        return $connectionPool->getQueryBuilderForTable($this->tableName);
    }

}
