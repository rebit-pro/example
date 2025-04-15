<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Ramsey\Uuid\Nonstandard\Uuid;
use Webmozart\Assert\Assert;

final class Id
{
    public function __construct(
        private string $value,
    ) {
        Assert::uuid($this->value);

        $this->value = mb_strtolower($this->value);
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
