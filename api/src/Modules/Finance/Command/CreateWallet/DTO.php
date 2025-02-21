<?php

declare(strict_types=1);

namespace App\Modules\Finance\Command\CreateWallet;

use App\Common\Enum\CurrencyEnum;
use App\Modules\Finance\Entity\Wallet;
use Symfony\Component\Validator\Constraints;

final class DTO
{
    public const string FIELD_NAME = Wallet::PROP_NAME;
    public const string FIELD_USER_ID = Wallet::PROP_USER_ID;
    public const string FIELD_INITIAL_BALANCE = Wallet::PROP_INITIAL_BALANCE;
    public const string FIELD_CURRENCY = Wallet::PROP_CURRENCY;

    public function __construct(
        #[Constraints\NotBlank]
        public string $name,
        #[Constraints\Uuid]
        public string $user_id,
        public CurrencyEnum $currency,
        public int $initial_balance = 0,
    ) {}
}
