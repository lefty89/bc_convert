<?php

namespace BC\BcConvert\Tests\Unit\Domain\Model;

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

use BC\BcConvert\Domain\Model\File;
use BC\BcConvert\Domain\Model\Queue;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test for Queue model
 *
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class QueueTest extends UnitTestCase
{
    /**
     * @var \BC\BcConvert\Domain\Model\Queue
     */
    protected $fileDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fileDomainModelInstance = new Queue();
    }

    /**
     * Test if file can be set
     *
     * @test
     * @return void
     */
    public function fileCanBeSet()
    {
        $file = new File();
        $this->fileDomainModelInstance->setFile($file);
        $this->assertEquals($file, $this->fileDomainModelInstance->getFile());
    }

    /**
     * Test if time can be set
     *
     * @test
     * @return void
     */
    public function timeCanBeSet()
    {
        $time = new \DateTime();
        $this->fileDomainModelInstance->setTime($time);
        $this->assertEquals($time, $this->fileDomainModelInstance->getTime());
    }

    /**
     * Test if videoBitrate can be set
     *
     * @test
     * @return void
     */
    public function videoBitrateCanBeSet()
    {
        $videoBitrate = 10000;
        $this->fileDomainModelInstance->setVideoBitrate($videoBitrate);
        $this->assertEquals($videoBitrate, $this->fileDomainModelInstance->getVideoBitrate());
    }

    /**
     * Test if videoWidth can be set
     *
     * @test
     * @return void
     */
    public function videoWidthCanBeSet()
    {
        $videoWidth = 1920;
        $this->fileDomainModelInstance->setVideoWidth($videoWidth);
        $this->assertEquals($videoWidth, $this->fileDomainModelInstance->getVideoWidth());
    }

    /**
     * Test if videoHeight can be set
     *
     * @test
     * @return void
     */
    public function videoHeightCanBeSet()
    {
        $videoHeight = 1080;
        $this->fileDomainModelInstance->setVideoHeight($videoHeight);
        $this->assertEquals($videoHeight, $this->fileDomainModelInstance->getVideoHeight());
    }

    /**
     * Test if audioBitrate can be set
     *
     * @test
     * @return void
     */
    public function audioBitrateCanBeSet()
    {
        $audioBitrate = 10000;
        $this->fileDomainModelInstance->setAudioBitrate($audioBitrate);
        $this->assertEquals($audioBitrate, $this->fileDomainModelInstance->getAudioBitrate());
    }

    /**
     * Test if audioSamplingRate can be set
     *
     * @test
     * @return void
     */
    public function audioSamplingRateCanBeSet()
    {
        $audioSamplingRate = 10000;
        $this->fileDomainModelInstance->setAudioSamplingRate($audioSamplingRate);
        $this->assertEquals($audioSamplingRate, $this->fileDomainModelInstance->getAudioSamplingRate());
    }

    /**
     * Test if audioChannels can be set
     *
     * @test
     * @return void
     */
    public function audioChannelsCanBeSet()
    {
        $audioChannels = 5;
        $this->fileDomainModelInstance->setAudioChannels($audioChannels);
        $this->assertEquals($audioChannels, $this->fileDomainModelInstance->getAudioChannels());
    }

    /**
     * Test if format can be set
     *
     * @test
     * @return void
     */
    public function formatCanBeSet()
    {
        $format = 'video/webm';
        $this->fileDomainModelInstance->setFormat($format);
        $this->assertEquals($format, $this->fileDomainModelInstance->getFormat());
    }

    /**
     * Test if complete can be set
     *
     * @test
     * @return void
     */
    public function completeCanBeSet()
    {
        $complete = true;
        $this->fileDomainModelInstance->setComplete($complete);
        $this->assertEquals($complete, $this->fileDomainModelInstance->isComplete());
    }

    /**
     * Test if path can be set
     *
     * @test
     * @return void
     */
    public function pathCanBeSet()
    {
        $path = '/var/www/test';
        $this->fileDomainModelInstance->setPath($path);
        $this->assertEquals($path, $this->fileDomainModelInstance->getPath());
    }
}