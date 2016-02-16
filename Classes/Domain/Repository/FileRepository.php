<?php
namespace BC\BcConvert\Domain\Repository;
/**
 *
 * User: Lefty
 * Date: 31.01.2015
 * Time: 13:21
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use BC\BcConvert\Domain\Model\File;
use BC\BcConvert\Utility\FileUtility;
use BC\BcConvert\Utility\ValidationUtility;
use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FileRepository extends Repository {

	/**
	 * @param string $path
	 * @param string $hash
	 * @param Object $manifest
	 * @return \BC\BcConvert\Domain\Model\File
	 */
	private function createFileDbEntry($path, $hash, $manifest) {

		$file = new File();
		$file->setHash($hash);
		$file->setName($manifest->name);
		$file->setSize($manifest->size);
		$file->setMime($manifest->mime);
		$file->setPath($path);
		$this->add($file);

		$persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
		$persistenceManager->persistAll();

		return $file;
	}

	/**
	 * @return array|null
	 * @throws \Exception
	 */
	public function createManifest() {

		try
		{
			/** @var Object $manifest */
			$manifest = FileUtility::getManifest();

			/** @var string $hash */
			$hash = ValidationUtility::getFileHash();

			/** @var \BC\BcConvert\Domain\Model\File $file */
			$file = $this->findOneByHash($hash);

			if ($file === NULL) {
				// create a new file on the file system
				$path = FileUtility::createPersistentFile($hash, $manifest);
				// create a new file in the typo3 db
				$this->createFileDbEntry($path, $hash, $manifest);
				return $manifest->chunks;
			}
			else
			{
				/** @var Object $fileManifest */
				$fileManifest = FileUtility::readFileManifest($file);
				return array_merge(array_diff($fileManifest->chunks, array(FileUtility::COMPLETE_HASH)));
			}
		}
		catch (Exception $e) {
			return null;
		}
	}

	/**
	 * @return array|null
	 */
	public function addChunkToFile()
	{
		try
		{
			$chunks = array();

			/** @var string $hash */
			$hash = ValidationUtility::getFileHash();

			/** @var \BC\BcConvert\Domain\Model\File $file */
			$file = $this->findOneByHash($hash);

			if ($file !== null) {
				/** @var Object $fileManifest */
				$fileManifest = FileUtility::addChunkToExistingFile($file);
				/** @var array $chunks */
				$chunks = array_merge(array_diff($fileManifest->chunks, array(FileUtility::COMPLETE_HASH)));
			}

			if (!count($chunks)) {
				// complete file upload and update path
				if (FileUtility::completeFile($hash, $file)) {
					$this->update($file);
					$persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
					$persistenceManager->persistAll();
				}
			}

			return $chunks;
		}
		catch (Exception $e) {
			return null;
		}
	}
}