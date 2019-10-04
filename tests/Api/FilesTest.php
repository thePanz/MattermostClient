<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\FilesApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\File\FileInfo;
use Psr\Http\Message\StreamInterface;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\FilesApi
 */
class FilesTest extends BaseHttpApiTest
{
    /** @var FilesApi */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new FilesApi($this->httpClient, $this->requestFactory, $this->hydrator);
    }

    public function testGetFileSuccess(): void
    {
        $fileId = '12345';
        $contents = 'Lorem Lipsum';
        $this->configureMessage('GET', '/files/'.$fileId);
        $this->configureRequestAndResponse(200, $contents);
        $stream = $this->client->getFile($fileId);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame($contents, $stream->getContents());
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetFileException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId);
        $this->configureRequestAndResponse($code);
        $this->client->getFile($fileId);
    }

    public function testGetFileEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFile('');
    }

    public function testGetFileLinkSuccess(): void
    {
        $fileId = '12345';
        $contents = 'http://somelinks.com/file';
        $this->configureMessage('GET', '/files/'.$fileId.'/link');
        $this->configureRequestAndResponse(200, $contents);
        $link = $this->client->getFileLink($fileId);

        $this->assertIsString($link);
        $this->assertSame($contents, $link);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetFileLinkException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/link');
        $this->configureRequestAndResponse($code);
        $this->client->getFileLink($fileId);
    }

    public function testGetFileLinkEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileLink('');
    }

    public function testGetFileInfoSuccess(): void
    {
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/info');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(FileInfo::class);
        $this->client->getFileInfo($fileId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     */
    public function testGetFileInfoException(string $exception, int $code): void
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/info');
        $this->configureRequestAndResponse($code);
        $this->client->getFileInfo($fileId);
    }

    public function testGetFileInfoEmptyId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileInfo('');
    }
}
