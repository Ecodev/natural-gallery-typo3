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

use Fab\NaturalGallery\Domain\Repository\CategoryRepository;
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

        $items = [];

        $processedUids = [];
        $items = [];

        foreach ($images as $image) {
            /** @var \TYPO3\CMS\Core\Resource\File $file */
            if (!empty($image['uid']) && !in_array($image['uid'], $processedUids)) {
                    $file = GeneralUtility::makeInstance(ResourceFactory::class)->getFileObject($image['uid']);

                    $thumbnailFile = $this->createProcessedFile($file, 'thumbnailMaximumWidth', 'thumbnailMaximumHeight');
                    $enlargedFile = $this->createProcessedFile($file, 'enlargedImageMaximumWidth', 'enlargedImageMaximumHeight');
                    $categories = [];

                    $categoryRepository = GeneralUtility::makeInstance(CategoryRepository::class);
                    $metadataCategories = $categoryRepository->findFileCategories($file->getMetaData()['uid']);
                    if ($metadataCategories && is_array($metadataCategories)) {
                        $categories = array_map(function ($cat) {
                            return [
                                'id' => $cat['uid'],
                                'title' => $cat['title']
                            ];
                        },$metadataCategories);
                    }

                    $baseUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
                    $item = [
                        // v10 format - new property names
                        'thumbnailSrc' => $baseUrl . $thumbnailFile->getPublicUrl(),
                        'enlargedSrc' => $baseUrl . $enlargedFile->getPublicUrl(),
                        'enlargedWidth' => $enlargedFile->getProperty('width'),
                        'enlargedHeight' => $enlargedFile->getProperty('height'),
                        
                        // Common properties
                        'id' => $file->getProperty('uid'),
                        'title' => $file->getProperty('title'),
                        'description' => $file->getProperty('description'),
                        
                        // Thumbnail dimensions (for internal use)
                        'tWidth' => $thumbnailFile->getProperty('width'),
                        'tHeight' => $thumbnailFile->getProperty('height'),
                        'categories' => $categories,
                        
                        // Keep backward compatibility properties for now
                        'thumbnail' => $baseUrl . $thumbnailFile->getPublicUrl(),
                        'enlarged' => $baseUrl . $enlargedFile->getPublicUrl(),
                        'eWidth' => $enlargedFile->getProperty('width'),
                        'eHeight' => $enlargedFile->getProperty('height')
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
        return $this->templateVariableContainer->get('settings');
    }
}
