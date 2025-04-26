<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new \DateTimeImmutable()
        );
        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $token = new Token(
            mb_strtolower($value),
            new \DateTimeImmutable()
        );

        self::assertEquals($value, $token->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Token('', new \DateTimeImmutable());
    }

    public function testIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Token('123456789', new \DateTimeImmutable());
    }
}
