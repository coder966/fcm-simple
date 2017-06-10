<?php

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
	private $response;

	/**
	 * Constructor
	 * @param string $serverKey FCM server key
	 */
	public function __construct($serverKey) {
		$this->serverKey = $serverKey;
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
		// check required data
		if (strlen($this->serverKey) < 20) {
			$this->error("FCM server key is not set. Please pass it through the constructor.");
		}
		$tempTtokens;
		if (is_array($tokens) && count($tokens) > 0) {
			$tempTtokens = $tokens;
		} else if (is_array($this->tokens) && count($this->tokens) > 0) {
			$tempTtokens = $this->tokens;
		} else {
			$this->error("Tokens not set. Pass them through FCMSimple::send()'s second argument or through FCMSimple::setTokens.");
		}

		// prepare message
		$fields = array(
			"registration_ids" => $tempTtokens,
			"data" => $messageData,
		);
		$headers = array(
			"Authorization: key=" . $this->serverKey,
			"Content-Type: application/json"
		);

		// Open connection
		$ch = curl_init();

		// Setup connection
		curl_setopt($ch, CURLOPT_URL, $this->FCM_SEND_ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Avoid problem with https certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Execute post
		$this->response = curl_exec($ch);

		// Close connection
		curl_close($ch);

		return $this->response;
	}

	/**
	 * Stop executing the script and show an error message
	 * @param string $message Error message
	 */
	private function error($message) {
		echo "FCM send message failed with error:\t$message";
		exit(1);
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

}
