<?php

declare(strict_types=1);

namespace App\Modules\Finance\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Override;

#[ORM\Entity]
#[ORM\Table(name: self::TABLE_NAME)]
#[ORM\UniqueConstraint(name: 'unique_user_id_category_name', columns: [self::PROP_USER_ID, self::PROP_NAME])]
#[ORM\HasLifecycleCallbacks]
class Category implements JsonSerializable
{
    public const string PROP_ID = 'id';
    public const string PROP_NAME = 'name';
    public const string PROP_USER_ID = 'user_id';
    public const string PROP_CREATED_AT = 'created_at';
    public const string PROP_UPDATED_AT = 'updated_at';
    public const string TABLE_NAME = 'finance_categories';

    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::GUID)]
    private string $user_id;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'category')]
    private Collection $transactions;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        string $uuid,
        string $name,
        string $user_id
    ) {
        $this->id = $uuid;
        $this->name = $name;
        $this->user_id = $user_id;
        $this->transactions = new ArrayCollection();
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::PROP_ID => $this->id,
            self::PROP_NAME => $this->name,
            self::PROP_USER_ID => $this->user_id,
            self::PROP_CREATED_AT => $this->createdAt?->format('Y-m-d H:i:s'),
            self::PROP_UPDATED_AT => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
