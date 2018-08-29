<?php

namespace FCMSimple;

/**
 * PHP class to send simple messages using Firebase Cloud Messaging (FCM)
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <me@coder966.net>
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
		$this->defaultTokens = array();

		// prepare a dry run
		$message = new Message();
		$message->put("", "");
		$message->setDryRun(true);
		$tokens = array("");

		// preform a call
		$response = Client::performCall($serverKey, $message, $tokens);

		// check server key validity
		if ($response->isSuccessful()) {
			$this->serverKey = $serverKey;
		} else {
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

		// choose which array
		if ($tokens != null) {
			$recipientTokens = $tokens;
		} else {
			$recipientTokens = $this->defaultTokens;
		}

		// check if empty
		if(count($recipientTokens) == 0){
			throw new \InvalidArgumentException("Tokens array cannot be empty.");
		}

		return Client::performCall($this->serverKey, $message, $recipientTokens);
	}

	/**
	 * A utility function to execute post calls to FCM's send endpoint.
	 *
	 * @param string $serverKey FCM server key
	 * @param Message $message The message
	 * @param array $tokens Array of device tokens
	 * @return Response The resulting response
	 */
	private static function performCall($serverKey, Message $message, array $tokens) {
		// check arguments
		if($serverKey == null || !is_string($serverKey)){
			throw new \InvalidArgumentException("Invalid FCM server key.");
		}
		
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
		$responseBody = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// close connection
		curl_close($ch);

		return new Response($responseCode, $responseBody, $tokens);
	}

}
