<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

return array(
	'ctrl' => array(
		'title' => 'Queue',
		'label' => 'file',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'searchFields' => 'file,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('bc_convert') . 'Resources/Public/Icons/entity.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'file, video_bitrate, audio_bitrate, format, time, complete',
	),
	'types' => array(
		'1' => array('showitem' => 'file, video_bitrate, audio_bitrate, format, time, complete'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'file' => Array (
			'exclude' => 0,
			'label' => 'file',
			'config' => array(
				'type' => 'select',
				'minitems' => 1,
				'maxitems' => 1,
				'size' => 1,
				'foreign_table' => 'tx_bcconvert_domain_model_file',
			)
		),
		'video_bitrate' => Array (
			'exclude' => 0,
			'label' => 'video_bitrate',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'audio_bitrate' => Array (
			'exclude' => 0,
			'label' => 'video_bitrate',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'format' => Array (
			'exclude' => 0,
			'label' => 'format',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'time' => Array (
			'exclude' => 0,
			'label' => 'Queue time',
			'config' => Array (
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
			)
		),
		'complete' => array(
			'exclude' => 0,
			'label' => 'complete',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
	),
);
