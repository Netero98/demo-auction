<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateTransaction;

use App\Modules\Finance\Entity\Category;
use App\Modules\Finance\Entity\Transaction;
use App\Modules\Finance\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;

final readonly class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function handle(DTO $dto): Transaction
    {
        $wallet = $this->entityManager->find(Wallet::class, $dto->wallet_id);

        if (!$wallet) {
            throw new Exception('Wallet not found');
        }

        $category = $this->entityManager->find(Category::class, $dto->category_id);

        if (!$category) {
            throw new Exception('Category not found');
        }

        $transaction = new Transaction(
            uuid: $dto->id,
            wallet: $wallet,
            amount: $dto->amount,
            category: $category,
            description: $dto->description,
        );

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }
}
