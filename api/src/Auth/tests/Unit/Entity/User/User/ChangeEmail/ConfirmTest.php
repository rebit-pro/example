<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\tests\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class ConfirmTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testHandlerConfirm(): void
    {
        $user = new UserBuilder()
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = new Email('new-test@email.app'));
        self::assertNotNull($user->getNewEmailToken());

        $user->confirmEmailChanging($token->getValue(), $now);

        self::assertNull($user->getNewEmailToken());
        self::assertNull($user->getNewEmail());
        self::assertEquals($new, $user->getEmail());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testInvalidToken(): void
    {
        $user = new UserBuilder()
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = new Email('new-test@email.app'));

        $this->expectExceptionMessage('Token is invalid.');
        $user->confirmEmailChanging(
            Uuid::uuid4()->toString(),
            $now->modify('+1 day')
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testExpiredToken(): void
    {
        $user = new UserBuilder()
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, new Email('new-test@email.app'));

        $this->expectExceptionMessage('Confirmation token is expired.');
        $user->confirmEmailChanging(
            $token->getValue(),
            $now->modify('+2 day')
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testNotRequest(): void
    {
        $user = new UserBuilder()
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('Email is not requested.');
        $user->confirmEmailChanging(
            $token->getValue(),
            $now,
        );
    }

    private function createToken(\DateTimeImmutable $now): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $now
        );
    }
}
