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
class FCMSimple {

	private static $FCM_SEND_ENDPOINT = "https://fcm.googleapis.com/fcm/send";
	private $serverKey;
	private $tokens;

	/**
	 * Constructor
	 * @param string $serverKey FCM server key
	 */
	public function __construct($serverKey) {
		$valid = FCMSimple::_send($serverKey);
		if ($valid) {
			$this->serverKey = $serverKey;
		}else{
			throw new \InvalidArgumentException("Invalid FCM server key.");
		}
	}

	/**
	 * Set the default tokens which will be used when no tokens are passed when calling {@link FCMSimple::send()}
	 * @param array $tokens Array of device tokens to send to
	 */
	public function setTokens(array $tokens) {
		$this->tokens = $tokens;
	}

	/**
	 * Send message to the tokens
	 * @param Message $message Message object
	 * @param array $tokens [optional] Array of the tokens of the devices to send to.
	 * Can be null and therefore the array passed through {@link FCMSimple::setTokens($tokens)} will be used.
	 * @return Response A response object regarding the send operation.
	 */
	public function send(Message $message, array $tokens = null) {
		// prepare the tokens
		if (is_array($tokens) && count($tokens) > 0) {
			$tempTokens = $tokens;
		} else if (is_array($this->tokens) && count($this->tokens) > 0) {
			$tempTokens = $this->tokens;
		} else {
			throw new \RuntimeException("Tokens not set. Pass them through FCMSimple::send()'s second argument or through FCMSimple::setTokens.");
		}

		return $this::_send($this->serverKey, $tempTokens, $message);
	}

	/**
	 * A utility function to execute post calls to the send endpoint.
	 * Pass null for (or just skip) $tokens and $message if you want to check the server key,
	 * the function will return a boolean indicating whether the passed server key
	 * is valid or not.
	 *
	 * @param string $serverKey FCM server key
	 * @param array [optional] $tokens Array of device tokens
	 * @param Message [optional] $message The message
	 * @return mixed The response if all arguments are passed, boolean otherwise
	 */
	private static function _send($serverKey, array $tokens = null, Message $message = null) {
		// prepare the headers
		$headers = array(
			"Authorization: key={$serverKey}",
			"Content-Type: application/json"
		);

		// prepare post fields
		$postFields = array(
			"registration_ids" => $tokens,
			"data" => $message->map,
		);

		// open connection
		$ch = curl_init();

		// setup connection
		curl_setopt($ch, CURLOPT_URL, FCMSimple::$FCM_SEND_ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

		// avoid problems with https certification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// execute post
		$response = curl_exec($ch);

		// check server key
		if($tokens == null && $message == null){
			curl_setopt($ch, CURLOPT_NOBODY  , true);  // we don't need body
			$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($responseCode == 401){
				return false;
			}else{
				return true;
			}
		}

		// close connection
		curl_close($ch);

		return new Response($response, $tokens);
	}

}
