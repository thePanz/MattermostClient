# Mattermost API Client

[![Latest Version](https://img.shields.io/github/release/thePanz/MattermostClient.svg)](https://github.com/thePanz/MattermostClient/releases)
[![Build Status](https://img.shields.io/travis/thePanz/MattermostClient.svg)](https://travis-ci.org/thePanz/MattermostClient)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/thePanz/MattermostClient.svg)](https://scrutinizer-ci.com/g/thePanz/MattermostClient)
[![Quality Score](https://img.shields.io/scrutinizer/g/thePanz/MattermostClient.svg)](https://scrutinizer-ci.com/g/thePanz/MattermostClient)
[![Total Downloads](https://img.shields.io/packagist/dt/pnz/mattermost-client.svg)](https://packagist.org/packages/pnz/mattermost-client)

A PHP library providing a client for the REST API v4 of [Mattermost](https://www.mattermost.org).

This library allows developers to use Mattermost data as objects via a set of specific Models.
Data related to Team, Channel, User, Posts and so on are converted to model objects to be easily used
and manipulated. Error responses from the Mattermost API are also handled as specific domain exceptions.

Your IDE will be able to auto-complete and suggest model properties, thus lowering the
barrier to start using the Mattermost APIs without reading the extensive API documentation.

Following the example of [Friends of Api](https://github.com/FriendsOfApi/boilerplate) this library allows
developers to use and extend the `Hydrators` used to parse the API responses.
Those are responsible to transform the JSON returned by the API into Models (by default) or into other
response types.

Model `builders` are included to facilitate the creation/update of models via the API. 

Refer to the [Changelog](https://github.com/thePanz/MattermostClient/blob/master/changelog.md) for the list of
changes.
The list of supported APIs endpoints are available in this [Google Spreadsheet](https://docs.google.com/spreadsheets/d/1mLH2aYC8mMv8sLf_mZWxW8H-67juDYJ9M8dCxwWXdf4/edit?usp=sharing) document.

## Installation

**TL;DR**
```bash
composer require php-http/curl-client nyholm/psr7 php-http/message pnz/mattermost-client
```

This library does not have a dependency on Guzzle or any other library that sends HTTP requests. We use the awesome 
HTTPlug to achieve the decoupling. We want you to choose what library to use for sending HTTP requests. Consult this list 
of packages that support [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) 
find clients to use. For more information about virtual packages please refer to 
[HTTPlug](http://docs.php-http.org/en/latest/httplug/users.html). Example:

```bash
composer require php-http/curl-client
```

You do also need to install a PSR-7 implementation and a factory to create PSR-7 messages (PSR-17 whenever that is 
released). You could use Nyholm PSR-7 implementation and factories from php-http:

```bash
composer require nyholm/psr7 php-http/message
```

Now you may install the library by running the following:

```bash
composer require pnz/mattermost-client
```

## Usage example

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

### Handling Mattermost entities

Specific Model Builders are available to help the creation of Mattermost entities.

As an example, to create a Team use a `TeamBuilder()` instance, add the desired fields and call `build()`
to obtain the data needed to invoke the `createTeam()` API.

Create a Team:
``` php
use Pnz\MattermostClient\Model\Team;

$teamData = (new Team\TeamBuilder())
    ->setDisplayName('Team 01')
    ->setName('team-01')
    ->setType(Team\Team::TEAM_INVITE_ONLY)
    ->build();

$team = $apiClient->teams()->createTeam($teamData);
```

The model builders can also be used to generate the data required to update or to patch a Mattermost entity.

Patch a Post:
``` php
<?php
use Pnz\MattermostClient\Model\Post;

$post = $apiClient->posts()->getPost('zhcapisftibyjnf54gixg3hdew');
$postData = (new Post\PostBuilder())
    ->setMessage('I can `format` the _text_ of a *message*, including [links](www.mattermost.com)')
    ->setIsPinned(true)
    ->build(Post\PostBuilder::BUILD_FOR_PATCH);

$post = $apiClient->posts()->patchPost($post->getId(), $postData);
```
