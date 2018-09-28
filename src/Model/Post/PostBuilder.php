<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model\Post;

use Pnz\MattermostClient\Model\ModelBuilder;

class PostBuilder extends ModelBuilder
{
    /**
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->params['message'] = $message;

        return $this;
    }

    /**
     * @param $channelId
     *
     * @return $this
     */
    public function setChannelId($channelId)
    {
        $this->params['channel_id'] = $channelId;

        return $this;
    }

    /**
     * Set the post ID to comment on.
     *
     * @param $postId
     *
     * @return $this
     */
    public function setRootId(string $postId)
    {
        $this->params['root_id'] = $postId;

        return $this;
    }

    /**
     * A list of file IDs to associate with the post.
     *
     * @param $fileIds
     *
     * @return $this
     */
    public function setFileIds(array $fileIds)
    {
        $this->params['file_ids'] = $fileIds;

        return $this;
    }

    /**
     * Set if the post is pinned.
     *
     *
     * @return $this
     */
    public function setIsPinned(bool $isPinned)
    {
        $this->params['is_pinned'] = $isPinned;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredFields($buildType = self::BUILD_FOR_CREATE)
    {
        switch ($buildType) {
            case self::BUILD_FOR_CREATE:
                return ['channel_id', 'message'];
            default:
                return [];
        }
    }
}
