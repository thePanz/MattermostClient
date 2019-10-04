<?php

declare(strict_types=1);

namespace Pnz\MattermostClient\Hydrator;

use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate a PSR-7 response to something else.
 */
interface Hydrator
{
    /**
     * Hydrate the data in the response to an instance of the given class.
     *
     * @param ResponseInterface $response The Response
     * @param string            $class    The class to Hydrate to
     */
    public function hydrate(ResponseInterface $response, string $class);
}
