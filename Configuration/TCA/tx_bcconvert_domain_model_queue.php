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
		'showRecordFieldList' => 'file, video_bitrate, audio_bitrate, mp4, ogg, webm',
	),
	'types' => array(
		'1' => array('showitem' => 'file, video_bitrate, audio_bitrate, mp4, ogg, webm'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'file' => Array (
			'exclude' => 1,
			'label' => 'file',
			'config' => array(
				'type' => 'select',
				'minitems' => 1,
				'maxitems' => 1,
				'size' => 1,
				'foreign_table' => 'tx_bc_convert_domain_model_file',
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
		'mp4' => Array (
			'exclude' => 0,
			'label' => 'mp4',
			'config' => array(
				'type' => 'check',
			),
		),
		'ogg' => Array (
			'exclude' => 0,
			'label' => 'ogg',
			'config' => array(
				'type' => 'check',
			),
		),
		'webm' => Array (
			'exclude' => 0,
			'label' => 'webm',
			'config' => array(
				'type' => 'check',
			),
		),
	),
);
