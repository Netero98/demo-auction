<?php

declare(strict_types=1);

namespace App\Modules\Finance\Query\FindAllUserCategories;

use App\Modules\Finance\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class Handler
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Category::class);
    }

    /**
     * Возвращает список категорий для указанного пользователя.
     *
     * @return array<Category>
     */
    public function handle(string $user_id): array
    {
        /** @var array<Category> $categories */
        return $this->repository->findBy([Category::PROP_USER_ID => $user_id]);
    }
}
