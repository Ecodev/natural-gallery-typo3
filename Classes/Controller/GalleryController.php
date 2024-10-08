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
use Fab\NaturalGallery\Persistence\MatcherFactory;
use Fab\NaturalGallery\Persistence\OrderFactory;
use Fab\NaturalGallery\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Controller
 */
class GalleryController extends ActionController
{
    protected ImageGalleryRepository $galleryRepository;

    protected MatcherFactory $matcherFactory;
    protected OrderFactory $orderFactory;

    protected array $configuration = array();

    protected $settings = array(
        'folders'=> '',
        'additionalEquals'=> '',
        'sorting'=> '',
        'direction'=> '',
        'categories'=> '',

    );
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
        $this->matcherFactory = GeneralUtility::makeInstance(MatcherFactory::class);
    }


    /**
     * @return void|string
     */
    public function listAction()
    {
        if (!isset($this->settings['imagesPerRow'])) {
            return '<strong style="color: red">Please save your plugin settings in the BE beforehand.</strong>';
        }

        $this->settings['folders'] = ConfigurationUtility::getInstance()->get('folders');
        $this->settings['additionalEquals'] = ConfigurationUtility::getInstance()->get('additionalEquals');
        $this->settings['sorting'] = ConfigurationUtility::getInstance()->get('sorting');
        $this->settings['direction'] = ConfigurationUtility::getInstance()->get('direction');
        $this->settings['categories'] = ConfigurationUtility::getInstance()->get('categories');

        // Initialize some objects related to the query.
        $matcher =  $this->matcherFactory->getMatcher($this->settings);
//        $order = $this->orderFactory->getOrder($this->settings);

       // Fetch and count files
       $images = $this->galleryRepository->findByDemand($matcher, $this->getOrderings());

       var_dump($this->settings);
       exit();

        // Assign template variables
        $this->view->assign('settings', $this->settings);
        $this->view->assign('data', $this->configurationManager->getcontentObject()->data);
        $this->view->assign('images', $images);

        $identifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE);
        $this->view->assign('categories', $this->galleryRepository->findByCategories($identifiers));
    }

    protected function getOrderings(): array
    {
        $sortBy = $this->settings['sorting'] ?? 'crdate';
        if (!in_array($sortBy, $this->allowedColumns)) {
            $sortBy = 'crdate';
        }
        $defaultDirection = QueryInterface::ORDER_DESCENDING;
        $direction = $this->settings['direction'] ?? $defaultDirection;
        if ($this->settings['direction'] && strtoupper($direction) === 'DESC') {
            $defaultDirection = QueryInterface::ORDER_ASCENDING;
        }
        return [
            $sortBy => $defaultDirection,
        ];
    }

//    protected function getDemand(): array
//    {
//        $searchTerm = $this->request->hasArgument('searchTerm') ? $this->request->getArgument('searchTerm') : '';
//        $demand = [];
//        if (strlen($searchTerm) > 0) {
//            foreach ($this->demandFields as $field) {
//                $demand[$field] = $searchTerm;
//            }
//        }
//        return $demand;
//    }

}
