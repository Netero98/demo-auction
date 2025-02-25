<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Wallet;

use App\Common\Helper\HttpHelper;
use App\Modules\Finance\Fixture\Wallets\WalletsFixture;
use JsonException;
use Override;
use Test\Functional\AuthHeader;
use Test\Functional\V1\Auth\UserFixture;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetWalletsTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            UserFixture::class,
            WalletsFixture::class,
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testAllUserWalletsAreFound(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/wallets',
            )->withHeader('Authorization', AuthHeader::for(UserFixture::MAIN_TEST_USER_ID, UserFixture::MAIN_TEST_USER_ROLE))
        );

        self::assertSame(HttpHelper::STATUS_OK, $response->getStatusCode());

        /**
         * @var array<int, array{user_id: string, name: string, initial_balance: int, currency: string}> $responseArray
         */
        $body = $response->getBody()->getContents();
        $responseArray = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($responseArray);
        self::assertCount(2, $responseArray);

        foreach ($responseArray as $wallet) {
            self::assertIsArray($wallet);
            self::assertArrayHasKey('user_id', $wallet);
            self::assertArrayHasKey('name', $wallet);
            self::assertArrayHasKey('initial_balance', $wallet);
            self::assertArrayHasKey('currency', $wallet);

            self::assertIsString($wallet['user_id']);
            self::assertIsString($wallet['name']);
            self::assertIsInt($wallet['initial_balance']);
            self::assertIsString($wallet['currency']);
        }
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/finance/wallets',
            )
        );

        self::assertSame(HttpHelper::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
