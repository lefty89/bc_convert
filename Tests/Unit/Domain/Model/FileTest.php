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
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test for File model
 *
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class FileTest extends UnitTestCase
{
    /**
     * @var \BC\BcConvert\Domain\Model\File
     */
    protected $fileDomainModelInstance;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fileDomainModelInstance = new File();
    }

    /**
     * Test if name can be set
     *
     * @test
     * @return void
     */
    public function nameCanBeSet()
    {
        $name = 'Video.mp4';
        $this->fileDomainModelInstance->setName($name);
        $this->assertEquals($name, $this->fileDomainModelInstance->getName());
    }

    /**
     * Test if hash can be set
     *
     * @test
     * @return void
     */
    public function hashCanBeSet()
    {
        $hash = 'ABCDE123456';
        $this->fileDomainModelInstance->setHash($hash);
        $this->assertEquals($hash, $this->fileDomainModelInstance->getHash());
    }

    /**
     * Test if mime can be set
     *
     * @test
     * @return void
     */
    public function mimeCanBeSet()
    {
        $mime = 'video/mp4';
        $this->fileDomainModelInstance->setMime($mime);
        $this->assertEquals($mime, $this->fileDomainModelInstance->getMime());
    }

    /**
     * Test if size can be set
     *
     * @test
     * @return void
     */
    public function sizeCanBeSet()
    {
        $size = 123456;
        $this->fileDomainModelInstance->setSize($size);
        $this->assertEquals($size, $this->fileDomainModelInstance->getSize());
    }

    /**
     * Test if mirror can be set
     *
     * @test
     * @return void
     */
    public function mirrorCanBeSet()
    {
        $mirror = '/var/www/test/file.mp4';
        $this->fileDomainModelInstance->setMirror($mirror);
        $this->assertEquals($mirror, $this->fileDomainModelInstance->getMirror());
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