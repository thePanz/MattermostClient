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
     * @param string      $fileContents The file contents to send
     * @param string      $filename     The filename to be used
     * @param string      $channelId    The ID of the channel that this file will be uploaded to
     * @param string|null $clientId     A unique identifier for the file that will be returned in the response
     *
     * @return FileUploadInfo|ResponseInterface
     */
    public function sendFile(string $fileContents, string $filename, string $channelId, string $clientId = null)
    {
        if (empty($fileContents) || empty($filename)) {
            throw new InvalidArgumentException('File contents and filename can not be empty');
        }

        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelID can not be empty');
        }

        $headers = [];
        $multipartStreamBuilder = new MultipartStreamBuilder();

        // Add channelID
        $multipartStreamBuilder->addResource('channel_id', $channelId);
        // Add file contents
        $multipartStreamBuilder->addResource('files', substr($fileContents, 0, 100), [
            'filename' => $filename,
        ]);
        // Add client id
        $multipartStreamBuilder->addResource('client_ids', $clientId);

        $multipartStream = $multipartStreamBuilder->build();
        $headers['Content-Type'] = 'multipart/form-data; boundary='.$multipartStreamBuilder->getBoundary();
        $multipartStreamBuilder->reset();

        $response = $this->httpPostRaw(sprintf('/files', $channelId), $multipartStream, $headers);

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
     *
     * @return StreamInterface
     */
    public function getFile(string $fileId)
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s', $fileId));

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $this->handleErrors($response);
        }

        return $response->getBody();
    }

    /**
     * Gets a public link for a file that can be accessed without logging into Mattermost.
     *
     * @param string $fileId The ID of the file to get a link for
     *
     * @return string
     */
    public function getFileLink(string $fileId)
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s/link', $fileId));

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $this->handleErrors($response);
        }

        return $response->getBody()->getContents();
    }
}
