<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Hydrator;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate an HTTP response to array.
 */
final class ArrayHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, string $class): array
    {
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ArrayHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        try {
            return Json::decode((string) $response->getBody(), true);
        } catch (\JsonException $exception) {
            throw new HydrationException(sprintf('Error when trying to decoding the JSON response: %s', $exception->getMessage()), 0, $exception);
        }
    }
}
