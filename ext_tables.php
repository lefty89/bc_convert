<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Bugcluster Video Converter');

/**
 * Flexform for Info Plugin
 */
$TCA['tt_content']['types']['list']['subtypes_addlist'][$extensionName.'_info'] = 'pi_flexform';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($extensionName.'_info', 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForm/flexform_info.xml');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Info',
	'Bugcluster Video Converter'
);