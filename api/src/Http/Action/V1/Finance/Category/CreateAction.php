<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Finance\Category;

use App\Common\Helper\HttpHelper;
use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Middleware\Auth\Authenticate;
use App\Http\Response\JsonResponse;
use App\Modules\Finance\Command\CreateCategory\DTO;
use App\Modules\Finance\Command\CreateCategory\Handler;
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
            name: (string)$body[DTO::FIELD_NAME],
            user_id: $identity->id,
        );

        $this->validator->validate($dto);

        $category = $this->handler->handle($dto);

        return new JsonResponse(
            [
                'message' => 'Wallet successfully created',
                'category' => $category->jsonSerialize(),
            ],
            HttpHelper::STATUS_CREATED
        );
    }
}
