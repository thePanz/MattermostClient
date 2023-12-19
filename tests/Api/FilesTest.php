<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Pnz\MattermostClient\Api\FilesApi;
use Pnz\MattermostClient\Exception\DomainException;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Error;
use Pnz\MattermostClient\Model\File\FileInfo;

/**
 * @internal
 */
#[CoversClass(FilesApi::class)]
final class FilesTest extends AbstractHttpApiTestCase
{
    private FilesApi $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new FilesApi($this->httpClient, $this->psr17factory, $this->psr17factory, $this->hydrator);
    }

    public function testGetFileSucceeds(): void
    {
        $fileContents = 'Lorem Ipsum';
        $response = $this->buildResponse(200, $fileContents);

        $this->expectRequest('GET', '/files/'.self::FILE_UUID, [], $response);

        $stream = $this->client->getFile(self::FILE_UUID);

        $this->assertSame($fileContents, (string) $stream);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetFileThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);
        $this->expectRequest('GET', '/files/'.self::FILE_UUID, [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getFile(self::FILE_UUID);
    }

    public function testGetFileEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFile('');
    }

    public function testGetFileLinkSucceeds(): void
    {
        $contents = 'http://somelinks.com/file';
        $response = $this->buildResponse(200, $contents);

        $this->expectRequest('GET', '/files/'.self::FILE_UUID.'/link', [], $response);
        $link = $this->client->getFileLink(self::FILE_UUID);

        $this->assertSame($contents, $link);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetFileLinkThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/files/'.self::FILE_UUID.'/link', [], $response);
        $this->expectHydration($response, Error::class);
        $this->expectException($exception);

        $this->client->getFileLink(self::FILE_UUID);
    }

    public function testGetFileLinkEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileLink('');
    }

    public function testGetFileInfoSucceeds(): void
    {
        $response = $this->buildResponse(200);

        $this->expectRequest('GET', '/files/'.self::FILE_UUID.'/info', [], $response);
        $this->expectHydration($response, FileInfo::class);

        $this->client->getFileInfo(self::FILE_UUID);
    }

    /**
     * @param class-string<DomainException> $exception
     */
    #[DataProvider('provideErrorCodesExceptionsCases')]
    public function testGetFileInfoThrows(string $exception, int $code): void
    {
        $response = $this->buildResponse($code);

        $this->expectRequest('GET', '/files/'.self::FILE_UUID.'/info', [], $response);
        $this->expectException($exception);
        $this->expectHydration($response, Error::class);

        $this->client->getFileInfo(self::FILE_UUID);
    }

    public function testGetFileInfoEmptyIdThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileInfo('');
    }
}
