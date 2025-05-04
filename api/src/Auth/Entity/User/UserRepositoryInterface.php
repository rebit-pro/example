<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;
    public function hasByNetwork(Network $identity): bool;
    public function findByConfirmToken(string $token): ?User;
    public function findByPasswordResetToken(string $token): ?User;

    /*
     * @param Id $id
     * @return User $user
     * @throws \DomainException
     * */
    public function get(Id $id): User;

    /*
     * @param Id $id
     * @return User $user
     * @throws \DomainException
     * */
    public function getByEmail(Email $email): User;
    public function add(User $user): void;
    public function remove(User $user): void;
}
