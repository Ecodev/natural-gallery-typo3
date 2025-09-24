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
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Context\Context;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper
 */
class ImageStackViewHelper extends AbstractViewHelper
{

    public function __construct(
        private \TYPO3\CMS\Core\Context\Context $context,
        private CategoryRepository $categoryRepository
    ) {
    }
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

                    $metadataCategories = $this->categoryRepository->findFileCategories($file->getMetaData()['uid']);
                    if ($metadataCategories && is_array($metadataCategories)) {
                        $categories = array_map(function ($cat) {
                            return [
                                'id' => $cat['uid'],
                                'title' => $cat['title']
                            ];
                        },$metadataCategories);
                    }

                    $baseUrl = $this->getSiteUrl();
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

    /**
     * Get the site URL
     * @return string
     */
    protected function getSiteUrl(): string
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        
        // Try to get current page ID from different sources
        $pageId = $this->getCurrentPageId();
        
        try {
            $site = $siteFinder->getSiteByPageId($pageId);
            return (string)$site->getBase();
        } catch (\Exception $e) {
            // Fallback to default site or root page
            try {
                $sites = $siteFinder->getAllSites();
                if (!empty($sites)) {
                    $defaultSite = reset($sites);
                    return (string)$defaultSite->getBase();
                }
            } catch (\Exception $e) {
                // Ultimate fallback
                return '/';
            }
        }
        
        return '/';
    }

    /**
     * Get current page ID from various sources
     * @return int
     */
    protected function getCurrentPageId(): int
    {
        // Try to get from context first
        try {
            if ($this->context->hasAspect('frontend.page')) {
                return $this->context->getPropertyFromAspect('frontend.page', 'id', 1);
            }
        } catch (\Exception $e) {
            // Context aspect not available, try other methods
        }

        // Try to get from TSFE (TypoScript Frontend Controller)
        if (isset($GLOBALS['TSFE']) && $GLOBALS['TSFE'] instanceof \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController) {
            return $GLOBALS['TSFE']->id;
        }

        // Try to get from request
        if (isset($GLOBALS['TYPO3_REQUEST'])) {
            $request = $GLOBALS['TYPO3_REQUEST'];
            if ($request->getAttribute('routing')) {
                $pageArguments = $request->getAttribute('routing');
                if ($pageArguments instanceof \TYPO3\CMS\Core\Routing\PageArguments) {
                    return $pageArguments->getPageId();
                }
            }
        }

        // Fallback to root page
        return 1;
    }
}
