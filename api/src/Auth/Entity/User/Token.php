<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;
use DateTimeImmutable;

final class Token
{
    public function __construct(
        private string $value,
        private readonly DateTimeImmutable $expires,
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
            throw new \DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new \DomainException('Confirmation token is expired.');
        }
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    private function isExpiredTo($date): bool
    {
        return $this->expires < $date;
    }
}
