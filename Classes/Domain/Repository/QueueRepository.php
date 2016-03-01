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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

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