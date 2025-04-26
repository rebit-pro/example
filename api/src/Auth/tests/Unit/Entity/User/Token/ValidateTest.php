<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class ValidateTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new \DateTimeImmutable()
        );

        $this->expectNotToPerformAssertions();

        $token->validate($value, $expires->modify('-1 secs'));
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testWrong(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new \DateTimeImmutable()
        );

        $this->expectExceptionMessage('Token is invalid.');
        $token->validate(
            Uuid::uuid4()->toString(),
            $expires->modify('-1 secs')
        );
    }

    public function testExpired(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new \DateTimeImmutable()
        );

        $this->expectExceptionMessage('Confirmation token is expired.');
        $token->validate(
            $value,
            $expires->modify('+1 secs')
        );
    }
}
