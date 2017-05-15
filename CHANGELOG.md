Changelog
===

##### v2.0.1 (26-11-2016):
* Fix typos

##### v2.0.0 (12-11-2016):
* WARNNING: The license has been changed to a less-restrictive license, Apache-2.0
* WARNNING: getUpdatedTokens() is totally different now. It returns an array of only the updated tokens in the format: {'old'=>oldToken, 'new'=>newToken}.
* Add: getBadTokens() which returns an array of the tokens you should remove from your database.

##### v1.2.1 (20-7-2016):
* Fix in Composer support

##### v1.2 (20-7-2016):
* Support Composer

##### v1.1 (10-7-2016):
* Support sending array instead of plain string
* Rename setDevices() to setTokens()
* Rename fixDevices() to getUpdatedTokens()

##### v1.0 (17-6-2016):
* Initial release
