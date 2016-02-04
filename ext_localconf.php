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