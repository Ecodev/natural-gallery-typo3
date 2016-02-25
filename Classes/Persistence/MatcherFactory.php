<?php
namespace Fab\NaturalGallery\Persistence;

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

use Fab\Vidi\Domain\Model\Selection;
use Fab\Vidi\Domain\Repository\SelectionRepository;
use Fab\Vidi\Resolver\FieldPathResolver;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use Fab\Vidi\Persistence\Matcher;
use Fab\Vidi\Tca\Tca;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Factory class related to Matcher object.
 */
class MatcherFactory implements SingletonInterface
{

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    protected $dataType = 'sys_file';

    /**
     * Gets a singleton instance of this class.
     *
     * @return MatcherFactory
     */
    static public function getInstance()
    {
        return GeneralUtility::makeInstance(self::class);
    }

    /**
     * Returns a matcher object.
     *
     * @param array $settings
     * @return Matcher
     */
    public function getMatcher(array $settings)
    {

        $this->settings = $settings;

        /** @var $matcher Matcher */
        $matcher = GeneralUtility::makeInstance(Matcher::class);

        // We only want files of type images, consider it as a prerequisite.
        $matcher->equals('type', File::FILETYPE_IMAGE);

        $matcher = $this->applyCriteriaFromFolders($matcher);
        $matcher = $this->applyCriteriaFromSelection($matcher);
        $matcher = $this->applyCriteriaFromAdditionalConstraints($matcher);

        // Trigger signal for post processing Matcher Object.
        $this->emitPostProcessMatcherObjectSignal($matcher);

        return $matcher;
    }

    /**
     * Apply criteria specific from folder given as settings.
     *
     * @param Matcher $matcher
     * @return Matcher $matcher
     */
    protected function applyCriteriaFromFolders(Matcher $matcher)
    {
        if (!empty($this->settings['folders'])) {
            $folderIdentifiers = GeneralUtility::trimExplode(',', $this->settings['folders'], true);
            $fileUids = [];
            foreach ($folderIdentifiers as $folderIdentifier) {
                $folderIdentifier = str_replace('file:', '', $folderIdentifier);
                $folder = ResourceFactory::getInstance()->getFolderObjectFromCombinedIdentifier($folderIdentifier);
                $files = $folder->getFiles();
                foreach ($files as $file) {
                    $fileUids[] = $file->getUid();
                }
            }

            $matcher->in('uid', $fileUids);
        }

        return $matcher;
    }

    /**
     * Apply criteria from categories.
     *
     * @param Matcher $matcher
     * @return Matcher $matcher
     */
    protected function applyCriteriaFromAdditionalConstraints(Matcher $matcher)
    {

        if (!empty($this->settings['additionalEquals'])) {
            $constraints = GeneralUtility::trimExplode(',', $this->settings['additionalEquals'], TRUE);
            foreach ($constraints as $constraint) {

                if (preg_match('/.+=.+/isU', $constraint, $matches)) {
                    $constraintParts = GeneralUtility::trimExplode('=', $constraint, TRUE);
                    if (count($constraintParts) === 2) {
                        $matcher->equals(trim($constraintParts[0]), trim($constraintParts[1]));
                    }
                } elseif (preg_match('/.+like.+/isU', $constraint, $matches)) {
                    $constraintParts = GeneralUtility::trimExplode('like', $constraint, TRUE);
                    if (count($constraintParts) === 2) {
                        $matcher->like(trim($constraintParts[0]), trim($constraintParts[1]));
                    }
                }

            }
        }
        return $matcher;
    }

    /**
     * Apply criteria from selection.
     *
     * @param Matcher $matcher
     * @return Matcher $matcher
     */
    protected function applyCriteriaFromSelection(Matcher $matcher)
    {

        $selectionIdentifier = (int)$this->settings['selection'];
        if ($selectionIdentifier > 0) {

            /** @var SelectionRepository $selectionRepository */
            $selectionRepository = $this->getObjectManager()->get(SelectionRepository::class);

            /** @var Selection $selection */
            $selection = $selectionRepository->findByUid($selectionIdentifier);
            $queryParts = json_decode($selection->getQuery(), TRUE);
            $matcher = $this->parseQuery($queryParts, $matcher, $this->dataType);
        }
        return $matcher;
    }

    /**
     * Apply criteria specific to jQuery plugin DataTable.
     *
     * @param array $queryParts
     * @param Matcher $matcher
     * @param string $dataType
     * @return Matcher $matcher
     */
    protected function parseQuery(array $queryParts, Matcher $matcher, $dataType)
    {

        foreach ($queryParts as $queryPart) {
            $fieldNameAndPath = key($queryPart);

            $resolvedDataType = $this->getFieldPathResolver()->getDataType($fieldNameAndPath, $dataType);
            $fieldName = $this->getFieldPathResolver()->stripFieldPath($fieldNameAndPath, $dataType);

            // Retrieve the value.
            $value = current($queryPart);

            if (Tca::grid($resolvedDataType)->hasFacet($fieldName) && Tca::grid($resolvedDataType)->facet($fieldName)->canModifyMatcher()) {
                $matcher = Tca::grid($resolvedDataType)->facet($fieldName)->modifyMatcher($matcher, $value);
            } elseif (Tca::table($resolvedDataType)->hasField($fieldName)) {
                // Check whether the field exists and set it as "equal" or "like".
                if ($this->isOperatorEquals($fieldNameAndPath, $dataType, $value)) {
                    $matcher->equals($fieldNameAndPath, $value);
                } else {
                    $matcher->like($fieldNameAndPath, $value);
                }
            } elseif ($fieldNameAndPath === 'text') {
                // Special case if field is "text" which is a pseudo field in this case.
                // Set the search term which means Vidi will
                // search in various fields with operator "like". The fields come from key "searchFields" in the TCA.
                $matcher->setSearchTerm($value);
            }
        }

        return $matcher;
    }

    /**
     * Tell whether the operator should be equals instead of like for a search, e.g. if the value is numerical.
     *
     * @param string $fieldName
     * @param string $dataType
     * @param string $value
     * @return bool
     */
    protected function isOperatorEquals($fieldName, $dataType, $value)
    {
        return (Tca::table($dataType)->field($fieldName)->hasRelation() && MathUtility::canBeInterpretedAsInteger($value))
        || Tca::table($dataType)->field($fieldName)->isNumerical();
    }

    /**
     * Signal that is called for post-processing a matcher object.
     *
     * @param Matcher $matcher
     * @signal
     */
    protected function emitPostProcessMatcherObjectSignal(Matcher $matcher)
    {
        $this->getSignalSlotDispatcher()->dispatch(self::class, 'postProcessMatcherObject', array($matcher, $matcher->getDataType()));
    }

    /**
     * Get the SignalSlot dispatcher
     *
     * @return Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {
        return $this->getObjectManager()->get(Dispatcher::class);
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @return FieldPathResolver
     */
    protected function getFieldPathResolver()
    {
        return GeneralUtility::makeInstance(FieldPathResolver::class);
    }

}
