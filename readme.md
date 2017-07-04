# Mattermost API Client

[![Latest Version](https://img.shields.io/github/release/thePanz/MattermostClient.svg)](https://github.com/thePanz/MattermostClient/releases)
[![Build Status](https://img.shields.io/travis/thePanz/MattermostClient.svg)](https://travis-ci.org/thePanz/MattermostClient)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/thePanz/MattermostClient.svg)](https://scrutinizer-ci.com/g/thePanz/MattermostClient)
[![Quality Score](https://img.shields.io/scrutinizer/g/thePanz/MattermostClient.svg)](https://scrutinizer-ci.com/g/thePanz/MattermostClient)
[![Total Downloads](https://img.shields.io/packagist/dt/pnz/mattermost-client.svg)](https://packagist.org/packages/pnz/mattermost-client)

A PHP library providing a client for the REST API v4 of [Mattermost](https://www.mattermost.org).

*NOTE*: The implementation is still in progress (as today: 2017-06-23), feel free to add or
    extend the current implementation.
    To track the available API endpoints, visit this [Google Spreadsheet](https://docs.google.com/spreadsheets/d/1mLH2aYC8mMv8sLf_mZWxW8H-67juDYJ9M8dCxwWXdf4/edit?usp=sharing) document.

## Installation

**TL;DR**
```bash
composer require php-http/curl-client guzzlehttp/psr7 php-http/message pnz/mattermost-client
```

This library does not have a dependency on Guzzle or any other library that sends HTTP requests. We use the awesome 
HTTPlug to achieve the decoupling. We want you to choose what library to use for sending HTTP requests. Consult this list 
of packages that support [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) 
find clients to use. For more information about virtual packages please refer to 
[HTTPlug](http://docs.php-http.org/en/latest/httplug/users.html). Example:

```bash
composer require php-http/guzzle6-adapter
```

You do also need to install a PSR-7 implementation and a factory to create PSR-7 messages (PSR-17 whenever that is 
released). You could use Guzzles PSR-7 implementation and factories from php-http:

```bash
composer require guzzlehttp/psr7 php-http/message 
```

Now you may install the library by running the following:

```bash
composer require pnz/mattermost-client
```

## Using

``` php
<?php

require_once 'vendor/autoload.php';

$endpoint = 'http://mattermostserver.ext/api/v4';
$username = 'username';
$password = 'password';

$configurator = (new HttpClientConfigurator())
    ->setEndpoint($endpoint)
    ->setCredentials($username, $password);
$apiClient =  ApiClient::configure($configurator);

try {
    // Get the currently logged-in User; the "me" ID is a special one, as documented on Mattermost.org APIs.
    $user = $apiClient->users()->getUserById('me');
    var_dump($user->getUsername());

```

### Creating a Team

Specific Model Builders are available to help the entity creation, as an example,
to create a Team use a `TeamBuilder()` instance, add the desired fields and call `build()`
to obtain the data needed to invoke the `createTeam()` API. 


``` php
<?php

require_once 'vendor/autoload.php';

use Pnz\MattermostClient\Model\Team;

$endpoint = 'http://mattermostserver.ext/api/v4';
$username = 'username';
$password = 'password';

$configurator = (new HttpClientConfigurator())
    ->setEndpoint($endpoint)
    ->setCredentials($username, $password);
$apiClient =  ApiClient::configure($configurator);

try {
    $teamData = (new Team\TeamBuilder())
        ->setDisplayName('Team 01')
        ->setName('team-01')
        ->setType(Team\Team::TEAM_INVITE_ONLY)
        ->build();

    $team = $apiClient->teams()->createTeam($teamData);

```
