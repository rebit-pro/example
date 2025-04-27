<?php

declare(strict_types=1);

namespace App\Auth\tests\Unit\Entity\User;

use App\Auth\Entity\User\NetworkIdentity;
use PHPUnit\Framework\TestCase;

class NetworkIdentityTest extends TestCase
{
    public function testSuccess(): void
    {
        $network = new NetworkIdentity($name = 'google', $identity = 'google-1');

        $this->assertEquals($name, $network->getNetwork());
        $this->assertEquals($identity, $network->getIdentity());
    }

    public function testEmptyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new NetworkIdentity($name = '', $identity = 'google-1');
    }

    public function testEmptyIdentity(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new NetworkIdentity($name = 'google', $identity = '');
    }

    public function testIsEqual(): void
    {
        $network = new NetworkIdentity($name = 'google', $identity = 'google-1');

        $this->assertTrue($network->isEqualTo(new NetworkIdentity($name, 'google-1')));
        $this->assertFalse($network->isEqualTo(new NetworkIdentity($name, 'google-2')));
        $this->assertFalse($network->isEqualTo(new NetworkIdentity('vk', 'vk-1')));
    }
}
