<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    public const string ROLE_USER = 'user';
    public const string ROLE_ADMIN = 'admin';

    private string $role;
    public function __construct(string $role)
    {
        Assert::oneOf($role, [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ]);

        $this->role = $role;
    }
    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public function getName(): string
    {
        return $this->role;
    }

    public function isEqualTo(Role $role): bool
    {
        return $this->role === $role->getName();
    }
}
