<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User;

use App\Auth\Entity\User\Role;
use App\Auth\tests\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class RemoveTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     * @doesNotPerformAssertions
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $this->expectNotToPerformAssertions();

        $user->remove();
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testActive(): void
    {
        $user = (new UserBuilder())->active()->build();

        $this->expectExceptionMessage('Unable to remove active user.');

        $user->remove();
    }
}
