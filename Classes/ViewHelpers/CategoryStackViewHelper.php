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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
                    'id' => $cat['uid'],
                    'title' => $cat['title']
                ];

                $items[] = $item;
            }
        }

        return json_encode($items);
    }

}
