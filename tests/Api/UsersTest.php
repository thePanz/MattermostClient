<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Api\UsersApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\User\User;
use Pnz\MattermostClient\Model\User\Users;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\UsersApi
 */
class UsersTest extends BaseHttpApiTest
{
    /**
     * @var UsersApi
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new UsersApi($this->httpClient, $this->requestFactory, $this->hydrator);
    }

    public function testLoginSuccess(): void
    {
        $loginId = 'user@example.com';
        $password = 'password';
        $expectedToken = '123456';
        $data = [
            'login_id' => $loginId,
            'password' => $password,
        ];

        $token = null;
        $this->configureMessage('POST', '/users/login', [], Json::encode($data));
        $this->configureRequestAndResponse(200, '', ['Token' => [$expectedToken]]);
        $this->configureHydrator(User::class);

        $this->client->login($loginId, $password, $token);

        $this->assertSame($expectedToken, $token, 'Returned token must match!');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testLoginSuccessException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $loginId = 'user@example.com';
        $password = 'password';
        $data = [
            'login_id' => $loginId,
            'password' => $password,
        ];

        $this->configureMessage('POST', '/users/login', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->login($loginId, $password, $token);
    }

    public function testLoginEmptyLoginId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->login('', 'password');
    }

    public function testLoginEmptyPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->login('login-id', '');
    }

    public function testGetUserByEmailSuccess(): void
    {
        $userEmail = 'user@example.com';
        $this->configureMessage('GET', '/users/email/'.$userEmail);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);

        $this->client->getUserByEmail($userEmail);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUserByEmailException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userEmail = 'user@example.com';
        $this->configureMessage('GET', '/users/email/'.$userEmail);
        $this->configureRequestAndResponse($code);

        $this->client->getUserByEmail($userEmail);
    }

    public function testGetUserByEmailEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByEmail('');
    }

    public function testGetUserByIdSuccess(): void
    {
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);
        $this->client->getUserById($userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUserByIdException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->getUserById($userId);
    }

    public function testGetUserByIdEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserById('');
    }

    public function testGetUserTeamsSuccess(): void
    {
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId.'/teams');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Teams::class);
        $this->client->getUserTeams($userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUserTeamsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId.'/teams');
        $this->configureRequestAndResponse($code);
        $this->client->getUserTeams($userId);
    }

    public function testGetUserTeamsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserTeams('');
    }

    public function testGetUserByUsernameSuccess(): void
    {
        $username = 'user-name';
        $this->configureMessage('GET', '/users/username/'.$username);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);
        $this->client->getUserByUsername($username);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUserByUsernameException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $username = 'user-name';
        $this->configureMessage('GET', '/users/username/'.$username);
        $this->configureRequestAndResponse($code);
        $this->client->getUserByUsername($username);
    }

    public function testGetUserByUsernameEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByUsername('');
    }

    public function testDeactivateUserSuccess(): void
    {
        $userId = '12345';
        $this->configureMessage('DELETE', '/users/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deactivateUser($userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testDeactivateUserException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('DELETE', '/users/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->deactivateUser($userId);
    }

    public function testDeactivateUserEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deactivateUser('');
    }

    public function testSetUserActiveSuccess(): void
    {
        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/active', [], Json::encode([
            'active' => true,
        ]));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->setUserActive($userId, true);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testSetUserActiveException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/active', [], Json::encode([
            'active' => false,
        ]));
        $this->configureRequestAndResponse($code);
        $this->client->setUserActive($userId, false);
    }

    public function testSetUserActiveEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->setUserActive('', false);
    }

    public function testUpdateUserPasswordSuccess(): void
    {
        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/password', [], Json::encode([
            'current_password' => 'current-pw',
            'new_password' => 'new-pw',
        ]));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->updateUserPassword($userId, 'current-pw', 'new-pw');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdateUserPasswordException(string $exception, int $code): void
    {
        $this->expectException($exception);

        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/password', [], Json::encode([
            'current_password' => 'current-pw',
            'new_password' => 'new-pw',
        ]));
        $this->configureRequestAndResponse($code);
        $this->client->updateUserPassword($userId, 'current-pw', 'new-pw');
    }

    public function testUpdateUserPasswordEmptyIdException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserPassword('', 'current-pw', 'new-pw');
    }

    public function testUpdateUserPasswordEmptyCurrentPasswordException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserPassword('user-12345', '', 'new-pw');
    }

    public function testUpdateUserPasswordEmptyNewPasswordException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserPassword('user-12345', 'current-pw', '');
    }

    public function testUpdateUserRolesSuccess(): void
    {
        $userId = '12345';
        $data = ['roles' => 'system_admin'];
        $this->configureMessage('PUT', '/users/'.$userId.'/roles', [], Json::encode($data));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->updateUserRoles($userId, 'system_admin');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdateUserRolesException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '12345';
        $data = ['roles' => 'system_admin'];
        $this->configureMessage('PUT', '/users/'.$userId.'/roles', [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateUserRoles($userId, 'system_admin');
    }

    public function testUpdateUserRolesEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserRoles('', '');
    }

    public function testGetUsersByIdsSuccess(): void
    {
        $userIds = ['111', '222'];
        $this->configureMessage('POST', '/users/ids', [], Json::encode($userIds));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Users::class);
        $this->client->getUsersByIds($userIds);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUsersByIdsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userIds = ['111', '222'];
        $this->configureMessage('POST', '/users/ids', [], Json::encode($userIds));
        $this->configureRequestAndResponse($code);
        $this->client->getUsersByIds($userIds);
    }

    public function testGetUsersByIdsEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByIds([]);
    }

    public function testGetUsersByUsernamesSuccess(): void
    {
        $userIds = ['username-1', 'username-2'];

        $this->configureMessage('POST', '/users/usernames', [], Json::encode($userIds));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Users::class);

        $this->client->getUsersByUsernames($userIds);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetUsersByUsernamesException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userIds = ['username-1', 'username-2'];

        $this->configureMessage('POST', '/users/usernames', [], Json::encode($userIds));
        $this->configureRequestAndResponse($code);

        $this->client->getUsersByUsernames($userIds);
    }

    public function testGetUsersByUsernamesEmptyNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByUsernames([]);
    }

    public function testCreateUserSuccess(): void
    {
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('POST', '/users', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);

        $this->client->createUser($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testCreateUserException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('POST', '/users', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createUser($data);
    }

    public function testPatchUserSuccess(): void
    {
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('PUT', '/users/'.$userId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);

        $this->client->patchUser($userId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testPatchUserException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('PUT', '/users/'.$userId.'/patch', [], Json::encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchUser($userId, $data);
    }

    public function testPatchUsersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchUser('', []);
    }

    public function testUpdateUserSuccess(): void
    {
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/users/'.$userId, [], Json::encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);
        $this->client->updateUser($userId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testUpdateUserException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/users/'.$userId, [], Json::encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateUser($userId, $data);
    }

    public function testUpdateUsersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUser('', []);
    }

    public function testGetUserSuccess(): void
    {
        $this->configureMessage('GET', '/users');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Users::class);
        $this->client->getUsers();
    }

    public function testGetUserParametersSuccess(): void
    {
        $this->configureMessage('GET', '/users'.
            '?per_page=1&page=2&in_channel=channel&in_team=team&not_in_channel=channel-not-in');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Users::class);
        $this->client->getUsers([
            'per_page' => 1,
            'page' => 2,
            'in_channel' => 'channel',
            'in_team' => 'team',
            'not_in_channel' => 'channel-not-in',
        ]);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetChannelPostsException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $this->configureMessage('GET', '/users');
        $this->configureRequestAndResponse($code);
        $this->client->getUsers();
    }

    public function testDeleteProfileImageEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteProfileImage('');
    }

    public function testDeleteProfileImageSuccess(): void
    {
        $userId = '1234';
        $this->configureMessage('DELETE', '/users/'.$userId.'/image');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deleteProfileImage($userId);
    }

    public function testUpdateProfileImageEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateProfileImage('', 'some-contents-here');
    }

    public function testUpdateProfileImageEmptyResource(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateProfileImage('1234', null);
    }
}
