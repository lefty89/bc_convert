<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.' . $_EXTKEY,
	'Info',
	array(
		'Info' => 'show',
	),
	// non-cacheable actions
	array(
		'Info' => 'show',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.'.$_EXTKEY,
	'File',
	array(
		'File' => 'handle, download, upload, queueState, state, convert, transcodeList',
	),
	// non-cacheable actions
	array(
		'File' => 'handle, download, upload, queueState, state, convert, transcodeList'
	)
);

// scheduler task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'BC\\BcConvert\\Command\\ConvertCommandController';