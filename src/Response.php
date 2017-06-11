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
	 * JSON decoded response
	 * @var array
	 */
	private $response;

	/**
	 * Array of the tokens sent along with the request
	 * @var array
	 */
	private $tokens;

	/**
	 *
	 * @param string $response JSON encoded response
	 * @param array $tokens The tokens sent along with the request
	 */
	public function __construct($response, array $tokens) {
		$this->response = json_decode($response, true);
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
		$response = $response["results"];

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
		$response = $response["results"];

		$updatedTokens = array();
		for ($i = 0; $i < count($this->tokens); $i++) {
			if (isset($response[$i]["registration_id"])) {
				array_push($updatedTokens, array("old" => $this->tokens[$i], "new" => $response[$i]["registration_id"]));
			}
		}

		return $updatedTokens;
	}

}
