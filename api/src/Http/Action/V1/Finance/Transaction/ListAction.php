<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Finance\Transaction;

use App\Common\Helper\HttpHelper;
use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Modules\Finance\Query\FindAllUserTransactions\Handler;
use JsonException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

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
        try {
            $identity = Authenticate::identity($request);

            if ($identity === null) {
                throw new UnauthorizedHttpException($request);
            }

            $categories = $this->handler->handle($identity->id);
        } catch (UnauthorizedHttpException $e) {
            throw $e;
        } catch (Throwable $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage(),
                ],
                HttpHelper::STATUS_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($categories);
    }
}
