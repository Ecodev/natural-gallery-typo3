<?php

namespace Fab\NaturalGallery\ViewHelpers;

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

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper
 */
class ImageStackViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render(): string
    {
        $images = $this->templateVariableContainer->get('images');
        $processedUids = [];
        $items = [];

        foreach ($images as $image) {
            /** @var \TYPO3\CMS\Core\Resource\File $file */
            if (!empty($image['uid']) && !in_array($image['uid'], $processedUids)) {
                    $file = GeneralUtility::makeInstance(ResourceFactory::class)->getFileObject($image['uid']);

                    $thumbnailFile = $this->createProcessedFile($file, null, 'rowHeight');
                    $enlargedFile = $file;

                    $baseUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                    $item = [
                        'thumbnailSrc' => $baseUrl . $thumbnailFile->getPublicUrl(),
                        'enlargedSrc' => $baseUrl . $enlargedFile->getPublicUrl(),
                        'id' => $file->getProperty('uid'),
                        'title' => $file->getProperty('title'),
                        'description' => $file->getProperty('description'),
                        'enlargedWidth' => $file->getProperty('width'),
                        'enlargedHeight' => $file->getProperty('height'),
                    ];

                    $items[] = $item;
                    $processedUids[] = $image['uid'];
            }
        }

        return json_encode($items);

    }

    /**
     * @param File $file
     * @param $widthFormat
     * @param $heightFormat
     * @return File|ProcessedFile
     * @internal param Content $image
     */
    public function createProcessedFile(File $file, $widthFormat, $heightFormat): File|ProcessedFile
    {
        $configuration = [];
        
        if ($heightFormat && $this->getSettings()[$heightFormat]) {
            $configuration['maxHeight'] = $this->getSettings()[$heightFormat];
        }

        if (!empty($configuration)) {
            $file = $file->process(ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $configuration);
        }

        return $file;
    }


    /**
     * @throws array
     */
    public function getSettings()
    {
        return $this->templateVariableContainer->get('settings');
    }
}
