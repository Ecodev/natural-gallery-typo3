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
 * View helper for inline style.
 */
class DynamicStyleViewHelper extends AbstractViewHelper
{

    /**
     * Generate a JSON array of images suiting xoyview
     *
     * @param array $settings containing images
     * @return string
     */
    public function render(array $settings)
    {

        # Defines width and height values dynamically
        $styles = <<<EOF
<style>
.tx-infinitscrollgallery-thumbnail {
	width: {$settings['thumbnailMaximumWidth']}px;
	height: {$settings['thumbnailMaximumHeight']}px;
}
</style>
EOF;

        return $styles;
    }
}