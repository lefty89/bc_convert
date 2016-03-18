<?php

namespace BC\BcConvert\Tests\Unit\Domain\Repository;

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

use TYPO3\CMS\Core\Tests\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class QueueRepositoryTest extends FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var  \BC\BcConvert\Domain\Repository\QueueRepository */
    protected $queueRepository;

    /** @var array */
    protected $testExtensionsToLoad = array(
        'typo3conf/ext/bc_convert');

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->queueRepository = $this->objectManager->get('BC\\BcConvert\\Domain\\Repository\\QueueRepository');

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_bcconvert_domain_model_queue.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/tx_bcconvert_domain_model_file.xml');
    }

    /**
     * Test getNextItem
     *
     * @test
     * @return void
     */
    public function getNextItem() {

        /** @var \BC\BcConvert\Domain\Model\Queue $queue */
        $queue = $this->queueRepository->getNextItem()->getFirst();

        $this->assertEquals($queue->getPath(), '/var/www/world.mp4');
    }
}