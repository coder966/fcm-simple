# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [3.3.0] - 2018-08-29

### Added
- Response#isSuccessful()

### Changed
- Deprecate Client#setTokens(tokens: array)
- Improve Wiki and README examples

### Fixed
- Client may just fail silently if a message is larger than what FCM expect. It now throws an exception.
- Client may just fail silently if tokens array exceeds FCM limit. It now throws an exception.

## [3.2.1] - 2018-05-19

### Fixed
- Header content-length was hard-coded to 0

## [3.2.0] - 2018-05-17

### Changed
- Raise minimum required version of PHP to 5.6.0.
- Deprecate Message#add. Use Message#put instead.
- Minor improvements.

### Fixed
- Not being able to use default tokens
- Tests fail due to PHP notice

## [3.1.0] - 2017-08-17

### Added
- Support more message params:
    - setCollapseKey
    - setPriority
    - setTimeToLive
    - setDryRun
    - setRestrictedPackageName
- Add Wiki

## [3.0.0] - 2017-06-12

### Added
- Real server key validation
- Add tests using PHPUnit

### Changed
- Upgrade to PHP 5.4
- Completely new API. Not backward-compatible
- Introducing namespaces
- Throw exceptions rather than showing errors

## [2.0.1] - 2016-11-26

### Fixed
- Fix typos

## [2.0.0] - 2016-11-12
WARNNING: The license has been changed to a less-restrictive license, Apache-2.0

### Added
- getBadTokens() which returns an array of the tokens you should remove from your database

### Changed
- getUpdatedTokens() now returns an array of only the updated tokens in the format: {'old'=>oldToken, 'new'=>newToken}

## [1.2.1] - 2016-07-20

### Fixed
- Fix in Composer support

## [1.2] - 2016-07-20

### Added
- Support for installation through Composer

## [1.1] - 2016-07-10

### Added
- Support for sending array instead of plain string

### Changed
- Rename setDevices() to setTokens()
- Rename fixDevices() to getUpdatedTokens()

## [1.0] - 2016-06-17
Initial release
