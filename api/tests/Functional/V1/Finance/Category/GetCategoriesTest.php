<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Category;

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
final class GetCategoriesTest extends WebTestCase
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
    public function testAllUserCategoriesAreFound(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/categories',
            )->withHeader('Authorization', AuthHeader::for(UserFixture::MAIN_TEST_USER_ID, UserFixture::MAIN_TEST_USER_ROLE))
        );

        // Проверяем, что статус ответа 200 OK
        self::assertSame(HttpHelper::STATUS_OK, $response->getStatusCode());

        // Декодируем тело ответа
        $body = $response->getBody()->getContents();
        $responseArray = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        // Проверяем, что ответ является массивом
        self::assertIsArray($responseArray);

        // Проверяем количество категорий (предположим, что ожидается 2 категории)
        self::assertCount(2, $responseArray);

        // Проверяем структуру каждой категории
        foreach ($responseArray as $category) {
            self::assertIsArray($category);
            self::assertArrayHasKey('id', $category);
            self::assertArrayHasKey('name', $category);
            self::assertArrayHasKey('user_id', $category);
            self::assertArrayHasKey('created_at', $category);
            self::assertArrayHasKey('updated_at', $category);

            // Проверяем типы данных
            self::assertIsString($category['id']);
            self::assertIsString($category['name']);
            self::assertIsString($category['user_id']);
            self::assertIsString($category['created_at']);
            self::assertIsString($category['updated_at']);

            // Проверяем, что категория принадлежит основному пользователю
            self::assertSame(UserFixture::MAIN_TEST_USER_ID, $category['user_id']);
        }
    }

    public function testGuest(): void
    {
        // Отправляем запрос без авторизации
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/categories',
            )
        );

        // Проверяем, что статус ответа 401 Unauthorized
        self::assertSame(HttpHelper::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
