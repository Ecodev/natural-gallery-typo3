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
	 * @var \TYPO3\CMS\Media\Domain\Repository\CategoryRepository $categoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \TYPO3\CMS\Media\Domain\Repository\ImageRepository $imageRepository
	 */
	protected $imageRepository;

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
	 * @param \TYPO3\CMS\Media\Domain\Repository\ImageRepository $imageRepository
	 * @return void
	 */
	public function injectImageRepository(\TYPO3\CMS\Media\Domain\Repository\ImageRepository $imageRepository) {
		/** @var $imageRepository \TYPO3\CMS\Media\Domain\Repository\ImageRepository */
		$this->imageRepository = $imageRepository;
	}

	/**
	 * @param \TYPO3\CMS\Media\Domain\Repository\CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(\TYPO3\CMS\Media\Domain\Repository\CategoryRepository $categoryRepository) {
		/** @var $categoryRepository \TYPO3\CMS\Media\Domain\Repository\CategoryRepository */
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Initializes default settings for all actions.
	 */
	public function initializeAction() {

		// TypoScript configuration
		$this->frontendConfiguration = $GLOBALS['TSFE']->tmpl->setup['config.'];
		$this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['infinite_scroll_gallery']);

	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		$stockLimit = $this->settings['limit'] . ' , ' . 10000000; //fix maximum limit

		/** @var $order \TYPO3\CMS\Media\QueryElement\Order */
		$order = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Order');
		$parts = explode(' ', $this->settings['orderBy']);
		$order->addOrdering($parts[0], $parts[1]);

		/** @var $matcher \TYPO3\CMS\Media\QueryElement\Matcher */
		$matcher = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Matcher');

		$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['categories']);

		// Ugly trick to get a first empty value in form.select View Helper
		/** @var \TYPO3\CMS\Media\Domain\Model\Category $category */
		$category = $this->objectManager->get('TYPO3\CMS\Media\Domain\Model\Category');
		$category->setTitle(
			\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('select_category', 'infinite_scroll_gallery')
		);

		$categoryObjects[] = $category;
		foreach ($categories as $category) {
			$matcher->addCategory($category);
			$category = $this->categoryRepository->findByUid($category);
			$matcher->addCategory($category);
			$categoryObjects[] = $category;
		}

		$totalImages = $this->imageRepository->countBy($matcher);
		$this->view->assign('totalImages', $totalImages);
		$this->view->assign('settings', $this->settings);
		$this->view->assign('language',
			empty($this->frontendConfiguration['language']) ? 'en' : $this->frontendConfiguration['language']
		);

		$this->view->assign('categories', $categoryObjects);
		$this->view->assign('loadJquery', $this->configuration['loadJquery']);

		$this->view->assign('images', $this->imageRepository->findBy($matcher, $order, $this->settings['limit']));
		$this->view->assign('stockImages', $this->imageRepository->findBy($matcher, $order, $stockLimit));
		$this->view->assign('numberOfVisibleImages', $this->settings['limit'] > $totalImages ? $totalImages : $this->settings['limit']);
		$this->view->assign('baseUri', $this->request->getBaseURI());
	}

	/**
	 * Get a list that is suitable for an Ajax
	 *
	 * @return void
	 */
	public function listAjaxAction() {
		$limit = $this->settings['limit'] ? (int)$this->settings['limit'] : 10;

		$offset = (int)$this->request->getArgument('offset');
		$stockLimit = ($offset + $limit) . ' , ' . 10000000; //fix maximum limit
		$limit = $offset . ' , ' . $limit;

		$this->view->assign('data', $this->settings);
		$this->view->assign('loadJquery', $this->configuration['loadJquery']);
		$this->view->assign('images', $this->imageRepository->findAll($this->request, $limit, $this->settings));
		$this->view->assign('stockImages', $this->imageRepository->findStock($this->request, $stockLimit, $this->settings));
		$totalImages = $this->imageRepository->countImages($this->request, $limit, $this->settings);
		$this->view->assign('totalImages', $totalImages);
		$this->view->assign('baseUri', $this->request->getBaseURI());
	}
}

?>
