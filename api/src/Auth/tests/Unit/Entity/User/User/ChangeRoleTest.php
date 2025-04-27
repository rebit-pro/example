<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User;

use App\Auth\Entity\User\Role;
use App\Auth\tests\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class ChangeRoleTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $user->changeRole($role = new Role(Role::ROLE_ADMIN));

        self::assertEquals($role, $user->getRole());
    }
}
