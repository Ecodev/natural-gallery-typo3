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
                $this->getQueryBuilder()
                    ->expr()
                    ->eq('uid', $this->getQueryBuilder()->expr()->literal($uid)),
            );

        $messages = $query->execute()->fetchOne();

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
            ->innerJoin(
                'sys_file',
                'sys_file_metadata',
                'sys_file_metadata',
                'sys_file.uid = sys_file_metadata.file'
            )
            ->innerJoin(
                'sys_file_metadata',
                'sys_category_record_mm',
                'sys_category_record_mm',
                'sys_category_record_mm.uid_foreign = sys_file_metadata.uid AND tablenames = "sys_file_metadata" AND fieldname = "categories"'
            );

        if (!empty($categories)) {
            $queryBuilder->where(
                $queryBuilder->expr()->in('sys_category_record_mm.uid_local', $categories)
            );
        }

        $queryBuilder->addOrderBy('sys_file_metadata.year', 'DESC')
            ->addOrderBy('sys_file_metadata.title', 'ASC');

        return $queryBuilder
            ->execute()
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
                'sys_file_metadata',
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('sys_file.uid', 'sys_file_metadata.file'),
                    $queryBuilder->expr()->lte('sys_file_metadata.t3ver_state', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('sys_file_metadata.t3ver_wsid', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('sys_file_metadata.t3ver_oid', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_file_metadata.t3ver_state', $queryBuilder->createNamedParameter(4, \PDO::PARAM_INT))
                    ),
                    $queryBuilder->expr()->in('sys_file_metadata.sys_language_uid', [0, -1])
                )
            )
            ->leftJoin(
                'sys_file_metadata',
                'sys_category_record_mm',
                'sys_category_record_mm0',
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('sys_file_metadata.uid', 'sys_category_record_mm0.uid_foreign'),
                    $queryBuilder->expr()->eq('sys_category_record_mm0.tablenames', $queryBuilder->createNamedParameter('sys_file_metadata')),
                    $queryBuilder->expr()->eq('sys_category_record_mm0.fieldname', $queryBuilder->createNamedParameter('categories'))
                )
            )
            ->leftJoin(
                'sys_category_record_mm0',
                'sys_category',
                'sys_category0',
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('sys_category_record_mm0.uid_local', 'sys_category0.uid'),
                    $queryBuilder->expr()->eq('sys_category0.deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->lte('sys_category0.t3ver_state', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('sys_category0.t3ver_wsid', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('sys_category0.t3ver_oid', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_category0.t3ver_state', $queryBuilder->createNamedParameter(4, \PDO::PARAM_INT))
                    ),
                    $queryBuilder->expr()->eq('sys_category0.hidden', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->lte('sys_category0.starttime', $queryBuilder->createNamedParameter($timestamp, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('sys_category0.endtime', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->gt('sys_category0.endtime', $queryBuilder->createNamedParameter($timestamp, \PDO::PARAM_INT))
                    ),
                    $queryBuilder->expr()->in('sys_category0.sys_language_uid', [0, -1])
                )
            )
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('sys_file.type', $queryBuilder->createNamedParameter(2, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->in('sys_file.uid', (array)$uids)
                )
            )

            ->orderBy('sys_file.name', 'ASC');
        if ($categoryConditions) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('sys_category0.uid', $queryBuilder->createNamedParameter((int)$categoryConditions, \PDO::PARAM_INT))
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
            ->where($this->getQueryBuilder()->expr()->in('uid', $uids));

        return $query->execute()->fetchAllAssociative();
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        return $connectionPool->getQueryBuilderForTable($this->tableName);
    }

}
