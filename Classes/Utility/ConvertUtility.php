<?php

namespace BC\BcConvert\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Custom Storage backend
 */
class ConvertUtility {

	/**
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 * @return bool
	 */
	public static function isConvertionFinished($queue)
	{
		$data = array();
		ConvertUtility::parseCurrentProcess($data);

		return ($data['path'] === GeneralUtility::getFileAbsFileName($queue->getPath())) && 	// check whether the last converting file is in the current queue
		(array_key_exists('progress', $data) && (intval($data['progress']) === 100));	// check whether the progress is at 100 percent
	}

	/**
	 * check whether an error occurred during file transcoding
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 * @return boolean
	 */
	public static function isTranscodedFileValid($queue)
	{
		if (!empty($queue->getPath())) {

			/** @var string $completePath */
			$completePath = GeneralUtility::getFileAbsFileName($queue->getPath());

			return (file_exists($completePath) && (filesize($completePath) > 0));
		}

		return true;
	}

	/**
	 * gets the absolute path to the log file
	 * @return string
	 */
	protected static function getLogRelFile()
	{
		/** @var string $storagePath */
		$storagePath = FileUtility::getSettings('fileStoragePath');

		/** @var string $storagePath */
		$logFile = FileUtility::getSettings('logFile');

		/** @var string $log */
		$log = $storagePath.$logFile;

		return $log;
	}

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
	 * @param array $data
	 */
	public static function parseCurrentProcess(&$data)
	{
		/** @var string $content */
		if ($content = @file_get_contents(GeneralUtility::getFileAbsFileName(ConvertUtility::getLogRelFile()))){

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
			if (preg_match('/Output\s#0,\s(\w{1,4}),\sto\s\'([a-zA-Z0-9\/\._]*)\':/', $content, $output)) {
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
	 * @return void
	 */
	public static function startConvertingVideo(&$queue)
	{
		// get folders
		$script = GeneralUtility::getFileAbsFileName("EXT:bc_convert/Resources/Private/Bash/bugcluster-video-converter.sh");

		// necessary data
		$inputFile = $queue->getFile()->getPath();
		$outputFile = $queue->getPath() ?: FileUtility::createTempFileInFolder(pathinfo($queue->getFile()->getName(), PATHINFO_FILENAME), $queue->getFormat());

		// prepare shell script
		$shell = sprintf("nohup bash %s %s %s %s %d %d %d %d %d %d %s %s >/dev/null 2>&1 &",
			$script,											// full convert script path
			$queue->getFile()->getHash(),						// PARAM 1: file hash
			GeneralUtility::getFileAbsFileName($inputFile),		// PARAM 2: absolute path to input file
			$queue->getFormat(),								// PARAM 3: format
			$queue->getVideoBitrate(),							// PARAM 4: video bitrate
			$queue->getVideoWidth(),							// PARAM 5: video width
			$queue->getVideoHeight(),							// PARAM 6: video height
			$queue->getAudioBitrate(),							// PARAM 7: audio bitrate
			$queue->getAudioChannels(),							// PARAM 8: audio channels
			$queue->getAudioSamplingRate(),						// PARAM 9: audio sample rate
			GeneralUtility::getFileAbsFileName(
				ConvertUtility::getLogRelFile()),				// PARAM 10: log
			GeneralUtility::getFileAbsFileName($outputFile)		// PARAM 11: absolute path to input file
		);
		shell_exec($shell);

		$queue->setPath($outputFile);
	}
}