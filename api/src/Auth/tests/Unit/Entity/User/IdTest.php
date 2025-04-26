<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User;

use App\Auth\Entity\User\Id;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\UuidV4;

/**
 * @covers Id
 */
class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = UuidV4::uuid4()->toString());

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = UuidV4::uuid4()->toString();

        $id = new Id(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testGenerate(): void
    {
        $id = Id::generate();

        self::assertNotEmpty($id->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Id('');
    }

    public function testIncorrect(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Id('12345');
    }
}
