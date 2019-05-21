<?php

namespace FCMSimple;

/**
 * Provides information about the response from FCM server.
 * You can get the invalid tokens you used and the outdated ones as well. Also you can check whether the send was successful or not.
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class Response {

	/**
	 * HTTP response code == 200 ?
	 * @var bool
	 */
	private $isSuccessful;

	/**
	 * The "results" array
	 * @var array
	 */
	private $results;

	/**
	 * Array of the tokens sent along with the request
	 * @var array
	 */
	private $tokens;

	/**
	 * Create the response object to deal with it.
	 *
	 * @param int $responseCode Response http code
	 * @param string $responseBody Response body
	 * @param array $tokens The tokens sent along with the request
	 */
	public function __construct($responseCode, $responseBody, array $tokens) {
		$this->isSuccessful = $responseCode == 200;
		$this->results = json_decode($responseBody, true)["results"];
		$this->tokens = $tokens;
	}

	/**
	 * To indicate whether the request was successfully understood and executed by FCM server.
	 * This is not about devices actually receiving the message.
	 *
	 * @return boolean Is successful ?
	 */
	public function isSuccessful(){
		return $this->isSuccessful;
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
		// error types
		$errorTypes = [
			"MissingRegistration",
			"InvalidRegistration",
			"NotRegistered"
        ];

		$badTokens = [];
		for ($i = 0; $i < count($this->tokens); $i++) {
			if(isset($this->results[$i]["error"])){
				if (in_array($this->results[$i]["error"], $errorTypes)) {
					array_push($badTokens, $this->tokens[$i]);
				}
			}
		}

		return $badTokens;
	}

	/**
	 * Returns an array of the updated tokens. You should update old tokens with the new ones for future requests; otherwise, the messages might be rejected.
	 *
	 * @return array An array where each element is also an array of the format <code>{'old'=>oldToken, 'new'=>newToken}</code>
	 */
	public function getUpdatedTokens() {
		$updatedTokens = [];
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (isset($this->results[$i]["registration_id"])) {
				array_push($updatedTokens, ["old" => $this->tokens[$i], "new" => $this->results[$i]["registration_id"]]);
			}
		}

		return $updatedTokens;
	}

}
