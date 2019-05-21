# Wiki
A brief wiki explaining library's API.

## Overview
You first will need to create a `Client` that can send `Message`s. You can send as many `Message`s as you need using the same `Client` object. `Message` objects can also be reused.

When you send a `Message`, a `Response` object will return from the `Client#send` method. You may, but you don't have to, store the `Response` in a variable so that you can access its useful methods.

## Client
The `Client` is responsible for sending `Message`s and returning `Response`s. When you send a `Message`, tokens must be set either by `Client#setTokens` (DEPRECATED) or `Client#send`'s second argument. To create a `Client` object, you need to pass your valid FCM server key via the constructor; otherwise, an exception will be thrown.

## Message
You can put pairs of data (keys and corresponding values) via `Message#put` method. There are also some options you can set on your `Message` using the following methods:

* `Message#setCollapseKey`
* `Message#setPriority`
* `Message#setTimeToLive`
* `Message#setRestrictedPackageName`
* `Message#setDryRun`

You may find more information about these options in methods' PHPDocs or in https://firebase.google.com/docs/cloud-messaging/http-server-ref

## Response
A `Response` is returned after sending a `Message`. A `Response` have these public methods:

* `Response#isSuccessful`: To indicate whether the request was successfully understood and executed by FCM server. This is not about devices actually receiving the message.
* `Response#getBadTokens`: Returns an array of bad tokens. You should delete these from your server database.
* `Response#getUpdatedTokens`: Returns an array of the updated tokens. You should update old tokens with the new ones for future requests; otherwise, the messages might be rejected.

Example:
```php
// don't forget to add
use FCMSimple\Response;

// send the message and receive the response
$response = $client->send($msg, $tokens);

if($response->isSuccessful()){
	// an array of the tokens that are not valid anymore, you should remove these from your DB
	$badTokens = $response->getBadTokens();
	foreach ($badTokens as $token) {
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
