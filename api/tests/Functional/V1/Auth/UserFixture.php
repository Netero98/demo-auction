<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth;

use App\Auth\Entity\User\Id;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class UserFixture extends AbstractFixture
{
    public const string MAIN_TEST_USER_ID = '00000000-0000-0000-0000-000000000001';
    public const string MAIN_TEST_USER_ROLE = 'user';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id(self::MAIN_TEST_USER_ID))
            ->active()
            ->build();

        $manager->persist($user);

        $manager->flush();
    }
}
