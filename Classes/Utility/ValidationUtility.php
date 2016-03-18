<?php

namespace BC\BcConvert\Utility;

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

use Exception;

/**
 * Custom Storage backend
 */
class ValidationUtility
{
    /**
     * @return int
     * @throws \Exception
     */
    public static function getMessageSize()
    {
        // check if set
        if (!isset($_SERVER['HTTP_X_MESSAGE_SIZE'])) {
            throw new Exception('message size not set');
        }

        // validate
        if (!preg_match('/\d+/i', $_SERVER['HTTP_X_MESSAGE_SIZE'])) {
            throw new Exception('message size not set');
        }

        return intval($_SERVER['HTTP_X_MESSAGE_SIZE']);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getFileHash()
    {
        // check if set
        if (!isset($_SERVER['HTTP_X_FILE_HASH'])) {
            throw new Exception('file hash not set');
        }

        // validate
        if (!preg_match('/^[-a-z0-9_][-a-z0-9_.]*$/i', $_SERVER['HTTP_X_FILE_HASH'])) {
            throw new Exception('file hash not valid');
        }

        return $_SERVER['HTTP_X_FILE_HASH'];
    }

    /**
     * @param mixed $data
     * @return Object
     * @throws \Exception
     */
    public static function parseManifest($data)
    {
        $manifest = json_decode($data);

        // gets the manifest
        if ($manifest === null) {
            throw new Exception('Manifest not valid');
        }

        if (!array_key_exists('chunks', $manifest)) {
            throw new Exception('Chunks key missing in manifest');
        }

        if (!array_key_exists('name', $manifest)) {
            throw new Exception('Name key missing in manifest');
        }

        if (!array_key_exists('mime', $manifest)) {
            throw new Exception('Mime key missing in manifest');
        }

        if (!array_key_exists('size', $manifest)) {
            throw new Exception('Size key missing in manifest');
        }

        return $manifest;
    }
}