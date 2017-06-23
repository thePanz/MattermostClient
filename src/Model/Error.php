<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

final class Error extends Model
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    /**
     * @return string
     */
    public function getDetailedError(): string
    {
        return $this->data['detailed_error'] ?? '';
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->data['status_code'] ?? 0;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->data['request_id'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
    {
        return ['id', 'message', 'status_code', 'request_id', 'detailed_error'];
    }
}
