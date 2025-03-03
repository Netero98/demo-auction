<?php

declare(strict_types=1);

namespace App\Modules\Finance\Fixture\Wallets;

use App\Common\Enum\CurrencyEnum;
use App\Modules\Finance\Entity\Wallet;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Ramsey\Uuid\Uuid;
use Test\Functional\V1\Auth\UserFixture;

final class WalletsFixture extends AbstractFixture
{
    // Константы для ссылок на кошельки
    public const string REFERENCE_WALLET_1 = 'wallet_1';
    public const string REFERENCE_WALLET_2 = 'wallet_2';
    public const string REFERENCE_WALLET_3 = 'wallet_3';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $wallet1 = new Wallet(
            uuid: 'a62a368e-cf9a-4363-b5f5-408049e05e58',
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

        // Сохраняем ссылки на кошельки для использования в других фикстурах и тестах
        $this->addReference(self::REFERENCE_WALLET_1, $wallet1);
        $this->addReference(self::REFERENCE_WALLET_2, $wallet2);
        $this->addReference(self::REFERENCE_WALLET_3, $wallet3);
    }
}
