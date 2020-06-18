<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\User\User;
use Pnz\MattermostClient\Model\User\Users;
use Pnz\MattermostClient\Model\User\UserStatus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class UsersApi extends HttpApi
{
    /**
     * @param string $loginId  The login Id
     * @param string $password The password
     * @param string $token    The login token, as output variable
     *
     * @return User|ResponseInterface
     */
    public function login(string $loginId, string $password, string &$token = null)
    {
        if (empty($loginId) || empty($password)) {
            throw new InvalidArgumentException('LoginId and Password cannot be empty');
        }

        $response = $this->httpPost('/users/login', [
            'login_id' => $loginId,
            'password' => $password,
        ]);

        // Use any valid status code here
        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        $tokens = $response->getHeader('Token');
        if (\count($tokens)) {
            $token = reset($tokens);
        }

        return $this->handleResponse($response, User::class);
    }

    /**
     * Returns an user by its ID, use "me" to get the current logged in user.
     *
     * @param string $userId User GUID
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
     * Get a list of teams that a user is on.
     *
     * @param string $userId User GUID
     *
     * @return User|ResponseInterface
     */
    public function getUserTeams(string $userId)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/%s/teams', $userId));

        return $this->handleResponse($response, Teams::class);
    }

    /**
     * Returns a collection of users matching the given IDs.
     *
     * @param string[] $userIds
     *
     * @return Users|ResponseInterface
     */
    public function getUsersByIds(array $userIds)
    {
        if (empty($userIds)) {
            throw new InvalidArgumentException('UserIDs can not be empty');
        }

        $response = $this->httpPost('/users/ids', $userIds);

        return $this->handleResponse($response, Users::class);
    }

    /**
     * Returns a collection of users.
     *
     * @param array $params The listing params, 'page', 'per_page', 'in_channel', 'in_team', 'not_in_channel'
     *
     * @return Users|ResponseInterface
     */
    public function getUsers(array $params = [])
    {
        $response = $this->httpGet('/users', $params);

        return $this->handleResponse($response, Users::class);
    }

    /**
     * Returns a user given its email.
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
     * @param string[] $userNames
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1usernames%2Fpost
     *
     * @return Users|ResponseInterface
     */
    public function getUsersByUsernames(array $userNames)
    {
        if (empty($userNames)) {
            throw new InvalidArgumentException('Usernames can not be empty');
        }

        $response = $this->httpPost('/users/usernames', $userNames);

        return $this->handleResponse($response, Users::class);
    }

    /**
     * Update user active or inactive status.
     *
     * @param string $userId       The user ID
     * @param bool   $activeStatus Use `true` to set the user active, `false` for inactive
     *
     * @return Status|ResponseInterface
     */
    public function setUserActive(string $userId, bool $activeStatus)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('User ID can not be empty');
        }

        $response = $this->httpPut(sprintf('/users/%s/active', $userId), [
            'active' => $activeStatus,
        ]);

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Deactivates the user by archiving its user object.
     *
     * @param string $userId The user GUID
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
     * Update a user's system-level roles.
     * Valid user roles are "system_user", "system_admin" or both of them.
     * Overwrites any previously assigned system-level roles.
     *
     * @param string $userId The user GUID
     * @param string $roles  Space-delimited system roles to assign to the user
     *
     * @return Status|ResponseInterface
     */
    public function updateUserRoles(string $userId, string $roles)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('User ID can not be empty');
        }

        $response = $this->httpPut(sprintf('/users/%s/roles', $userId), [
            'roles' => $roles,
        ]);

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Create a user. Required parameters: 'username', 'email' and 'password'.
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users%2Fpost
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
     * Update a user.
     *
     * @see https://api.mattermost.com/v4/#tag/users%2Fpaths%2F~1users~1%7Buser_id%7D%2Fput
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
     * Update a user's password. New password must meet password policy set by server configuration.
     *
     * @param string $currentPassword The current password for the user
     * @param string $newPassword     The new password for the user
     *
     * @return Status|ResponseInterface
     */
    public function updateUserPassword(string $userId, string $currentPassword, string $newPassword)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }
        if (empty($currentPassword) || empty($newPassword)) {
            throw new InvalidArgumentException('The current password and the new password can not be empty');
        }

        $response = $this->httpPut(sprintf('/users/%s/password', $userId), [
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
        ]);

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Returns an user by its username, use "me" to get the current logged in user.
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

    /**
     * Get the user status by its ID.
     *
     * @return UserStatus|ResponseInterface
     */
    public function getUserStatus(string $userId)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpGet(sprintf('/users/%s/status', $userId));

        return $this->handleResponse($response, UserStatus::class);
    }

    /**
     * Delete a user's picture.
     *
     * @return Status|ResponseInterface
     */
    public function deleteProfileImage(string $userId)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        $response = $this->httpDelete(sprintf('/users/%s/image', $userId));

        return $this->handleResponse($response, Status::class);
    }

    /**
     * Update a user's picture.
     *
     * @param string|resource|StreamInterface|null $image The image contents to use as profile image
     *
     * @return Status|ResponseInterface
     */
    public function updateProfileImage(string $userId, $image)
    {
        if (empty($userId)) {
            throw new InvalidArgumentException('UserId can not be empty');
        }

        if (!($image instanceof StreamInterface || \is_resource($image) || \is_string($image))) {
            throw new InvalidArgumentException('Image: must be a string, resource or StreamInterface');
        }

        $multipartStreamBuilder = new MultipartStreamBuilder();
        $multipartStreamBuilder->addResource('image', $image);
        $headers = ['Content-Type' => 'multipart/form-data; boundary='.$multipartStreamBuilder->getBoundary()];
        $multipartStream = $multipartStreamBuilder->build();

        $response = $this->httpPostRaw(sprintf('/users/%s/image', $userId), $multipartStream, $headers);

        return $this->handleResponse($response, Status::class);
    }
}
