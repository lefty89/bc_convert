<?php

namespace BC\BcConvert\Command;

use BC\BcConvert\Utility\ConvertUtility;
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
	 * Converts the next video
	 */
	public function convertVideoCommand() {

		if (ConvertUtility::isCurrentlyConverting() === NULL) {

			/** @var \BC\BcConvert\Domain\Model\Queue $queue */
			$queue = $this->queueRepository->getNextItem()->getFirst();

			if (($queue !== NULL) && (!$this->queueRepository->convertingIsFinished($queue))) {
				ConvertUtility::startConvertingVideo($queue);
			}
		}
	}

} 