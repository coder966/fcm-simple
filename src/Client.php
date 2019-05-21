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
     * Constructor
     * @param string $serverKey FCM server key
     */
    public function __construct($serverKey) {
        $this->serverKey = $serverKey;

        // preform a call just to validate server key
        $httpResponse = Client::performCall($serverKey, new Message(), []);
        if ($httpResponse[0] == 401) {
            throw new \InvalidArgumentException("Invalid FCM server key.");
        }
    }

    /**
     * Send a message to a specific topic.
     * @param \FCMSimple\Message $message Message object
     * @param string $topic The name of the topic.
     * @return \FCMSimple\Response A response object regarding the send operation.
     */
    public function sendToTopic(Message $message, $topic) {
        if ($message == null) {
            throw new \InvalidArgumentException("The message cannot be null.");
        }

        if ($topic == null) {
            throw new \InvalidArgumentException("The topic cannot be null.");
        }else if(strlen($topic) == 0){
            throw new \InvalidArgumentException("The topic cannot be empty.");
        }

        $httpResponse = Client::performCall($this->serverKey, $message, $topic);
        return new Response($httpResponse[0], $httpResponse[1], null);
    }

    /**
     * Send a message to the specified tokens.
     * @param \FCMSimple\Message $message Message object
     * @param array $tokens Array of the tokens of the devices to send to.
     * @return \FCMSimple\Response A response object regarding the send operation.
     */
    public function sendToTokens(Message $message, array $tokens) {
        if ($message == null) {
            throw new \InvalidArgumentException("The message cannot be null.");
        }

        if ($tokens == null) {
            throw new \InvalidArgumentException("Tokens array cannot be null.");
        }else if(count($tokens) == 0){
            throw new \InvalidArgumentException("Tokens array cannot be empty.");
        }

        // chunk tokens array to avoid arrays exceeding 1000 which is the limit defined by FCM
        $isSuccessful = true;
        $results = [];
        $chunks = array_chunk($tokens, 1000, false);
        foreach($chunks as $chunk){
            $httpResponse = Client::performCall($this->serverKey, $message, $chunk);
            $isSuccessful = $isSuccessful && ($httpResponse[0] == 200);
            $results = array_merge($results, json_decode($httpResponse[1], true)["results"]);
        }

        return new Response($isSuccessful ? 200 : 500, json_encode(["results"=>$results]), $tokens);
    }

    /**
     * A utility function to execute post calls to FCM's send endpoint.
     *
     * @param string $serverKey FCM server key
     * @param \FCMSimple\Message $message The message
     * @param mixed $target A topic name or an array of device tokens
     * @return array array[0]: response code, array[1]: response body
     */
    private static function performCall($serverKey, Message $message, $target) {
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
        $headers = [
            "Authorization: key={$serverKey}",
            "Content-Type: application/json"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // request body
        $messageBody = $message->fields;
        if(is_array($target)){
            $messageBody["registration_ids"] = $target;
        }else{
            $messageBody["to"] = "/topics/$target";
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageBody));

        // execute call
        $httpResponse[1] = curl_exec($ch);
        $httpResponse[0] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // close connection
        curl_close($ch);

        return $httpResponse;
    }

}
