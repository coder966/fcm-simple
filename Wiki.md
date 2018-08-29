# Wiki
A brief wiki explaining the library's API.

## Overview
You first will need to create a `Client` that can send `Message`s. You can send as many `Message`s as you need using the same `Client` object. When you send a `Message`, a `Response` object will return from the `Client#send` method. You may store the `Response` in a variable so that you can access its useful methods. 

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
A `Response` is returned after sending a `Message`. A `Response` have these methods:

* `Response#getBadTokens`: Returns an array of bad tokens. You should delete these from your server database.
* `Response#getUpdatedTokens`: Returns an array of the updated tokens. You should update old tokens with the new ones for future requests; otherwise, the messages might be rejected.




