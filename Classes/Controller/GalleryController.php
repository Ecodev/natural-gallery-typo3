<?php
namespace Fab\InfiniteScrollGallery\Controller;

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

use Fab\Vidi\Domain\Repository\ContentRepositoryFactory;
use Fab\Vidi\Persistence\Matcher;
use Fab\Vidi\Persistence\Order;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller
 */
class GalleryController extends ActionController
{

    /**
     * @var \TYPO3\CMS\Extbase\Service\FlexFormService
     * @inject
     */
    protected $flexFormService;

    /**
    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @return void
     */
    public function listAction()
    {
        // Initialize a Matcher object.
        /** @var \Fab\Vidi\Persistence\Matcher $matcher */
        $matcher = GeneralUtility::makeInstance(Matcher::class);

        // Add some criteria.
        $matcher->equals('storage', '1');
        $matcher->equals('type', File::FILETYPE_IMAGE);

        /** @var \Fab\Vidi\Persistence\order $order */
        $order = GeneralUtility::makeInstance(Order::class);

        // Fetch the adequate repository for a known data type.
        $dataType = 'sys_file';
        $contentRepository = ContentRepositoryFactory::getInstance($dataType);

        // Fetch and count files
        $images = $contentRepository->findBy($matcher, $order, (int)$this->settings['limit']);
        $totalNumberOfImages = $contentRepository->countBy($matcher);

        // Assign template variables
        $this->view->assign('totalNumberOfImages', $totalNumberOfImages);
        $this->view->assign('settings', $this->settings);
        $this->view->assign('data', $this->configurationManager->getcontentObject()->data);
        $this->view->assign('images', $images);
        $this->view->assign('numberOfVisibleImages', $this->settings['limit'] > $totalNumberOfImages ? $totalNumberOfImages : $this->settings['limit']);
        // @todo Fabien restore me
        //$this->view->assign('categories', $this->getCategoriesObjects());
//        $this->view->assign('imageStack', $this->imageRepository->findBy($matcher, $orderObject, 10000000, $this->settings['limit']));
    }

//    /**
//     * Get category objects
//     *
//     * @return \TYPO3\CMS\Extbase\Domain\Model\Category[]
//     */
//    protected function getCategoriesObjects()
//    {
//        $categories = GeneralUtility::trimExplode(',', $this->settings['categories']);
//
//        // Ugly trick to get a first empty value in form.select View Helper
//        /** @var \Fab\Media\Domain\Model\Category $category */
//        $category = $this->objectManager->get('Fab\Media\Domain\Model\Category');
//        $category->setTitle(
//            LocalizationUtility::translate('select_category', 'infinite_scroll_gallery')
//        );
//
//        $categoryObjects[] = $category;
//        foreach ($categories as $category) {
//            $categoryObjects[] = $this->categoryRepository->findByUid($category);
//        }
//        return $categoryObjects;
//    }
//
//    /**
//     * Get an order object
//     *
//     * @return \Fab\Media\QueryElement\Order
//     */
//    protected function getOrderObject()
//    {
//        /** @var $order \Fab\Media\QueryElement\Order */
//        $order = $this->objectManager->get('Fab\Media\QueryElement\Order');
//        $parts = explode(' ', $this->settings['orderBy']);
//        $order->addOrdering($parts[0], $parts[1]);
//
//        return $order;
//    }
//
//    /**
//     * Get an matcher object
//     *
//     * @return \Fab\Media\QueryElement\Matcher
//     */
//    protected function getMatcherObject()
//    {
//        /** @var $matcher \Fab\Media\QueryElement\Matcher */
//        $matcher = $this->objectManager->get('Fab\Media\QueryElement\Matcher');
//
//        // Get categories from argument if existing. Otherwise from settings.
//        if ($this->request->hasArgument('category') && (int)$this->request->getArgument('category') > 0) {
//            $categories[] = (int)$this->request->getArgument('category');
//        } else {
//            $categories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['categories']);
//        }
//
//        foreach ($categories as $category) {
//            $matcher->addCategory($category);
//        }
//
//        // Add possible search term
//        if ($this->request->hasArgument('searchTerm') && $this->request->getArgument('searchTerm') != '') {
//            $matcher->setSearchTerm($this->request->getArgument('searchTerm'));
//        }
//
//        return $matcher;
//    }
}
