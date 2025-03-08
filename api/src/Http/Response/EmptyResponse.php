<?php

declare(strict_types=1);

namespace App\Http\Response;

use RuntimeException;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

final class EmptyResponse extends Response
{
    public function __construct(int $status = 204)
    {
        $resource = fopen('php://temp', 'rb');

        if ($resource === false) {
            throw new RuntimeException('Unable to open resource.');
        }

        parent::__construct(
            $status,
            null,
            (new StreamFactory())->createStreamFromResource($resource)
        );
    }
}
