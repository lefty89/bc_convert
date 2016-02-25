<?php

namespace BC\BcConvert\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Custom Storage backend
 */
class ConvertUtility {

	/**
	 * checks whether the converting script is currently running and returns its given hash
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @return boolean
	 */
	public static function isConvertable($file)
	{
		return (strpos($file->getMime(), "video/") !==  FALSE);
	}

	/**
	 * checks whether the converting script is currently running and returns its given hash
	 * @return string|null
	 */
	public static function isCurrentlyConverting()
	{
		$script = GeneralUtility::getFileAbsFileName("EXT:bc_convert/Resources/Private/Bash/bugcluster-video-converter.sh");
		$quoted = preg_quote($script, '/');

		// prepare shell script
		$hits = array();
		$shell = "ps aux | grep $script";
		$shellReturn = shell_exec($shell);

		// execute shell script
		preg_match('/bash\s'.$quoted.'\s([a-zA-Z0-9]+)/m', $shellReturn, $hits);

		return $hits[1];
	}

	/**
	 * FROM: http://stackoverflow.com/questions/11441517/ffmpeg-progress-bar-encoding-percentage-in-php
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @param array $data
	 */
	public static function parseCurrentProcess($file, &$data)
	{
		/** @var string $log */
		$log = PATH_site.dirname($file->getPath())."/transcode/log.txt";

		if ($content = @file_get_contents($log)){

			//get duration of source
			preg_match("/Duration: (.*?), start:/", $content, $matches);

			$rawDuration = $matches[1];

			//rawDuration is in 00:00:00.00 format. This converts it to seconds.
			$ar = array_reverse(explode(":", $rawDuration));
			$duration = floatval($ar[0]);
			if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
			if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

			//get the time in the file that is already encoded
			preg_match_all("/time=(.*?) bitrate/", $content, $matches);

			$rawTime = array_pop($matches);

			//this is needed if there is more than one match
			if (is_array($rawTime)){$rawTime = array_pop($rawTime);}

			//rawTime is in 00:00:00.00 format. This converts it to seconds.
			$ar = array_reverse(explode(":", $rawTime));
			$time = floatval($ar[0]);
			if (!empty($ar[1])) $time += intval($ar[1]) * 60;
			if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

			// parse output path
			$output = array();
			if (preg_match('/Output\s#0,\s(\w{1,3}),\sto\s\'([a-zA-Z0-9\/\._]*)\':/', $content, $output)) {
				$data['ext']  = $output[1];
				$data['path'] = $output[2];
			}

			// write to array
			$data['progress'] = round(($time/$duration) * 100);
			$data['duration'] = $duration;
			$data['ctime'] = $time;

		}
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 */
	public static function startConvertingVideo($queue)
	{
		// bash script
		$script = GeneralUtility::getFileAbsFileName("EXT:bc_convert/Resources/Private/Bash/bugcluster-video-converter.sh");

		// necessary data
		$hash = $queue->getFile()->getHash();
		$path = PATH_site.$queue->getFile()->getPath();
		$transPath = dirname($path).'/transcode';

		// delete breaked files and recreate folder
		if (file_exists($transPath)) {
			array_map('unlink', glob("$transPath/*.*"));
		}
		mkdir($transPath, 0777, true);

		// prepare shell script
		$shell = sprintf("nohup bash %s %s %s %d >/dev/null 2>&1 &",
			$script,	// full convert script path
			$hash,		// file hash
			$path,		// path to original file
			1
		);
		shell_exec($shell);
	}
}