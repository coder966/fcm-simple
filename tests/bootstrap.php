<?php

$serverKey = getenv("SERVER_KEY");
if($serverKey == false){
    die("\n\nUsage: SERVER_KEY=<a-valid-server-key> phpunit\n\n");
}else{
    define("SERVER_KEY", $serverKey);
}
