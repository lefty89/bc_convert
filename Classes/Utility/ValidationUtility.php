<?php

namespace BC\BcConvert\Utility;

use Exception;

/**
 * Custom Storage backend
 */
class ValidationUtility {

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
		if ($manifest === NULL)
			throw new Exception('Manifest not valid');

		if (!array_key_exists('chunks', $manifest))
			throw new Exception('Chunks key missing in manifest');

		if (!array_key_exists('name', $manifest))
			throw new Exception('Name key missing in manifest');

		if (!array_key_exists('mime', $manifest))
			throw new Exception('Mime key missing in manifest');

		if (!array_key_exists('size', $manifest))
			throw new Exception('Size key missing in manifest');

		return $manifest;
	}
}