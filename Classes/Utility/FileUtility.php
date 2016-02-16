<?php

namespace BC\BcConvert\Utility;

use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 * Custom Storage backend
 */
class FileUtility {

	const COMPLETE_HASH = "00000000000000000000000000000000";

	/**
	 * get full typoscript settings for this extension
	 *
	 * @param string $name
	 * @return array
	 */
	private static function getSettings($name) {

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		/** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager */
		$configurationManager = $objectManager->get(ConfigurationManagerInterface::class);

		/** @var array $typoscript */
		$typoscript = $configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
			'BcConvert'
		);

		/** @var \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService */
		$typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

		// switch to no dot notation
		$rawTs = $typoScriptService->convertTypoScriptArrayToPlainArray($typoscript);

		return $rawTs['plugin']['tx_bcconvert']['settings'][$name];
	}

	/**
	 * @return Object
	 * @throws \Exception
	 */
	public static function getManifest() {

		/** mixed $data */
		if (($data = file_get_contents("php://input")) === FALSE)
			throw new Exception('Input data not valid');

		return ValidationUtility::parseManifest($data);
	}

	/**
	 * @param string $hash
	 * @param Object $manifest
	 * @return string
	 */
	public static function createPersistentFile($hash, $manifest) {

		/** @var string $storagePath */
		$storagePath = FileUtility::getSettings('fileStoragePath');

		// create directory if not existing
		$dir = $storagePath.$hash."/";
		if (!is_dir($dir)) mkdir($dir, 0777, true);

		// clean file name
		$cleanName = preg_replace('/[^a-zA-Z0-9-_\.]/','', $manifest->name);

		// concat temp filename
		$filename = $dir.$cleanName.".temp";

		// save file
		if (!file_exists($filename)) {

			// open in write mode.
			$fp = fopen($filename, 'w');

			// write manifest
			$man = json_encode($manifest) . PHP_EOL;
			fwrite($fp, $man, strlen($man));
			fflush($fp);

			// buffer file size
			$size = intval($manifest->size) + strlen($man);

			// write dummy content
			fseek($fp, $size-1, SEEK_SET);
			fwrite($fp, 'a');
			fflush($fp);

			// close file
			fclose($fp);
		}

		return $filename;
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @return Object
	 * @throws \Exception
	 */
	public static function readFileManifest($file)
	{
		if (file_exists($file->getPath())) {

			// open in read mode.
			$fp = fopen($file->getPath(), 'r');

			// read first line
			$manifestString = fgets($fp);

			fflush($fp);
			// close file
			fclose($fp);

			return ValidationUtility::parseManifest($manifestString);
		}
		else throw new Exception('File does not exist');
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @return Object
	 * @throws \Exception
	 */
	public static function addChunkToExistingFile($file)
	{
		/** mixed $blob */
		if (($blob = file_get_contents("php://input")) === FALSE)
			throw new Exception('Input data not valid');

		/** @var string $md5 */
		$md5 = md5($blob);

		if (file_exists($file->getPath())) {

			/** @var Object $fileManifest */
			$fileManifest = FileUtility::readFileManifest($file);

			// gets the index of the uploaded chunk
			$idx = array_search($md5, $fileManifest->chunks);

			if ($idx !== FALSE) {
				// clear the new part in the manifest file
				$fileManifest->chunks[$idx] = FileUtility::COMPLETE_HASH;
				// convert manifest back to json string
				$jsonManifest = json_encode($fileManifest) . PHP_EOL;

				// open in wrtie mode.
				$fp = fopen($file->getPath(), 'c+');

				# write manifest
				fseek($fp, 0, SEEK_SET);
				fwrite($fp, $jsonManifest, strlen($jsonManifest));
				fflush($fp);

				# write data
				fseek($fp, strlen($jsonManifest) + (1024 * 1024 * $idx), SEEK_SET);    // 1MB CHUNK SIZE
				fwrite($fp, $blob, strlen($blob));
				fflush($fp);

				// close file
				fclose($fp);
			}
			else throw new Exception('Chunk was not found in manifest');

			return $fileManifest;
		}
		else throw new Exception('File does not exist');
	}

	/**
	 * @param string $hash
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @return boolean
	 * @throws \Exception
	 */
	public static function completeFile($hash, $file)
	{
		// remove the file manifest
		if ($handle = fopen($file->getPath(), "c+")) {
			if (flock($handle, LOCK_EX)) {
				while (($line = fgets($handle)) !== FALSE) {
					if (!isset($write_position)) {
						$write_position = 0;
					} else {
						$read_position = ftell($handle);
						fseek($handle, $write_position);
						fputs($handle, $line);
						fseek($handle, $read_position);
						$write_position += strlen($line);
					}
				}
				fflush($handle);
				ftruncate($handle, $write_position);
				flock($handle, LOCK_UN);
			}
			fclose($handle);
		}
		else throw new Exception('File could not be opened');

		// validate complete file
		if ((!is_file($file->getPath())) || ($hash !== md5_file($file->getPath()))) {
			throw new Exception('File is not valid');
		}

		/** @var string $filename */
		$filename = substr($file->getPath(), 0, strrpos($file->getPath(), "."));

		// rename file to its original name
		if (rename($file->getPath(), $filename)) {
			$file->setPath($filename);

			return true;
		}

		return false;
	}

}