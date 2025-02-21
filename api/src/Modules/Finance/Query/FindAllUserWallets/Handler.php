<?php

declare(strict_types=1);

namespace App\Modules\Finance\Query\FindAllUserWallets;

use App\Modules\Finance\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class Handler
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Wallet::class);
    }

    public function handle(string $userId): array
    {
        return $this->repository->findBy(['user_id' => $userId]);
    }
}
