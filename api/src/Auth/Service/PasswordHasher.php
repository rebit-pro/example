<?php

declare(strict_types=1);

namespace App\Auth\Service;

use Webmozart\Assert\Assert;

final class PasswordHasher
{
    public function __construct(
        private readonly int $memoryCost = PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
    ) {}

    public function hash(string $password): string
    {
        Assert::notEmpty($password, 'Password cannot be empty');

        $hash = password_hash($password, PASSWORD_ARGON2I, [
            'memory_cost' => $this->memoryCost,
        ]);

        return $hash ?: throw new \RuntimeException('Failed to hash password');
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
