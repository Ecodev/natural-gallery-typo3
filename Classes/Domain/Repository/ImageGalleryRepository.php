<?php
namespace Fab\NaturalGallery\Domain\Repository;


use Fab\NaturalGallery\Persistence\Matcher;
use Fab\NaturalGallery\Persistence\Order;
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

    // public function findByCategory(int $category): array
    // {
    //     /** @var QueryBuilder $query */
    //     $queryBuilder = $this->getQueryBuilder();
    //     $queryBuilder->select('*')
    //         ->from($this->tableName)
    //         ->innerJoin(
    //             'sys_file',
    //             'sys_file_metadata',
    //             'sys_file_metadata',
    //             'sys_file.uid = sys_file_metadata.file'
    //         )
    //         ->innerJoin(
    //             'sys_file_metadata',
    //             'sys_category_record_mm',
    //             'sys_category_record_mm',
    //             'sys_category_record_mm.uid_foreign = sys_file_metadata.uid AND tablenames = "sys_file_metadata" AND fieldname = "categories"'
    //         )
    //         ->where(
    //             $queryBuilder->expr()->eq('sys_category_record_mm.uid_local', $category)
    //         )
    //         ->addOrderBy('sys_file_metadata.year', 'DESC')
    //         ->addOrderBy('sys_file_metadata.title', 'ASC');

    //     return $queryBuilder
    //         ->execute()
    //         ->fetchAllAssociative();
    // }

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

    public function findByDemand(array|Matcher $demand = [], array $orderings = [], int $offset = 0, int $limit = 0): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $constraints = [];


        if ($demand['likes']) {
            foreach ($demand['likes'] as $field => $value) {
                $constraints[] = $queryBuilder->select('*')->from($this->tableName)
                    ->expr()
                    ->like(
                        $field,
                        $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($value) . '%'),
                    );
            }

            if ($constraints) {
                $queryBuilder->where($queryBuilder->expr()->orX(...$constraints));
            }
    
            # We handle the sorting
            $queryBuilder->addOrderBy(key($orderings), current($orderings));
    
        }
        

        if ($demand['identifiers']) {
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

            if (!empty($demand['identifiers'])) {
                $queryBuilder->where(
                    $queryBuilder->expr()->in('sys_category_record_mm.uid_local', $demand['identifiers'])
                );
            }

            $queryBuilder->addOrderBy('sys_file_metadata.year', 'DESC')
                ->addOrderBy('sys_file_metadata.title', 'ASC');
        }
        
        if ($limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->execute()->fetchAllAssociative();
    }


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
