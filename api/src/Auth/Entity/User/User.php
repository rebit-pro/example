<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

final class User
{
    private Status $status;
    public function __construct(
        private Id $id,
        private \DateTimeImmutable $date,
        private Email $email,
        private string $hash,
        private ?Token $tokenizer,
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

    public function getJoinConfirmToken(): Token
    {
        return $this->tokenizer;
    }
}
