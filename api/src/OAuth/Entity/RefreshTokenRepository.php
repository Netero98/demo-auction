<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Override;

final readonly class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @param EntityRepository<RefreshToken> $repo
     */
    public function __construct(
        private EntityManagerInterface $em,
        private EntityRepository $repo
    ) {}

    #[Override]
    public function getNewRefreshToken(): ?RefreshToken
    {
        return new RefreshToken();
    }

    #[Override]
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        if ($this->exists($refreshTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    #[Override]
    public function revokeRefreshToken(string $tokenId): void
    {
        if ($token = $this->repo->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    #[Override]
    public function isRefreshTokenRevoked(string $tokenId): bool
    {
        return !$this->exists($tokenId);
    }

    public function removeAllForUser(string $userId): void
    {
        $this->em->createQueryBuilder()
            ->delete(RefreshToken::class, 'rt')
            ->andWhere('rt.userIdentifier < :user_id')
            ->setParameter(':user_id', $userId)
            ->getQuery()->execute();
    }

    public function removeAllExpired(DateTimeImmutable $now): void
    {
        $this->em->createQueryBuilder()
            ->delete(RefreshToken::class, 'rt')
            ->andWhere('rt.expiryDateTime < :date')
            ->setParameter(':date', $now->format(DATE_ATOM))
            ->getQuery()->execute();
    }

    private function exists(string $id): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.identifier)')
            ->andWhere('t.identifier = :identifier')
            ->setParameter(':identifier', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
