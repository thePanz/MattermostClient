<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Model;

final class Error extends Model
{
    public function getId(): string
    {
        return $this->data['id'] ?? '';
    }

    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    public function getDetailedError(): string
    {
        return $this->data['detailed_error'] ?? '';
    }

    public function getStatusCode(): int
    {
        return $this->data['status_code'] ?? 0;
    }

    public function getRequestId(): string
    {
        return $this->data['request_id'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields(): array
    {
        return ['id', 'message', 'status_code', 'request_id', 'detailed_error'];
    }
}
