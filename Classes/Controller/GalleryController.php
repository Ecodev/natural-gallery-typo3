<?php

/***************************************************************
 *  Copyright notice
 *  (c) 2011 Fabien Udriot <fabien.udriot
 * @ecodev.ch>, Ecodev
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package infinite_scroll_gallery
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later

 */
class Tx_InfiniteScrollGallery_Controller_GalleryController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * tagRepository
	 *
	 * @var Tx_InfiniteScrollGallery_Domain_Repository_TagRepository $tagRepository
	 */
	protected $tagRepository;

	/**
	 * imageRepository
	 *
	 * @var Tx_InfiniteScrollGallery_Domain_Repository_ImageRepository $imageRepository
	 */
	protected $imageRepository;

	/**
	 * @param Tx_InfiniteScrollGallery_Domain_Repository_ImageRepository $imageRepository
	 * @return void
	 */
	public function injectImageRepository(Tx_InfiniteScrollGallery_Domain_Repository_ImageRepository $imageRepository) {
		$this->imageRepository = $imageRepository;
	}

	/**
	 * injectTagRepository
	 *
	 * @param Tx_InfiniteScrollGallery_Domain_Repository_TagRepository $tagRepository
	 * @return void
	 */
	public function injectTagRepository(Tx_InfiniteScrollGallery_Domain_Repository_TagRepository $tagRepository) {
		$this->tagRepository = $tagRepository;
	}

	/**
	 * Initializes default settings for all actions.
	 */
	public function initializeAction() {
		// TypoScript configuration
		$this->frontendConfiguration = $GLOBALS['TSFE']->tmpl->setup['config.'];
		$this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['infinite_scroll_gallery']);

		// this value can be overrident by a parameter for Ajax purposes
		if ($this->request->hasArgument('recordUid')) {
			$this->contentObjectData = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tt_content', 'uid = ' . (int)$this->request->getArgument('recordUid'));
		}
		else {
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
			$languageInfo = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'sys_language', 'uid = ' . intval($this->frontendConfiguration['sys_language_uid']));

			if (!empty($languageInfo)) {
				$language = $languageInfo['title'];
			}
		}
		$totalImages = $this->imageRepository->countImages($this->request, $limit, $this->contentObjectData);
		$this->view->assign('totalImages', $totalImages);
		$this->view->assign('data', $this->contentObjectData);
		$this->view->assign('enableMoreLoading', $this->contentObjectData['tx_infinitescrollgallery_enablemoreloading']);
		$this->view->assign('language', $language);
		$this->view->assign('tags', $this->tagRepository->findAll($this->contentObjectData));
		$this->view->assign('loadJquery', $this->configuration['loadJquery']);
		$this->view->assign('images', $this->imageRepository->findAll($this->request, $limit, $this->contentObjectData));
		$this->view->assign('stockImages', $this->imageRepository->findStock($this->request, $stockLimit, $this->contentObjectData));
		$this->view->assign('recordUid', $this->contentObjectData['uid']);
		$this->view->assign('limit', $limit);
		$this->view->assign('numberOfVisibleImages', $limit > $totalImages ? $totalImages : $limit);
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
	}
}

?>
