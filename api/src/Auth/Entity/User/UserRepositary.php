<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

interface UserRepositary
{
    public function add(User $user): void;
    public function hasByEmail(Email $email): bool;
}
