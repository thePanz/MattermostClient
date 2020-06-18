# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/thePanz/MattermostClient/compare/0.1.0...master)
### Added
- Add `/users/{user_id}/image` creation and deletion (UsersApi::updateProfileImage(), UsersApi::deleteProfileImage())
- Expose User's `delete_at` property
- Enable new scrutinizer analysis
- Run PHPStan level=7 for code checks
### Changed
- Allow string|resource|StreamInterface for file uploads (FilesApi::sendFile, UsersApi::updateProfileImage())
- Refactored Authentication process
- Requirements for HttpPlug 2.x
### Deprecated
### Removed
### Fixed
- Fix typo in readme
- Fix issue with php-http/common v1.8
### Security

## v[0.1.0](https://github.com/thePanz/MattermostClient/releases/tag/0.1.0)
### Added
- Added `/teams/{team_id}/members/{user_id}` API endpoint (TeamsApi::getTeamMember())
- Added `/users/{user_id}/teams` API endpoint (UsersApi::getUserTeams())

### Changed
- Refactored Exceptions structure and inheritances

## v[0.0.1](https://github.com/thePanz/MattermostClient/releases/tag/0.0.1)

First release of MattermostClient for PHP

### Added
- Added Users API endpoint
- Added Teams API endpoint
- Added Channels API endpoint
- Added Files API endpoint
