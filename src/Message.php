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
	 * This parameter identifies a group of messages
	 * (e.g., with collapse_key: "Updates Available") that can be collapsed,
	 * so that only the last message gets sent when delivery can be resumed.
	 * This is intended to avoid sending too many of the same messages when the
	 * device comes back online or becomes active.
	 *
	 * Note that there is no guarantee of the order in which messages get sent.
	 *
	 * Note: A maximum of 4 different collapse keys is allowed at any given time.
	 * This means a FCM connection server can simultaneously store 4 different
	 * send-to-sync messages per client app. If you exceed this number, there is
	 * no guarantee which 4 collapse keys the FCM connection server will keep.
	 *
	 * For more information see: {@link https://firebase.google.com/docs/cloud-messaging/http-server-ref}
	 *
	 * @param string $key The Collapse Key
	 */
	public function setCollapseKey($key) {
		$this->fields["collapse_key"] = $key;
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
