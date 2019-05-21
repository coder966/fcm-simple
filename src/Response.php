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
     * A flag to know by which method this object is created
     * @var bool
     */
    private $tokensUsed;

    /**
     * The "results" array
     * Used only whit Client#sendToTokens
     * @var array
     */
    private $results;

    /**
     * Array of the tokens sent along with the request
     * Used only whit Client#sendToTokens
     * @var array
     */
    private $tokens;

    /**
     * Create the response object to deal with it.
     *
     * @param int $responseCode Response http code
     * @param string $responseBody Response body
     * @param array $tokens [Optional] The tokens sent along with the request
     */
    public function __construct($responseCode, $responseBody, array $tokens = null) {
        $this->isSuccessful = $responseCode == 200;

        $body = json_decode($responseBody, true);
        if(isset($body["multicast_id"])){
            $this->tokensUsed = true;
            $this->results = $body["results"];
            $this->tokens = $tokens;
        }else{
            $this->tokensUsed = false;
        }
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
     * Returns an array of invalid / no longer valid tokens. You should delete these from your server database.
     * @return array Invalid tokens to be removed
     */
    public function getInvalidTokens() {
        if(!$this->tokensUsed){
            return [];
        }

        $errorTypes = [
            "MissingRegistration",
            "InvalidRegistration",
            "NotRegistered"
        ];

        $invalidTokens = [];
        for ($i = 0; $i < count($this->tokens); $i++) {
            if(isset($this->results[$i]["error"])){
                if (in_array($this->results[$i]["error"], $errorTypes)) {
                    array_push($invalidTokens, $this->tokens[$i]);
                }
            }
        }

        return $invalidTokens;
    }

    /**
     * Returns an array of the updated tokens. You should update old tokens with the new ones for future requests; otherwise, the messages might be rejected.
     *
     * @return array An array where each element is also an array of the format <code>{'old'=>oldToken, 'new'=>newToken}</code>
     */
    public function getUpdatedTokens() {
        if(!$this->tokensUsed){
            return [];
        }

        $updatedTokens = [];
        for ($i = 0; $i < count($this->tokens); $i++) {
            if (isset($this->results[$i]["registration_id"])) {
                array_push($updatedTokens, ["old" => $this->tokens[$i], "new" => $this->results[$i]["registration_id"]]);
            }
        }

        return $updatedTokens;
    }

}
