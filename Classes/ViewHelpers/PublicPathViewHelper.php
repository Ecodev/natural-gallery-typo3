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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper which return the public path of a resource.
 */
class PublicPathViewHelper extends AbstractViewHelper
{

    /**
     * @var string
     */
    protected $extensionKey = 'infinite_scroll_gallery';

    /**
     * Return the public path of a resource
     *
     * @param string $resource
     * @return string
     */
    public function render($resource)
    {
        return sprintf('%sResources/Public/%s',
            str_replace(PATH_site, '', ExtensionManagementUtility::extPath($this->extensionKey)),
            $resource
        );
    }
}