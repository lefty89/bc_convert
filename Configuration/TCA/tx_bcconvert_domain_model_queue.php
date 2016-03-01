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
		'delete' => 'deleted',
		'dividers2tabs' => TRUE,

		'searchFields' => 'file,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('bc_convert') . 'Resources/Public/Icons/file.svg'
	),
	'interface' => array(
		'showRecordFieldList' => 'file, video_bitrate, video_width, video_height, audio_bitrate, audio_sampling_rate, audio_channels, format, time, complete, path',
	),
	'types' => array(
		'1' => array('showitem' => 'file, video_bitrate, video_width, video_height, audio_bitrate, audio_sampling_rate, audio_channels, format, time, complete, path'),
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
		'video_width' => Array (
			'exclude' => 0,
			'label' => 'video_bitrate',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'video_height' => Array (
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
		'audio_sampling_rate' => Array (
			'exclude' => 0,
			'label' => 'video_bitrate',
			'config' => array(
				'type' => 'input',
				'eval' => 'int'
			),
		),
		'audio_channels' => Array (
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
			),
		),
		'time' => Array (
			'exclude' => 0,
			'label' => 'Queue time',
			'config' => Array (
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => '0000-00-00 00:00:00'
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
		'path' => Array (
			'exclude' => 0,
			'label' => 'path',
			'config' => array(
				'type' => 'input',
			),
		),
	),
);
