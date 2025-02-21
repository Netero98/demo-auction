<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Wallet\Query;

use App\Common\Helper\HttpHelper;
use Override;
use Test\Functional\AuthHeader;
use Test\Functional\V1\Auth\UserFixture;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class GetWalletsQueryTest extends WebTestCase
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

    public function testAllUserWalletsAreFound(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/auth/finance/wallets',
            )->withHeader('Authorization', AuthHeader::for(UserFixture::MAIN_TEST_USER_ID, UserFixture::MAIN_TEST_USER_ROLE))
        );

        self::assertSame(HttpHelper::STATUS_OK, $response->getStatusCode());

        $responseArray = (array)json_decode($response->getBody()->getContents(), true);

        self::assertCount(2, $responseArray);
    }

    public function testGuest(): void
    {
        $response = $this->app()->handle(
            self::json(
                'GET',
                '/v1/auth/finance/wallets',
            )
        );

        self::assertSame(HttpHelper::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
