<?php

namespace App\Auth\Entity\User;

use App\Auth\Entity\User\FlusherInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class Flasher implements FlusherInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function flush(): void
    {
        $this->em->flush();
    }

    public function send(string $email, string $token): void
    {
        // TODO: Implement send() method.
    }
}