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

	private $serverKey = "";
	private $tokens = array();
	private $response;


	/**
	 * Constructor
	 * @param [string] $serverKey The server key
	 */
	public function __construct($serverKey){
		$this->serverKey = $serverKey;
	}

	/**
	 * Set the tokens to send to
	 * @param [array] $deviceTokens Array of device tokens to send to
	 */
	public function setTokens(array $deviceTokens){
		$this->tokens = $deviceTokens;
	}

	/**
	 * Send message to the tokens
	 * @param  [array]  $messageData Array contains keys and values
	 * @return [json]      	         The response from server
	 */
	public function send(array $messageData){
		// check required data
		if(strlen($this->serverKey) < 20){
			$this->error("Server Key not set");
		}
		if(!is_array($this->tokens) || count($this->tokens) == 0){
			$this->error("No tokens set");
		}

		// prepare message
		$fields = array(
			"registration_ids"  => $this->tokens,
			"data"              => $messageData,
		);
		$headers = array(
			"Authorization: key=".$this->serverKey,
			"Content-Type: application/json"
		);

		// Open connection
		$ch = curl_init();

		// Setup connection
		curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
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
	 * @param  [string] $message Error message
	 */
	private function error($message){
		echo "Android send notification failed with error:\t$message";
		exit(1);
	}

	/**
	 * Returns an array of bad registration ids. You should delete these from your server.
	 * This method can handle these errors:
	 * 		1- MissingRegistration : Empty registration id
	 * 		2- InvalidRegistration : Not even a a registration id, random string
	 * 		3- NotRegistered       : The device has uninstalled the app
	 *
	 * @return [array] Bad tokens to be removed
	 */
	public function getBadTokens(){
		$response = json_decode($this->response, true)["results"];

		$badTokens = array();
		for($i=0; $i<count($this->tokens); $i++){
			if(isset($response[$i]["error"]) and (
					($response[$i]["error"] == "MissingRegistration") or
					($response[$i]["error"] == "InvalidRegistration") or
					($response[$i]["error"] == "NotRegistered"))){

				array_push($badTokens, $this->tokens[$i]);
			}
		}

		return $badTokens;
	}

	/**
	 * Returns an array of the updated registration ids. You should update old tokens with the new ones
	 *
	 * @return [array] An array of format {'old'=>oldToken, 'new'=>newToken}
	 */
	public function getUpdatedTokens(){
		$response = json_decode($this->response, true)["results"];

		$updatedTokens = array();
		for($i=0; $i<count($this->tokens); $i++){
			if(isset($response[$i]["registration_id"])){
				array_push($updatedTokens, array("old" => $this->tokens[$i], "new" => $response[$i]["registration_id"]));
			}
		}

		return $updatedTokens;
	}


}
