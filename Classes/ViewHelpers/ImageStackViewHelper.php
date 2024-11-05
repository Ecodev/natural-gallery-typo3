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
    public function render()
    {
        $images = $this->templateVariableContainer->get('images');

        $items = [];

        $processedUids = []; 
        $items = [];

        foreach ($images as $image) {
            /** @var \TYPO3\CMS\Core\Resource\File $file */
            if (!empty($image['uid']) && !in_array($image['uid'], $processedUids)) {
                try {
                    $file = GeneralUtility::makeInstance(ResourceFactory::class)->getFileObject($image['uid']);
                    
                    $thumbnailFile = $this->createProcessedFile($file, 'thumbnailMaximumWidth', 'thumbnailMaximumHeight');
                    $enlargedFile = $this->createProcessedFile($file, 'enlargedImageMaximumWidth', 'enlargedImageMaximumHeight');
                    $categories = [];

                    if (isset($image['metadata']['categories']) && is_array($image['metadata']['categories'])) {
                        $categories = array_map(function ($cat) {
                            return [
                                'id' => $cat['uid'],
                                'title' => $cat['title']
                            ];
                        }, $image['metadata']['categories']);
                    }

                    $baseUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                    $item = [
                        'thumbnail' => $baseUrl . $thumbnailFile->getPublicUrl(),
                        'enlarged' => $baseUrl . $enlargedFile->getPublicUrl(),
                        'id' => $file->getProperty('uid'),
                        'title' => $file->getProperty('title'),
                        'description' => $file->getProperty('description'),
                        'tWidth' => $thumbnailFile->getProperty('width'),
                        'tHeight' => $thumbnailFile->getProperty('height'),
                        'eWidth' => $enlargedFile->getProperty('width'),
                        'eHeight' => $enlargedFile->getProperty('height'),
                        'categories' => $categories
                    ];

                    $items[] = $item; 
                    $processedUids[] = $image['uid']; 
                } catch (\TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException $e) {
                    
                }
            }
        }

        return json_encode($items);

    }

    /**
     * @param File $file
     * @param $widthFormat
     * @param $heightFormat
     * @return ProcessedFile
     * @internal param Content $image
     */
    public function createProcessedFile(File $file, $widthFormat, $heightFormat)
    {
        $configuration = [
            'maxWidth' => $this->getSettings()[$widthFormat] ? $this->getSettings()[$widthFormat] : null,
            'maxHeight' => $this->getSettings()[$heightFormat] ? $this->getSettings()[$heightFormat] : null,
        ];

        if ($configuration['maxWidth'] || $configuration['maxHeight']) {
            $file = $file->process(ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $configuration);
        }

        return $file;
    }


    /**
     * @throws array
     */
    public function getSettings()
    {
        $settings = $this->templateVariableContainer->get('settings');
        return $settings;
    }
}
