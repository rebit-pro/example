<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

final class User
{
    private Status $status;
    public function __construct(
        private readonly Id $id,
        private readonly \DateTimeImmutable $date,
        private readonly Email $email,
        private readonly string $hash,
        private ?Token $joinConfirmToken,
    ) {
        $this->status = Status::wait();
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getPasswordHash(): string
    {
        return $this->hash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
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
