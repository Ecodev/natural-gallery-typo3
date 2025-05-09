<?php
namespace Fab\NaturalGallery\ViewHelpers;

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

use FluidTYPO3\Vhs\Asset;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper to load a JavaScript file
 */
class LoadAssetsViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function render(): void
    {
        $settings = $this->templateVariableContainer->get('settings');

        if ($settings['asset']) {
            foreach ($settings['asset'] as $assetName => $asset) {
                if ($this->shouldLoadByVhs($settings)) {
                    $asset['name'] = $assetName;
                    $this->loadByVhs($asset);

                } else {
                    $this->loadByCorePageRender($asset);
                }
            }
        }
    }

    /**
     * @param array $asset
     * @return void
     */
    protected function loadByVhs(array $asset): void
    {

        if (Environment::getContext()->isDevelopment()) {
            $developmentFile = $this->getDevelopmentFile($asset);
            if ($developmentFile) {
                $asset['path'] = str_replace('.min.', '.', $asset['path']);
            }
        }
        Asset::createFromSettings($asset);
    }

    /**
     * @param array $asset
     * @return void
     */
    protected function loadByCorePageRender(array $asset): void
    {

        $file = $this->resolveFileForApplicationContext($asset);

        $fileNameAndPath = GeneralUtility::getFileAbsFileName($file);
        $fileNameAndPath = PathUtility::stripPathSitePrefix($fileNameAndPath);

        if ($asset['type'] === 'js') {
            $this->getPageRenderer()->addJsFooterFile($fileNameAndPath);
        } elseif ($asset['type'] === 'css') {
            $this->getPageRenderer()->addCssFile($fileNameAndPath);
        }
    }

    /**
     * @param array $settings
     * @return bool
     */
    protected function shouldLoadByVhs(array $settings): bool
    {
        return ExtensionManagementUtility::isLoaded('vhs') && $settings['loadAssetWithVhsIfAvailable'];
    }

    /**
     * @param array $asset
     * @return string|NULL
     */
    protected function getDevelopmentFile(array $asset): ?string
    {
        $possibleDevelopmentFile = str_replace('.min.', '.', $asset['path']);
        $developmentFile = GeneralUtility::getFileAbsFileName($possibleDevelopmentFile);
        if (!file_exists($developmentFile)) {
            $developmentFile = NULL;
        }
        return $developmentFile;
    }

    /**
     * @param array $asset
     * @return string
     */
    protected function resolveFileForApplicationContext(array $asset): string
    {
        $resolvedFile = $asset['path']; // default value

        // check if there is a non minimized file for context Development
        if (Environment::getContext()->isDevelopment()) {
            $developmentFile = $this->getDevelopmentFile($asset);
            if ($developmentFile) {
                $resolvedFile = $developmentFile;
            }
        }
        return $resolvedFile;
    }

    /**
     * @return PageRenderer
     */
    protected function getPageRenderer(): PageRenderer
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * Returns an instance of the Frontend object.
     *
     * @return TypoScriptFrontendController
     */
    protected function getFrontendObject(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

}
