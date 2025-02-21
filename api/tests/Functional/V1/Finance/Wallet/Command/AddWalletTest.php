<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Wallet\Command;

use App\Common\Enum\CurrencyEnum;
use App\Modules\Finance\Command\CreateWallet\DTO;
use App\Modules\Finance\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Test\Functional\AuthHeader;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class AddWalletTest extends WebTestCase
{
    public function testWalletCreated(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/finance/wallet',
                [
                    DTO::FIELD_NAME => 'test_wallet_name',
                    DTO::FIELD_USER_ID => Uuid::uuid4()->toString(),
                    DTO::FIELD_INITIAL_BALANCE => 100500,
                    DTO::FIELD_CURRENCY => CurrencyEnum::RUB->value,
                ]
            )->withHeader('Authorization', AuthHeader::for('00000000-0000-0000-0000-000000000001', 'user'))
        );

        self::assertSame(201, $response->getStatusCode());

        $container = $this->app()->getContainer();

        /**
         * @psalm-suppress PossiblyNullReference
         */
        $em = $container->get(EntityManagerInterface::class);

        $responseArray = (array)json_decode($response->getBody()->getContents(), true);

        $wallet = $em->find(Wallet::class, $responseArray['id']);

        self::assertInstanceOf(Wallet::class, $wallet);
        self::assertSame($wallet->id, $responseArray['id']);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json(
                'POST',
                '/v1/auth/finance/wallet',
                [
                    DTO::FIELD_NAME => 'test_wallet_name',
                    DTO::FIELD_USER_ID => Uuid::uuid4()->toString(),
                    DTO::FIELD_INITIAL_BALANCE => 100500,
                    DTO::FIELD_CURRENCY => CurrencyEnum::RUB->value,
                ]
            )
        );

        self::assertSame(401, $response->getStatusCode());
    }
}
