<?php
namespace Fab\InfiniteScrollGallery\ViewHelpers;

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

use Fab\Vidi\Domain\Model\Content;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

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
        foreach ($images as $image) {

            /** @var \TYPO3\CMS\Core\Resource\File $file */
            $file = ResourceFactory::getInstance()->getFileObject($image->getUid());
            $thumbnailFile = $this->createProcessedFile($file, 'thumbnailMaximumWidth', 'thumbnailMaximumHeight');
            $enlargedFile = $this->createProcessedFile($file, 'enlargedImageMaximumWidth', 'enlargedImageMaximumHeight');

            $categories = array_map(function($cat) {
                return $cat['uid'];
            }, $image['metadata']['categories']);

            $item = [
                'thumbnail' => '/' . $thumbnailFile->getPublicUrl(true),
                'enlarged' => '/' . $enlargedFile->getPublicUrl(true),
                'title' => $file->getProperty('title'),
                'width' => $enlargedFile->getProperty('width'),
                'height' => $enlargedFile->getProperty('height'),
                'categories' => $categories
            ];

            $items[] = $item;
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

//        if ($configuration['maxWidth'] || $configuration['maxHeight']) {
            $file = $file->process(ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $configuration);
//        }

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
