<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use DateTimeImmutable;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use Override;
use RuntimeException;
use Stringable;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AccessToken implements AccessTokenEntityInterface, Stringable
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    private ?string $userRole = null;

    /**
     * @param ScopeEntityInterface[] $scopes
     */
    public function __construct(ClientEntityInterface $client, array $scopes)
    {
        $this->setClient($client);

        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }

    #[Override]
    public function __toString(): string
    {
        return $this->convertToJWT()->toString();
    }

    public function setUserRole(string $userRole): void
    {
        $this->userRole = $userRole;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function convertToJWT(): UnencryptedToken
    {
        $this->initJwtConfiguration();

        return $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo($this->getUserIdentifier() ?? throw new RuntimeException('Empty value.'))
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('role', $this->getUserRole())
            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }
}
