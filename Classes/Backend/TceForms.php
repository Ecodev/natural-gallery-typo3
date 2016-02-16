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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

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
    public function getSelections(&$parameters)
    {

        $parameters['items'][] = array('', '', NULL);

        /** @var SelectionRepository $selectionRepository */
        $selectionRepository = $this->getObjectManager()->get(SelectionRepository::class);
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
    protected function getDataTypeFromFlexform($parameters)
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
    protected function getPluginConfiguration()
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
    protected function getConfigurationManager()
    {
        return $this->getObjectManager()->get(BackendConfigurationManager::class);
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        /** @var ObjectManager $objectManager */
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

}
