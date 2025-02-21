<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateWallet;

use App\Modules\Finance\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function handle(DTO $dto): Wallet
    {
        $wallet = new Wallet(
            uuid: Uuid::uuid4()->toString(),
            name: $dto->name,
            user_id: $dto->user_id,
            initial_balance: $dto->initial_balance,
            currency: $dto->currency,
        );

        $this->entityManager->persist($wallet);
        $this->entityManager->flush();

        return $wallet;
    }
}
