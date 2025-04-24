<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;

/**
 * @covers Token
 */
class TokenTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertEquals($value, $id->getValue());
        self::assertEquals($expires, $id->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $token = new Token(mb_strtoupper($value), new DateTimeImmutable());

        self::assertEquals($value, $token->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Token('', new DateTimeImmutable());
    }

    public function testIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Token('12345', new DateTimeImmutable());
    }
}
