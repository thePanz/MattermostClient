<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Contract;

use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate a PSR-7 response to something else.
 */
interface HydratorInterface
{
    /**
     * Hydrate the data in the response to an instance of the given class.
     *
     * @template T of object
     *
     * @param ResponseInterface $response The Response
     * @param class-string<T>   $class    The class to Hydrate to
     *
     * @return T
     */
    public function hydrate(ResponseInterface $response, string $class): object;
}
