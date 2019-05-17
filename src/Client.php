<?php

namespace FCMSimple;

/**
 * A client used to pass messages from your app server to client apps via Firebase Cloud Messaging (FCM).
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class Client {

	/**
	 * FCM API send endpoint
	 * @var string
	 */
	private static $FCM_SEND_ENDPOINT = "https://fcm.googleapis.com/fcm/send";

	/**
	 * FCM server key
	 * @var string
	 */
	private $serverKey;

	/**
	 * Array of the default tokens
	 * @var array
	 */
	private $defaultTokens;

	/**
	 * Constructor
	 * @param string $serverKey FCM server key
	 */
	public function __construct($serverKey) {
        $this->serverKey = $serverKey;
        $this->defaultTokens = array();

		// preform a call just to validate server key
		$httpResponse = Client::performCall($serverKey, new Message(), []);
		if ($httpResponse[0] == 401) {
			throw new \InvalidArgumentException("Invalid FCM server key.");
		}
	}

	/**
	 * @deprecated
	 * Set the default tokens which will be used when no tokens are passed when calling {@link Client#send()}
	 * @param array $tokens Array of device tokens to send to
	 */
	public function setTokens(array $tokens) {
		$this->defaultTokens = $tokens;
	}

	/**
	 * Send a message to the specified devices.
	 * @param \FCMSimple\Message $message Message object
	 * @param array $tokens [optional] Array of the tokens of the devices to send to.
	 * Can be null and therefore the array passed through {@link Client#setTokens()} will be used.
	 * @return \FCMSimple\Response A response object regarding the send operation.
	 */
	public function send(Message $message, array $tokens = null) {
		if ($message == null) {
			throw new \InvalidArgumentException("The message cannot be null.");
		}

		// choose which array
		if ($tokens != null) {
			$recipientTokens = $tokens;
		} else {
			$recipientTokens = $this->defaultTokens;
		}

		// check if empty
		$count = count($recipientTokens);
		if($count == 0){
			throw new \InvalidArgumentException("Tokens array cannot be empty.");
		}else if($count > 1000){
			throw new \Exception("Too many tokens provided. The total number of tokens used in a single push cannot exceed 1000 tokens. Please chunk your tokens array.");
		}

        $httpResponse = Client::performCall($this->serverKey, $message, $recipientTokens);
        return new Response($httpResponse[0], $httpResponse[1], $recipientTokens);
	}

	/**
	 * A utility function to execute post calls to FCM's send endpoint.
	 *
	 * @param string $serverKey FCM server key
	 * @param \FCMSimple\Message $message The message
	 * @param array $tokens Array of device tokens
	 * @return array array[0]: response code, array[1]: response body
	 */
	private static function performCall($serverKey, Message $message, array $tokens) {
		// open connection
		$ch = curl_init();

		// setup connection
		curl_setopt($ch, CURLOPT_URL, Client::$FCM_SEND_ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// avoid problems with https certification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// request header
		$headers = array(
			"Authorization: key={$serverKey}",
			"Content-Type: application/json"
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// request body
		$messageBody = $message->fields;
        $messageBody["registration_ids"] = $tokens;

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageBody));

        // execute call
        $httpResponse[1] = curl_exec($ch);
		$httpResponse[0] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// close connection
		curl_close($ch);

		return $httpResponse;
	}

}
