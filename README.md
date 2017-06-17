FCMSimple
===
[![Latest Stable Version](https://poser.pugx.org/coder966/fcm-simple/v/stable)](https://packagist.org/packages/coder966/fcm-simple)
[![License](https://poser.pugx.org/coder966/fcm-simple/license)](https://packagist.org/packages/coder966/fcm-simple)

A simple PHP library to send messages to devices registered through Firebase Cloud Messaging (FCM).
Features:
- Lightweight & simple
- Zero dependencies
- Provides you with the tokens you should (remove/update) from your database.
- Implemented using cURL


Usage
---
For further details, see the [Wiki](https://github.com/coder966/FCMSimple/blob/master/Wiki.md "Wiki")
##### Installation:
Run the following command in your project-root directory
```
$ composer require coder966/fcm-simple
```

##### PHP-Server:
```php
require 'vendor/autoload.php';

use FCMSimple\Client;
use FCMSimple\Message;
use FCMSimple\Response;

// prepare your array of tokens
$tokens = array(
    "token_1",
    "token_2",
    "token_3"
);

// create the client
$client = new Client("YOUR_SERVER_KEY");

// create the message
$msg = new Message();
$msg->add("key1", "value1");
$msg->add("key2", "value2");

// send the message and receive the response
$response = $client->send($msg, $tokens);

// an array of the tokens that are not valid anymore, you should remove them from your DB
$badTokens = $response->getBadTokens();
foreach ($badTokens as $token) {
    // remove $token from your DB
}

// an array of the tokens that have got updated, you should update them in your DB
$updatedTokens = $response->getUpdatedTokens();
foreach ($updatedTokens as $token) {
    // update $token["old"] with $token["new"] in your DB
}
```

##### Android-Client:
In the service that extends `FirebaseMessagingService`, in method `onMessageReceived`, use:
```java
Map<String, String> msg = remoteMessage.getData();
String value1 = msg.get("key1");
String value2 = msg.get("key2");
String value3 = msg.get("key3");
```


License
---
```
Copyright 2016 Khalid H. Alharisi

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
