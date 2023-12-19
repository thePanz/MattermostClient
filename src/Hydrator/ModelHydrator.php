<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Hydrator;

use Pnz\MattermostClient\Contract\HydratorInterface;
use Pnz\MattermostClient\Exception\HydrationException;
use Pnz\MattermostClient\Model\CreatableFromArray;
use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate an HTTP response to domain object.
 */
final class ModelHydrator implements HydratorInterface
{
    public function hydrate(ResponseInterface $response, string $class): object
    {
        $body = (string) $response->getBody();

        if (!str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ModelHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        try {
            $data = json_decode($body, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new HydrationException(sprintf('Error when trying to decode the JSON response: %s', $exception->getMessage()), 0, $exception);
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            return $class::createFromArray($data);
        }

            return new $class($data);
    }
}
