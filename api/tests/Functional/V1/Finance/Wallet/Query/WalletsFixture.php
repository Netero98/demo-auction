<?php

declare(strict_types=1);

namespace Test\Functional\V1\Finance\Wallet\Query;

use App\Common\Enum\CurrencyEnum;
use App\Modules\Finance\Entity\Wallet;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Ramsey\Uuid\Uuid;
use Test\Functional\V1\Auth\UserFixture;

final class WalletsFixture extends AbstractFixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $wallet1 = new Wallet(
            uuid: Uuid::uuid4()->toString(),
            currency: CurrencyEnum::RUB,
            name: 'Wallet rub',
            initial_balance: 199898,
            user_id: UserFixture::MAIN_TEST_USER_ID
        );

        $wallet2 = new Wallet(
            uuid: Uuid::uuid4()->toString(),
            currency: CurrencyEnum::USD,
            name: 'Wallet usd',
            initial_balance: 546,
            user_id: UserFixture::MAIN_TEST_USER_ID
        );

        $wallet3 = new Wallet(
            uuid: Uuid::uuid4()->toString(),
            currency: CurrencyEnum::THB,
            name: 'Wallet thb',
            initial_balance: 77700,
            user_id: '00000000-0000-0000-0000-000000000009' // non existent
        );

        $manager->persist($wallet1);
        $manager->persist($wallet2);
        $manager->persist($wallet3);

        $manager->flush();
    }
}
