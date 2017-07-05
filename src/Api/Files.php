<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Api;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Pnz\MattermostClient\Exception\InvalidArgumentException;
use Pnz\MattermostClient\Model\Channel\Channel;
use Pnz\MattermostClient\Model\Channel\ChannelMember;
use Pnz\MattermostClient\Model\Channel\ChannelMembers;
use Pnz\MattermostClient\Model\Channel\ChannelStats;
use Pnz\MattermostClient\Model\File\FileMetadata;
use Pnz\MattermostClient\Model\Post\Posts;
use Pnz\MattermostClient\Model\Status;
use Psr\Http\Message\ResponseInterface;

final class Files extends HttpApi
{
    /**
     * Upload a File to a channel.
     *
     *
     * @see: https://api.mattermost.com/v4/#tag/files%2Fpaths%2F~1files%2Fpost
     *
     * @return FileInfo|ResponseInterface
     */
    public function sendFile(string $fileContents, string $channelId, string $clientId = null)
    {
        if (empty($fileContents)) {
            throw new InvalidArgumentException('File contents can not be empty');
        }

        if (empty($channelId)) {
            throw new InvalidArgumentException('ChannelID can not be empty');
        }


        $headers = [];
        $multipartStreamBuilder = new MultipartStreamBuilder();

        // Add channelID
        $multipartStreamBuilder->addResource('channel_id', $channelId);
        // Add file contents
        $multipartStreamBuilder->addResource('files', base64_encode(substr($fileContents, 0, 100)));
        // Add client id
        $multipartStreamBuilder->addResource('client_ids', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

        $multipartStream = $multipartStreamBuilder->build();
        $headers['Content-Type'] = 'multipart/form-data; boundary='.$multipartStreamBuilder->getBoundary();
        $multipartStreamBuilder->reset();

        //var_dump($fileContents, $multipartStream->getContents(), $headers);

        $response = $this->httpPostRaw(sprintf('/files', $channelId), $multipartStream, $headers);

        var_dump($response->getBody()->getContents());

        // return $this->handleResponse($response, Posts::class);
    }


    /**
     * Get a file metadata.
     *
     * @param string $fileId
     *
     * @see https://api.mattermost.com/v4/#tag/files%2Fpaths%2F~1files~1%7Bfile_id%7D~1info%2Fget
     *
     * @return FileMetadata|ResponseInterface
     */
    public function getFileMetadata(string $fileId)
    {
        if (empty($fileId)) {
            throw new InvalidArgumentException('FileID can not be empty');
        }

        $response = $this->httpGet(sprintf('/files/%s', $fileId));

        return $this->handleResponse($response, FileMetadata::class);
    }
}
