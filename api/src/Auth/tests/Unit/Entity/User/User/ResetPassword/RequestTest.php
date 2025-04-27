<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User\ResetPassword;

use App\Auth\Entity\User\Token;
use App\Auth\tests\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class RequestTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testSuccess(): void
    {
        $user = new UserBuilder()->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());
        self::assertEquals($token, $user->getPasswordResetToken());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testAlReady(): void
    {
        $user = new UserBuilder()->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Password reset token already exists.');
        $user->requestPasswordReset($token, $now);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testExpired(): void
    {
        $user = new UserBuilder()->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestPasswordReset($token, $now);

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hour'));
        $user->requestPasswordReset($newToken, $newDate);

        self::assertEquals($newToken, $user->getPasswordResetToken());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testNotActive(): void
    {
        $user = new UserBuilder()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $this->expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($token, $now);
    }


    public function createToken(DateTimeImmutable $now): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $now
        );
    }
}
