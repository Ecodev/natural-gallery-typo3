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

use Fab\Vidi\Domain\Model\Content;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper
 */
class CategoryStackViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render()
    {
        $cats = $this->templateVariableContainer->get('categories');
        $items = [];

        if ($cats) {
            foreach ($cats as $cat) {

                $item = [
                    'id' => $cat->getUid(),
                    'title' => $cat->getTitle()
                ];

                $items[] = $item;
            }
        }

        return json_encode($items);
    }

}
