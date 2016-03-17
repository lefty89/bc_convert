<?php

namespace BC\BcConvert\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class QueueRepository extends Repository {

	/**
	 * gets the next video added to queue
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function getNextItem()
	{
		/** @var \TYPO3\CMS\Extbase\Persistence\QueryInterface $query */
		$query = $this->createQuery();

		$query->setOrderings(array(
			'time' => QueryInterface::ORDER_ASCENDING)
		);

		// get only video files
		$query->matching($query->logicalAnd(array(
			$query->like('file.mime', "video/%"),
			$query->like('complete', false)
		)));

		// ignore pid
		$query->getQuerySettings()->setRespectStoragePage(false);

		return $query->execute();
	}

	/**
	 * @param \BC\BcConvert\Domain\Model\Queue $queue
	 * @return int
	 */
	public function getQueuePosition($queue)
	{
		/** @var \TYPO3\CMS\Extbase\Persistence\QueryInterface $query */
		$query = $this->createQuery();

		$query->setOrderings(array(
			'time' => QueryInterface::ORDER_ASCENDING)
		);

		// get only video files
		$query->matching($query->logicalAnd(array(
			$query->like('file.mime', "video/%"),
			$query->like('complete', false)
		)));

		// ignore pid
		$query->getQuerySettings()->setRespectStoragePage(false);

		/** @var array $queuedItems */
		$queuedItems = $query->execute(true);

		/** @var  $v */
		foreach ($queuedItems as $key => $value) {
			if (intval($value['uid']) === $queue->getUid()) return $key + 1;
		}

		return 0;
	}


	/**
	 * @param @param string $hash
	 * @return \BC\BcConvert\Domain\Model\Queue|null
	 */
	public function getOneQueueByFileHash($hash)
	{
		/** @var \TYPO3\CMS\Extbase\Persistence\QueryInterface $query */
		$query = $this->createQuery();

		// get only video files
		$query->matching($query->logicalAnd(array(
			$query->like('file.hash', $hash),
			$query->like('complete', false)
		)));

		// ignore pid
		$query->getQuerySettings()->setRespectStoragePage(false);

		return $query->execute()->getFirst();
	}
}