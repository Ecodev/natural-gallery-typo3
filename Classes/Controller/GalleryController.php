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
	protected $contentObjectData = array();

	/**
	 * @var \TYPO3\CMS\Dbal\Database\DatabaseConnection
	 */
	protected $databaseHandler;

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
		$this->databaseHandler = $GLOBALS['TYPO3_DB'];

		// this value can be overridden by a parameter for Ajax purposes
		if ($this->request->hasArgument('recordUid')) {
			$this->contentObjectData = $this->databaseHandler->exec_SELECTgetSingleRow('*', 'tt_content', 'uid = ' . (int)$this->request->getArgument('recordUid'));
		} else {
			$this->contentObjectData = $this->configurationManager->getcontentObject()->data;
		}
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		$limit = $this->contentObjectData['tx_infinitescrollgallery_limit'] ? (int)$this->contentObjectData['tx_infinitescrollgallery_limit'] : 10;
		$stockLimit = $limit . ' , ' . 10000000; //fix maximum limit

		// search for language configuration
		$language = '';
		if (!empty($this->frontendConfiguration['language'])) {
			$language = $this->frontendConfiguration['language'];
		} elseif ($this->frontendConfiguration['sys_language_uid']) {

			// @todo remove me?
			$languageInfo = $this->databaseHandler->exec_SELECTgetSingleRow('*', 'sys_language', 'uid = ' . intval($this->frontendConfiguration['sys_language_uid']));

			if (!empty($languageInfo)) {
				$language = $languageInfo['title'];
			}
		}

		/** @var $order \TYPO3\CMS\Media\QueryElement\Order */
		$order = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Order');
		$parts = explode(' ', $this->contentObjectData['tx_infinitescrollgallery_orderby']);
		$order->addOrdering($parts[0], $parts[1]);

		/** @var $filter \TYPO3\CMS\Media\QueryElement\Filter */
		$filter = $this->objectManager->get('TYPO3\CMS\Media\QueryElement\Filter');

		$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->contentObjectData['tx_infinitescrollgallery_defaulttagfilter']);
		$categoryObjects = array();
		foreach ($categories as $category) {
			$filter->addCategory($category);
			$categoryObjects[] = $this->categoryRepository->findByUid($category);
		}

		$totalImages = $this->imageRepository->countFiltered($filter);
		$this->view->assign('totalImages', $totalImages);
		$this->view->assign('data', $this->contentObjectData);
		$this->view->assign('orderby', $this->contentObjectData['tx_infinitescrollgallery_orderby']);
		$this->view->assign('enableMoreLoading', $this->contentObjectData['tx_infinitescrollgallery_enablemoreloading']);
		$this->view->assign('language', $language);

		$this->view->assign('tags', $categoryObjects);
		$this->view->assign('loadJquery', $this->configuration['loadJquery']);

		$this->view->assign('images', $this->imageRepository->findFiltered($filter, $order, $limit));
		$this->view->assign('stockImages', $this->imageRepository->findFiltered($filter, $order, $stockLimit));
		$this->view->assign('recordUid', $this->contentObjectData['uid']);
		$this->view->assign('limit', $limit);
		$this->view->assign('numberOfVisibleImages', $limit > $totalImages ? $totalImages : $limit);
		$this->view->assign('baseUri', $this->request->getBaseURI());
		$this->view->assign('showFilters', $this->contentObjectData['tx_infinitescrollgallery_showfilters']);
	}

	/**
	 * Get a list that is suitable for an Ajax
	 *
	 * @return void
	 */
	public function listAjaxAction() {
		$limit = $this->contentObjectData['tx_infinitescrollgallery_limit'] ? (int)$this->contentObjectData['tx_infinitescrollgallery_limit'] : 10;

		$offset = (int)$this->request->getArgument('offset');
		$stockLimit = ($offset + $limit) . ' , ' . 10000000; //fix maximum limit
		$limit = $offset . ' , ' . $limit;

		$this->view->assign('data', $this->contentObjectData);
		$this->view->assign('loadJquery', $this->configuration['loadJquery']);
		$this->view->assign('images', $this->imageRepository->findAll($this->request, $limit, $this->contentObjectData));
		$this->view->assign('stockImages', $this->imageRepository->findStock($this->request, $stockLimit, $this->contentObjectData));
		$totalImages = $this->imageRepository->countImages($this->request, $limit, $this->contentObjectData);
		$this->view->assign('totalImages', $totalImages);
		$this->view->assign('baseUri', $this->request->getBaseURI());
	}
}

?>
