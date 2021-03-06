<?php

namespace BC\BcConvert\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_convert
 */
class Queue extends AbstractEntity
{
    /**
     * file
     *
     * @var \BC\BcConvert\Domain\Model\File
     */
    protected $file;

    /**
     * @var \DateTime
     */
    protected $time;

    /**
     * videoBitrate
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=600000)
     */
    protected $videoBitrate;

    /**
     * videoWidth
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=1920)
     */
    protected $videoWidth;

    /**
     * videoHeight
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=1080)
     */
    protected $videoHeight;

    /**
     * audioBitrate
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=600000)
     */
    protected $audioBitrate;

    /**
     * audioSamplingRate
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=600000)
     */
    protected $audioSamplingRate;

    /**
     * audioChannels
     *
     * @var int
     * @validate NumberRange(minimum=1, maximum=10)
     */
    protected $audioChannels;

    /**
     * format
     *
     * @var string
     */
    protected $format;

    /**
     * complete
     *
     * @var boolean
     */
    protected $complete;

    /**
     * path
     *
     * @var string
     */
    protected $path;

    /**
     * @return \BC\BcConvert\Domain\Model\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \BC\BcConvert\Domain\Model\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getVideoBitrate()
    {
        return $this->videoBitrate;
    }

    /**
     * @param int $videoBitrate
     */
    public function setVideoBitrate($videoBitrate)
    {
        $this->videoBitrate = $videoBitrate;
    }

    /**
     * @return int
     */
    public function getAudioBitrate()
    {
        return $this->audioBitrate;
    }

    /**
     * @param int $audioBitrate
     */
    public function setAudioBitrate($audioBitrate)
    {
        $this->audioBitrate = $audioBitrate;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return boolean
     */
    public function isComplete()
    {
        return $this->complete;
    }

    /**
     * @param boolean $complete
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }

    /**
     * @return int
     */
    public function getVideoWidth()
    {
        return $this->videoWidth;
    }

    /**
     * @param int $videoWidth
     */
    public function setVideoWidth($videoWidth)
    {
        $this->videoWidth = $videoWidth;
    }

    /**
     * @return int
     */
    public function getVideoHeight()
    {
        return $this->videoHeight;
    }

    /**
     * @param int $videoHeight
     */
    public function setVideoHeight($videoHeight)
    {
        $this->videoHeight = $videoHeight;
    }

    /**
     * @return int
     */
    public function getAudioSamplingRate()
    {
        return $this->audioSamplingRate;
    }

    /**
     * @param int $audioSamplingRate
     */
    public function setAudioSamplingRate($audioSamplingRate)
    {
        $this->audioSamplingRate = $audioSamplingRate;
    }

    /**
     * @return int
     */
    public function getAudioChannels()
    {
        return $this->audioChannels;
    }

    /**
     * @param int $audioChannels
     */
    public function setAudioChannels($audioChannels)
    {
        $this->audioChannels = $audioChannels;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}