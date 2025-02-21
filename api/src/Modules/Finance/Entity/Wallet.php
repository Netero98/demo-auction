<?php

declare(strict_types=1);

namespace App\Modules\Finance\Entity;

use App\Common\Enum\CurrencyEnum;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: self::TABLE_NAME)]
#[ORM\UniqueConstraint(name: 'unique_user_id_wallet_name', columns: [self::PROP_USER_ID, self::PROP_NAME])]
#[ORM\HasLifecycleCallbacks]
final class Wallet
{
    public const string PROP_NAME = 'name';
    public const string PROP_USER_ID = 'user_id';
    public const string PROP_INITIAL_BALANCE = 'initial_balance';
    public const string PROP_CURRENCY = 'currency';
    public const string TABLE_NAME = 'finance_wallets';

    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    public string $id;

    #[ORM\Column(type: Types::GUID)]
    private string $user_id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $initial_balance;

    #[ORM\Column(type: Types::STRING, nullable: false, enumType: CurrencyEnum::class)]
    private CurrencyEnum $currency;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        string $uuid,
        string $name,
        string $user_id,
        int $initial_balance,
        CurrencyEnum $currency
    ) {
        $this->id = $uuid;
        $this->name = $name;
        $this->user_id = $user_id;
        $this->initial_balance = $initial_balance;
        $this->currency = $currency;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
