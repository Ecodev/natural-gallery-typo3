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
use Fab\NaturalGallery\Persistence\MatcherFactory;
use Fab\NaturalGallery\Persistence\OrderFactory;
use Fab\Vidi\Domain\Repository\ContentRepositoryFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller
 */
class GalleryController extends ActionController
{
    protected CategoryRepository $categoryRepository;

    protected array $configuration = array();

    protected $settings = array();

    /**
     * @return void|string
     */
    public function listAction()
    {
        if (!isset($this->settings['imagesPerRow'])) {
            return '<strong style="color: red">Please save your plugin settings in the BE beforehand.</strong>';
        }

        // Initialize some objects related to the query.
        $matcher = MatcherFactory::getInstance()->getMatcher($this->settings);
        $order = OrderFactory::getInstance()->getOrder($this->settings);

        // Fetch the adequate repository for a known data type.
        $contentRepository = ContentRepositoryFactory::getInstance('sys_file');

        // Fetch and count files
        $images = $contentRepository->findBy($matcher, $order);

        // Assign template variables
        $this->view->assign('settings', $this->settings);
        $this->view->assign('data', $this->configurationManager->getcontentObject()->data);
        $this->view->assign('images', $images);

        $identifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], TRUE);
        $this->view->assign('categories', $this->categoryRepository->findByIdentifiers($identifiers));
    }

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

}
