<?php

namespace BC\BcConvert\Controller;

/**
 *
 * User: Lefty
 * Date: 31.01.2015
 * Time: 13:21
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class InfoController
 * @package BC\BcMediastream\Controller
 */
class FileController extends ActionController {

	/**
	 * fileRepository
	 *
	 * @var \BC\BcConvert\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * show action
	 */
	public function handleAction() {

		switch($_SERVER['HTTP_X_METHOD']) {
			case '1': {$this->getManifest();  break;}
			case '2': {$this->putFileChunk(); break;}
		}
		exit;
	}

	/**
	 * show action
	 */
	public function downloadAction() {

		if ($v = GeneralUtility::_GP("v")) {

			/** @var \BC\BcConvert\Domain\Model\File $file */
			$file = $this->fileRepository->findOneByHash($v);

			// redirect to the file
			if ($file) {$this->redirectToUri($file->getPath());}
		}
	}

	/**
	 * creates the manifest and returns the chunk list
	 */
	protected function getManifest()
	{
		// set status code
		if (($chunks = $this->fileRepository->createManifest()) === null) {
			$this->response->setStatus(400);
		}
		echo json_encode($chunks ?: array());
	}

	/**
	 * uploads a new file chunk
	 */
	protected function putFileChunk()
	{
		// set status code
		if (($chunks = $this->fileRepository->addChunkToFile()) === null) {
			$this->response->setStatus(400);
		}
		echo json_encode($chunks ?: array());
	}
}