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
     * @param string $id
     *
     * @return User|ResponseInterface
     */
    public function getUserById($id)
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Id can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/%s', $id));

        return $this->handleResponse($response, User::class);
    }

    /**
     * Returns a collection of users matching the given IDs.
     *
     * @param array $ids
     *
     * @return UsersCollection|ResponseInterface
     */
    public function getUsersByIds(array $ids)
    {
        if (empty($ids)) {
            throw new InvalidArgumentException('IDs can not be empty');
        }

        $response = $this->httpPost('/users/ids', $ids);

        return $this->handleResponse($response, UsersCollection::class);
    }

    /**
     * Returns a user given its email.
     *
     * @param string $email
     *
     * @return User|ResponseInterface
     */
    public function getUserByEmail($email)
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
     * @param array $usernames
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1usernames%2Fpost
     *
     * @return UsersCollection|ResponseInterface
     */
    public function getUsersByUsernames(array $usernames)
    {
        $response = $this->httpPost('/users/usernames',
            $usernames
        );

        return $this->handleResponse($response, UsersCollection::class);
    }

    /**
     * Deactivate a user.
     *
     * @param string $id The user ID
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1%7Buser_id%7D%2Fdelete
     *
     * @return Status|ResponseInterface
     */
    public function deactivateUser($id)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('User ID can not be empty');
        }

        $response = $this->httpDelete(sprintf('/users/%s', $id));

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
        $response = $this->httpPost('/users',
            $params
        );

        return $this->handleResponse($response, User::class);
    }
}
