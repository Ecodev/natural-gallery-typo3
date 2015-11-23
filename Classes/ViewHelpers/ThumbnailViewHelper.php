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
 * View helper.
 */
class ThumbnailViewHelper extends AbstractViewHelper
{

    /**
     * @param Content $image
     * @return string
     */
    public function render(Content $image)
    {

        $file = ResourceFactory::getInstance()->getFileObject($image->getUid());
        $processedFile = $this->createProcessedFile($file);

        // @todo incomplete implementation...
        $thumbnail = $processedFile->getPublicUrl(true);

        return $thumbnail;
    }

    /**
     * @param File $file
     * @return ProcessedFile
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    public function createProcessedFile(File $file)
    {

        $configuration = [];
        if ($this->getSettings()['thumbnailMaximumWidth'] > $file->getProperty('width')) {
            $configuration['width'] = $file->getProperty('width');
        }
        if ($this->getSettings()['thumbnailMaximumHeight'] > $file->getProperty('height')) {
            $configuration['height'] = $file->getProperty('height');
        }

        $processedFile = $file->process(ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $configuration);

        return $processedFile;
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