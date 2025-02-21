<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Finance\Wallet;

use App\Common\Enum\CurrencyEnum;
use App\Common\Helper\HttpHelper;
use App\Common\Helper\ValidateHelper;
use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Modules\Finance\Command\CreateWallet\DTO;
use App\Modules\Finance\Command\CreateWallet\Handler;
use App\Validator\Validator;
use JsonException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class CreateAction implements RequestHandlerInterface
{
    public function __construct(
        private Validator $validator,
        private Handler $handler,
        private ValidateHelper $validateHelper,
    ) {}

    /**
     * @throws JsonException
     * @psalm-suppress PossiblyNullArrayAccess
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = Authenticate::identity($request);

        if ($identity === null) {
            throw new UnauthorizedHttpException($request);
        }

        $body = (array)$request->getParsedBody();

        $requiredFields = [
            DTO::FIELD_NAME,
            DTO::FIELD_CURRENCY,
        ];

        $notProvidedFields = $this->validateHelper->getNotProvidedFields($body, $requiredFields);

        $errors = [];

        foreach ($notProvidedFields as $field) {
            $errors[$field] = 'Field is required';
        }

        if (!empty($notProvidedFields)) {
            return new JsonResponse(
                [
                    'message' => 'Validation error',
                    'errors' => $errors,
                ],
                HttpHelper::STATUS_UNPROCESSABLE
            );
        }

        $currencyEnum = CurrencyEnum::tryFrom((string)$body[DTO::FIELD_CURRENCY]);

        if (!$currencyEnum instanceof CurrencyEnum) {
            return new JsonResponse(
                [
                    'message' => 'Validation error',
                    'errors' => [
                        'currency' => 'Invalid currency. Must be on of: ' . implode(', ', CurrencyEnum::getValues()) . '.',
                    ],
                ],
                HttpHelper::STATUS_UNPROCESSABLE
            );
        }

        $initial_balance = isset($body[DTO::FIELD_INITIAL_BALANCE])
            ? (int)$body[DTO::FIELD_INITIAL_BALANCE]
            : 0;

        $dto = new DTO(
            name: (string)$body[DTO::FIELD_NAME],
            user_id: $identity->id,
            currency: $currencyEnum,
            initial_balance: $initial_balance,
        );

        $this->validator->validate($dto);

        $wallet = $this->handler->handle($dto);

        return new JsonResponse(
            [
                'message' => 'Wallet successfully created',
                'id' => $wallet->id,
            ],
            HttpHelper::STATUS_CREATED
        );
    }
}
