<?php

declare(strict_types=1);

namespace App\Modules\Finance\Fixture\Transactions;

use App\Modules\Finance\Entity\Category;
use App\Modules\Finance\Entity\Transaction;
use App\Modules\Finance\Entity\Wallet;
use App\Modules\Finance\Fixture\Categories\CategoriesFixture;
use App\Modules\Finance\Fixture\Wallets\WalletsFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Ramsey\Uuid\Uuid;

final class TransactionsFixture extends AbstractFixture
{
    public const string REFERENCE_TRANSACTION_1 = 'transaction_1';
    public const string REFERENCE_TRANSACTION_2 = 'transaction_2';
    public const string REFERENCE_TRANSACTION_3 = 'transaction_3';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        // Получаем существующие кошельки из фикстуры WalletsFixture
        $wallet1 = $this->getReference(WalletsFixture::REFERENCE_WALLET_1, Wallet::class);
        $wallet3 = $this->getReference(WalletsFixture::REFERENCE_WALLET_3, Wallet::class);

        // Получаем существующие категории из фикстуры CategoriesFixture.php
        $category1 = $this->getReference(CategoriesFixture::REFERENCE_CATEGORY_1, Category::class);
        $category2 = $this->getReference(CategoriesFixture::REFERENCE_CATEGORY_2, Category::class);

        // Создаем несколько тестовых транзакций
        $transaction1 = new Transaction(
            uuid: Uuid::uuid4()->toString(),
            wallet: $wallet1,
            amount: 1000,
            category: $category1,
            description: 'Test transaction 1'
        );

        $transaction2 = new Transaction(
            uuid: Uuid::uuid4()->toString(),
            wallet: $wallet3,
            amount: -500,
            category: $category2,
            description: 'Test transaction 2'
        );

        $transaction3 = new Transaction(
            uuid: Uuid::uuid4()->toString(),
            wallet: $wallet1,
            amount: 2000,
            category: $category1,
            description: 'Test transaction 3'
        );

        $manager->persist($transaction1);
        $manager->persist($transaction2);
        $manager->persist($transaction3);

        $manager->flush();

        // Сохраняем ссылки на транзакции для использования в тестах
        $this->addReference(self::REFERENCE_TRANSACTION_1, $transaction1);
        $this->addReference(self::REFERENCE_TRANSACTION_2, $transaction2);
        $this->addReference(self::REFERENCE_TRANSACTION_3, $transaction3);
    }
}
