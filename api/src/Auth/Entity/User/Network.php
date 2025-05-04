<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Network
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        private string $name,
        #[ORM\Column(type: 'string')]
        private string $identity,
    ) {
        Assert::notEmpty($this->name);
        Assert::notEmpty($this->identity);

        $this->name = mb_strtolower($this->name);
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
        return $this->name;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}