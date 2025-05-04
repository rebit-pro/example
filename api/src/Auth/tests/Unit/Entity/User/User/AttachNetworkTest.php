<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User\User;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\tests\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 *  @covers User
 */
class AttachNetworkTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new Network('vk', 'vk-1');
        $user->attachNetwork($network);

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testAlreadyAttached(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new Network('vk', 'vk-1');

        $user->attachNetwork($network);

        $this->expectExceptionMessage('User with this network already exists.');
        $user->attachNetwork($network);
    }
}
