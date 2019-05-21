# Wiki
A brief wiki explaining library's API.

## Overview
You first will need to create a `Client` that is responsible for sending `Message`s. You can send as many `Message`s as you need using the same `Client` object. `Message` objects can also be reused. When a `Message` is sent, a `Response` is returned.

## Client
The `Client` is responsible for sending `Message`s and returning `Response`s. A valid FCM server-key is needed. If the provided key is invalid an exception will be thrown.

## Message
A `Message` is much like a `map`. You can put pairs of data (keys and corresponding values) via `Message#put` method. There are also some options you can set on your `Message` using the following methods:

* `Message#setCollapseKey`
* `Message#setPriority`
* `Message#setTimeToLive`
* `Message#setRestrictedPackageName`
* `Message#setDryRun`

You may find more information about these options at https://firebase.google.com/docs/cloud-messaging/http-server-ref

## Response
A `Response` is returned after sending a `Message`. A `Response` exposes these public methods:

* `Response#isSuccessful`: To indicate whether the request was successfully understood and executed by FCM server. This is not about devices actually receiving the message.
* `Response#getInvalidTokens`: Returns an array of invalid tokens. You should delete these from your server database.
* `Response#getUpdatedTokens`: Returns an array of the updated tokens. You should update old tokens with the new ones for future requests; otherwise, the messages might be rejected.

Example:
```php
// send the message and receive the response
$response = $client->sendToTokens($msg, $tokens);

if($response->isSuccessful()){
	// an array of the tokens that are not valid anymore, you should remove these from your DB
	$invalidTokens = $response->getInvalidTokens();
	foreach ($invalidTokens as $token) {
		// remove $token from your DB
	}

	// an array of the tokens that have got updated, you should update these in your DB
	$updatedTokens = $response->getUpdatedTokens();
	foreach ($updatedTokens as $token) {
		// update $token["old"] with $token["new"] in your DB
	}
}else{
	// pushing message failed
}

```
