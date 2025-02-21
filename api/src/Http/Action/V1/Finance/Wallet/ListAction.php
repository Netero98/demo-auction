<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Finance\Wallet;

use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Modules\Finance\Query\FindAllUserWallets\Handler;
use JsonException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class ListAction implements RequestHandlerInterface
{
    public function __construct(
        private Handler $handler
    ) {}

    /**
     * @throws JsonException
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        $wallets = $this->handler->handle($identity->id);

        return new JsonResponse($wallets);
    }
}
