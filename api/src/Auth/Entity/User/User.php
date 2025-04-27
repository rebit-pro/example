<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use AllowDynamicProperties;

#[AllowDynamicProperties] final class User
{
    private ?string $passwordHash = null;
    private ?Token $joinConfirmToken = null;
    private \ArrayObject $networks;

    private function __construct(
        private readonly Id $id,
        private \DateTimeImmutable $date,
        private Email $email,
        private Status $status
    ) {
        $this->networks = new \ArrayObject();
    }

    public static function requestJoinByEmail(
        Id $id,
        \DateTimeImmutable $date,
        Email $email,
        string $hash,
        Token $joinConfirmToken
    ): self {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $hash;
        $user->joinConfirmToken = $joinConfirmToken;
        return $user;
    }

    public static function joinByNetwork(
        Id $id,
        \DateTimeImmutable $date,
        Email $email,
        NetworkIdentity $identity
    ): self {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($identity);
        return $user;
    }

    public function attachNetwork(NetworkIdentity $identity): void
    {
        /** @var NetworkIdentity $network */
        foreach ($this->networks as $network) {
            if ($network->isEqualTo($identity)) {
                throw new \DomainException('User with this network already exists.');
            }
        }

        $this->networks->append($identity);
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getNetworks(): array
    {
        return $this->networks->getArrayCopy();
    }

    public function confirmJoin(string $token, \DateTimeImmutable $date): void
    {
        if ($this->isActive() === true) {
            throw new \DomainException('User is already active.');
        }

        if ($this->joinConfirmToken === null) {
            throw new \DomainException('Confirmation is not required.');
        }

        $this->joinConfirmToken->validate($token, $date);

        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }
}
