<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User;

use App\Auth\Service\PasswordHasher;
use App\Auth\tests\Builder\UserBuilder;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hash = $this->createHasher(true, 'new-hash');

        $user->changePassword(
            'oldPassword',
            'newPassword',
            $hash
        );

        self::assertEquals(
            $hash->hash('newPassword'),
            $user->getPasswordHash()
        );
    }

    /**
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function testWrongCurrent(): void
    {

        $user = (new UserBuilder())->active()->build();

        $hash = $this->createHasher(false, 'new-hash');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Wrong current password');

        $user->changePassword(
            'newPassword',
            'oldPassword',
            $hash
        );
    }

    public function testByNetwork()
    {
        $user = (new UserBuilder())->viaNetwork()->build();

        $hasher = $this->createHasher(false, 'new-hash');

        $this->expectExceptionMessage('User does not have an old password.');
        $user->changePassword(
            'any-old-password',
            'new-password',
            $hasher
        );
    }

    /**
     * @throws Exception
     */
    private function createHasher($valid, $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('hash')->willReturn($hash);
        $hasher->method('validate')->willReturn($valid);

        return $hasher;
    }
}
