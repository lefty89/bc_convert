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
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function getNextItem()
	{
		/** @var \TYPO3\CMS\Extbase\Persistence\QueryInterface $query */
		$query = $this->createQuery();

		$query->setOrderings(array(
			'uid' => QueryInterface::ORDER_ASCENDING)
		);
		$query->getQuerySettings()->setRespectStoragePage(false);

		return $query->execute();
	}

}