<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;
use DateTimeImmutable;

final class Status
{
    private const string WAIT = 'wait';
    private const string ACTIVE = 'active';

    private readonly string $name;

    public function __construct($name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
        ]);
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
