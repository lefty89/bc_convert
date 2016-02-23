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
use BC\BcConvert\Utility\ConvertUtility;
use BC\BcConvert\Utility\FileUtility;
use BC\BcConvert\Utility\ValidationUtility;
use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FileRepository extends Repository {

	/**
	 * @param array $data
	 * @param \BC\BcConvert\Domain\Model\File $file
	 */
	private function fillMessage(&$data, $file) {

		$data = array_merge($data, array(
			'link'  => $file->getPath(),
			'cable' => ConvertUtility::isConvertable($file),
			'list'  => array()
		));

	}

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
	 * @param array $data
	 * @return boolean
	 * @throws \Exception
	 */
	public function createManifest(&$data) {

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
				$data['chunks'] = $manifest->chunks;
			}
			else if (!$file->isComplete()) {
				/** @var Object $fileManifest */
				$fileManifest = FileUtility::readFileManifest($file);
				$data['chunks'] = array_merge(array_diff($fileManifest->chunks, array(FileUtility::COMPLETE_HASH)));
			}
			else $this->fillMessage($data, $file);
		}
		catch (Exception $e) {
			error_log(print_r($e->getMessage(), TRUE));
			return false;
		}

		return true;
	}

	/**
	 * @param array $data
	 * @return boolean
	 */
	public function addChunkToFile(&$data)
	{
		try
		{
			/** @var string $hash */
			$hash = ValidationUtility::getFileHash();

			/** @var \BC\BcConvert\Domain\Model\File $file */
			$file = $this->findOneByHash($hash);

			if ($file !== null) {
				/** @var Object $fileManifest */
				$fileManifest = FileUtility::addChunkToExistingFile($file);
				/** @var array $chunks */
				$chunks = $data['chunks'] = array_merge(array_diff($fileManifest->chunks, array(FileUtility::COMPLETE_HASH)));

				if (!count($chunks)) {
					// complete file upload and update path
					if (FileUtility::completeFile($hash, $file)) {
						$this->fillMessage($data, $file);
						$file->setMirror($file->getUid());
						$this->update($file);
					}
				}
			}
			else throw new Exception('File was not found');
		}
		catch (Exception $e) {
			error_log(print_r($e->getMessage(), TRUE));
			return false;
		}

		return true;
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\File $file
	 * @return array
	 */
	public function getMirrorList($file)
	{
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			"name, path",
			"tx_bcconvert_domain_model_file",
			sprintf("mirror = %d AND uid != %d",
				$file->getMirror(),
				$file->getUid()),
			"",
			"",
			""
		);

		return $result;
	}
}