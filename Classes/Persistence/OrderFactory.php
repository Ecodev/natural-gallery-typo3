<?php
namespace Fab\NaturalGallery\Persistence;

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

use Fab\Messenger\Utility\TcaFieldsUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Factory class related to Order object.
 */
class OrderFactory implements SingletonInterface
{


    protected array $settings = array();

    protected string|array $dataType = 'sys_file';

    /**
     * Gets a singleton instance of this class.
     *
     * @return OrderFactory
     */
    static public function getInstance(): OrderFactory
    {
        return GeneralUtility::makeInstance(self::class);
    }

    /**
     * Returns an order object.
     *
     * @param array $settings
     * @return Order
     */
    public function getOrder(array $settings = []): Order
    {
        $this->settings = $settings;

        // Default ordering
        $order = TcaFieldsUtility::getFields($this->dataType);

        if (!empty($this->settings['sorting'])) {
            $direction = empty($this->settings['direction']) ? 'ASC' : $this->settings['direction'];
            $order = array(
                $this->settings['sorting'] => $direction
            );
        }

        return GeneralUtility::makeInstance(Order::class, $order);
    }

}
