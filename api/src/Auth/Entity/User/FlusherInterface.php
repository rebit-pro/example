<?php

namespace App\Auth\Entity\User;

interface FlusherInterface
{
    public function flush(): void;

    public function send(string $email, string $token): void;
}
