<?php

namespace Fab\NaturalGallery\Domain\Repository;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Fab\NaturalGallery\Domain\Model\Selection;

/**
 * Repository for accessing Selections
 */
class SelectionRepository extends Repository
{
    /**
     * @param string $dataType
     * @return QueryResultInterface|\mixed[][]|object[]
     */
    public function findByDataTypeForCurrentBackendUser(string $dataType): array|QueryResultInterface
    {
        $query = $this->createQuery();

        // Compute the OR part
        if ($this->getBackendUser()->isAdmin()) {
            $logicalOr = $query->logicalOr([$query->equals('visibility', Selection::VISIBILITY_EVERYONE), $query->equals('visibility', Selection::VISIBILITY_ADMIN_ONLY), $query->equals('cruser_id', $this->getBackendUser()->user['uid'])]);
        } else {
            $logicalOr = $query->logicalOr([$query->equals('visibility', Selection::VISIBILITY_EVERYONE), $query->equals('cruser_id', $this->getBackendUser()->user['uid'])]);
        }

        // Add matching criteria
        $query->matching(
            $query->logicalAnd([$query->equals('dataType', $dataType), $logicalOr])
        );

        // Set ordering
        $query->setOrderings(
            array('name' => QueryInterface::ORDER_ASCENDING)
        );

        return $query->execute();
    }

    /**
     * @param string $dataType
     * @return QueryResultInterface|array[]|object[]
     */
    public function findForEveryone(string $dataType): array|QueryResultInterface
    {
        $query = $this->createQuery();

        // Add matching criteria
        $query->matching(
            $query->logicalAnd([$query->equals('dataType', $dataType), $query->equals('visibility', Selection::VISIBILITY_EVERYONE)])
        );

        // Set ordering
        $query->setOrderings(
            array('name' => QueryInterface::ORDER_ASCENDING)
        );

        return $query->execute();
    }

    /**
     * Returns an instance of the current Backend User.
     *
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
