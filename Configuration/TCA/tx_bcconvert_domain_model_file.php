<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

return array(
	'ctrl' => array(
		'title' => 'File',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'searchFields' => 'name,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('bc_convert') . 'Resources/Public/Icons/entity.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'name, hash, mime, size, complete, path',
	),
	'types' => array(
		'1' => array('showitem' => 'name, hash, mime, size, complete, path'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'name' => Array (
			'exclude' => 0,
			'label' => 'name',
			'config' => array(
				'type' => 'input',
				'eval' => 'trim'
			),
		),
		'hash' => Array (
			'exclude' => 0,
			'label' => 'hash',
			'config' => array(
				'type' => 'input',
				'eval' => 'trim'
			),
		),
		'mime' => Array (
			'exclude' => 0,
			'label' => 'mime',
			'config' => array(
				'type' => 'input',
				'eval' => 'trim'
			),
		),
		'size' => Array (
			'exclude' => 0,
			'label' => 'size',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'complete' => array(
			'exclude' => 1,
			'label' => 'complete',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'path' => Array (
			'exclude' => 0,
			'label' => 'path',
			'config' => array(
				'type' => 'input',
			),
		),
	),
);
