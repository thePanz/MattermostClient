<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Hydrator;

use Pnz\JsonException\Json;
use Pnz\MattermostClient\Exception\HydrationException;
use Pnz\MattermostClient\Model\CreatableFromArray;
use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate an HTTP response to domain object.
 */
class ModelHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, string $class)
    {
        $body = (string) $response->getBody();

        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ModelHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        try {
            $data = Json::decode($body, true);
        } catch (\JsonException $exception) {
            throw new HydrationException(sprintf('Error when trying to decode the JSON response: %s', $exception->getMessage()), 0, $exception);
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            $object = $class::createFromArray($data);
        } else {
            $object = new $class($data);
        }

        return $object;
    }
}
