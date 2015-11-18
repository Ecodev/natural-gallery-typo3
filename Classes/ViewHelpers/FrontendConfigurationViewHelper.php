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
 * View helper for frontend configuration
 */
class FrontendConfigurationViewHelper extends AbstractViewHelper
{

    /**
     * Return a key
     *
     * @param string $key
     * @param string $default value if $key is not found
     * @return string
     */
    public function render($key, $default = '')
    {
        $result = $default;
        $frontendConfiguration = $GLOBALS['TSFE']->tmpl->setup['config.'];

        if (!empty($frontendConfiguration[$key])) {
            $result = $frontendConfiguration[$key];
        }

        return $result;
    }
}