<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Transaction;

use App\Common\Helper\HttpHelper;
use App\Modules\Finance\Fixture\Categories\CategoriesFixture;
use App\Modules\Finance\Fixture\Transactions\TransactionsFixture;
use App\Modules\Finance\Fixture\Wallets\WalletsFixture;
use JsonException;
use Override;
use Test\Functional\AuthHeader;
use Test\Functional\V1\Auth\UserFixture;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetTransactionsTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        // Загружаем все необходимые фикстуры
        $this->loadFixtures([
            UserFixture::class,
            WalletsFixture::class,
            CategoriesFixture::class,
            TransactionsFixture::class,
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testAllUserTransactionsAreFound(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/transactions',
            )->withHeader('Authorization', AuthHeader::for(UserFixture::MAIN_TEST_USER_ID, UserFixture::MAIN_TEST_USER_ROLE))
        );

        self::assertSame(HttpHelper::STATUS_OK, $response->getStatusCode());

        $body = $response->getBody()->getContents();
        $responseArray = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($responseArray);
        self::assertCount(2, $responseArray);

        foreach ($responseArray as $transaction) {
            self::assertIsArray($transaction);
            self::assertArrayHasKey('id', $transaction);
            self::assertArrayHasKey('wallet_id', $transaction);
            self::assertArrayHasKey('amount', $transaction);
            self::assertArrayHasKey('category_id', $transaction);
            self::assertArrayHasKey('created_at', $transaction);
            self::assertArrayHasKey('updated_at', $transaction);

            // Проверяем типы данных
            self::assertIsString($transaction['id']);
            self::assertIsString($transaction['wallet_id']);
            self::assertIsInt($transaction['amount']);
            self::assertIsString($transaction['category_id']);
            self::assertIsString($transaction['created_at']);
            self::assertIsString($transaction['updated_at']);
        }
    }

    public function testGuest(): void
    {
        // Отправляем запрос без авторизации
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/transactions',
            )
        );

        self::assertSame(HttpHelper::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
