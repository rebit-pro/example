<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Service;

use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new \App\Auth\Service\PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertNotEquals($password, $hash);
        self::assertNotEmpty($hash);
    }

    public function testValidate(): void
    {
        $hasher = new \App\Auth\Service\PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }

    public function testEmptyHash(): void
    {
        $hasher = new \App\Auth\Service\PasswordHasher(16);
        $this->expectException(\InvalidArgumentException::class);
        $hasher->hash('');
    }
}
