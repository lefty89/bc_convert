<?php

namespace BC\BcConvert\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Custom Storage backend
 */
class StandaloneUtility {

	/**
	 * render template
	 *
	 * @param string $templateFile
	 * @param array $variables
	 * @return string
	 */
	public static function renderStandaloneView($templateFile, $variables = array()) {

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
		$standaloneView = $objectManager->get(StandaloneView::class);

		/** @var string $templatePathAndFilename */
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($templateFile);

		$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
		$standaloneView->assignMultiple($variables);

		return $standaloneView->render();
	}
}