<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Pnz\MattermostClient\Api\UsersApi;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Error;
use Pnz\MattermostClient\Model\Status;
use Pnz\MattermostClient\Model\Team\Teams;
use Pnz\MattermostClient\Model\User\User;
use Pnz\MattermostClient\Model\User\Users;

/**
 * @internal
 */
#[CoversClass(UsersApi::class)]
final class UsersTest extends AbstractHttpApiTestCase
{
    private UsersApi $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new UsersApi($this->httpClient, $this->psr17factory, $this->psr17factory, $this->hydrator);
    }

    public function testLoginSucceeds(): void
    {
        $requestData = ['login_id' => self::USER_EMAIL, 'password' => self::USER_PASSWORD];

        $response = $this->buildResponse(200, [], ['Token' => ['f3c63140']]);
        $this->expectRequest('POST', '/users/login', $requestData, $response);
        $this->expectHydration($response, User::class);

        $token = null;
        $this->client->login(self::USER_EMAIL, self::USER_PASSWORD, $token);

        $this->assertSame('f3c63140', $token, 'Returned token must match!');
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testLoginSuccessThrows(string $exception, int $code): void
    {
        $requestData = ['login_id' => self::USER_EMAIL, 'password' => self::USER_PASSWORD];

        $response = $this->buildResponse($code);
        $this->expectRequest('POST', '/users/login', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->login(self::USER_EMAIL, self::USER_PASSWORD, $token);
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

    public function testGetUserByEmailSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/users/email/'.self::USER_EMAIL, [], $response);
        $this->expectHydration($response, User::class);

        $this->client->getUserByEmail(self::USER_EMAIL);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUserByEmailThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/users/email/'.self::USER_EMAIL, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUserByEmail(self::USER_EMAIL);
    }

    public function testGetUserByEmailEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByEmail('');
    }

    public function testGetUserByIdSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/users/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, User::class);

        $this->client->getUserById(self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUserByIdThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/users/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUserById(self::USER_UUID);
    }

    public function testGetUserByIdEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserById('');
    }

    public function testGetUserTeamsSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/users/'.self::USER_UUID.'/teams', [], $response);
        $this->expectHydration($response, Teams::class);

        $this->client->getUserTeams(self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUserTeamsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/users/'.self::USER_UUID.'/teams', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUserTeams(self::USER_UUID);
    }

    public function testGetUserTeamsEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserTeams('');
    }

    public function testGetUserByUsernameSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/users/username/'.self::USER_USERNAME, [], $response);
        $this->expectHydration($response, User::class);

        $this->client->getUserByUsername(self::USER_USERNAME);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUserByUsernameThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/users/username/'.self::USER_USERNAME, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUserByUsername(self::USER_USERNAME);
    }

    public function testGetUserByUsernameEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUserByUsername('');
    }

    public function testDeactivateUserSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('DELETE', '/users/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->deactivateUser(self::USER_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testDeactivateUserThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('DELETE', '/users/'.self::USER_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->deactivateUser(self::USER_UUID);
    }

    public function testDeactivateUserEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deactivateUser('');
    }

    public function testSetUserActiveSucceeds(): void
    {
        $requestData = ['active' => true];
        $response = $this->buildResponse(200);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/active', $requestData, $response);
        $this->expectHydration($response, Status::class);

        $this->client->setUserActive(self::USER_UUID, true);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testSetUserActiveThrows(string $exception, int $code): void
    {
        $requestData = ['active' => false];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/active', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->setUserActive(self::USER_UUID, false);
    }

    public function testSetUserActiveWithEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->setUserActive('', false);
    }

    public function testUpdateUserPasswordSucceeds(): void
    {
        $requestData = ['current_password' => self::USER_PASSWORD, 'new_password' => self::USER_PASSWORD2];
        $response = $this->buildResponse(200);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/password', $requestData, $response);
        $this->expectHydration($response, Status::class);

        $this->client->updateUserPassword(self::USER_UUID, 'current-pw', 'new-pw');
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdateUserPasswordThrows(string $exception, int $code): void
    {
        $requestData = ['current_password' => self::USER_PASSWORD, 'new_password' => self::USER_PASSWORD2];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/password', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updateUserPassword(self::USER_UUID, 'current-pw', 'new-pw');
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

    public function testUpdateUserRolesSucceeds(): void
    {
        $requestData = ['roles' => 'system_admin'];
        $response = $this->buildResponse(200);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/roles', $requestData, $response);
        $this->expectHydration($response, Status::class);

        $this->client->updateUserRoles(self::USER_UUID, 'system_admin');
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdateUserRolesThrows(string $exception, int $code): void
    {
        $requestData = ['roles' => 'system_admin'];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/roles', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updateUserRoles(self::USER_UUID, 'system_admin');
    }

    public function testUpdateUserRolesEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUserRoles('', '');
    }

    public function testGetUsersByIdsSucceeds(): void
    {
        $requestData = [self::USER_UUID, self::USER_UUID2];

        $response = $this->buildResponse(200);
        $this->expectRequest('POST', '/users/ids', $requestData, $response);
        $this->expectHydration($response, Users::class);

        $this->client->getUsersByIds($requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUsersByIdsThrows(string $exception, int $code): void
    {
        $requestData = [self::USER_UUID, self::USER_UUID2];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/users/ids', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUsersByIds($requestData);
    }

    public function testGetUsersByIdsEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByIds([]);
    }

    public function testGetUsersByUsernamesSucceeds(): void
    {
        $requestData = [self::USER_USERNAME, 'username-2'];
        $response = $this->buildResponse(200);

        $this->expectRequest('POST', '/users/usernames', $requestData, $response);
        $this->expectHydration($response, Users::class);

        $this->client->getUsersByUsernames($requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetUsersByUsernamesThrows(string $exception, int $code): void
    {
        $requestData = [self::USER_USERNAME, 'username-2'];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/users/usernames', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUsersByUsernames($requestData);
    }

    public function testGetUsersByUsernamesEmptyNames(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getUsersByUsernames([]);
    }

    public function testCreateUserSucceeds(): void
    {
        $requestData = ['username' => self::USER_USERNAME, 'email' => self::USER_EMAIL];

        $response = $this->buildResponse(201);
        $this->expectRequest('POST', '/users', $requestData, $response);
        $this->expectHydration($response, User::class);

        $this->client->createUser($requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testCreateUserThrows(string $exception, int $code): void
    {
        $requestData = ['username' => self::USER_USERNAME, 'email' => self::USER_EMAIL];
        $response = $this->buildResponse($code);

        $this->expectRequest('POST', '/users', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->createUser($requestData);
    }

    public function testPatchUserSucceeds(): void
    {
        $requestData = ['username' => self::USER_USERNAME];

        $response = $this->buildResponse(201);
        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, User::class);

        $this->client->patchUser(self::USER_UUID, $requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testPatchUserThrows(string $exception, int $code): void
    {
        $requestData = ['username' => self::USER_USERNAME];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID.'/patch', $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->patchUser(self::USER_UUID, $requestData);
    }

    public function testPatchUsersEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->patchUser('', []);
    }

    public function testUpdateUserSucceeds(): void
    {
        $requestData = ['username' => self::USER_USERNAME];
        $response = $this->buildResponse(201);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID, $requestData, $response);
        $this->expectHydration($response, User::class);

        $this->client->updateUser(self::USER_UUID, $requestData);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testUpdateUserThrows(string $exception, int $code): void
    {
        $requestData = ['username' => self::USER_USERNAME];
        $response = $this->buildResponse($code);

        $this->expectRequest('PUT', '/users/'.self::USER_UUID, $requestData, $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->updateUser(self::USER_UUID, $requestData);
    }

    public function testUpdateUsersEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateUser('', []);
    }

    public function testGetUsersSucceeds(): void
    {
        $responseData = [];
        $response = $this->buildResponse(200, $responseData);

        $this->expectRequest('GET', '/users', [], $response);
        $this->expectHydration($response, Users::class);

        $users = $this->client->getUsers();
        $this->assertCount(0, $users);
    }

    public function testGetUserParametersSucceeds(): void
    {
        $responseData = [];
        $response = $this->buildResponse(200, $responseData);

        $this->expectRequest(
            'GET',
            '/users?per_page=1&page=2&in_channel=channel&in_team=team&not_in_channel=channel-not-in',
            [],
            $response
        );
        $this->expectHydration($response, Users::class);

        $users = $this->client->getUsers([
            'per_page' => 1,
            'page' => 2,
            'in_channel' => 'channel',
            'in_team' => 'team',
            'not_in_channel' => 'channel-not-in',
        ]);

        $this->assertCount(0, $users);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetChannelPostsThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/users', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getUsers();
    }

    public function testDeleteProfileImageEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->deleteProfileImage('');
    }

    public function testDeleteProfileImageSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('DELETE', '/users/'.self::USER_UUID.'/image', [], $response);
        $this->expectHydration($response, Status::class);

        $this->client->deleteProfileImage(self::USER_UUID);
    }

    public function testUpdateProfileImageEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateProfileImage('', 'some-contents-here');
    }

    public function testUpdateProfileImageEmptyResource(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->updateProfileImage(self::USER_UUID, null);
    }
}
