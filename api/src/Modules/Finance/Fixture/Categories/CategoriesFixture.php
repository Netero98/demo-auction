<?php

declare(strict_types=1);

namespace App\Modules\Finance\Fixture\Categories;

use App\Common\Fixture\AbstractFixture;
use App\Modules\Finance\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Override;
use Ramsey\Uuid\Uuid;
use Test\Functional\V1\Auth\UserFixture;

final class CategoriesFixture extends AbstractFixture
{
    // Константы для ссылок на категории
    public const string REFERENCE_CATEGORY_1 = 'category_1';
    public const string REFERENCE_CATEGORY_2 = 'category_2';
    public const string REFERENCE_CATEGORY_3 = 'category_3';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $userId = UserFixture::MAIN_TEST_USER_ID;

        // Создаем несколько тестовых категорий
        $category1 = new Category(
            uuid: Uuid::uuid4()->toString(),
            name: 'Food',
            user_id: $userId
        );

        $category2 = new Category(
            uuid: Uuid::uuid4()->toString(),
            name: 'Transport',
            user_id: '00000000-0000-0000-0000-000006530009' // non existing
        );

        $category3 = new Category(
            uuid: Uuid::uuid4()->toString(),
            name: 'Entertainment',
            user_id: $userId
        );

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);

        $manager->flush();

        // Сохраняем ссылки на категории для использования в других фикстурах и тестах
        $this->addReference(self::REFERENCE_CATEGORY_1, $category1);
        $this->addReference(self::REFERENCE_CATEGORY_2, $category2);
        $this->addReference(self::REFERENCE_CATEGORY_3, $category3);
    }
}
