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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Factory class related to Matcher object.
 */
class DemandFactory implements SingletonInterface
{

    protected array $settings = [];

    protected string $tableName = 'sys_file';

    protected function applyCriteriaFromFolders(\Fab\NaturalGallery\Persistence\Matcher $matcher): \Fab\NaturalGallery\Persistence\Matcher
    {
        $folders = '';
        if (!empty($this->settings['folders'])) {

            if (str_contains($this->settings['folders'], 't3://')) {
                $decodedUrl = urldecode($this->settings['folders']);

                // In case identifier=/ is missing.
                if (!str_contains($decodedUrl, 'identifier=')) {
                    $decodedUrl .= '&identifier=/';
                }
                preg_match("/storage=([\d]+)&identifier=(.+)/", $decodedUrl, $matches);
                if (count($matches) === 3) {
                    $folders = $matches[1] . ':' . ltrim($matches[2], '/');
                }
            } else {
                $folders = $this->settings['folders'];
            }
            $folderIdentifiers = GeneralUtility::trimExplode(',', $folders, true);
            $fileUids = [];
            foreach ($folderIdentifiers as $folderIdentifier) {
                $folderIdentifier = str_replace('file:', '', $folderIdentifier);
                $folder = GeneralUtility::makeInstance(ResourceFactory::class)->getFolderObjectFromCombinedIdentifier($folderIdentifier);
                $files = $folder->getFiles();
                foreach ($files as $file) {
                    $fileUids[] = $file->getUid();
                }
            }

            $matcher->in('uid', $fileUids);
        }

        return $matcher;
    }
    protected function applyCriteriaFromAdditionalConstraints(Matcher $matcher): Matcher
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

    public function get(array $settings): Matcher
    {
        $this->settings = $settings;
        $matcher = GeneralUtility::makeInstance(Matcher::class);
        // We only want files of type images, consider it as a prerequisite.
        $matcher->equals('type', File::FILETYPE_IMAGE);
        $matcher = $this->applyCriteriaFromFolders($matcher);
        return $this->applyCriteriaFromAdditionalConstraints($matcher);
    }

}
