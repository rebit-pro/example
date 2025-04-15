<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

final class Email
{
    public function __construct(
        private string $value,
    ) {
        Assert::email($this->value);
        Assert::notEmpty($this->value);

        $this->value = mb_strtolower($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
