<?php

namespace FCMSimple\Tests;

/**
 * Message class holds pair of keys and values to be sent.
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class Message {

	/**
	 * Pairs of keys and values
	 * @var array
	 */
	private $map;

	/**
	 * Create new message
	 */
	public function __construct() {
		$this->map = array();
	}

	/**
	 * Add a pair of data
	 * @param string $key
	 * @param string $value
	 */
	public function add($key, $value) {
		$this->map[$key] = $value;
	}

	/**
	 * This is meant to be used only internally
	 * @return array The data map
	 */
	public function __get($field) {
		if ($field == "map") {
			return $this->map;
		}
	}

}
