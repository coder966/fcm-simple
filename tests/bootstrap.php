<?php

$serverKey = getenv("SERVER_KEY");
if($serverKey == false){
    die("\n\nUsage: SERVER_KEY=<a-valid-server-key> vendor/bin/phpunit\n\n");
}

error_reporting(E_ALL);
define("SERVER_KEY", $serverKey);
