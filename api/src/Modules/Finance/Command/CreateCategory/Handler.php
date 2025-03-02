<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateCategory;

use App\Modules\Finance\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function handle(DTO $dto): Category
    {
        $category = new Category(
            uuid: Uuid::uuid4()->toString(),
            name: $dto->name,
            user_id: $dto->user_id,
        );

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
