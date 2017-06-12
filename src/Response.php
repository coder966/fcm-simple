<?php

namespace FCMSimple;

/**
 * Response class that holds the response information.
 * You can benefit from this by being notified about the invalid tokens you used
 * and the outdated ones as well.
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class Response {

	/**
	 * JSON decoded response results
	 * @var array
	 */
	private $responseResults;

	/**
	 * Array of the tokens sent along with the request
	 * @var array
	 */
	private $tokens;

	/**
	 * Create the response object to deal with it.
	 *
	 * @param string $response JSON encoded response
	 * @param array $tokens The tokens sent along with the request
	 */
	public function __construct($response, array $tokens) {
		$this->responseResults = json_decode($response, true)["results"];
		$this->tokens = $tokens;
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
		$errorTypes = array(
			"MissingRegistration",
			"InvalidRegistration",
			"NotRegistered"
		);

		$badTokens = array();
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (in_array($this->responseResults[$i]["error"], $errorTypes)) {
				array_push($badTokens, $this->tokens[$i]);
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
		$updatedTokens = array();
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (isset($this->responseResults[$i]["registration_id"])) {
				array_push($updatedTokens, array("old" => $this->tokens[$i], "new" => $this->responseResults[$i]["registration_id"]));
			}
		}

		return $updatedTokens;
	}

}
