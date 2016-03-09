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

use FluidTYPO3\Vhs\Asset;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Fab\NaturalGallery\Utility\PartialLoadingRegister;

/**
 * View helper to load a JavaScript file
 */
class AbstractIsPartialAlreadyLoadedViewHelper extends AbstractViewHelper
{

    /**
     * @return bool
     */
    public function render()
    {
        return  !PartialLoadingRegister::getInstance()->usePartial($this->name);
    }

}
