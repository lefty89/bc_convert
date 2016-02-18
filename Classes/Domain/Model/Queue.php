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
	 * Webm
	 *
	 * @var boolean
	 */
	protected $webm;

	/**
	 * Mp4
	 *
	 * @var boolean
	 */
	protected $mp4;

	/**
	 * Ogg
	 *
	 * @var boolean
	 */
	protected $ogg;

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
	 * @return boolean
	 */
	public function isWebm()
	{
		return $this->webm;
	}

	/**
	 * @param boolean $Webm
	 */
	public function setWebm($Webm)
	{
		$this->webm = $Webm;
	}

	/**
	 * @return boolean
	 */
	public function isMp4()
	{
		return $this->mp4;
	}

	/**
	 * @param boolean $Mp4
	 */
	public function setMp4($Mp4)
	{
		$this->mp4 = $Mp4;
	}

	/**
	 * @return boolean
	 */
	public function isOgg()
	{
		return $this->ogg;
	}

	/**
	 * @param boolean $Ogg
	 */
	public function setOgg($Ogg)
	{
		$this->ogg = $Ogg;
	}
}