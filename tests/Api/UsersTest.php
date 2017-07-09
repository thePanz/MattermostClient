<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\Users;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\User\User;
use Pnz\MattermostClient\Model\User\Users as UsersCollection;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\Users
 */
class UsersTest extends BaseHttpApiTest
{
    /**
     * @var Users
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Users($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testLoginSuccess()
    {
        $loginId = 'user@example.com';
        $password = 'password';
        $expectedToken = '123456';
        $data = [
            'login_id' => $loginId,
            'password' => $password,
        ];

        $token = null;
        $this->configureMessage('POST', '/users/login', [], json_encode($data));
        $this->configureRequestAndResponse(200, '', ['Token' => [$expectedToken]]);
        $this->configureHydrator(User::class);

        $this->client->login($loginId, $password, $token);

        $this->assertSame($expectedToken, $token, 'Returned token must match!');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testLoginSuccessException($exception, $code)
    {
        $this->expectException($exception);
        $loginId = 'user@example.com';
        $password = 'password';
        $data = [
            'login_id' => $loginId,
            'password' => $password,
        ];

        $this->configureMessage('POST', '/users/login', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->login($loginId, $password, $token);
    }

    public function testLoginEmptyLoginId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->login('', 'password');
    }

    public function testLoginEmptyPassword()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->login('login-id', '');
    }

    public function testGetUserByEmailSuccess()
    {
        $userEmail = 'user@example.com';
        $this->configureMessage('GET', '/users/email/'.$userEmail);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);

        $this->client->getUserByEmail($userEmail);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetUserByEmailException($exception, $code)
    {
        $this->expectException($exception);
        $userEmail = 'user@example.com';
        $this->configureMessage('GET', '/users/email/'.$userEmail);
        $this->configureRequestAndResponse($code);

        $this->client->getUserByEmail($userEmail);
    }

    public function testGetUserByEmailEmptyEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByEmail('');
    }

    public function testGetUserByIdSuccess()
    {
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);
        $this->client->getUserById($userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetUserByIdException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('GET', '/users/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->getUserById($userId);
    }

    public function testGetUserByIdEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserById('');
    }

    //  **
    public function testGetUserByUsernameSuccess()
    {
        $username = 'user-name';
        $this->configureMessage('GET', '/users/username/'.$username);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(User::class);
        $this->client->getUserByUsername($username);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetUserByUsernameException($exception, $code)
    {
        $this->expectException($exception);
        $username = 'user-name';
        $this->configureMessage('GET', '/users/username/'.$username);
        $this->configureRequestAndResponse($code);
        $this->client->getUserByUsername($username);
    }

    public function testGetUserByUsernameEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByUsername('');
    }

    public function testDeactivateUserSuccess()
    {
        $userId = '12345';
        $this->configureMessage('DELETE', '/users/'.$userId);
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->deactivateUser($userId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testDeactivateUserException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('DELETE', '/users/'.$userId);
        $this->configureRequestAndResponse($code);
        $this->client->deactivateUser($userId);
    }

    public function testDeactivateUserEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deactivateUser('');
    }

    public function testSetUserActiveSuccess()
    {
        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/active', [], json_encode([
            'active' => true,
        ]));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->setUserActive($userId, true);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testSetUserActiveException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '12345';
        $this->configureMessage('PUT', '/users/'.$userId.'/active', [], json_encode([
            'active' => false,
        ]));
        $this->configureRequestAndResponse($code);
        $this->client->setUserActive($userId, false);
    }

    public function testSetUserActiveEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->setUserActive('', false);
    }

    public function testUpdateUserRolesSuccess()
    {
        $userId = '12345';
        $data = ['roles' => 'system_admin'];
        $this->configureMessage('PUT', '/users/'.$userId.'/roles', [], json_encode($data));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(Status::class);
        $this->client->updateUserRoles($userId, 'system_admin');
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUpdateUserRolesException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '12345';
        $data = ['roles' => 'system_admin'];
        $this->configureMessage('PUT', '/users/'.$userId.'/roles', [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateUserRoles($userId, 'system_admin');
    }

    public function testUpdateUserRolesEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserRoles('', '');
    }

    public function testGetUsersByIdsSuccess()
    {
        $userIds = ['111', '222'];
        $this->configureMessage('POST', '/users/ids', [], json_encode($userIds));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(UsersCollection::class);
        $this->client->getUsersByIds($userIds);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetUsersByIdsException($exception, $code)
    {
        $this->expectException($exception);
        $userIds = ['111', '222'];
        $this->configureMessage('POST', '/users/ids', [], json_encode($userIds));
        $this->configureRequestAndResponse($code);
        $this->client->getUsersByIds($userIds);
    }

    public function testGetUsersByIdsEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByIds([]);
    }

    public function testGetUsersByUsernamesSuccess()
    {
        $userIds = ['username-1', 'username-2'];

        $this->configureMessage('POST', '/users/usernames', [], json_encode($userIds));
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(UsersCollection::class);

        $this->client->getUsersByUsernames($userIds);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetUsersByUsernamesException($exception, $code)
    {
        $this->expectException($exception);
        $userIds = ['username-1', 'username-2'];

        $this->configureMessage('POST', '/users/usernames', [], json_encode($userIds));
        $this->configureRequestAndResponse($code);

        $this->client->getUsersByUsernames($userIds);
    }

    public function testGetUsersByUsernamesEmptyNames()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByUsernames([]);
    }

    public function testCreateUserSuccess()
    {
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('POST', '/users', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);

        $this->client->createUser($data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testCreateUserException($exception, $code)
    {
        $this->expectException($exception);
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('POST', '/users', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->createUser($data);
    }

    public function testPatchUserSuccess()
    {
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('PUT', '/users/'.$userId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);

        $this->client->patchUser($userId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testPatchUserException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];
        $this->configureMessage('PUT', '/users/'.$userId.'/patch', [], json_encode($data));
        $this->configureRequestAndResponse($code);

        $this->client->patchUser($userId, $data);
    }

    public function testPatchUsersEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchUser('', []);
    }

    public function testUpdateUserSuccess()
    {
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/users/'.$userId, [], json_encode($data));
        $this->configureRequestAndResponse(201);
        $this->configureHydrator(User::class);
        $this->client->updateUser($userId, $data);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testUpdateUserException($exception, $code)
    {
        $this->expectException($exception);
        $userId = '111';
        $data = [
            'username' => 'username',
            'email' => 'email,',
        ];

        $this->configureMessage('PUT', '/users/'.$userId, [], json_encode($data));
        $this->configureRequestAndResponse($code);
        $this->client->updateUser($userId, $data);
    }

    public function testUpdateUsersEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUser('', []);
    }

    public function testGetUserSuccess()
    {
        $this->configureMessage('GET', '/users');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(UsersCollection::class);
        $this->client->getUsers();
    }

    public function testGetUserParametersSuccess()
    {
        $this->configureMessage('GET', '/users'.
            '?per_page=1&page=2&in_channel=channel&in_team=team&not_in_channel=channel-not-in');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(UsersCollection::class);
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetChannelPostsException($exception, $code)
    {
        $this->expectException($exception);
        $this->configureMessage('GET', '/users');
        $this->configureRequestAndResponse($code);
        $this->client->getUsers();
    }
}
