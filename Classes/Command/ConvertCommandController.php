<?php

namespace BC\BcConvert\Command;

use BC\BcConvert\Utility\ConvertUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

class ConvertCommandController extends CommandController {

	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @var \BC\BcConvert\Domain\Repository\QueueRepository
	 * @inject
	 */
	protected $queueRepository;

	/**
	 * @var \BC\BcConvert\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * Converts the next video
	 */
	public function convertVideoCommand() {

		if (ConvertUtility::isCurrentlyConverting() === NULL) {
			/** @var \BC\BcConvert\Domain\Model\Queue $queue */
			$queue = $this->queueRepository->getNextItem()->getFirst();

			if ($queue !== NULL) {
				// check whether a transcoding is possible with the
				// given parameters
				if (ConvertUtility::isTranscodedFileBroken($queue)) {
					// delete corrupted queue
					$this->queueRepository->remove($queue);
					// remove file
					unlink(GeneralUtility::getFileAbsFileName($queue->getPath()));
				}
				else {
					// parse the progress of the given queue item
					$data = array();
					ConvertUtility::parseCurrentProcess($data);

					if (ConvertUtility::isConvertionFinished($queue)) {
						// mark queue as complete
						$queue->setComplete(true);
						// create new file
						$this->fileRepository->createFromQueue($queue, $data);
					}
					else {
						ConvertUtility::startConvertingVideo($queue);
					}
					$this->queueRepository->update($queue);
				}
			}
		}
	}

} 