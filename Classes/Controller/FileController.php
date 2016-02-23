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

use BC\BcConvert\Utility\ConvertUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

/**
 * Class InfoController
 * @package BC\BcMediastream\Controller
 */
class FileController extends ActionController {

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'TYPO3\\CMS\\Extbase\\Mvc\\View\\JsonView';

	/**
	 * fileRepository
	 *
	 * @var \BC\BcConvert\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * queueRepository
	 *
	 * @var \BC\BcConvert\Domain\Repository\QueueRepository
	 * @inject
	 */
	protected $queueRepository;

	/**
	 * show queueState
	 * @param string $hash
	 */
	public function queueStateAction($hash) {

		/** @var array $data */
		$data = array();

		/** @var \BC\BcConvert\Domain\Model\Queue $queue */
		$queue = $this->queueRepository->getOneQueueByFileHash($hash);

		if ($queue !== NULL) {
			// file is currently converting
			if ($queue->getFile()->getHash() === ConvertUtility::isCurrentlyConverting()) {
				// sets converting state
				$data['state'] = "converting";
				$data['position'] = -1;
				// sets current progress
				ConvertUtility::parseCurrentProcess($queue->getFile(), $data);
			}
			else {
				// sets the queue position
				$data['state'] = "converting";
				$data['position'] = $this->queueRepository->getQueuePosition($queue);
			}
		}
		else {
			// show dialog for new transcoding
			$data['state'] = "transcodeable";
		}

		$this->view->assign('value', $data);
	}

	/**
	 * action initialize convert
	 */
	public function initializeConvertAction()
	{
		if (isset($this->arguments['queue'])) {

			/** @var array $args */
			$args = $this->request->getArguments();

			if ($args['queue']['format']) {
				$this->arguments['queue']->getPropertyMappingConfiguration()->allowProperties('format');
			}
			if ($args['queue']['videoBitrate']) {
				$this->arguments['queue']->getPropertyMappingConfiguration()->allowProperties('videoBitrate');
			}
			if ($args['queue']['audioBitrate']) {
				$this->arguments['queue']->getPropertyMappingConfiguration()->allowProperties('audioBitrate');
			}

			// allow creating of new object
			$propertyMappingConfiguration = $this->arguments['queue']->getPropertyMappingConfiguration();
			$propertyMappingConfiguration->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		}
	}

	/**
	 * @param string $hash
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 */
	public function convertAction($hash, $queue) {

		$data = array();

		/** @var \BC\BcConvert\Domain\Model\File $file */
		$file = $this->fileRepository->findOneByHash($hash);

		if ($file !== NULL) {
			$queue->setFile($file);
			$queue->setTime(new \DateTime());
			$this->queueRepository->add($queue);

			// set state
			$data['state'] 	  = "converting";
			$data['position'] = -1;
		}

		$this->view->assign('value', $data);
	}

	/**
	 * show action
	 */
	public function handleAction() {

		switch($_SERVER['HTTP_X_METHOD']) {
			case '1': {
				$this->getManifest();  break;}
			case '2': {
				$this->putFileChunk(); break;}
		}
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
	 * @param string $hash
	 */
	public function stateAction($hash)
	{
		$data = array();

		/** @var \BC\BcConvert\Domain\Model\File $file */
		$file = $this->fileRepository->findOneByHash($hash);

		if ($file !== NULL) {
			ConvertUtility::parseCurrentProcess($file, $data);
		}

		$this->view->assign('value', $data);
	}

	/**
	 * transcodeList action
	 *
	 * @param string $hash
	 */
	public function transcodeListAction($hash) {

		$data = array();

		/** @var \BC\BcConvert\Domain\Model\File $file */
		$file = $this->fileRepository->findOneByHash($hash);

		if ($file !== NULL) {
			$data = $this->fileRepository->getMirrorList($file);
		}
		$this->view->assign('value', $data);
	}

	/**
	 * creates the manifest and returns the chunk list
	 */
	protected function getManifest()
	{
		/** @var array $data */
		$data = array('chunks' => array());

		// set status code
		if (!$this->fileRepository->createManifest($data)) {
			$this->response->setStatus(400);
		}
		$this->view->assign('value', $data);
	}

	/**
	 * uploads a new file chunk
	 */
	protected function putFileChunk()
	{
		/** @var array $data */
		$data = array('chunks' => array());

		// set status code
		if (!$this->fileRepository->addChunkToFile($data)) {
			$this->response->setStatus(400);
		}
		$this->view->assign('value', $data);
	}

}