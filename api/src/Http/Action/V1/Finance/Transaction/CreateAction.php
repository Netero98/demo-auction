<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Finance\Transaction;

use App\Common\Helper\HttpHelper;
use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Modules\Finance\Command\CreateTransaction\DTO;
use App\Modules\Finance\Command\CreateTransaction\Handler;
use App\Validator\Validator;
use JsonException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final readonly class CreateAction implements RequestHandlerInterface
{
    public function __construct(
        private Validator $validator,
        private Handler $handler,
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

        $body = (array)$request->getParsedBody();

        $dto = new DTO(
            id: Uuid::uuid4()->toString(),
            wallet_id: (string)($body[DTO::FIELD_WALLET_ID] ?? ''),
            amount: (int)($body[DTO::FIELD_AMOUNT] ?? 0),
            category_id: (string)($body[DTO::FIELD_CATEGORY_ID] ?? ''),
        );

        $this->validator->validate($dto);

        $transaction = $this->handler->handle($dto);

        return new JsonResponse(
            [
                'message' => 'Wallet successfully created',
                'transaction' => $transaction->jsonSerialize(),
            ],
            HttpHelper::STATUS_CREATED
        );
    }
}
