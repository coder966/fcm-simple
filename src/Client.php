<?php

namespace FCMSimple;

/**
 * PHP class to send simple messages using Firebase Cloud Messaging (FCM)
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class Client {

	/**
	 * FCM send endpoint, should be constant
	 * @var string
	 */
	private static $FCM_SEND_ENDPOINT = "https://fcm.googleapis.com/fcm/send";

	/**
	 * FCM server key
	 * @var string
	 */
	private $serverKey;

	/**
	 * Array of the tokens
	 * @var array
	 */
	private $defaultTokens;

	/**
	 * Constructor
	 * @param string $serverKey FCM server key
	 */
	public function __construct($serverKey) {
		$valid = Client::performCall($serverKey);
		if ($valid) {
			$this->serverKey = $serverKey;
		} else {
			throw new \InvalidArgumentException("Invalid FCM server key.");
		}
	}

	/**
	 * Set the default tokens which will be used when no tokens are passed when calling {@link Client#send()}
	 * @param array $tokens Array of device tokens to send to
	 */
	public function setTokens(array $tokens) {
		$this->defaultTokens = $tokens;
	}

	/**
	 * Send message to the tokens
	 * @param Message $message Message object
	 * @param array $tokens [optional] Array of the tokens of the devices to send to.
	 * Can be null and therefore the array passed through {@link Client#setTokens()} will be used.
	 * @return Response A response object regarding the send operation.
	 */
	public function send(Message $message, array $tokens = null) {
		if ($message == null) {
			throw new \InvalidArgumentException("The message cannot be null.");
		}

		// prepare the tokens
		if (count($tokens) > 0) {
			$recipientTokens = $tokens;
		} else if (count($this->tokens) > 0) {
			$recipientTokens = $this->defaultTokens;
		} else {
			throw new \RuntimeException("Tokens not set. Pass them through FCMSimple::send()'s second argument or through FCMSimple::setTokens.");
		}

		return Client::performCall($this->serverKey, $message, $recipientTokens);
	}

	/**
	 * A utility function to execute post calls to the send endpoint.
	 * Pass null for (or just skip) $tokens and $message if you want to check the server key,
	 * the function will return a boolean indicating whether the passed server key
	 * is valid or not.
	 *
	 * @param string $serverKey FCM server key
	 * @param Message [optional] $message The message
	 * @param array [optional] $tokens Array of device tokens
	 * @return mixed The response if all arguments are passed, boolean otherwise
	 */
	private static function performCall($serverKey, Message $message = null, array $tokens = null) {
		// prepare the headers
		$headers = array(
			"Authorization: key={$serverKey}",
			"Content-Type: application/json"
		);

		// inject tokens into the message
		$messge = $message->fields;
		$messge["registration_ids"] = $tokens;

		// open connection
		$ch = curl_init();

		// setup connection
		curl_setopt($ch, CURLOPT_URL, Client::$FCM_SEND_ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messge));

		// avoid problems with https certification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// execute post
		$response = curl_exec($ch);

		// check server key
		if ($tokens == null && $message == null) {
			curl_setopt($ch, CURLOPT_NOBODY, true);  // we don't need body
			$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($responseCode == 401) {
				return false;
			} else {
				return true;
			}
		}

		// close connection
		curl_close($ch);

		return new Response($response, $tokens);
	}

}
