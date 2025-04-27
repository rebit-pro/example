<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User\JoinByEmail;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Status;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 *  @covers User
 */
class RequestTest extends TestCase
{
    public function testSeccuess(): void
    {
        $user = User::requestJoinByEmail(
            $id = Id::generate(),
            $date = new DateTimeImmutable(),
            $email = new Email('mail@example.com'),
            $hash = 'hash',
            $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable()),
        );

        self::assertEquals($id->getValue(), $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email->getValue(), $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getJoinConfirmToken());

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertNotNull($user->getJoinConfirmToken());
    }
}
