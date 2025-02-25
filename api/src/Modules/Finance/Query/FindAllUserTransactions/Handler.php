<?php

declare(strict_types=1);

namespace App\Modules\Finance\Query\FindAllUserTransactions;

use App\Modules\Finance\Entity\Transaction;
use App\Modules\Finance\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class Handler
{
    private EntityRepository $transactionRepository;
    private EntityRepository $walletRepository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $this->walletRepository = $this->entityManager->getRepository(Wallet::class);
    }

    /**
     * Возвращает список транзакций для указанного пользователя.
     *
     * @return array<Transaction>
     */
    public function handle(string $user_id): array
    {
        /** @var array<Wallet> $wallets */
        $wallets = $this->walletRepository->findBy([Wallet::PROP_USER_ID => $user_id]);

        /** @var array<int> $walletIds */
        $walletIds = array_map(static fn (Wallet $wallet): string => $wallet->getId(), $wallets);

        if (empty($walletIds)) {
            return [];
        }

        /** @var array<Transaction> $transactions */
        $transactions = $this->transactionRepository->createQueryBuilder('t')
            ->where('t.wallet IN (:walletIds)')
            ->setParameter('walletIds', $walletIds)
            ->getQuery()
            ->getResult();

        return $transactions;
    }
}
