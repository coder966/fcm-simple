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

	const PRIORITY_NORMAL = "normal";
	const PRIORITY_HIGH = "high";

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
	 * @deprecated This method is deprecated. Use {@link Message#put} instead.
	 * Add a pair of data
	 * @param string $key
	 * @param string $value
	 */
	public function add($key, $value) {
		$this->fields["data"][$key] = $value;
	}

	/**
	 * Put a pair of data (key->value)
	 * @param string $key
	 * @param string $value
	 */
	public function put($key, $value) {
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
	 * Sets the priority of the message. Valid values are:
	 * {@link Message::PRIORITY_NORMAL} and {@link Message::PRIORITY_HIGH}
	 *
	 * By default, messages are sent with normal priority.
	 *
	 * Normal priority optimises the client app's battery consumption and should
	 * be used unless immediate delivery is required. For messages with normal
	 * priority, the app may receive the message with unspecified delay.
	 *
	 * When a message is sent with high priority, it is sent immediately, and the
	 * app can wake a sleeping device and open a network connection to your server.
	 *
	 * For more information, see {@link https://firebase.google.com/docs/cloud-messaging/concept-options#setting-the-priority-of-a-message}
	 *
	 * @param string $priority Message Priority
	 */
	public function setPriority($priority) {
		$this->fields["priority"] = $priority;
	}

	/**
	 * This parameter specifies how long (in seconds) the message should be kept
	 * in FCM storage if the device is offline. The maximum time to live
	 * supported is 4 weeks, and the default value is 4 weeks.
	 *
	 * For more information, see {@link https://firebase.google.com/docs/cloud-messaging/concept-options#ttl}
	 *
	 * @param int $time Time to live
	 */
	public function setTimeToLive($time) {
		$this->fields["time_to_live"] = $time;
	}

	/**
	 * Specifies the package name of the application where the registration
	 * tokens must match in order to receive the message.
	 *
	 * @param string $packageName Restricted package name
	 */
	public function setRestrictedPackageName($packageName) {
		$this->fields["restricted_package_name"] = $packageName;
	}

	/**
	 * When set to true, allows developers to test a request without actually
	 * sending a message. The default value is false.
	 *
	 * @param bool $dryRun Dry Run ?
	 */
	public function setDryRun($dryRun = true) {
		$this->fields["dry_run"] = $dryRun;
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
