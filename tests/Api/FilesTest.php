<?php

namespace Pnz\MattermostClient\Tests\Api;

use Pnz\MattermostClient\Api\FilesApi;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\File\FileInfo;
use Psr\Http\Message\StreamInterface;

/**
 * @coversDefaultClass \Pnz\MattermostClient\Api\Files
 */
class FilesTest extends BaseHttpApiTest
{
    /**
     * @var FilesApi
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new FilesApi($this->httpClient, $this->messageFactory, $this->hydrator);
    }

    public function testGetFileSuccess()
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
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetFileException($exception, $code)
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId);
        $this->configureRequestAndResponse($code);
        $this->client->getFile($fileId);
    }

    public function testGetFileEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFile('');
    }

    public function testGetFileLinkSuccess()
    {
        $fileId = '12345';
        $contents = 'http://somelinks.com/file';
        $this->configureMessage('GET', '/files/'.$fileId.'/link');
        $this->configureRequestAndResponse(200, $contents);
        $link = $this->client->getFileLink($fileId);

        $this->assertInternalType('string', $link);
        $this->assertSame($contents, $link);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetFileLinkException($exception, $code)
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/link');
        $this->configureRequestAndResponse($code);
        $this->client->getFileLink($fileId);
    }

    public function testGetFileLinkEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileLink('');
    }

    public function testGetFileInfoSuccess()
    {
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/info');
        $this->configureRequestAndResponse(200);
        $this->configureHydrator(FileInfo::class);
        $this->client->getFileInfo($fileId);
    }

    /**
     * @dataProvider getErrorCodesExceptions
     *
     * @param string $exception
     * @param int    $code
     */
    public function testGetFileInfoException($exception, $code)
    {
        $this->expectException($exception);
        $fileId = '12345';
        $this->configureMessage('GET', '/files/'.$fileId.'/info');
        $this->configureRequestAndResponse($code);
        $this->client->getFileInfo($fileId);
    }

    public function testGetFileInfoEmptyId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFileInfo('');
    }
}
