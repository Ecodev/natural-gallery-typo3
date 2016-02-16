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

use Fab\Vidi\Persistence\Order;
use Fab\Vidi\Tca\Tca;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Factory class related to Order object.
 */
class OrderFactory implements SingletonInterface
{

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    protected $dataType = 'sys_file';

    /**
     * Constructor
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Gets a singleton instance of this class.
     *
     * @param array $settings
     * @return OrderFactory
     */
    static public function getInstance(array $settings)
    {
        return GeneralUtility::makeInstance(self::class, $settings);
    }

    /**
     * Returns an order object.
     *
     * @return Order
     */
    public function getOrder()
    {

        // Default ordering
        $order = Tca::table($this->dataType)->getDefaultOrderings();

        if (!empty($this->settings['sorting'])) {
            $direction = empty($this->settings['direction']) ? 'ASC' : $this->settings['direction'];
            $order = array(
                $this->settings['sorting'] => $direction
            );
        }

        return GeneralUtility::makeInstance(Order::class, $order);
    }

}
