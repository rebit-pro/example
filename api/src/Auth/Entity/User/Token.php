<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;
use Webmozart\Assert\Assert;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Token
{
    public function __construct(
        #[ORM\Column(name: 'token', type: 'string', nullable: true)]
        private string $value,
        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        private DateTimeImmutable $expires,
    ) {
        Assert::uuid($this->value);
        $this->value = mb_strtolower($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function validate(string $token, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($token)) {
            throw new DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Confirmation token is expired.');
        }
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $date >= $this->expires;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}