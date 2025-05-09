<?php

namespace Fab\NaturalGallery\Controller;

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

use Fab\NaturalGallery\Domain\Repository\ImageGalleryRepository;
use Fab\NaturalGallery\Persistence\DemandFactory;
use Fab\NaturalGallery\Persistence\OrderFactory;
use Fab\NaturalGallery\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * Controller
 */
class GalleryController extends ActionController
{
    protected ImageGalleryRepository $galleryRepository;

    protected DemandFactory $demandFactory;

    protected OrderFactory $orderFactory;

    protected array $configuration = array();

    protected $settings = [];

    protected array $allowedColumns = [
        'crdate',
        'tstamp',
        'title',
        'uid',
    ];

    public function initializeAction(): void
    {
        $this->galleryRepository = GeneralUtility::makeInstance(ImageGalleryRepository::class);
        $this->orderFactory = GeneralUtility::makeInstance(OrderFactory::class);
        $this->demandFactory = GeneralUtility::makeInstance(DemandFactory::class);
    }

    /**
     * @return void|string
     */
    public function listAction()
    {


        if (!isset($this->settings['imagesPerRow'])) {
            return '<strong style="color: red">Please save your plugin settings in the BE beforehand.</strong>';
        }

        $images = $this->galleryRepository->findByDemand($this->getDemand(), $this->getOrderings(),0,100);
        // Assign template variables
        $this->view->assign('settings', $this->settings);
        $this->view->assign('data', $this->configurationManager->getcontentObject()->data);
        $this->view->assign('images', $images);

        $identifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE);

        $this->view->assign('categories', $this->galleryRepository->findByCategories($identifiers));
    }

    protected function getOrderings(): array
    {
        $sortBy = $this->settings['sorting'] ?? 'tstamp';

        if (!in_array($sortBy, $this->allowedColumns, true)) {
            $sortBy = 'tstamp';
        }

        $directionSetting = strtoupper(trim((string)($this->settings['direction'] ?? 'DESC')));

        $direction = $directionSetting === 'ASC'
            ? QueryInterface::ORDER_ASCENDING
            : QueryInterface::ORDER_DESCENDING;

        return [
            $sortBy => $direction,
        ];
    }


    protected function getDemand(): array
    {
        return [
            'likes' => $this->demandFactory->get($this->settings),
            'identifiers' => GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE)
        ];
    }


}
