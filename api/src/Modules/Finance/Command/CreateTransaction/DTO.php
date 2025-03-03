<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateTransaction;

use App\Modules\Finance\Entity\Transaction;
use Symfony\Component\Validator\Constraints;

final class DTO
{
    public const string FIELD_WALLET_ID = Transaction::PROP_WALLET_ID;
    public const string FIELD_AMOUNT = Transaction::PROP_AMOUNT;
    public const string FIELD_CATEGORY_ID = Transaction::PROP_CATEGORY_ID;

    public function __construct(
        #[Constraints\NotBlank]
        public string $id,
        #[Constraints\NotBlank]
        public string $wallet_id,
        #[Constraints\NotBlank]
        #[Constraints\Positive]
        public int $amount,
        #[Constraints\NotBlank]
        public string $category_id,
        #[Constraints\Length(max: 255)]
        public ?string $description = null,
    ) {}
}
