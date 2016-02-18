<?php

namespace BC\BcConvert\Command;

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

		$queue = $this->queueRepository->getNextItem();


		$this->output(' Objekte wurden als Buchungen hinzugefügt.' . "\n");
	}

} 