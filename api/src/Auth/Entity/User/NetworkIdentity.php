<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class NetworkIdentity
{
    public function __construct(
        private string $network,
        private string $identity,
    ) {
        Assert::notEmpty($this->network);
        Assert::notEmpty($this->identity);

        $this->network = mb_strtolower($this->network);
        $this->identity = mb_strtolower($this->identity);
    }

    public function isEqualTo(self $network): bool
    {
        return
            $this->getNetwork() === $network->getNetwork() &&
            $this->getIdentity() === $network->getIdentity();
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
