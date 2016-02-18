<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.' . $_EXTKEY,
	'Info',
	array(
		'Info' => 'show, convert, prepare',
	),
	// non-cacheable actions
	array(
		'Info' => 'show, convert, prepare',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.'.$_EXTKEY,
	'File',
	array(
		'File' => 'handle, download, upload',
	),
	// non-cacheable actions
	array(
		'File' => 'handle, download, upload'
	)
);

// scheduler task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'BC\\BcConvert\\Command\\ConvertCommandController';