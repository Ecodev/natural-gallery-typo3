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

        $matcher = $demand['likes'];
        $inConditions = $matcher->getIn();
        $uids = $inConditions[0]['operand'];
        $query = $this->getQueryBuilder();
        $query
            ->select('*')
            ->from($this->tableName)
            ->where(
                $query->expr()->in(
                    'uid',
                    implode(',', array_map('intval', $uids)) // safe and correct formatting
                )
            );
        if ($orderings) {
            foreach ($orderings['*orderings'] as $ordering => $direction) {
                $query->addOrderBy($ordering, $direction);
                if ($this->hasForeignRelationIn($ordering)) {
                    $relationalField = $this->getForeignRelationFrom($ordering);
                    $demand->like($relationalField . '.uid', '');
                }
            }
        }

        if ($offset > 0) {
            $query->setFirstResult($offset);
        }

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }


        return $query->execute()->fetchAllAssociative();

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
