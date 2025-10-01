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

use Fab\NaturalGallery\Domain\Repository\CategoryRepository;
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
    protected CategoryRepository $categoryRepository;

    protected array $configuration = array();
    protected $settings = [];

    protected array $allowedColumns = [
        'crdate',
        'tstamp',
        'title',
        'uid',
    ];

    public function __construct(
        ImageGalleryRepository $galleryRepository,
        DemandFactory $demandFactory,
        OrderFactory $orderFactory,
        CategoryRepository $categoryRepository
    ) {
        $this->galleryRepository = $galleryRepository;
        $this->demandFactory = $demandFactory;
        $this->orderFactory = $orderFactory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return void|string
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {


        if (!isset($this->settings['imagesPerRow'])) {
            return $this->htmlResponse('<strong style="color: red">Please save your plugin settings in the BE beforehand.</strong>');
        }

        $images = $this->galleryRepository->findByDemand($this->getDemand(), (array)$this->getOrderings(),0,0);
        $identifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE);
        $categories = $this->categoryRepository->findByIdentifiers($identifiers);
        $this->view->assignMultiple([
            'settings' => $this->settings,
            'data' => $this->request->getAttribute('currentContentObject')->data,
            'images' => $images,
            'categories' => $categories,
        ]);


        return $this->htmlResponse();
    }

    protected function getOrderings(): \Fab\NaturalGallery\Persistence\Order
    {

        return OrderFactory::getInstance()->getOrder($this->settings);

    }


    protected function getDemand(): array
    {
        return [
            'likes' => $this->demandFactory->get($this->settings),
            'identifiers' => GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE)
        ];
    }
}
