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
	public $response;

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
	 * @param array $messageData Message array containing pairs of keys and values
	 * @param array $tokens [optional] Array of the tokens of the devices to send to.
	 * Can be null and therefore the array passed through {@link FCMSimple::setTokens($tokens)} will be used.
	 * @return string JSON encoded response
	 */
	public function send(array $messageData, array $tokens = null) {
		// prepare the tokens
		if (is_array($tokens) && count($tokens) > 0) {
			$tempTokens = $tokens;
		} else if (is_array($this->tokens) && count($this->tokens) > 0) {
			$tempTokens = $this->tokens;
		} else {
			throw new \RuntimeException("Tokens not set. Pass them through FCMSimple::send()'s second argument or through FCMSimple::setTokens.");
		}

		$this->response = $this::_send($this->serverKey, $tempTokens, $messageData);

		return $this->response;
	}

	/**
	 * Returns an array of bad tokens. You should delete these from your server database.
	 * This method can handle these errors:
	 * <li>1- MissingRegistration : Empty device token</li>
	 * <li>2- InvalidRegistration : Not a device token</li>
	 * <li>3- NotRegistered : The device has uninstalled the application</li>
	 *
	 * @return array Bad tokens to be removed
	 */
	public function getBadTokens() {
		$response = json_decode($this->response, true)["results"];

		$badTokens = array();
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (isset($response[$i]["error"]) and (
					($response[$i]["error"] == "MissingRegistration") or ( $response[$i]["error"] == "InvalidRegistration") or ( $response[$i]["error"] == "NotRegistered"))) {

				array_push($badTokens, $this->tokens[$i]);
			}
		}

		return $badTokens;
	}

	/**
	 * Returns an array of the updated tokens. You should update old tokens with the new ones.
	 *
	 * @return array An array of format {'old'=>oldToken, 'new'=>newToken}
	 */
	public function getUpdatedTokens() {
		$response = json_decode($this->response, true)["results"];

		$updatedTokens = array();
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (isset($response[$i]["registration_id"])) {
				array_push($updatedTokens, array("old" => $this->tokens[$i], "new" => $response[$i]["registration_id"]));
			}
		}

		return $updatedTokens;
	}

	/**
	 * A utility function to execute post calls to the send endpoint.
	 * Pass null for (or just skip) $tokens and $message if you want to check the server key,
	 * the function will return a boolean indicating whether the passed server key
	 * is valid or not.
	 *
	 * @param string $serverKey FCM server key
	 * @param array [optional] $tokens Array of device tokens
	 * @param array [optional] $message The message
	 * @return array The response
	 */
	private static function _send($serverKey, array $tokens = null, array $message = null) {
		// prepare the headers
		$headers = array(
			"Authorization: key={$serverKey}",
			"Content-Type: application/json"
		);

		// prepare post fields
		$postFields = array(
			"registration_ids" => $tokens,
			"data" => $message,
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

		return $response;
	}

}
