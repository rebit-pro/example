<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User\ChangeEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\tests\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class RequestTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = new Email('old-test@test.com'))
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = new Token(Uuid::uuid4()->toString(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = new Email('new-test@test.com'));

        self::assertNotNull($user->getNewEmailToken());
        self::assertEquals($old, $user->getEmail());
        self::assertEquals($new, $user->getNewEmail());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testSame(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = new Email('old-test@test.com'))
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('New email is the same as current.');
        $user->requestEmailChanging($token, $now, $old);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testAlReady(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $email = new Email('new-test@test.com'));

        $this->expectExceptionMessage('Email is already requested.');
        $user->requestEmailChanging($token, $now, $email);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testExpired(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestEmailChanging($token, $now, new Email('temp-test@test.com'));

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hours'));
        $user->requestEmailChanging($newToken, $newDate, $newEmail = new Email('new-test@test.com'));

        self::assertEquals($newToken, $user->getNewEmailToken());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testNotActive(): void
    {
        $user = (new UserBuilder())->build();

        $now = new \DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('User is not active.');
        $user->requestEmailChanging($token, $now, new Email('new-test@test.com'));
    }

    private function createToken(\DateTimeImmutable $now): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $now
        );
    }
}
