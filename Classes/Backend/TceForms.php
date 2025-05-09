<?php
namespace Fab\NaturalGallery\Backend;

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

use Fab\Vidi\Domain\Model\Selection;
use Fab\Vidi\Domain\Repository\SelectionRepository;
use Fab\Vidi\Tca\Tca;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;


/**
 * A class to interact with TCEForms.
 */
class TceForms
{


    /**
     * This method modifies the list of items for FlexForm "selection".
     *
     * @param array $parameters
     */
    public function getSelections(array &$parameters): void
    {

        $parameters['items'][] = array('', '', NULL);

        /** @var SelectionRepository $selectionRepository */
        $selectionRepository = GeneralUtility::makeInstance(SelectionRepository::class);
        $selections = $selectionRepository->findForEveryone('sys_file');

        if ($selections) {
            foreach ($selections as $selection) {
                /** @var Selection $selection */
                $values = array($selection->getName(), $selection->getUid(), NULL);
                $parameters['items'][] = $values;
            }
        }
    }

    /**
     * @param $parameters
     * @return string
     */
    protected function getDataTypeFromFlexform($parameters): string
    {

        $configuredDataType = '';
        if (!empty($parameters['row']['settings.dataType'][0])) {
            $configuredDataType = $parameters['row']['settings.dataType'][0];
        }

        if (!empty($parameters['row']['pi_flexform'])) {

            $flexform = GeneralUtility::xml2array($parameters['row']['pi_flexform']);
            if (!empty($flexform['data']['general']['lDEF']['settings.dataType'])) {
                $configuredDataType = $flexform['data']['general']['lDEF']['settings.dataType']['vDEF'];
            }
        }
        return $configuredDataType;
    }

    /**
     * Returns the TypoScript configuration for this extension.
     *
     * @return array
     */
    protected function getPluginConfiguration(): array
    {
        $setup = $this->getConfigurationManager()->getTypoScriptSetup();

        $pluginConfiguration = array();
        if (is_array($setup['plugin.']['tx_naturalgallery.'])) {

            /** @var TypoScriptService $typoScriptService */
            $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
            $pluginConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray($setup['plugin.']['tx_naturalgallery.']);
        }
        return $pluginConfiguration;
    }

    /**
     * @return BackendConfigurationManager
     */
    protected function getConfigurationManager(): BackendConfigurationManager
    {
        return GeneralUtility::makeInstance(BackendConfigurationManager::class);
    }

}
