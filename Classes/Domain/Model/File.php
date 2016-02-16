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
class File extends AbstractEntity {

	/**
	 * name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * hash
	 *
	 * @var string
	 */
	protected $hash;

	/**
	 * mime
	 *
	 * @var string
	 */
	protected $mime;

	/**
	 * size
	 *
	 * @var int
	 */
	protected $size;

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
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @param string $hash
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;
	}

	/**
	 * @return string
	 */
	public function getMime()
	{
		return $this->mime;
	}

	/**
	 * @param string $mime
	 */
	public function setMime($mime)
	{
		$this->mime = $mime;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param int $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
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