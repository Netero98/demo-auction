<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateCategory;

use App\Modules\Finance\Entity\Wallet;
use Symfony\Component\Validator\Constraints;

final class DTO
{
    public const string FIELD_NAME = Wallet::PROP_NAME;

    public function __construct(
        #[Constraints\NotBlank]
        public string $name,
        #[Constraints\NotBlank]
        public string $user_id,
    ) {}
}
