# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](#unreleased)
### Added
- Expose User's delete_at property
- Enable new scrutinizer analysis
### Changed
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
- Added /teams/{team_id}/members/{user_id} API endpoint (TeamsApi::getTeamMember())
- Added /users/{user_id}/teams API endpoint (UsersApi::getUserTeams())

### Changed
- Refactored Exceptions structure and inheritances

## v[0.0.1](https://github.com/thePanz/MattermostClient/releases/tag/0.0.1)

First release of MattermostClient for PHP

### Added
- Added Users API endpoint
- Added Teams API endpoint
- Added Channels API endpoint
- Added Files API endpoint
