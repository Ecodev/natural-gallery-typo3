<?php
namespace TYPO3\CMS\InfiniteScrollGallery\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@ecodev.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package infinite_scroll_gallery
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class GalleryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\CMS\Media\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;

	/**
	 * @var \TYPO3\CMS\Media\Domain\Repository\ImageRepository
	 * @inject
	 */
	protected $imageRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\FlexFormService
	 * @inject
	 */
	protected $flexFormService;

	/**
	 * @var array
	 */
	protected $frontendConfiguration = array();

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
	public function listAction() {
		$orderObject = $this->getOrderObject();
		$matcher = $this->getMatcherObject();

		$totalImages = $this->imageRepository->countBy($matcher);
		$this->view->assign('totalImages', $totalImages);

		$this->view->assign('settings', $this->settings);
		$this->view->assign('data', $this->configurationManager->getcontentObject()->data);
		$this->view->assign('categories', $this->getCategoriesObjects());
		$this->view->assign('images', $this->imageRepository->findBy($matcher, $orderObject, $this->settings['limit']));
		$this->view->assign('imageStack', $this->imageRepository->findBy($matcher, $orderObject, 10000000, $this->settings['limit']));
		$this->view->assign('numberOfVisibleImages', $this->settings['limit'] > $totalImages ? $totalImages : $this->settings['limit']);
	}

	/**
	 * Get a list that is suitable for an Ajax
	 *
	 * @param int $contentElement
	 * @return void
	 */
	public function listAjaxAction($contentElement) {

		/** @var $contentRepository \TYPO3\CMS\InfiniteScrollGallery\Domain\Repository\ContentRepository */
		$contentRepository = $this->objectManager->get('TYPO3\CMS\InfiniteScrollGallery\Domain\Repository\ContentRepository');
		$contentElement = $contentRepository->findByUid($contentElement);

		$flexFormSettings = $this->flexFormService->convertFlexFormContentToArray($contentElement['pi_flexform']);
		$this->settings = $flexFormSettings['settings'];

		$limit = $this->settings['limit'] ? (int)$this->settings['limit'] : 10;

		$orderObject = $this->getOrderObject();
		$matcher = $this->getMatcherObject();

		$offset = (int)$this->request->getArgument('offset');
		$offsetImageStack = ($offset + $limit); //fix maximum limit

		$this->view->assign('settings', $this->settings);
		$this->view->assign('images', $this->imageRepository->findBy($matcher, $orderObject, $this->settings['limit'], $offset));
		$this->view->assign('imageStack', $this->imageRepository->findBy($matcher, $orderObject, 10000000, $offsetImageStack));
		$this->view->assign('totalImages', $this->imageRepository->countBy($matcher));

	}

	/**
	 * Get category objects
	 *
	 * @return \TYPO3\CMS\Media\Domain\Model\Category[]
	 */
	protected function getCategoriesObjects() {
		$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['categories']);

		// Ugly trick to get a first empty value in form.select View Helper
		/** @var \TYPO3\CMS\Media\Domain\Model\Category $category */
		$category = $this->objectManager->get('TYPO3\CMS\Media\Domain\Model\Category');
		$category->setTitle(
			\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('select_category', 'infinite_scroll_gallery')
		);

		$categoryObjects[] = $category;
		foreach ($categories as $category) {
			$categoryObjects[] = $this->categoryRepository->findByUid($category);
		}
		return $categoryObjects;
	}

	/**
	 * Get an order object
	 *
	 * @return \TYPO3\CMS\Media\QueryElement\Order
	 */
	protected function getOrderObject() {
		/** @var $order \TYPO3\CMS\Media\QueryElement\Order */
		$order = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Order');
		$parts = explode(' ', $this->settings['orderBy']);
		$order->addOrdering($parts[0], $parts[1]);

		return $order;
	}

	/**
	 * Get an matcher object
	 *
	 * @return \TYPO3\CMS\Media\QueryElement\Matcher
	 */
	protected function getMatcherObject() {
		/** @var $matcher \TYPO3\CMS\Media\QueryElement\Matcher */
		$matcher = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Matcher');

		// Get categories from argument if existing. Otherwise from settings.
		if ($this->request->hasArgument('category') && (int) $this->request->getArgument('category') > 0) {
			$categories[] = (int) $this->request->getArgument('category');
		} else {
			$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['categories']);
		}

		foreach ($categories as $category) {
			$matcher->addCategory($category);
		}

		// Add possible search term
		if ($this->request->hasArgument('searchTerm') && $this->request->getArgument('searchTerm') != '') {
			$matcher->setSearchTerm($this->request->getArgument('searchTerm'));
		}

		return $matcher;
	}
}

?>
