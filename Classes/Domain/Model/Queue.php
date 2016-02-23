<?php
namespace BC\BcConvert\Domain\Model;

/**
 *
 * User: Lefty
 * Date: 31.01.2015
 * Time: 13:21
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Chunk
 */
class Queue extends AbstractEntity {

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
	 */
	protected $videoBitrate;

	/**
	 * audioBitrate
	 *
	 * @var int
	 */
	protected $audioBitrate;

	/**
	 * format
	 *
	 * @var int
	 */
	protected $format;

	/**
	 * complete
	 *
	 * @var boolean
	 */
	protected $complete;

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
	 * @return int
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * @param int $format
	 */
	public function setFormat($format)
	{
		$this->format = $format;
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
}