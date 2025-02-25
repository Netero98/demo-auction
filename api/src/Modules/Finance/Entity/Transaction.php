<?php

declare(strict_types=1);

namespace App\Modules\Finance\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Override;

#[ORM\Entity]
#[ORM\Table(name: self::TABLE_NAME)]
#[ORM\HasLifecycleCallbacks]
class Transaction implements JsonSerializable
{
    public const string PROP_ID = 'id';
    public const string PROP_WALLET_ID = 'wallet_id';
    public const string PROP_AMOUNT = 'amount';
    public const string PROP_CATEGORY_ID = 'category_id';
    public const string PROP_DESCRIPTION = 'description';
    public const string PROP_CREATED_AT = 'created_at';
    public const string PROP_UPDATED_AT = 'updated_at';
    public const string TABLE_NAME = 'finance_transactions';

    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: self::PROP_WALLET_ID, referencedColumnName: Wallet::PROP_ID, nullable: false)]
    private Wallet $wallet;

    #[ORM\Column(type: Types::INTEGER)]
    private int $amount;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: self::PROP_CATEGORY_ID, referencedColumnName: Category::PROP_ID, nullable: false)]
    private Category $category;

    public function __construct(
        string $uuid,
        Wallet $wallet,
        int $amount,
        Category $category,
        ?string $description = null
    ) {
        $this->id = $uuid;
        $this->wallet = $wallet;
        $this->amount = $amount;
        $this->category = $category;
        $this->description = $description;
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

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::PROP_ID => $this->id,
            self::PROP_WALLET_ID => $this->wallet->getId(),
            self::PROP_AMOUNT => $this->amount,
            self::PROP_CATEGORY_ID => $this->category->getId(),
            self::PROP_DESCRIPTION => $this->description,
            self::PROP_CREATED_AT => $this->createdAt?->format('Y-m-d H:i:s'),
            self::PROP_UPDATED_AT => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
