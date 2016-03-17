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
	 * mirror
	 *
	 * @var int
	 */
	protected $mirror;

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

	/**
	 * @return int
	 */
	public function getMirror()
	{
		return $this->mirror;
	}

	/**
	 * @param int $mirror
	 */
	public function setMirror($mirror)
	{
		$this->mirror = $mirror;
	}

}