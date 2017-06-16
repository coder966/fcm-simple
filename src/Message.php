<?php

namespace FCMSimple;

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
	 * message fields
	 * @var array
	 */
	private $fields;

	/**
	 * Create new message
	 */
	public function __construct() {
		$this->fields = array(
			"data" => array(),
		);
	}

	/**
	 * Add a pair of data
	 * @param string $key
	 * @param string $value
	 */
	public function add($key, $value) {
		$this->fields["data"][$key] = $value;
	}

	/**
	 * This is meant to be used only internally
	 * @return array The data map
	 */
	public function __get($field) {
		if ($field == "fields") {
			return $this->fields;
		}
	}

}
