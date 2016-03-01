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

		/** @var array $output */
		$output = array();
		/** @var \BC\BcConvert\Domain\Model\Queue $queue */
		$queue = $this->queueRepository->getOneQueueByFileHash($hash);

		if ($queue !== NULL) {
			// file is currently converting
			if ($queue->getFile()->getHash() === ConvertUtility::isCurrentlyConverting()) {

				// parse current converting process
				$data = array();
				ConvertUtility::parseCurrentProcess($data);

				// sets converting state
				$output['state']    = "converting";
				$output['progress'] = $data['progress'];
				$output['duration'] = $data['duration'];
				$output['ctime']    = $data['ctime'];
			}
			else {
				if (!$queue->getPath()) {
					// sets the queue position
					$output['state'] = "waiting";
					if ($output['position'] = $this->queueRepository->getQueuePosition($queue)) {
						// error handlnig for missing queue position
					}
				} else {
					// if queue already had a path
					// its only waiting for finalisation
					$output['state'] = "finalize";
				}
			}
		}
		else {
			// show dialog for new transcoding
			$output['state'] = "transcodeable";
		}

		$this->view->assign('value', $output);
	}

	/**
	 * action initialize convert
	 */
	public function initializeConvertAction()
	{
		if (isset($this->arguments['queue'])) {

			/** @var array $args */
			$args = $this->request->getArguments();

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
			$queue->setTime(new \DateTime("now"));
			$this->queueRepository->add($queue);

			// set state
			$data['state'] = "waiting";
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
	public function uploadManifestAction()
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
	public function uploadChunkAction()
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