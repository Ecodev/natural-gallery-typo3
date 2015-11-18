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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper for rendering script
 *
 * = Examples =
 */
class ImageStackViewHelper extends AbstractViewHelper
{

    /**
     * Generate a JSON array of images suitable for xoyview
     *
     * @param array $images
     * @param array $settings
     * @return string
     */
    public function render($images, $settings)
    {

        /** @var \Fab\Media\Service\ThumbnailService $thumbnailService */
        $thumbnailService = $this->objectManager->get('Fab\Media\Service\ThumbnailService');
        $thumbnailService->setOutputType(\Fab\Media\Service\ThumbnailInterface::OUTPUT_URI)
            ->setConfiguration(
                array(
                    'width' => $settings['enlargedImageMaximumWidth'],
                    'height' => $settings['enlargedImageMaximumHeight']
                )
            );

        /** @var \Fab\Media\Domain\Model\Image $image */
        foreach ($images as $image) {
            $output[] = array(
                'media' => array(
                    'src' => '/' . $image->getThumbnail($thumbnailService),
                    'title' => $image->getTitle(),
                ),
            );
        }
        return json_encode($output);
    }
}