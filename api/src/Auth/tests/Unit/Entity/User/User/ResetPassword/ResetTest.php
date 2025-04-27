<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User\ResetPassword;

use App\Auth\Entity\User\Token;
use App\Auth\tests\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class ResetTest extends TestCase
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

        $user->resetPassword($token->getValue(), $hash = 'new-password', $now);

        self::assertNull($user->getPasswordResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testInvalidToken(): void
    {

        $user = new UserBuilder()->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Uuid::uuid4()->toString(), 'new-password', $now);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testExpiredToken(): void
    {
        $user = new UserBuilder()->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Confirmation token is expired.');
        $user->resetPassword($token->getValue(), 'new-password', $now->modify('+1 day'));
    }

    public function testNotRequested()
    {
        $user = new UserBuilder()->active()->build();

        $this->expectExceptionMessage('Password reset token is not requested.');
        $user->resetPassword(Uuid::uuid4()->toString(), 'new-password', new DateTimeImmutable());
    }

    private function createToken(DateTimeImmutable $now): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $now
        );
    }
}
