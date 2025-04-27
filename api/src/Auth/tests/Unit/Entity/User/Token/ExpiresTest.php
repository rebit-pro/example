<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class ExpiresTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testNot(): void
    {
        $token = new Token(
            Uuid::uuid4()->toString(),
            $expires = new \DateTimeImmutable()
        );

        self::assertFalse($token->isExpiredTo($expires->modify('-1 secs')));
        self::assertTrue($token->isExpiredTo($expires));
        self::assertTrue($token->isExpiredTo($expires->modify('+1 secs')));
    }
}
