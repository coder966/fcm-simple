Changelog
===
##### v3.2.0 (17-05-2018):
* Raise minimum required version of PHP to 5.6.0.
* Deprecate Message#add. Use Message#put instead.
* Fix: Not being able to use default tokens.
* Minor some tests fail due to PHP notice.
* Require PHPUnit using composer.
* Remove Netbeans files.
* Minor improvements.

##### v3.1.0 (17-08-2017):
* Support more message params (CollapseKey, Priority, TimeToLive, DryRun, RestrictedPackageName)
* Add Wiki

##### v3.0.0 (12-06-2017):
* Upgrade to PHP 5.4
* Completely new API. Not backward-compatible.
* Start using namespace "FCMSimple".
* Three new classes (Client, Message, Response). Removing the old single class.
* Real server key validation.
* Add tests using PHPUnit.
* Throw exceptions rather than showing errors.
* Use NetBeans IDE.

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
