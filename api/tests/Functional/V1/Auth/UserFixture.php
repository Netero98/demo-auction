<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use App\Common\Fixture\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class UserFixture extends AbstractFixture
{
    public const string MAIN_TEST_USER_ID = '00000000-0000-0000-0000-000000000001';
    public const string MAIN_TEST_USER_ROLE = 'user';
    public const string REFERENCE_USER = 'user'; // Константа для ссылки на пользователя

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id(self::MAIN_TEST_USER_ID))
            ->active()
            ->build();

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE_USER, $user);
    }
}
