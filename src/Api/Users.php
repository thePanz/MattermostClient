<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\User\User;
use Pnz\MattermostClient\Model\User\Users as UsersCollection;
use Psr\Http\Message\ResponseInterface;

final class Users extends HttpApi
{
    /**
     * @param string $loginId  The login Id
     * @param string $password The password
     * @param null   $token    The login token, as output variable
     *
     * @return User|ResponseInterface
     */
    public function login($loginId, $password, &$token = null)
    {
        if (empty($loginId) || empty($password)) {
            throw new InvalidArgumentException('LoginId and Password cannot be empty');
        }

        $response = $this->httpPost('/users/login', [
            'password' => $password,
            'login_id' => $loginId,
        ]);

        // Use any valid status code here
        if ($response->getStatusCode() !== 200) {
            $this->handleErrors($response);
        }

        $tokens = $response->getHeader('Token');
        if (count($tokens)) {
            $token = reset($tokens);
        }

        return $this->hydrator->hydrate($response, User::class);
    }

    /**
     * Returns an user by its ID, use "me" to get the current logged in user.
     *
     * @param string $userId
     *
     * @return User|ResponseInterface
     */
    public function getUserById(string $userId)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/%s', $userId));

        return $this->handleResponse($response, User::class);
    }

    /**
     * Returns a collection of users matching the given IDs.
     *
     * @param array $userIds
     *
     * @return UsersCollection|ResponseInterface
     */
    public function getUsersByIds(array $userIds)
    {
        if (empty($userIds)) {
            throw new InvalidArgumentException('UserIDs can not be empty');
        }

        $response = $this->httpPost('/users/ids', $userIds);

        return $this->handleResponse($response, UsersCollection::class);
    }

    /**
     * Returns a collection of users.
     *
     * @param array $params The listing params, 'page', 'per_page', 'in_channel', 'in_team', 'not_in_channel'
     *
     * @return UsersCollection|ResponseInterface
     */
    public function getUsers(array $params = [])
    {
        $response = $this->httpGet('/users', $params);

        return $this->handleResponse($response, UsersCollection::class);
    }

    /**
     * Returns a user given its email.
     *
     * @param string $email
     *
     * @return User|ResponseInterface
     */
    public function getUserByEmail(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/email/%s', $email));

        return $this->handleResponse($response, User::class);
    }

    /**
     * Returns a collection of users matching the given usernames.
     *
     * @param array $userNames
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1usernames%2Fpost
     *
     * @return UsersCollection|ResponseInterface
     */
    public function getUsersByUsernames(array $userNames)
    {
        if (empty($userNames)) {
            throw new InvalidArgumentException('Usernames can not be empty');
        }

        $response = $this->httpPost('/users/usernames', $userNames);

        return $this->handleResponse($response, UsersCollection::class);
    }

    /**
     * Deactivate a user.
     *
     * @param string $userId The user ID
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1%7Buser_id%7D%2Fdelete
     *
     * @return Status|ResponseInterface
     */
    public function deactivateUser(string $userId)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('User ID can not be empty');
        }

        $response = $this->httpDelete(sprintf('/users/%s', $userId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Create a user. Required parameters: 'username', 'email' and 'password'.
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users%2Fpost
     *
     * @param array $params
     *
     * @return User|ResponseInterface
     */
    public function createUser(array $params)
    {
        $response = $this->httpPost('/users', $params);

        return $this->handleResponse($response, User::class);
    }

    /**
     * Patch a user.
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1%7Buser_id%7D~1patch%2Fput
     *
     * @param string $userId
     * @param array  $params
     *
     * @return User|ResponseInterface
     */
    public function patchUser(string $userId, array $params)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpPut(sprintf('/users/%s/patch', $userId), $params);

        return $this->handleResponse($response, User::class);
    }

    /**
     * Update a user, required parameter: 'id'.
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1%7Buser_id%7D%2Fput
     *
     * @param string $userId
     * @param array  $params
     *
     * @return User|ResponseInterface
     */
    public function updateUser(string $userId, array $params)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpPut(sprintf('/users/%s', $userId), $params);

        return $this->handleResponse($response, User::class);
    }

    /**
     * Returns an user by its username, use "me" to get the current logged in user.
     *
     * @param string $username
     *
     * @return User|ResponseInterface
     */
    public function getUserByUsername(string $username)
    {
        if (empty($username)) {
            throw new InvalidArgumentException('Username can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/username/%s', $username));

        return $this->handleResponse($response, User::class);
    }
}
