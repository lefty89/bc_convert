<?php

namespace BC\BcConvert\Command;

/***************************************************************
 *  Copyright notice
 *
 *  (C) 2016 Lefty (fb.lefty@web.de)
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this script. If not, see <http://www.gnu.org/licenses/>.
 *
 ***************************************************************/

use BC\BcConvert\Utility\ConvertUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
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