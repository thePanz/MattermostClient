<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\File\FileInfo;
use Pnz\MattermostClient\Model\File\FileUploadInfo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class FilesApi extends HttpApi
{
    /**
     * Uploads a file that can later be attached to a post.
     *
     * @see: https://api.mattermost.com/v4/#tag/files%2Fpaths%2F~1files%2Fpost
     *
     * @param string|resource|StreamInterface|null $file     The file contents to send
     * @param string|null                          $clientId A unique identifier for the file that will be returned in the response
     *
     * @return FileUploadInfo|ResponseInterface
     */
    public function sendFile($file, string $filename, string $channelId, string $clientId = null)
    {
        if (!($file instanceof StreamInterface || \is_resource($file) || \is_string($file))) {
            throw new InvalidArgumentException('File must be a string, resource or StreamInterface');
        }
        if (empty($filename)) {
            throw new InvalidArgumentException('File filename can not be empty');
        }
        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelID can not be empty');
        }

        $multipartStreamBuilder = new MultipartStreamBuilder();
        $multipartStreamBuilder->addResource('channel_id', $channelId);
        $multipartStreamBuilder->addResource('files', $file, ['filename' => $filename]);

        if ($clientId) {
            // Add client id
            $multipartStreamBuilder->addResource('client_ids', $clientId);
        }

        $headers = ['Content-Type' => 'multipart/form-data; boundary='.$multipartStreamBuilder->getBoundary()];
        $multipartStream = $multipartStreamBuilder->build();

        $response = $this->httpPostRaw('/files', $multipartStream, $headers);

        return $this->handleResponse($response, FileUploadInfo::class);
    }

    /**
     * Get metadata for a file.
     *
     * @param string $fileId The ID of the file info to get
     *
     * @see https://api.mattermost.com/v4/#tag/files%2Fpaths%2F~1files~1%7Bfile_id%7D~1info%2Fget
     *
     * @return FileInfo|ResponseInterface
     */
    public function getFileInfo(string $fileId)
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s/info', $fileId));

        return $this->handleResponse($response, FileInfo::class);
    }

    /**
     * Gets a file that has been uploaded previously.
     *
     * @param string $fileId The ID of the file to get
     */
    public function getFile(string $fileId): StreamInterface
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s', $fileId));

        $status = $response->getStatusCode();
        if (200 !== $status && 201 !== $status) {
            $this->handleErrors($response);
        }

        $response->getBody()->rewind();

        return $response->getBody();
    }

    /**
     * Gets a public link for a file that can be accessed without logging into Mattermost.
     *
     * @param string $fileId The ID of the file to get a link for
     */
    public function getFileLink(string $fileId): string
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s/link', $fileId));

        if (200 !== $response->getStatusCode() && 201 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return (string) $response->getBody();
    }
}
